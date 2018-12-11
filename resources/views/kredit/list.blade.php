@extends ('layout')

@section('right')
	<a href="{{ route($route) }}">{{ $addTitle }}</a>
	<table class="table">
		<tr>
			<th>№</th>
			<th>Название</th>
			<th>Ставка<br>(%)</th>
			<th>Срок<br>(лет)</th>
			<th>Платёж<br>(руб.)</th>
			<th>Первый взнос<br>(%)</th>
			<th>Начало</th>
			<th>Конец</th>
			<th>Модели</th>
			<th></th>
			<th></th>
		</tr>
	@foreach($list as $key=>$kredit)
		<tr>
			<td>{{$key+1}}</td>
			<td>{{$kredit->name}}</td>
			<td>{{$kredit->rate}}</td>
			<td>{{$kredit->period}}</td>
			<td>{{$kredit->pay}}</td>
			<td>{{$kredit->contibution}}</td>
			<td>{{date('d.m.Y',$kredit->day_in)}}</td>
			<td>{{date('d.m.Y',$kredit->day_out)}}</td>
			<td class="font-12">

				@isset($kredit->model)
					@foreach($kredit->model as $k_model)
						{{$k_model->model->name}},
					@endforeach
				@endisset
			</td>
			<td><a href="{{ route($edit,['id'=>$kredit->id]) }}">Изменить</a>
			<td><a href="{{ route($delete,['id'=>$kredit->id]) }}">Удалить</a>
		</tr>
	@endforeach
	</table>
@endsection