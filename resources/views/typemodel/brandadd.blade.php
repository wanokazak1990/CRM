@extends('layout')



@section('right')
	{!! Form::open(array('files'=>'true')) !!}
		<div class="row">
			<div class="col-sm-2">
				{{ Form::label('title','Название:*')}} 
				{{ Form::text('name',@$type->name,['class'=>'form-control']) }}
			</div>

			<div class="col-sm-2">
				{{ Form::label('title','Иконка:*')}} 
				{{ Form::file('icon') }}
			</div>

			<div class="col-sm-2">
				@isset($type->icon)
					<?=$type->getIcon(70);?>
				@endisset
			</div>

			<div class="clearfix"></div>

			<div class="adding-control">
				<div class="col-sm-2">
					<input type="submit" value="Создать" name="submit" class="form-control btn btn-primary">
				</div>
				<div class="col-sm-2">
					<input type="submit" value="Отмена" name="cansel" class="form-control btn btn-danger">
				</div>
			</div>
		</div>
		{{ csrf_field() }}
	{!! Form::close() !!}
@endsection