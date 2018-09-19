@extends('layouts.blog')
@section('content')

    @if ($posts->count())
    @foreach($posts as $post)
        <div><b>{{$post->happened_at}}</b>
        @foreach($post->tags as $tag)
            <a href="{{route('post.tag.list',$tag->slug)}}"><b style='color:#A28D2B'>{{$tag->name}}</b></a>@unless ($loop->last),@endunless
        @endforeach
        </div>
        <div>{{$post->body}}</div>
        <a href="{{route('posts.edit',['id' => $post->id])}}">Редактировать</a>
        <hr>
    @endforeach
    @else
    пусто   
    
    @endif
<a href="{{route('posts.create')}}">Добавить</a>
@endsection