@extends('layout')



@section('right')
	{!! Form::open() !!}
		<div class="row">
			<div class="col-sm-12">Вы действительно хотите удалить {{ $type->name }}?</div>
			
			<div class="adding-control">
				<div class="col-sm-2">
					<input type="submit" value="Отмена" name="cansel" class="form-control btn btn-success">
				</div>
				<div class="col-sm-2">
					<input type="submit" value="Удалить" name="delete" class="form-control btn btn-danger">
				</div>
			</div>
		</div>
		<input type="hidden" name="_method" value="delete">
		{{ csrf_field() }}
	{!! Form::close() !!}
@endsection