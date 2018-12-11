@extends('layout')



@section('right')
	{!! Form::open() !!}
		<div>Вы действительно хотите удалить {{ $company->name }}?</div>
		<input type="submit" value="Удалить" name="delete">
		<input type="submit" value="Отмена" name="cansel">
		<input type="hidden" name="_method" value="delete">
		{{ csrf_field() }}
	{!! Form::close() !!}
@endsection