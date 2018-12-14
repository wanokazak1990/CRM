@extends ('layout')

@section('right')

	{{ Form::open(['method'=>'get','style'=>'padding-bottom:30px;']) }}
		<div class="row">
			<div class="col-sm-2">
				<label>&nbsp</label>
				<a class='form-control btn btn-danger' href="{{ route($route) }}">{{ $addTitle }}</a>
			</div>

			<div class="col-sm-2">
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

			<div class="col-sm-1">
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
			Заблокировано: {{App\avacar::where('status_id',3)->count()}} |
			Тест-драйв: {{App\avacar::where('status_id',1)->count()}} |
			Активно: {{App\avacar::where('status_id',4)->count()}}
		</div>
		<div class="col-sm-6 text-right">
			На складе: {{App\avacar::where('location_id',2)->count()}} | 
			В отгрузке: {{App\avacar::where('location_id',3)->count()}} |
			На заводе: {{App\avacar::where('location_id',4)->count()}} |
		</div>
		<div class="col-sm-12">
			@if(!empty($filter))
				Фильтром найдено: {{$list->total()}}
			@endif
		</div>
	</div>
	<table class="table avacars">
		<tr>
			<th>№</th>
			<th>VIN</th>
			<th>Бренд</th>
			<th>Модель</th>
			<th>Комплектация</th>
			<th>Мотор</th>
			<th>Кол-во</br>опции(шт.)</th>
			<th>Статус</th>
			<th>Поставка</th>
			<th>Год <br>выпуска</th>
			<th>Цена <br>(руб.)</th>
			<th>Дата <br>добавления</th>
			<th>Последнее<br> изменение</th>
			<th></th>
			<th></th>
		</tr>
	@foreach($list as $key=> $car)
		<tr>
			<td>{{$key+1}}</td>
			<td>{{$car->vin}}</td>
			<td>
				@isset($car->brand)
					<?=$car->brand->getIcon();?>
				@endisset
			</td>
			<td>
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
				@isset($car->complect)
					{{$car->complect->motor->forAdmin()}}
				@endisset
			</td>
			<td>
				@isset($car->packs)
					{{count($car->packs)}}
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
			<td>{{$car->year}}</td>
			<td>{{number_format($car->totalPrice(),0,'',' ')}}</td>
			<td>{{$car->created_at->format('d.m.Y')}}</td>
			<td>{{$car->updated_at->format('d.m.Y')}}</td>
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