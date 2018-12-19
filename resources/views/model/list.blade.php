@extends ('layout')

@section('right')
	
	{{ Form::open(['method'=>'get','style'=>'padding-bottom:30px;']) }}
		<div class="row">
			<div class="col-sm-2">
				<label>&nbsp</label>
				<a class='form-control btn btn-danger' href="{{ route($route) }}">{{ $addTitle }}</a>
			</div>
		</div>
	{{ Form::close() }}

	<table class="table">
	@foreach($list as $key=>$model)
		<tr>
			<td class="width-50">{{$key+1}}</td>
			<td class="width-50"><?= $model->brand->getIcon(50); ?></td>
			
			<td class="width-100">
				<img src="{{ Storage::url(('images/'.$model->link).'/'.$model->icon) }}" style="height: 50px;">
			</td>
			<td class="width-100">{{$model->label}}</td>
			<td class="width-200">{{$model->name}}</td>
			<td class="width">{{$model->link}}</td>
			
			
			<td class="width-50"><?=$model->country->getFlag();?></td>
			<td class="width-100 text-left"><?=$model->type->getIcon(50);?></td>
			
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