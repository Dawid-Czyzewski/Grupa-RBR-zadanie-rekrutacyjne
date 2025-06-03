<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Przypomnienie o zadaniu</title>
</head>
<body>
    <h1>Cześć {{ $task->user->name ?? 'Użytkowniku' }}!</h1>

    <p>To przypomnienie o zadaniu:</p>

    <ul>
        <li><strong>Tytuł:</strong> {{ $task->title }}</li>
        <li><strong>Termin:</strong> {{ $task->due_date->format('Y-m-d') }}</li>
    </ul>

    <p>Nie zapomnij go wykonać!</p>
</body>
</html>
