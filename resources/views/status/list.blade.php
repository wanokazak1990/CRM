@extends ('layout')

@section('right')
	<a href="{{ route($route) }}">{{ $addTitle }}</a>
	<table class="table">
	@foreach($list as $key=>$status)
		<tr>
			<td>{{$key+1}}</td>
			<td>{{$status->name}}</td>
			<td><a href="{{ route($edit,['id'=>$status->id]) }}">Изменить</a></td>
			<td>
				@if($key!=0)
				<a href="{{ route($delete,['id'=>$status->id]) }}">Удалить</a>
				@endif
			</td>
		</tr>
	@endforeach
	</table>
@endsection