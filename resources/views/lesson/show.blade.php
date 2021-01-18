<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>{{ $lesson->name }}</h1>
    <div>
        <span>空き状況: {{ $lesson->vacancyLevel->mark() }}</span>
    </div>
    @if(strcmp($lesson->vacancyLevel->mark(), "×") === 0)
        <span class="btn btn-primary" disabled>予約できません</span>
    @else
        <button class="btn btn-primary">このレッスンを予約する</button>
    @endif
</body>
</html>
