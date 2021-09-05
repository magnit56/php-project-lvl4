@extends('layouts.app')

@section('content')
    <h1 class="mb-5">Изменение статуса</h1>
    <form method="POST" action="/task_statuses/{{ $taskStatus->id }}" accept-charset="UTF-8" class="w-50">
        <input name="_method" type="hidden" value="PATCH">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="name">Имя</label>
            <input class="form-control" name="name" type="text" value="{{ $taskStatus->name }}" id="name">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <input class="btn btn-primary" type="submit" value="Обновить">
    </form>
@endsection
