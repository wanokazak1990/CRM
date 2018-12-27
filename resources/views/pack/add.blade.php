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
					<div class="col-sm-1">
						<label class="text-center">
							<img src="{{ Storage::url(('images/'.$model->link).'/'.$model->alpha) }}" >
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
					</div>
				@endforeach
			@endisset
		</div>

		<div class="col-sm-12"><h3>Составное оборудование</h3></div>
		<div class="option col-sm-12">
			@isset($options)
				@foreach($options as $key => $option)
					@if($key == 0 )
						<div class="">
							<h4>{{App\option_parent::find($option->parent_id)->name}}</h4>
						</div>
						<div class="column">
					@elseif($options[$key-1]->parent_id != $option->parent_id)
						</div>
						<div class="">
							<h4>{{App\option_parent::find($option->parent_id)->name}}</h4>
						</div>
						<div class="column">
					@endif
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
								{{mb_strimwidth($option->name, 0, 40, "...")}}
								@if(mb_strlen($option->name)>40)
									<span 
										style="float: right; margin-top: -13px;" 
										class="glyphicon glyphicon-info-sign" 
										data-toggle="tooltip" 
										data-placement="top"
										title="{{$option->name}}"
									>
									</span>
								@endif
							</label>
				@endforeach
						</div>
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