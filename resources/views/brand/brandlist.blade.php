@extends('layout')



@section('right')
	<a href="{{ route($route) }}">{{ $addTitle }}</a>
	<table class="table">
	@foreach ($list as $key=>$item)
		<tr>
			<td>{{$key+1}}</td>
	    	<td>{{ $item->name }}</td>
	    	<td><a href="{{ route($edit, array('id'=>$item->id)) }}">Открыть</a></td>
	    	<td><a href="{{ route($delete, array('id'=>$item->id)) }}">Удалить</a></td>
		</tr>
	@endforeach
	</table>
@endsection