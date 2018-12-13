@extends ('layout')

@section('right')
	<a href="{{ route($route) }}">{{ $addTitle }}</a>
	<table class="table">
	@foreach($list as $key=>$motor)
		<tr>
			<td>{{$key+1}}</td>
			<td><?= $motor->brand->getIcon(); ?></td>
			<td>{{ $motor->type->name }}</td>
			<td>{{ $motor->size }} л.</td>
			<td>{{ $motor->power }} л.с.</td>
			<td>{{ $motor->valve }} кл.</td>
			<td>{{ $motor->transmission->name }}</td>
			<td>{{ $motor->wheel->name }}</td>			
			<td><a href="{{ route($edit,['id'=>$motor->id]) }}">Изменить</a>
			<td><a href="{{ route($delete,['id'=>$motor->id]) }}">Удалить</a>
		</tr>
	@endforeach
	</table>
@endsection