@extends ('layout')

@section('right')
{!! Form::open(array('files'=>'true')) !!}
	<input type="text" name="name" class="form-control" placeholder="Введите название характеристики">
	<input type="submit" value="Создать" name="submit">
	<input type="submit" value="Отмена" name="cansel">
	{{ csrf_field() }}
{!! Form::close() !!}
@endsection