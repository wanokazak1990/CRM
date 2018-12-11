@extends('layout')



@section('right')
	{!! Form::open(array('files'=>'true')) !!}
	<div class="col-sm-4"> 

		<h3>Основные данные модели</h3>

		{!! Form::label('title', 'Бренд: *') !!}
			{!! Form::select('brand_id',$brands,$model->brand_id, ['class' => 'form-control']) !!}
		<br>
		{!! Form::label('title', 'Тип авто: *') !!}
			{!! Form::select('type_id',$types,$model->type_id, ['class' => 'form-control']) !!}
		<br>
		{!! Form::label('title', 'Производство: *') !!}
			{!! Form::select('country_id',$countrys,$model->country_id, ['class' => 'form-control']) !!}
		<br>
		{!! Form::label('title', 'Название: *') !!}
			{!! Form::text('name', $model->name, ['class' => 'form-control']) !!}
		<br>
		{!! Form::label('title','Префикс:') !!}
			{!! Form::text('label',$model->label, ['class' => 'form-control']) !!}
		<br>
		{!! Form::label('title', 'Ссылка: *') !!}
			{!! Form::text('link', $model->link, ['class' => 'form-control']) !!}
		<br>
		
		{!! Form::label('title','Слоган: *') !!}
			{!! Form::text('slogan',$model->slogan, ['class' => 'form-control']) !!}
		<br>
		{!! Form::label('title','Описание: *') !!}
			{!! Form::textarea('text',$model->text,['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-8">
		<div class="row ">
			<div class="col-sm-12 "><h3>Изображение модели</h3></div>
			<div class="col-sm-4"> 
				{!! Form::label('title','Баннер') !!}<br>
					@isset($model->banner)
						<img src="{{ Storage::url(('images/'.$model->link).'/'.$model->banner) }}" style="height: 100px;">
					@endisset
					{!! Form::file('banner') !!}
			</div>

			<div class="col-sm-4"> 
				{!! Form::label('title','Иконка') !!}<br>
					@isset($model->icon)
						<img src="{{ Storage::url(('images/'.$model->link).'/'.$model->icon) }}" style="height: 100px;">
					@endisset
					{!! Form::file('icon') !!}
			</div>
		
			<div class="col-sm-4"> 
				{!! Form::label('title','Альфа') !!}<br>
					@isset($model->alpha)
						<img src="{{ Storage::url(('images/'.$model->link).'/'.$model->alpha) }}" style="height: 100px;">
					@endisset
					{!! Form::file('alpha') !!}
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12"><h3>Палитра цветов</h3></div>
			<div class="color">
			@foreach($model->colorBybrand as $color )
				<div class="col-sm-2">
					<div>{{ $color->name }} ({{ $color->rn_code }})</div>
					<div style="border:1px solid #ccc;height:20px;background: {{ $color->web_code }}"></div>
					<label>
						<input 
							type="checkbox" 
							name="color_id[]" 
							value="{{ $color->id }}"
							<?php
								if(isset($model->colorBymodel))
									if($model->colorBymodel->contains('color_id',$color->id))
									{
										echo "checked";
									}
									else
									{
										echo "";
									}
								else
									echo "";
							?>
						> 
						Использовать
					</label>
				</div>
			@endforeach
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
			<h3>Характеристики</h3>
			@foreach($characters as $key => $character)
			<div class="col-sm-6" style="padding-bottom: 10px;">
			<label>{{ $character }}</label>
				<input type="text" name="char[ {{ $key }} ]" class="form-control" placeholder="{{ $character }}" title="{{ $character }}" 
				value="<?php 
				if (isset($model_characters))
				{
					foreach ($model_characters as $char) 
					{
						if ($key == $char->character_id)
						{
							echo $char->value;
						}
					}
				}
				?>">
			</div>
			@endforeach
			</div>
		</div>

	</div>

	<div style="clear: left;">
		{!! Form::submit('Создать',	 ['class' => 'form-control','name'=>'submit']) !!}
		{!! Form::submit('Отмена',	 ['class' => 'form-control','name'=>'cansel']) !!}
	</div>

	{!! Form::close() !!}
@endsection