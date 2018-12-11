@extends ('layout')

@section('right')
<a href="{{ route($route) }}">{{ $addTitle }}</a>
<table class="table">
	@foreach($list as $key => $char)
	<tr>
		<td>{{ $key + 1 }}</td>
		<td>{{ $char->name }}</td>	
		<td><a href="{{ route($edit, array('id'=>$char->id)) }}">Изменить</a></td>	
		<td><a href="{{ route($delete, array('id'=>$char->id)) }}">Удалить</a></td>	
	</tr>
	@endforeach
</table>
@endsection