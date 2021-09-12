@extends('layouts.app')

@section('content')
    <h1 class="mb-5">Задачи</h1>

    <div class="d-flex">
        <div>
            <form method="GET" action="/tasks" accept-charset="UTF-8" class="form-inline">
                <select class="form-control mr-2" name="filter[status_id]">
                    <option selected="selected" value="">Статус</option>
                    @foreach($taskStatuses as $taskStatus)
                        <option value="{{ $taskStatus->id }}">{{ $taskStatus->name }}</option>
                    @endforeach
                </select>
                <select class="form-control mr-2" name="filter[created_by_id]">
                    <option selected="selected" value="">Автор</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                <select class="form-control mr-2" name="filter[assigned_to_id]">
                    <option selected="selected" value="">Исполнитель</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                <input class="btn btn-outline-primary mr-2" type="submit" value="Применить">
            </form>
        </div>
        @can('create', App\Models\Task::class)
            <a href="/tasks/create" class="btn btn-primary ml-auto">Создать задачу</a>
        @endcan
    </div>

    <table class="table mt-2">
        <thead>
        <tr>
            <th>ID</th>
            <th>Статус</th>
            <th>Имя</th>
            <th>Автор</th>
            <th>Исполнитель</th>
            <th>Дата создания</th>
            @auth
                <th>Действия</th>
            @endauth
        </tr>
        </thead>

        <tbody>
        @foreach ($tasks as $task)
            <tr>
                <td>{{ $task->id }}</td>
                <td>{{ $task->status->name }}</td>
                <td><a href="/tasks/{{ $task->id }}">{{ $task->name }}</a></td>
                <td>{{ $task->creator->name }}</td>
                <td>{{ optional($task->assignee)->name }}</td>
                <td>{{ \Carbon\Carbon::parse($task->created_at)->format('d.m.Y') }}</td>

                @auth
                    <td>
                        @can('delete', $task)
                            <a class="text-danger" href="/tasks/{{ $task->id }}/" data-confirm="Вы уверены?" data-method="delete" rel="nofollow">Удалить</a>
                        @endcan

                        @can('update', $task)
                            <a href="/tasks/{{ $task->id }}/edit">Изменить</a>
                        @endcan
                    </td>
                @endauth
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $tasks->links('vendor.pagination.bootstrap-4') }}
@endsection
