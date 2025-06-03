<form action="{{ route('tasks.index') }}" method="GET" class="row g-3 mb-4">
    <div class="col-md-3">
        <label for="priority_id" class="form-label">Priorytet</label>
        <select name="priority_id" id="priority_id" class="form-select">
            <option value="">-- wszystkie --</option>
            @foreach($priorities as $pri)
                <option value="{{ $pri->id }}" {{ request('priority_id') == $pri->id ? 'selected' : '' }}>
                    {{ ucfirst($pri->name) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label for="status_id" class="form-label">Status</label>
        <select name="status_id" id="status_id" class="form-select">
            <option value="">-- wszystkie --</option>
            @foreach($statuses as $sta)
                <option value="{{ $sta->id }}" {{ request('status_id') == $sta->id ? 'selected' : '' }}>
                    {{ ucfirst($sta->name) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label for="due_from" class="form-label">Termin od</label>
        <input type="date" name="due_from" id="due_from" value="{{ request('due_from') }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label for="due_to" class="form-label">Termin do</label>
        <input type="date" name="due_to" id="due_to" value="{{ request('due_to') }}" class="form-control">
    </div>
    <div class="col-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-secondary me-2">Filtruj</button>
        <a href="{{ route('tasks.index') }}" class="btn btn-danger">Wyczyść</a>
    </div>
</form>
