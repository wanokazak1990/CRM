@extends ('layout')

@section('right')
	<a href="{{ route($route) }}">{{ $addTitle }}</a>
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
					{{$car->brand->name}}
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
			<td><a href="{{ route($edit,['id'=>$car->id]) }}">Изменить</a>
			<td><a href="{{ route($delete,['id'=>$car->id]) }}">Удалить</a>
		</tr>
	@endforeach
	</table>

	{{ $list->links()}}
@endsection