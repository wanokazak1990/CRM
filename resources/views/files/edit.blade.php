@extends ('layout')

@section('right')
	<div>
		{!! Form::open(array('files'=>'true')) !!}
			<div class="col-sm-3">
				<label>Название файла:</label>
				<input type="text" name="name" class="form-control" placeholder="Введите название файла" value="{{ $file->name }}">
			</div>
			
			<div class="clearfix"></div><br>

			<div class="col-sm-3">
				{{ Form::label('title','Тип:')}}
				{{ Form::select('type_id',$types,$file->type_id, ['class'=>'form-control'])}}
			</div>

			<div class="clearfix"></div><br>

			<div class="col-sm-3">
				{!! Form::label('title','Бренд:')!!}
				{!! Form::select('brand_id',$brands,$file->brand_id,['class'=>'form-control']) !!}
			</div>
			
			<div class="clearfix"></div><br>

			<div class="col-sm-3">
				<label>Модель:</label>
				<select class="model form-control" name="model_id">
					@foreach($models as $key => $model)
						<option value="{{ $key }}" <? if ($key == $file->model_id) echo ' selected'; ?> >{{ $model }}</option>
					@endforeach
				</select>
			</div>
			
			<div class="clearfix"></div><br>

			<div class="col-sm-3">
				<label>Файл:</label>
				<input type="file" name="file">
			</div>
			
			<div class="clearfix"></div><br>

			<div class="col-sm-12">
				<input type="submit" class="btn btn-success col-sm-1" value="Обновить" name="submit">
				<input type="submit" class="btn btn-primary col-sm-1" value="Отмена" name="cansel">
			</div>
			{{ csrf_field() }}
		{!! Form::close() !!}
	</div>
@endsection