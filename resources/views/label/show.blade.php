@extends('layouts.app')

@section('content')
    <h1 class="mb-5">Просмотр метки: {{ $label->name }}<a href="/labels/{{ $label->id }}/edit">⚙</a></h1>
    <p>Имя: {{ $label->name }}</p>
    <p>Описание: {{ $label->description }}</p>
@endsection
