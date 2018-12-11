@extends ('layout')

@section('right')
	<a href="{{ route($route) }}">{{ $addTitle }}</a>
	<table class="table">
		@foreach($list as $key => $type)
		<tr>
			<td>{{ $key + 1 }}</td>
			<td>{{ $type->name }}</td>
			<td><img src="{{ Storage::url('images/file_types/' . $type->icon) }}" width="100"></td>
			<td><a href="{{ route($edit, array('id'=>$type->id)) }}">Изменить</a></td>
			<td><a href="{{ route($delete, array('id'=>$type->id)) }}">Удалить</a></td>
		</tr>
		@endforeach
	</table>
@endsection