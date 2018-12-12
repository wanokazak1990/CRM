@extends ('layout')

@section('right')
	<a href="{{ route($route) }}">{{ $addTitle }}</a>
	<table class="table">
		@foreach($list as $key => $file)
		<tr>
			<td>{{ $key + 1 }}</td>
			<td>
				@isset($file->brand)
					<?=$file->brand->getIcon();?>
				@endisset
			</td>
			<td>{{ $file->model->name }}</td>
			<td>{{ $file->type->name }}</td>
			<td><a href="{{ Storage::url('model_docs/' . $file->file) }}" target="_blank">{{ $file->name }}</a></td>
			<td><a href="{{ route($edit, array('id'=>$file->id)) }}">Изменить</a></td>
			<td><a href="{{ route($delete, array('id'=>$file->id)) }}">Удалить</a></td>			
		</tr>
		@endforeach
	</table>
@endsection