@extends('layouts.app')

@section('content')
    <h1 class="mb-5">Создать статус</h1>
    <form method="POST" action="/task_statuses" accept-charset="UTF-8" class="w-50">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="name">Имя</label>
            <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" value="{{ old('name') ?? $taskStatus->name }}" id="name">

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="invalid-feedback">{{ $error }}</div>
                @endforeach
            @endif
        </div>
        <input class="btn btn-primary" type="submit" value="Создать">
    </form>
@endsection
