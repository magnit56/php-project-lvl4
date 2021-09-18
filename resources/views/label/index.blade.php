@extends('layouts.app')

@section('content')
    <h1 class="mb-5">Метки</h1>

    @can('create', App\Models\Label::class)
        <a href="/labels/create" class="btn btn-primary">Создать метку</a>
    @endcan

    <table class="table mt-2">
        <thead>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Описание</th>
            <th>Дата создания</th>
            <th>Действия</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($labels as $label)
            <tr>
                <td>{{ $label->id }}</td>
                <td>{{ $label->name }}</td>
                <td>{{ $label->description }} </td>
                <td>{{ \Carbon\Carbon::parse($label->created_at)->format('d.m.Y') }}</td>

                <td>
                    @can('delete', $label)
                        <a class="text-danger" href="/labels/{{ $label->id }}/" data-confirm="Вы уверены?" data-method="delete" rel="nofollow">Удалить</a>
                    @endcan

                    @can('update', $label)
                        <a href="/labels/{{ $label->id }}/edit">Изменить</a>
                    @endcan
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $labels->links('vendor.pagination.bootstrap-4') }}
@endsection
