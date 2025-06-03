<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskHistory;
use App\Models\Priority;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Throwable;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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

            return view('tasks.index', [
                'tasks' => $tasks,
                'priorities' => Priority::all(),
                'statuses' => Status::all(),
            ]);
        } catch (Throwable $e) {
            Log::error("Błąd podczas ładowania listy zadań: " . $e->getMessage());
            return back()->with('error', 'Wystąpił błąd podczas ładowania zadań.');
        }
    }

    public function create()
    {
        return view('tasks.create', [
            'priorities' => Priority::all(),
            'statuses' => Status::all(),
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'priority_id' => 'required|exists:priorities,id',
                'status_id' => 'required|exists:statuses,id',
                'due_date' => 'required|date|after_or_equal:today',
            ]);

            Task::create([
                ...$validated,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('tasks.index')->with('success', 'Zadanie utworzone.');
        } catch (Throwable $e) {
            Log::error("Błąd podczas tworzenia zadania: " . $e->getMessage());
            return back()->withInput()->with('error', 'Nie udało się utworzyć zadania.');
        }
    }

    public function edit(Task $task)
    {
        try {
            $this->authorize('update', $task);

            return view('tasks.edit', [
                'task' => $task,
                'priorities' => Priority::all(),
                'statuses' => Status::all(),
            ]);
        } catch (AuthorizationException $e) {
            return redirect()->route('tasks.index')->with('error', 'Brak dostępu do edycji zadania.');
        } catch (Throwable $e) {
            Log::error("Błąd podczas edycji zadania ID {$task->id}: " . $e->getMessage());
            return back()->with('error', 'Nie udało się załadować formularza edycji.');
        }
    }

    public function update(Request $request, Task $task)
    {
        try {
            $this->authorize('update', $task);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'priority_id' => 'required|exists:priorities,id',
                'status_id' => 'required|exists:statuses,id',
                'due_date' => 'required|date|after_or_equal:today',
            ]);

            $original = $task->getOriginal();
            $task->update($validated);

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

            return redirect()->route('tasks.index')->with('success', 'Zadanie zaktualizowane.');
        } catch (AuthorizationException $e) {
            return redirect()->route('tasks.index')->with('error', 'Brak dostępu do edycji zadania.');
        } catch (Throwable $e) {
            Log::error("Błąd podczas aktualizacji zadania ID {$task->id}: " . $e->getMessage());
            return back()->withInput()->with('error', 'Nie udało się zaktualizować zadania.');
        }
    }

    public function show(Task $task)
    {
        try {
            $this->authorize('view', $task);
            return view('tasks.show', compact('task'));
        } catch (AuthorizationException $e) {
            return redirect()->route('tasks.index')->with('error', 'Brak dostępu do tego zadania.');
        }
    }

    public function destroy(Task $task)
    {
        try {
            $this->authorize('delete', $task);
            $task->delete();

            return redirect()->route('tasks.index')->with('success', 'Zadanie usunięte.');
        } catch (AuthorizationException $e) {
            return redirect()->route('tasks.index')->with('error', 'Brak dostępu do usunięcia zadania.');
        } catch (Throwable $e) {
            Log::error("Błąd podczas usuwania zadania ID {$task->id}: " . $e->getMessage());
            return back()->with('error', 'Nie udało się usunąć zadania.');
        }
    }

    public function history(Task $task)
    {
        try {
            $this->authorize('view', $task);

            return view('tasks.history', [
                'task' => $task,
                'taskHistories' => TaskHistory::with('user')
                    ->where('task_id', $task->id)
                    ->latest()
                    ->get(),
                'priorityMap' => Priority::pluck('name', 'id'),
                'statusMap' => Status::pluck('name', 'id'),
            ]);
        } catch (AuthorizationException $e) {
            return redirect()->route('tasks.index')->with('error', 'Brak dostępu do historii zadania.');
        } catch (Throwable $e) {
            Log::error("Błąd podczas ładowania historii zadania ID {$task->id}: " . $e->getMessage());
            return back()->with('error', 'Nie udało się załadować historii.');
        }
    }

    public function share(Request $request, Task $task)
    {
        try {
            $this->authorize('update', $task);

            $validated = $request->validate([
                'expires_in_hours' => 'nullable|integer|min:1|max:168',
            ]);

            $hours = isset($validated['expires_in_hours']) ? (int) $validated['expires_in_hours'] : 24;

            $task->update([
                'share_token' => bin2hex(random_bytes(16)),
                'share_token_expires_at' => now()->addHours($hours),
            ]);

            $link = url("/tasks/shared/{$task->share_token}");

            return redirect()
                ->route('tasks.show', $task)
                ->with('success', "Wygenerowano link publiczny (ważny {$hours}h): $link");

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect()->route('tasks.index')
                ->with('error', 'Brak uprawnień do udostępnienia zadania.');
        } catch (\Throwable $e) {
            \Log::error("Błąd przy udostępnianiu zadania (ID {$task->id}): " . $e->getMessage(), [
                'task_id' => $task->id,
                'user_id' => auth()->id(),
            ]);
            return back()->with('error', 'Nie udało się wygenerować linku publicznego.');
        }
    }

    public function sharedShow($token)
    {
        try {
            $task = Task::where('share_token', $token)
                        ->where('share_token_expires_at', '>=', now())
                        ->firstOrFail();

            return view('tasks.shared', compact('task'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('tasks.index')->with('error', 'Nieprawidłowy lub wygasły link.');
        } catch (Throwable $e) {
            Log::error("Błąd podczas wyświetlania udostępnionego zadania: " . $e->getMessage());
            return redirect()->route('tasks.index')->with('error', 'Nie udało się załadować zadania.');
        }
    }
}
