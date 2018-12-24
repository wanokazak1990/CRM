@extends('layout')



@section('right')
	{!! Form::open(array('files'=>'true')) !!}
	<div class="row">
		<div class="col-sm-2">
			{!! Form::label('title','Бренд:') !!}
			{!! Form::select('brand_id',$brands,$complect->brand_id,['class'=>'form-control']) !!}			
		</div>

		<div class="col-sm-2">
			{!! Form::label('title','Статус:') !!}
			{!! Form::select(
					'status',
					['Удалено полностью','Удалиться при обнулении','Активна'],
					(!empty($complect->brand_id))?$complect->brand_id:'2',
					['class'=>'form-control']) 
			!!}			
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
					<div class="col-sm-4">
						<label>
							<div class="">
								<div class="col-sm-1 pad-0">
									<input 
										type="checkbox" 
										name="color_id[]" 
										value="{{ $color->id }}"
										<?php
											if($installs['install_colors']->contains('color_id',$color->id))
												echo "checked";
										?>
									> 
								</div>
								<div class="col-sm-2">
									<?=$color->getColorIcon();?>
								</div>
								<div class="col-sm-8 size-10" style="height: 30px;">
									{{ $color->name }} ({{ $color->rn_code }})
								</div>
							</div>
						</label>
					</div>
					
					
				@endforeach
			@endisset
		</div>
		
		<div class="clearfix"></div>

		<div class="col-sm-12"><h3>Оборудование</h3></div>
		<div class="option col-sm-12">
			@isset($options)
				@foreach($options as $key=>$option)
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
									type='checkbox' 
									name='pack_option[]' 
									value='{{ $option->id }}'
									<?php
										if($installs['install_options']->contains('option_id',$option->id))
											echo "checked";
									?>
								>
								{{mb_strimwidth($option->name, 0, 40, "...")}}
								@if(mb_strlen($option->name)>40)
									<span 
										style="float: right; margin-top: -15px;" 
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

		<div class="clearfix"></div>

		<div class="col-sm-12">
			<h3>Опции</h3>
			<table class="pack table">
				@isset($packs)
					@foreach($packs as $pack)
					<tr>
						<td class="width-200 checkbox-td">
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

						<td class="width-150">
							{{ $pack->code }}
						</td>

						<td class="width-200">
							{{ $pack->price }} руб.
						</td>

						<td class="font-12" >
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