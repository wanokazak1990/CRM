@extends('layout')



@section('right')
	{!! Form::open(array('files'=>'true')) !!}
	<div class="row">
		<div class="col-sm-2">
			{!! Form::label('title','Бренд:') !!}
			{!! Form::select('brand_id',$brands,$complect->brand_id,['class'=>'form-control']) !!}			
		</div>

		<div class="clearfix"></div> 

		<div class="col-sm-2">
			{!! Form::label('title','Модель:') !!}
			<!--select name="model_id" class="model form-control"></select-->
			{!! Form::select('model_id',isset($models)?$models:[],$complect->model_id,['class'=>'form-control model']) !!}
		</div>

		<div class="col-sm-2">
			{!! Form::label('title','Мотор:') !!}
			<!--select name="motor_id" class="motor form-control"></select-->
			{!! Form::select('motor_id',isset($motors)?$motors:[],$complect->motor_id,['class'=>'form-control motor']) !!}
		</div>

		<div class="clearfix"></div> 

		<div class="col-sm-2">
			{!! Form::label('title', 'Название:') !!}
			{!! Form::text('name', $complect->name, ['class' => 'form-control']) !!}
		</div>

		<div class="col-sm-2">
			{!! Form::label('title', 'Код:') !!}
			{!! Form::text('code', $complect->code, ['class' => 'form-control']) !!}
		</div>

		<div class="col-sm-2">
			{!! Form::label('title', 'Стоимость:') !!}
			{!! Form::text('price', ($complect->price)?$complect->price:'', ['class' => 'form-control']) !!}
		</div>
		
		<div class="clearfix"></div>

		<div class="col-sm-12"><h3>Доступные цвета</h3></div>
		<div class="color">
			@isset($colors)
				@foreach($colors as $color)
					<div class="col-sm-2">
						<div>{{ $color->name }} ({{ $color->rn_code }})</div>
						<div style="border:1px solid #ccc;height:20px;background: {{ $color->web_code }}"></div>
						<label>
							<input 
								type="checkbox" 
								name="color_id[]" 
								value="{{ $color->id }}"
								<?php
									if($installs['install_colors']->contains('color_id',$color->id))
										echo "checked";
								?>
							> 
							Использовать
						</label>
					</div>
				@endforeach
			@endisset
		</div>
		
		<div class="clearfix"></div>

		<div class="col-sm-12"><h3>Оборудование</h3></div>
		<div class="option">
			@isset($options)
				@foreach($options as $option)
					<div class="col-sm-3">
						<label>
							<input 
								type='checkbox' 
								name='pack_option[]' 
								value='{{ $option->id }}'
								<?php
									if($installs['install_options']->contains('option_id',$option->id))
										echo "checked";
								?>
							>
							{{ $option->name }}
						</label>
					</div>
				@endforeach
			@endisset
		</div>

		<div class="clearfix"></div>

		<div class="col-sm-12">
			<h3>Опции</h3>
			<table class="pack table">
				@isset($packs)
					@foreach($packs as $pack)
					<tr>
						<td>
							<input 
								type="checkbox" 
								name="packs[]" 
								value="{{ $pack->id }}"
								<?php
									if($installs['install_packs']->contains('pack_id',$pack->id))
										echo "checked";
								?>
							>			
							{{ $pack->name }}
						</td>

						<td>
							{{ $pack->code }}
						</td>

						<td >
							{{ $pack->price }} руб.
						</td>

						<td >
							@isset($pack->option)
								@foreach($pack->option as $option)
									{{ $option->option->name }}
								@endforeach
							@endisset
						</td>
					</tr>
					@endforeach
				@endisset
			</table>
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