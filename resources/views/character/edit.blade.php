@extends ('layout')

@section('right')
{!! Form::open(array('files'=>'true')) !!}
	<input type="text" name="name" class="form-control" placeholder="Введите название характеристики" value="{{ $character->name }}">
	<input type="submit" value="Обновить" name="submit">
	<input type="submit" value="Отмена" name="cansel">
	{{ csrf_field() }}
{!! Form::close() !!}
@endsection