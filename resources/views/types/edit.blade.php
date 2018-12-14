@extends ('layout')

@section('right')
	<div class="row">
		{!! Form::open(array('files'=>'true')) !!}
		<div class="col-sm-2">
			<label>Название типа:</label>
			<input type="text" name="name" class="form-control" placeholder="Название типа" value="{{ $type->name }}">
		</div>

		<div class="col-sm-2">
			<label>Иконка:</label>
			<input type="file" name="file">
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
			{{ csrf_field() }}
		{!! Form::close() !!}
	</div>
@endsection