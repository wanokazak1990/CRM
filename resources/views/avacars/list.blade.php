@extends ('layout')

@section('right')

	{{ Form::open(['method'=>'get','style'=>'padding-bottom:30px;']) }}
		<div class="company-dop">
			<div class="col-sm-8">
				<h4>Список оборудования</h4>
			</div>
			<div class="col-sm-4 text-right">
				<h4><button type="button" class="close">x</button></h4>
			</div>
			<div class="col-sm-12"></div> 
			<div class="dop"> 
			@isset($options_list)
				@foreach($options_list as $id=>$name)
					@if($id!=0)
					<div class="col-sm-4">
						<label>
							<input 
								type="checkbox" 
								name="option[]" 
								value="{{$id}}"
								<?php 
								if(isset($_GET['option']))
								{
									if(in_array($id, $_GET['option']))
										echo "checked";
								}
								?>
							>
							{{$name}}
						</label>
					</div>
					@endif
				@endforeach
			@endisset
			</div>
		</div>

		<div class="row">
			<div class="col-sm-2 ">
				<label>&nbsp</label>
				<a class='form-control btn btn-danger' href="{{ route($route) }}">{{ $addTitle }}</a>
			</div>

			<div class="col-sm-2 col-sm-offset-1">
				@isset($brands)
					{{Form::label('title','VIN:')}}
					{{Form::text('vin','',['class'=>'form-control'])}}
				@endisset
			</div>

			<div class="col-sm-1">
				@isset($brands)
					{{Form::label('title','Бренд:')}}
					{{Form::select('brand_id',$brands,'',['class'=>'form-control'])}}
				@endisset
			</div>

			<div class="col-sm-1">
				@isset($models)
				{{Form::label('title','Модель:')}}
				{{Form::select('model_id',$models,'',['class'=>'form-control'])}}
				@endisset
			</div>

			<div class="col-sm-1">
				@isset($complects)
				{{Form::label('title','Комплектация:')}}
				{{Form::select('complect_id',$complects,'',['class'=>'form-control'])}}
				@endisset
			</div>

			<div class="col-sm-1">
				@isset($statuses)
				{{Form::label('title','Статус:')}}
				{{Form::select('status_id',$statuses,'',['class'=>'form-control'])}}
				@endisset
			</div>

			<div class="col-sm-1">
				@isset($locations)
				{{Form::label('title','Поставка:')}}
				{{Form::select('location_id',$locations,'',['class'=>'form-control'])}}
				@endisset
			</div>

			<div class="col-sm-2 "  >
				<label>&nbsp</label> 
				<button type="button" id="option_check" class="form-control btn btn-info">Подбор по оборудованию</button>
			</div>

			<div class="clearfix"></div>

			<div class="col-sm-1 col-sm-offset-9">
				<label>&nbsp</label> 
				{{Form::submit('Найти',	 ['class' => 'form-control btn btn-primary'])}}
			</div>

			<div class="col-sm-1">
				<label>&nbsp</label> 
				{{Form::submit('Сбросить',	 ['class' => 'form-control btn btn-success','name'=>'reset'])}}
			</div>

			<div class="col-sm-1">
				<label>&nbsp</label> 
				{{Form::submit('Экспорт',	 ['class' => 'form-control btn btn-default','name'=>'export'])}}
			</div>
		</div>
	{{Form::close() }}

	<div class="row">
		<div class="col-sm-6">
			Всего: {{App\avacar::count()}} |
			@foreach(App\ava_status::pluck('name','id') as $key => $name)
				{{$name}} : {{App\avacar::where('status_id',$key)->count()}} |
			@endforeach
		</div>
		<div class="col-sm-6 text-right">
			@foreach(App\ava_loc::pluck('name','id') as $key => $name)
				{{$name}} : {{App\avacar::where('location_id',$key)->count()}} |
			@endforeach
		</div>
		<div class="col-sm-12">
			@if(!empty($filter))
				Фильтром найдено: {{$list->total()}}
			@endif
		</div>
	</div>
	<style>
		.table td,.table th{padding: 5px 0px !important;}
	</style>
	<table class="table avacars">
		<tr>
			<th class="size-10">№</th>
			<th class="size-10">VIN</th>
			<th class="size-10">Бренд</th>
			<th class="size-10">Модель</th>
			<th class="size-10">Комплектация</th>
			<th class="size-10">Цвет</th>
			<th class="size-10">Мотор</th>
			<th class="size-10">Опции</th>
			<th class="size-10">Статус</th>
			<th class="size-10">Поставка</th>
			<th class="size-10">Цена <br>(руб.)</th>
			<th class="text-center size-10">Год <br>выпуска</th>
			<th class="size-10 text-center">Дата <br>внесения</th>
			<th class="size-10 text-center">Послед<br>изменение</th>
			<th></th>
			<th></th>
		</tr>
	@foreach($list as $key=> $car)
		<tr>
			<td class="width-50">{{($list->currentPage()-1)*$list->perPage()+$key+1}}</td>
			<td>{{$car->vin}}</td>
			<td>
				@isset($car->brand)
					<?=$car->brand->getIcon();?>
				@endisset
			</td>
			<td class="width-100">
				@isset($car->model)
					{{$car->model->name}}
				@endisset
			</td>
			<td>
				@isset($car->complect)
					{{$car->complect->name}}
				@endisset
			</td>
			<td>
				@isset($car->color)
					<?=$car->color->getColorIcon();?>
				@endisset
			</td>
			<td>
				@isset($car->complect)
					{{$car->complect->motor->forAdmin()}}
				@endisset
			</td>
			<td class="size-10">
				@isset($car->packs)
					@foreach($car->packs as $pack)
						{{$pack->pack->code}}<br>
					@endforeach
				@endisset
			</td>
			<td>
				@isset($car->status)
					{{$car->status->name}}
				@endisset
			</td>
			<td>
				@isset($car->location)
					{{$car->location->name}}
				@endisset
			</td>
			<td>{{number_format($car->totalPrice(),0,'',' ')}}</td>
			<td class="size-10  text-center">
				{{($car->prodaction)?date('d.m.Y',$car->prodaction):$car->year}}
			</td>
			
			<td class="size-10 width-75 text-center">{{($car->created_at)?$car->created_at->format('d.m.Y'):"n/a"}}</td>
			<td class="size-10 width-75 text-center">{{($car->updated_at)?$car->updated_at->format('d.m.Y'):"n/a"}}</td>
			<td class="width-50">
				<a href="{{ route($edit,['id'=>$car->id]) }}"><i class="glyphicon glyphicon-cog"></i></a>
			</td>
			<td class="width-50">
				<a href="{{ route($delete,['id'=>$car->id]) }}"><i class="text-danger glyphicon glyphicon-remove"></i></a>
			</td>
		</tr>
	@endforeach
	</table>

	{{ $list->appends($url_get)->links() }}

@endsection