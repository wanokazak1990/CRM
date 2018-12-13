@extends ('layout')

@section('right')
	<a href="{{ route($route) }}">{{ $addTitle }}</a>
	<table class="table">
	@foreach($list as $key=>$complect)
		<tr>
			<td>{{$key+1}}</td>
			<td>
				@isset($complect->brand)
					<?=$complect->brand->getIcon();?>
				@endisset
			</td>
			<td>
				@isset($complect->model)
					{{ $complect->model->name }}
				@endisset
			</td>
			<td>{{ $complect->name }}</td>
			<td>{{ $complect->code }}</td>
			<td>
				@isset($complect->motor)
					{{ $complect->motor->name() }}
				@endisset
			</td>
			<td><a href="{{ route($edit,['id'=>$complect->id]) }}">Изменить</a>
			<td><a href="{{ route($delete,['id'=>$complect->id]) }}">Удалить</a>
		</tr>
	@endforeach
	</table>

	{{$list->links()}}
@endsection