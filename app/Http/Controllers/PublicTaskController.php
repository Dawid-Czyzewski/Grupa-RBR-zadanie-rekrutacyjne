<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PublicTaskController extends Controller
{
    public function show(string $token)
    {
        try {
            $task = Task::where('share_token', $token)->firstOrFail();

            if (! $task->isShareTokenValid()) {
                throw new HttpException(403, 'Dostęp do zadania został zablokowany.');
            }

            return view('tasks.public_show', compact('task'));
        } catch (ModelNotFoundException $e) {
            Log::warning("Nie znaleziono zadania z tokenem: {$token}");
            abort(404, 'Nie znaleziono zadania.');
        } catch (HttpException $e) {
            Log::info("Nieautoryzowany dostęp do zadania z tokenem: {$token}");
            abort($e->getStatusCode(), $e->getMessage());
        } catch (\Throwable $e) {
            Log::error("Błąd wewnętrzny podczas wyświetlania zadania z tokenem: {$token}. Szczegóły: " . $e->getMessage());
            abort(500, 'Wystąpił błąd podczas wyświetlania zadania.');
        }
    }
}
