@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Title</h1>
        <p>{{ $post->title }}</p>
        <h2> Descriptopn</h2>
        <p>{{ $post->description }}</p>
        <hr>
        <h3>Comments</h3>
        @include('hazem.c', ['comments' => $post->comments, 'pos_id' => $post->id])
        <form action="{{ route('comment.store') }}" method="POST">
            @csrf
            <input type="text" name="description">
            <input type="hidden" name="pos_id" value="{{ $post->id }}">
            <input type="hidden" name="parent_id">
            <input type="submit" value="addcomment">
        </form>
    </div>
@endsection
