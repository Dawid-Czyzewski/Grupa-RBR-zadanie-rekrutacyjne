@csrf

<div class="mb-3">
    <label for="name" class="form-label">Nazwa zadania <span class="text-danger">*</span></label>
    <input
        type="text"
        name="name"
        id="name"
        value="{{ old('name', $task->name ?? '') }}"
        class="form-control @error('name') is-invalid @enderror"
        maxlength="255"
        required
    >
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">Opis</label>
    <textarea
        name="description"
        id="description"
        rows="3"
        class="form-control @error('description') is-invalid @enderror"
        maxlength="1000"
    >{{ e(old('description', $task->description ?? '')) }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="priority_id" class="form-label">Priorytet <span class="text-danger">*</span></label>
        <select
            name="priority_id"
            id="priority_id"
            class="form-select @error('priority_id') is-invalid @enderror"
            required
        >
            <option value="" disabled {{ old('priority_id', $task->priority_id ?? '') == '' ? 'selected' : '' }}>
                -- wybierz --
            </option>
            @foreach($priorities as $pri)
                <option
                    value="{{ $pri->id }}"
                    {{ old('priority_id', $task->priority_id ?? '') == $pri->id ? 'selected' : '' }}
                >
                    {{ \Illuminate\Support\Str::ucfirst($pri->name) }}
                </option>
            @endforeach
        </select>
        @error('priority_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="status_id" class="form-label">Status <span class="text-danger">*</span></label>
        <select
            name="status_id"
            id="status_id"
            class="form-select @error('status_id') is-invalid @enderror"
            required
        >
            <option value="" disabled {{ old('status_id', $task->status_id ?? '') == '' ? 'selected' : '' }}>
                -- wybierz --
            </option>
            @foreach($statuses as $sta)
                <option
                    value="{{ $sta->id }}"
                    {{ old('status_id', $task->status_id ?? '') == $sta->id ? 'selected' : '' }}
                >
                    {{ \Illuminate\Support\Str::ucfirst($sta->name) }}
                </option>
            @endforeach
        </select>
        @error('status_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label for="due_date" class="form-label">Termin wykonania <span class="text-danger">*</span></label>
    <input
        type="date"
        name="due_date"
        id="due_date"
        value="{{ old('due_date', optional($task->due_date)->format('Y-m-d')) }}"
        class="form-control @error('due_date') is-invalid @enderror"
        required
        min="{{ now()->format('Y-m-d') }}"
    >
    @error('due_date')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="d-flex justify-content-between">
    <button type="submit" class="btn btn-primary">
        {{ isset($task) ? 'Aktualizuj' : 'Dodaj zadanie' }}
    </button>
    <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">Anuluj</a>
</div>
