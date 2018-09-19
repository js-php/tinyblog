@extends('layouts.blog')
@section('content')

    @if ($posts->count())
    @foreach($posts as $post)
            <div>
                <b>{{$post->happened_at}}</b>
                @foreach($post->tags as $tag)
                    @if($tag->id == $tag->id)
                    <b style='color:#00BCD4'>{{$tag->name}}</b>@unless ($loop->last),@endunless
                    @else
                    <b style='color:#A28D2B'>{{$tag->name}}</b>@unless ($loop->last),@endunless
                    @endif
                @endforeach
            </div>
            <div>{{$post->body}}</div>
            <hr>
    @endforeach
    @else
    пусто   
    
    @endif
@endsection