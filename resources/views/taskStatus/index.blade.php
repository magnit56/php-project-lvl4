@extends('layouts.app')

@section('content')
    <h1 class="mb-5">Статусы</h1>

    @can('create', App\Models\TaskStatus::class)
        <a href="/task_statuses/create" class="btn btn-primary">Создать статус</a>
    @endcan

    <table class="table mt-2">
        <thead>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Дата создания</th>
            <th>Действия</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($taskStatuses as $taskStatus)
            <tr>
                <td>{{ $taskStatus->id }}</td>
                <td>{{ $taskStatus->name }}</td>
                <td>{{ \Carbon\Carbon::parse($taskStatus->created_at)->format('d.m.Y') }}</td>

                <td>
                    @can('delete', $taskStatus)
                        <a class="text-danger" href="/task_statuses/{{ $taskStatus->id }}/" data-confirm="Вы уверены?" data-method="delete" rel="nofollow">Удалить</a>
                    @endcan

                    @can('update', $taskStatus)
                        <a href="/task_statuses/{{ $taskStatus->id }}/edit">Изменить</a>
                    @endcan
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $taskStatuses->links('vendor.pagination.bootstrap-4') }}
@endsection
