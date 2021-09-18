@extends('layouts.app')

@section('content')
    <h1 class="mb-5">Создать метку</h1>
    <form method="POST" action="/labels" accept-charset="UTF-8" class="w-50">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="name">Имя</label>
            <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" value="{{ old('name') }}" id="name">

            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Описание</label>
            <textarea class="form-control" name="description" cols="50" rows="10" id="description">{{ old('description') }}</textarea>
        </div>
        <input class="btn btn-primary" type="submit" value="Создать">

        @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </form>
@endsection
