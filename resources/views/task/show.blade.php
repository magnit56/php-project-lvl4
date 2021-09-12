@extends('layouts.app')

@section('content')
    <h1 class="mb-5">Просмотр задачи: {{ $task->name }}<a href="/tasks/{{ $task->id }}/edit">⚙</a>
    </h1>
    <p>Имя: {{ $task->name }}</p>
    <p>Статус: {{ $task->status->name }}</p>
    <p>Описание: {{ $task->description }}</p>
    <p>Метки:</p>
{{--    <ul>--}}
{{--        <li>Метка</li>--}}
{{--    </ul>--}}
@endsection

