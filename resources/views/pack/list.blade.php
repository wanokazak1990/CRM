@extends ('layout')

@section('right')
	<a href="{{ route($route) }}">{{ $addTitle }}</a>
	<table class="table">
		<tr>
			<th>№</th>
			<th>Бренд</th>
			<th>Код</th>
			<th>Название</th>
			<th>Модели</th>
			<th>Состав</th>
			<th>Цена (руб.)</th>
			<th>Тип</th>
			<th></th>
			<th></th>
		</tr>
	@foreach($list as $key=> $pack)
		<tr>
			<td>{{$key+1}}</td>
			<td>
				@isset($pack->brand)
					<?=$pack->brand->geticon();?>
				@endisset
			</td>
			<td>{{ $pack->code }}</td>
			<td>{{ $pack->name }}</td>
			<td class="font-12">
				@isset($pack->model)
					@foreach($pack->model as $model)
						@isset($model->model)
							{{$model->model->name}}
						@endisset
					@endforeach
				@endisset
			</td>
			<td class="font-12">
				@isset($pack->option)
					@foreach($pack->option as $option)
						@isset($option->option)
							{{$option->option->name}}, 
						@endisset
					@endforeach
				@endisset
			</td>
			<td>{{ $pack->price }}</td>
			<td>
				{{ !empty($pack->type)?"Цвет":""}}
			</td>
			<td><a href="{{ route($edit,['id'=>$pack->id]) }}">Изменить</a>
			<td><a href="{{ route($delete,['id'=>$pack->id]) }}">Удалить</a>
		</tr>
	@endforeach
	</table>

	{{ $list->links()}}
@endsection