@extends('layout')



@section('right')
	{!! Form::open(array('files'=>'true')) !!}

		<div class="col-sm-2"> 
			{!! Form::label('title','Бренд:') !!}
			{!! Form::select('brand_id',$brands,$car->brand_id,['class'=>'form-control']) !!}
		</div>
		<!-- ----------------------------------------------------------------------- -->
		
		<div class="clearfix"></div>

		<div class="col-sm-2">
			{!! Form::label('title','Модель:') !!}
			{!! Form::select('model_id', isset($models)?$models:[] ,$car->model_id,['class'=>'form-control model']) !!}
		</div>

		<div class="col-sm-2">
			{!! Form::label('title','Комплектация:') !!}
			{!! Form::select('complect_id', isset($complects)?$complects:[] ,$car->complect_id,['class'=>'form-control complect']) !!}
		</div>

		<div class="clearfix"></div>

		<div class="col-sm-2">
			{!! Form::label('title','Статус:') !!}
			{!! Form::select('status_id', isset($status)?$status:[] ,$car->status_id,['class'=>'form-control']) !!}
		</div>

		<div class="col-sm-2">
			{!! Form::label('title','Поставка:') !!}
			{!! Form::select('location_id', isset($loc)?$loc:[] ,$car->location_id,['class'=>'form-control']) !!}
		</div>

		<div class="clearfix"></div>

		<div class="col-sm-2">
			{{Form::label('title','VIN:')}}
			{{ Form::text('vin',$car->vin,['class'=>'form-control'])}}
		</div>

		<div class="col-sm-2">
			{{Form::label('title','Год выпуска:')}}
			{{ Form::select('year',$car->getYearArray(),$car->year,['class'=>'form-control'])}}
		</div>

		<div class="col-sm-2">
			{{Form::label('title','Сборка:')}}
			{{ Form::text('prodaction',$car->prodaction,['class'=>'form-control'])}}
		</div>

		<div class="col-sm-2">
			{{Form::label('title','Стоимость доп.оборудования:')}}
			{{ Form::text('dopprice',$car->dopprice,['class'=>'form-control'])}}
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
						<td>
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
		<div class="dop">
			@isset($dops)
				@foreach( $dops as $dop )
					<div class="col-sm-3">
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
							{{$dop->name}}
					</div>
				@endforeach
			@endisset
		</div>

		<div class="clearfix"></div><br><br><br>

		<div class="col-sm-2"> 
		{!! Form::submit('Создать',	 ['class' => 'form-control','name'=>'submit']) !!}
		</div>
		
		<div class="col-sm-2">
		{!! Form::submit('Отмена',	 ['class' => 'form-control','name'=>'cansel']) !!}
		</div>

	{!! Form::close() !!}
@endsection