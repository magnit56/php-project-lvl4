@extends('layouts.app')

@section('content')
    <h1 class="mb-5">Просмотр задачи: {{ $task->name }}<a href="/tasks/{{ $task->id }}/edit">⚙</a>
    </h1>
    <p>Имя: {{ $task->name }}</p>
    <p>Статус: {{ $task->status->name }}</p>
    <p>Описание: {{ $task->description }}</p>
    @isset($task->labels)
    <p>Метки:</p>
    @foreach($task->labels as $label)
    <ul>
        <li>{{ $label->name }}</li>
    </ul>
    @endforeach
    @endisset
@endsection

