@extends('layout')



@section('right')
	{!! Form::open(array('files'=>'true')) !!}
	<div class="row">
		<div class="col-sm-4">
			<div class="row">
				<div class="col-sm-6"> 
					{!! Form::label('title','Бренд:') !!}
					{!! Form::select('brand_id',$brands,$car->brand_id,['class'=>'form-control']) !!}
				</div>

				<div class="col-sm-6">
					{{Form::label('title','VIN:')}}
					{{ Form::text('vin',$car->vin,['class'=>'form-control'])}}
				</div>

				<!-- ----------------------------------------------------------------------- -->
				
				<div class="clearfix"></div>

				<div class="col-sm-6">
					{!! Form::label('title','Модель:') !!}
					{!! Form::select('model_id', isset($models)?$models:[] ,$car->model_id,['class'=>'form-control model']) !!}
				</div>

				<div class="col-sm-6">
					{!! Form::label('title','Комплектация:') !!}
					{!! Form::select('complect_id', isset($complects)?$complects:[] ,$car->complect_id,['class'=>'form-control complect']) !!}
				</div>

				<div class="clearfix"></div>

				<div class="col-sm-6">
					{!! Form::label('title','Статус:') !!}
					{!! Form::select('status_id', isset($status)?$status:[] ,$car->status_id,['class'=>'form-control']) !!}
				</div>

				<div class="col-sm-6">
					{!! Form::label('title','Поставка:') !!}
					{!! Form::select('location_id', isset($loc)?$loc:[] ,$car->location_id,['class'=>'form-control']) !!}
				</div>

				<div class="clearfix"></div>

				

				<div class="col-sm-6">
					{{Form::label('title','Год выпуска:')}}
					{{ Form::select('year',$car->getYearArray(),$car->year,['class'=>'form-control'])}}
				</div>

				<div class="col-sm-6">
					{{Form::label('title','Сборка:')}}
					{{ Form::text('prodaction',($car->prodaction)?date('d.m.Y',$car->prodaction):'',['class'=>'form-control calendar'])}}
				</div>

				<div class="clearfix"></div>

				<div class="col-sm-6">
					{{Form::label('title','Стоимость доп.оборудования:')}}
					{{ Form::text('dopprice',($car->dopprice)?$car->dopprice:'',['class'=>'form-control'])}}
				</div>
			</div>
		</div>

		<div class="col-sm-4">

		</div>

		<div class="col-sm-3" id="car-price" >
			<div class="fixed-count-price" style="width: inherit;">
				<div class="row">
					<div class="col-sm-5">
						<img src="" id="car-img" style="width: 80%;">
						<div style="padding: 10px;">
							<span id='car-brand'></span>
							<span id='car-model'></span>
							<div id='car-complect' class="size-12" style="white-space: nowrap;"></div>
						</div>
					</div>
					<div class="col-sm-7">
						<table class="table" style="margin-bottom: 0px;">
							<tr>
								<td class="width-100">Цена базы: </td>
								<td class="width-100 text-right" id="car-base">0</td>
								<td class="width-50"> руб.</td>
							</tr>
							<tr>
								<td>Цена опций: </td>
								<td class="width-100 text-right" id="car-option">0</td>
								<td> руб.</td>
							</tr>
							<tr>
								<td>Цена допов: </td>
								<td class="width-100 text-right" id="car-dop"> 0 </td>
								<td> руб.</td>
							</tr>
							<tr>
								<td>Цена всего: </td>
								<td class="width-100 text-right" id="car-total">0</td>
								<td> руб.</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-12"><h3>Доступные цвета</h3></div>
		<div class="color">
			@isset($colors)
				@foreach($colors as $color)
					<div class="col-sm-2">
						<div>{{ $color->name }} ({{ $color->rn_code }})</div>
						<div style="border:1px solid #ccc;height:20px;background: {{ $color->web_code }}"></div>
						<label>
							<input 
								type="radio" 
								name="color_id[]" 
								value="{{ $color->id }}"
								@if($car->color_id == $color->id)
									{{'checked'}}
								@endif
							> 
							Использовать
						</label>
					</div>
				@endforeach
			@endisset
		</div>

		<div class="col-sm-12">
			<h3>Опции</h3>
			<table class="pack table">
				@isset($packs)
					@foreach($packs as $pack)
					<tr>
						<td style="padding: 0px;">
							<input 
								type="checkbox" 
								name="packs[]" 
								value="{{ $pack->id }}"
								@isset($car->packs)
									@if($car->packs->contains('pack_id',$pack->id))
										{{'checked'}}
									@endif
								@endisset
							>			
							{{ $pack->name }}
						</td>

						<td>
							{{ $pack->code }}
						</td>

						<td >
							{{ $pack->price }} руб.
						</td>

						<td class="font-12">
							@isset($pack->option)
								@foreach($pack->option as $option)
									{{ $option->option->name }},
								@endforeach
							@endisset
						</td>
					</tr>
					@endforeach
				@endisset
			</table>
		</div>

		<div class="col-sm-12">
			<h3>Доп. оборудование</h3>
		</div>
		<div class="dop col-sm-12">
			@isset($dops)
				@foreach( $dops as $id => $dop )
					@if($id == 0)
						<div class="">
							<h4>{{App\option_parent::find($dop->parent_id)->name}}</h4>
						</div>
						<div class="column">
					@elseif($dops[$id]->parent_id != $dops[$id-1]->parent_id)
						</div>
						<div class="">
							<h4>{{App\option_parent::find($dop->parent_id)->name}}</h4>
						</div>
						<div class="column">
					@endif

							<label>
								<input 
									type="checkbox" 
									name="dops[]" 
									value="{{$dop->id}}"

									@isset($car->dops)
										@if($car->dops->contains('dop_id',$dop->id))
											{{'checked'}}
										@endif
									@endisset
								>
								{{mb_strimwidth($dop->name, 0, 40, "...")}}
								@if(mb_strlen($dop->name)>40)
									<span 
										class="glyphicon glyphicon-info-sign" 
										data-toggle="tooltip" 
										data-placement="top"
										title="{{$dop->name}}"
									>
									</span>
								@endif
							</label>
					
				@endforeach
						</div>
			@endisset
		</div>

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