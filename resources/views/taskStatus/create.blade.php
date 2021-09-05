@extends('layouts.app')

@section('content')
    <h1 class="mb-5">Создать статус</h1>
    <form method="POST" action="/task_statuses" accept-charset="UTF-8" class="w-50">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="name">Имя</label>
            <input class="form-control" name="name" type="text" id="name">

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
        <input class="btn btn-primary" type="submit" value="Создать">
    </form>
@endsection
