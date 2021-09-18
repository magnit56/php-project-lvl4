@extends('layouts.app')

@section('content')
    <h1 class="mb-5">Создать задачу</h1>
    <form method="POST" action="/tasks" accept-charset="UTF-8" class="w-50">
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

        <div class="form-group">
            <label for="status_id">Статус</label>
            <select class="form-control @error('status_id') is-invalid @enderror" id="status_id" name="status_id">
                <option @empty(old('status_id')) selected="selected" @endempty value="">----------</option>
                @foreach($taskStatuses as $taskStatus)
                    <option @if(old('status_id') == $taskStatus->id) selected="selected" @endif value="{{ $taskStatus->id }}">{{ $taskStatus->name }}</option>
                @endforeach
            </select>

            @error('status_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>


        <div class="form-group">
            <label for="assigned_to_id">Исполнитель</label>
            <select class="form-control @error('assigned_to_id') is-invalid @enderror" id="assigned_to_id" name="assigned_to_id">
                <option @empty(old('assigned_to_id')) selected="selected" @endempty value="">----------</option>
                @foreach($users as $user)
                    <option @if(old('assigned_to_id') == $user->id) selected="selected" @endif value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>

            @error('assigned_to_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="labels">Метки</label>
            <select class="form-control" multiple="" name="labels[]">
                <option value=""></option>
                @foreach($labels as $label)
                    <option @if(collect(old('labels'))->contains($label->id)) selected="selected" @endif value="{{ $label->id }}">{{ $label->name }}</option>
                @endforeach
            </select>

            @error('labels')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            @error('labels.*')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <input class="btn btn-primary" type="submit" value="Создать">
    </form>
@endsection
