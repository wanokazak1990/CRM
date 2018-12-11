@extends ('layout')

@section('right')
	<div>
		{!! Form::open(array('files'=>'true')) !!}
			<div class="col-sm-3">
				<label>Название файла:</label>
				<input type="text" name="name" class="form-control" placeholder="Введите название файла">
			</div>
			
			<div class="clearfix"></div><br>

			<div class="col-sm-3">
				{{ Form::label('title','Тип:')}}
				{{ Form::select('type_id', $types, '', ['class'=>'form-control'])}}
			</div>
			
			<div class="clearfix"></div><br>

			<div class="col-sm-3">
				{!! Form::label('title','Бренд:')!!}
				{!! Form::select('brand_id',$brands,'',['class'=>'form-control']) !!}
			</div>
			
			<div class="clearfix"></div><br>

			<div class="col-sm-3">
				<label>Модель:</label>
				<select class="form-control model" name="model_id">	
				</select>
			</div>
			
			<div class="clearfix"></div><br>

			<div class="col-sm-3">
				<label>Файл:</label>
				<input type="file" name="file">
			</div>
			
			<div class="clearfix"></div><br>

			<div class="col-sm-12">
				<input type="submit" class="btn btn-success col-sm-1" value="Создать" name="submit">
				<input type="submit" class="btn btn-primary col-sm-1" value="Отмена" name="cansel">
			</div>
			{{ csrf_field() }}
		{!! Form::close() !!}
	</div>
@endsection