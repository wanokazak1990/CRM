@extends('layout')



@section('right')
	{!! Form::open(array('files'=>'true')) !!}
		<input type="text" name="name" value="{{ @$brand->name }}">
		<input type="submit" value="Создать" name="submit">
		<input type="submit" value="Отмена" name="cansel">
		{{ csrf_field() }}
	{!! Form::close() !!}
@endsection