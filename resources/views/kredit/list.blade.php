@extends ('layout')

@section('right')

	{{ Form::open(['method'=>'get','style'=>'padding-bottom:30px;']) }}
		<div class="row">
			<div class="col-sm-2">
				<label>&nbsp</label>
				<a class='form-control btn btn-danger' href="{{ route($route) }}">{{ $addTitle }}</a>
			</div>
		</div>
	{{ Form::close() }}
	
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
			<th>Бренд</th>
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
			<td>{{number_format($kredit->pay,0,'',' ')}}</td>
			<td>{{$kredit->contibution}}</td>
			<td>{{date('d.m.Y',$kredit->day_in)}}</td>
			<td>{{date('d.m.Y',$kredit->day_out)}}</td>
			<td>
				@isset($kredit->brand)
					<?=$kredit->brand->getIcon();?>
				@endisset
			</td>
			<td class="font-12">

				@isset($kredit->model)
					@foreach($kredit->model as $k_model)
						{{$k_model->model->name}},
					@endforeach
				@endisset
			</td>
			<td class="width-50"><a href="{{ route($edit,['id'=>$kredit->id]) }}"><i class="glyphicon glyphicon-cog"></i></a></td>
			<td class="width-50"><a href="{{ route($delete,['id'=>$kredit->id]) }}"><i class="text-danger glyphicon glyphicon-remove"></i></a>
		</tr>
	@endforeach
	</table>
@endsection