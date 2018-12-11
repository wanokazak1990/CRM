@extends ('layout')

@section('right')
	<a href="{{ route($route) }}">{{ $addTitle }}</a>
	<table class="table">
	@foreach($list as $key=>$loc)
		<tr>
			<td>{{$key+1}}</td>
			<td>{{$loc->name}}</td>
			<td><a href="{{ route($edit,['id'=>$loc->id]) }}">Изменить</a>
			<td><a href="{{ route($delete,['id'=>$loc->id]) }}">Удалить</a>
		</tr>
	@endforeach
	</table>
@endsection