@extends ('layout')

@section('right')
	<a href="{{ route($route) }}">{{ $addTitle }}</a>
	<table class="table">
	@foreach($list as $key=>$model)
		<tr>
			<td>{{$key+1}}</td>
			<td class="width-50"><?= $model->brand->getIcon(); ?></td>
			<td class="width-50"><?=$model->country->getFlag();?></td>
			<td class="width-50"><?=$model->type->getIcon();?></td>
			<td class="width-100">{{$model->label}}</td>
			<td class="width-200">{{$model->name}}</td>
			<td class="width-200">{{$model->link}}</td>
			
			
			<td><img src="{{ Storage::url(('images/'.$model->link).'/'.$model->icon) }}" style="height: 50px;"></td>
			<td><img src="{{ Storage::url(('images/'.$model->link).'/'.$model->alpha) }}" style="height: 50px;"></td>
			<td><img src="{{ Storage::url(('images/'.$model->link).'/'.$model->banner) }}" style="height: 50px;"></td>
			<td class="width-50">
				<a href="{{ route($edit,['id'=>$model->id]) }}">
					<i class=" glyphicon glyphicon-cog"></i>
				</a>
			</td>
			<td class="width-50">
				<a href="{{ route($delete,['id'=>$model->id]) }}">
					<i class="text-danger glyphicon glyphicon-remove"></i>
				</a>
			</td>
		</tr>
	@endforeach
	</table>
@endsection