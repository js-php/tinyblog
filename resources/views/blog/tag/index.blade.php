@extends('layouts.myblog')
@section('content')

    @if ($tags->count())
    @foreach($tags as $tag)
            <a href="{{route('blogpost.tag.postlist',['tagname' => $tag->id])}}">{{$tag->name}}</a>@unless ($loop->last),@endunless
    @endforeach
    @else
    пусто   
    
    @endif
@endsection