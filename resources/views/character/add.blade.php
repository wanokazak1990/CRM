@extends ('layout')

@section('right')
{!! Form::open(array('files'=>'true')) !!}
	<div class="row">
		<div class="col-sm-2">
			{{Form::label('title','Название:*')}}
			<input type="text" name="name" class="form-control" placeholder="">	
		</div>

		<div class="clearfix"></div>

		<div class="adding-control">
			<div class="col-sm-2">
				<input type="submit" value="Создать" name="submit" class="btn btn-primary form-control">
			</div>
			<div class="col-sm-2">
				<input type="submit" value="Отмена" name="cansel" class="btn btn-danger form-control">
			</div>
		</div>
	</div>
	{{ csrf_field() }}
{!! Form::close() !!}
@endsection