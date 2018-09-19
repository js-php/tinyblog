@push('extralinks')
    @include('extra.jquery')
    @include('extra.jqueryui')
    @jscode('blog.create')
@endpush
@extends('layouts.blog')
@section('content')
    <div>
    <form method='post' action='{{route('posts.store')}}'>
      @csrf
      <div class="form-group">
        <label for="happened_at">Дата публикации</label>
        <input name="happened_at" type="text" class="form-control" id="happened_at" placeholder="Дата (текстом) дд.мм.гггг" value="{{old('happened_at')}}">
      </div>
      <div class="form-group">
        <label for="body">Описание событий</label>
        <textarea name="body" class="form-control" id="body" rows="21">{{old('body')}}</textarea>
      </div>
      <div class="form-group">
        <label for="tags">Теги</label>
        <input name="tags" type="text" class="form-control" id="tags" placeholder="Введите теги через запятую" value="{{old('tags')}}">
      </div>
      <input class="btn btn-primary" type="submit" value="Отправить" name="save_and_exit">
      <input class="btn btn-primary" dis1abled="disabled" type="submit" value="Сохранить и продолжить редактирование" name="save_and_edit">
    </form>

    @if($errors->any())
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Не удалось сохранить.</strong> Необходимо исправить следующие ошибки:
                @foreach($errors->all() as $er)
                    <div>{{$er}}</div>
                @endforeach
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <script>
        $(function(){
            LibAutocomplete.init(@json($tags));
        });
    </script>
@endsection