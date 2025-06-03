<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskHistory;
use App\Models\Priority;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Throwable;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        try {
            $tasks = Task::with(['priority', 'status'])
                ->where('user_id', Auth::id())
                ->when($request->filled('priority_id'), fn($q) => $q->where('priority_id', $request->priority_id))
                ->when($request->filled('status_id'), fn($q) => $q->where('status_id', $request->status_id))
                ->when($request->filled('due_from'), fn($q) => $q->whereDate('due_date', '>=', $request->due_from))
                ->when($request->filled('due_to'), fn($q) => $q->whereDate('due_date', '<=', $request->due_to))
                ->orderBy('due_date')
                ->get();

            return response()->json(['data' => $tasks]);
        } catch (Throwable $e) {
            Log::error('Błąd podczas pobierania zadań: ' . $e->getMessage());
            return response()->json(['message' => 'Nie udało się pobrać zadań.'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name'        => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'priority_id' => ['required', Rule::exists('priorities', 'id')],
                'status_id'   => ['required', Rule::exists('statuses', 'id')],
                'due_date'    => 'required|date|after_or_equal:today',
            ]);

            $task = Task::create([
                ...$data,
                'user_id' => Auth::id(),
            ])->load(['priority', 'status']);

            return response()->json(['message' => 'Zadanie utworzone.', 'data' => $task], 201);
        } catch (Throwable $e) {
            Log::error('Błąd podczas tworzenia zadania: ' . $e->getMessage());
            return response()->json(['message' => 'Nie udało się utworzyć zadania.'], 500);
        }
    }

    public function show(Task $task)
    {
        try {
            $this->authorize('view', $task);
            $task->load(['priority', 'status']);
            return response()->json(['data' => $task]);
        } catch (AuthorizationException) {
            return response()->json(['message' => 'Brak dostępu do tego zadania.'], 403);
        } catch (Throwable $e) {
            Log::error("Błąd przy pobieraniu zadania: " . $e->getMessage());
            return response()->json(['message' => 'Nie udało się pobrać zadania.'], 500);
        }
    }

    public function update(Request $request, Task $task)
    {
        try {
            $this->authorize('update', $task);

            $data = $request->validate([
                'name'        => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'priority_id' => ['required', Rule::exists('priorities', 'id')],
                'status_id'   => ['required', Rule::exists('statuses', 'id')],
                'due_date'    => 'required|date|after_or_equal:today',
            ]);

            $original = $task->getOriginal();
            $task->update($data);

            foreach ($task->getChanges() as $field => $newValue) {
                if ($field !== 'updated_at') {
                    TaskHistory::create([
                        'task_id' => $task->id,
                        'field' => $field,
                        'old_value' => $original[$field] ?? null,
                        'new_value' => $newValue,
                        'user_id' => Auth::id(),
                    ]);
                }
            }

            return response()->json(['message' => 'Zadanie zaktualizowane.', 'data' => $task->load(['priority', 'status'])]);
        } catch (AuthorizationException) {
            return response()->json(['message' => 'Brak dostępu do edycji zadania.'], 403);
        } catch (Throwable $e) {
            Log::error("Błąd podczas aktualizacji zadania ID {$task->id}: " . $e->getMessage());
            return response()->json(['message' => 'Nie udało się zaktualizować zadania.'], 500);
        }
    }

    public function destroy(Task $task)
    {
        try {
            $this->authorize('delete', $task);
            $task->delete();

            return response()->json(['message' => 'Zadanie usunięte.']);
        } catch (AuthorizationException) {
            return response()->json(['message' => 'Brak dostępu do usunięcia zadania.'], 403);
        } catch (Throwable $e) {
            Log::error("Błąd podczas usuwania zadania ID {$task->id}: " . $e->getMessage());
            return response()->json(['message' => 'Nie udało się usunąć zadania.'], 500);
        }
    }

    public function history(Task $task)
    {
        try {
            $this->authorize('view', $task);

            $histories = TaskHistory::with('user')
                ->where('task_id', $task->id)
                ->latest()
                ->get();

            return response()->json(['data' => $histories]);
        } catch (AuthorizationException) {
            return response()->json(['message' => 'Brak dostępu do historii zadania.'], 403);
        } catch (Throwable $e) {
            Log::error("Błąd podczas ładowania historii zadania ID {$task->id}: " . $e->getMessage());
            return response()->json(['message' => 'Nie udało się pobrać historii zadania.'], 500);
        }
    }

    public function share(Request $request, Task $task)
    {
        try {
            $this->authorize('update', $task);

            $validated = $request->validate([
                'expires_in_hours' => 'nullable|integer|min:1|max:168',
            ]);

            $hours = $validated['expires_in_hours'] ?? 24;

            $task->update([
                'share_token' => bin2hex(random_bytes(16)),
                'share_token_expires_at' => now()->addHours($hours),
            ]);

            return response()->json([
                'message' => "Wygenerowano link publiczny (ważny {$hours}h).",
                'link' => url("/api/tasks/shared/{$task->share_token}")
            ]);
        } catch (AuthorizationException) {
            return response()->json(['message' => 'Brak dostępu do udostępnienia zadania.'], 403);
        } catch (Throwable $e) {
            Log::error("Błąd przy udostępnianiu zadania (ID {$task->id}): " . $e->getMessage());
            return response()->json(['message' => 'Nie udało się wygenerować linku publicznego.'], 500);
        }
    }

    public function sharedShow($token)
    {
        try {
            $task = Task::where('share_token', $token)
                        ->where('share_token_expires_at', '>=', now())
                        ->firstOrFail();

            return response()->json(['data' => $task]);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Nieprawidłowy lub wygasły link.'], 404);
        } catch (Throwable $e) {
            Log::error("Błąd podczas wyświetlania udostępnionego zadania: " . $e->getMessage());
            return response()->json(['message' => 'Nie udało się załadować zadania.'], 500);
        }
    }
}
