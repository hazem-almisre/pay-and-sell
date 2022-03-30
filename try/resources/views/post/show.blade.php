@extends('prodect.layout')

@section('content')
    <div class="container">
        <h1>{{ $post->title }}</h1>
        <h1>{{ $post->content }}</h1>
        <h1><img src="{{ url($post->photo) }}" alt=""></h1>
    </div>
@endsection
