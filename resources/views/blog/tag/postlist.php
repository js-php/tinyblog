@extends('layouts.myblog')
@section('content')

    @if ($posts->count())
    @foreach($posts as $post)
            <div>{{$post->name}}</div>
    @endforeach
    @else
    пусто   
    
    @endif
@endsection