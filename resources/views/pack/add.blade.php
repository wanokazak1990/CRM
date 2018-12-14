@extends('layout')



@section('right')
	{!! Form::open(array('files'=>'true')) !!}
	<div class="row">
		<div class="col-sm-2">
			{!! Form::label('title', 'Бренд:') !!}
			{!! Form::select('brand_id',$brands,$pack->brand_id,['class'=>'form-control']) !!}
		</div>

		<div class="col-sm-2">
			{!! Form::label('title', 'Цветовой пакет:') !!}<br>
			<input class="" value="1" type="checkbox" name="type" <?=($pack->type)?"checked":"";?>>
		</div>

		<div class="clearfix"></div>

		<div class="col-sm-3">
		{!! Form::label('title', 'Название:') !!}
			{!! Form::text('name', $pack->name, ['class' => 'form-control']) !!}
		</div>

		<div class="col-sm-2">
		{!! Form::label('title', 'Код:') !!}
			{!! Form::text('code', $pack->code, ['class' => 'form-control']) !!}
		</div>

		<div class="col-sm-2">
		{!! Form::label('title', 'Цена:') !!}
			{!! Form::text('price', $pack->price, ['class' => 'form-control']) !!}
		</div>
		
		<div class="clearfix"></div>

		<div class="col-sm-12"><h3>Доступные модели</h3></div>
		<div class="model col-sm-12">
			@isset($models)
				@foreach($models as $key => $model)
					<span>
						<label>
							<input 
								type="checkbox" 
								name="pack_model[]" 
								value="{{$model->id}}"
								<?php
									if(isset($pack->model) && $pack->model->contains('model_id',$model->id)) : 
										echo "checked";
									endif;
								?>
							>
							{{ $model->name }}
						</label>
					</span>
				@endforeach
			@endisset
		</div>

		<div class="col-sm-12"><h3>Составное оборудование</h3></div>
		<div class="option">
			@isset($options)
				@foreach($options as $key => $option)
					<div class="col-sm-3">
						<label>
							<input 
								type="checkbox" 
								name="pack_option[]" 
								value="{{$option->id}}"
								<?php
									if(isset($pack->option) && $pack->option->contains('option_id',$option->id)) : 
										echo "checked";
									endif;
								?>
							>
							{{ $option->name }}
						</label>
					</div>
				@endforeach
			@endisset
		</div>

		<!-- ----------------------------------------------------------------------- -->
		<div class="clearfix"></div>
		
		<div class="adding-control">
			<div class="col-sm-2">
				{!! Form::submit('Создать',	 ['class' => 'form-control btn btn-primary','name'=>'submit']) !!}
			</div>
			<div class="col-sm-2"> 
				{!! Form::submit('Отмена',	 ['class' => 'form-control btn btn-danger','name'=>'cansel']) !!}
			</div>
		</div>
	</div>
	{!! Form::close() !!}
@endsection