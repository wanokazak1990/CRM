@extends ('layout')

@section('right')
	<div class="row">
		{!! Form::open(array('files'=>'true')) !!}
			<div class="col-sm-2">
				<label>Название файла:</label>
				<input type="text" name="name" class="form-control" placeholder="Введите название файла" value="{{ $file->name }}">
			</div>
			
			<div class="col-sm-2">
				{{ Form::label('title','Тип:')}}
				{{ Form::select('type_id',$types,$file->type_id, ['class'=>'form-control'])}}
			</div>
 
			<div class="col-sm-2">
				{!! Form::label('title','Бренд:')!!}
				{!! Form::select('brand_id',$brands,$file->brand_id,['class'=>'form-control']) !!}
			</div>
			 
			<div class="col-sm-2">
				<label>Модель:</label>
				<select class="model form-control" name="model_id">
					@foreach($models as $key => $model)
						<option value="{{ $key }}" <? if ($key == $file->model_id) echo ' selected'; ?> >{{ $model }}</option>
					@endforeach
				</select>
			</div>
			 
			<div class="col-sm-2">
				<label>Файл:</label>
				<input type="file" name="file">
			</div>
			
			<div class="clearfix"></div><br>

			<div class="adding-control">
				<div class="col-sm-2">
					<input type="submit" class="btn btn-primary form-control" value="Обновить" name="submit">
				</div>
				<div class="col-sm-2">
					<input type="submit" class="btn btn-danger form-control" value="Отмена" name="cansel">
				</div>
			</div>
			{{ csrf_field() }}
		{!! Form::close() !!}
	</div>
@endsection