@extends ('layout')

@section('right')
	<div>
		{!! Form::open(array('files'=>'true')) !!}
			<label>Название типа:</label>
			<input type="text" name="name" class="form-control" placeholder="Название типа">
			<br>
			<label>Иконка:</label>
			<input type="file" name="file">
			<br>
			<input type="submit" value="Создать" name="submit">
			<input type="submit" value="Отмена" name="cansel">
			{{ csrf_field() }}
		{!! Form::close() !!}
	</div>
@endsection