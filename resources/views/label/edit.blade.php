@extends('layouts.app')

@section('content')
    <h1 class="mb-5">Изменение метки</h1>
    <form method="POST" action="/labels/{{ $label->id }}" accept-charset="UTF-8" class="w-50">
        <input name="_method" type="hidden" value="PATCH">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="name">Имя</label>
            <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" value="{{ old('name', $label->name) }}" id="name">

            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Описание</label>
            <textarea class="form-control @error('description') is-invalid @enderror" name="description" cols="50" rows="10"  id="description">{{ old('description') ?? $label->description }}</textarea>
        </div>

        <input class="btn btn-primary" type="submit" value="Обновить">
    </form>
@endsection
