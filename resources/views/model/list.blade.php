@extends ('layout')

@section('right')
	<a href="{{ route($route) }}">{{ $addTitle }}</a>
	<table class="table">
	@foreach($list as $key=>$model)
		<tr>
			<td>{{$key+1}}</td>
			<td>{{$model->brand->name}}</td>
			<td>{{$model->label}}</td>
			<td>{{$model->name}}</td>
			<td>{{$model->link}}</td>
			<td>{{$model->type->name}}</td>
			<td>{{$model->country->name}}</td>
			<td><img src="{{ Storage::url(('images/'.$model->link).'/'.$model->icon) }}" style="height: 50px;"></td>
			<td><img src="{{ Storage::url(('images/'.$model->link).'/'.$model->alpha) }}" style="height: 50px;"></td>
			<td><img src="{{ Storage::url(('images/'.$model->link).'/'.$model->banner) }}" style="height: 50px;"></td>
			<td><a href="{{ route($edit,['id'=>$model->id]) }}">Изменить</a>
				<td><a href="{{ route($delete,['id'=>$model->id]) }}">Удалить</a>
		</tr>
	@endforeach
	</table>
@endsection