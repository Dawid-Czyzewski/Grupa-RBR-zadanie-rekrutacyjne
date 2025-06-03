<div class="card mb-4">
    <div class="card-header">
        <h3 class="h6 mb-0">Udostępnij zadanie publicznie</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('tasks.share', $task) }}" method="POST" class="row g-2 align-items-center">
            @csrf
            <div class="col-auto">
                <label for="expires_in_hours" class="form-label">Ważne przez (godziny):</label>
                <input
                    type="number"
                    name="expires_in_hours"
                    id="expires_in_hours"
                    value="24"
                    min="1"
                    max="168"
                    class="form-control"
                    style="width: 100px;"
                >
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mt-4">
                    Wygeneruj link
                </button>
            </div>
        </form>

        @if($task->share_token && $task->isShareTokenValid())
            <div class="mt-3">
                <label class="form-label">
                    Link publiczny 
                    @if($task->share_token_expires_at)
                        (ważny do {{ $task->share_token_expires_at->format('Y-m-d H:i') }})
                    @endif
                    :
                </label>
                <div class="input-group">
                    <input
                        type="text"
                        class="form-control"
                        readonly
                        value="{{ route('tasks.shared.show', $task->share_token) }}"
                    >
                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        onclick="navigator.clipboard.writeText('{{ route('tasks.shared.show', $task->share_token) }}')"
                    >
                        Kopiuj
                    </button>
                </div>
            </div>
        @elseif($task->share_token && ! $task->isShareTokenValid())
            <div class="alert alert-warning mt-3">
                Poprzedni link wygasł
                @if($task->share_token_expires_at)
                    ({{ $task->share_token_expires_at->format('Y-m-d H:i') }})
                @endif
                . Możesz wygenerować nowy.
            </div>
        @endif
    </div>
</div>
