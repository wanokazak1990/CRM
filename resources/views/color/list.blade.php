@extends ('layout')

@section('right')

	{{ Form::open(['method'=>'get','style'=>'padding-bottom:30px;']) }}
		<div class="row">
			<div class="col-sm-2">
				<label>&nbsp</label>
				<a class='form-control btn btn-danger' href="{{ route($route) }}">{{ $addTitle }}</a>
			</div>

			<div class="col-sm-2 col-sm-offset-6">
				@isset($brands)
					{{Form::label('title','Бренд:')}}
					{{Form::select('brand_id',$brands,'',['class'=>'form-control'])}}
				@endisset
			</div>

			<div class="col-sm-1">
			<label>&nbsp</label> 
				{{Form::submit('Найти',	 ['class' => 'form-control btn btn-primary'])}}
			</div>

			<div class="col-sm-1">
			<label>&nbsp</label> 
				{{Form::submit('Сбросить',	 ['class' => 'form-control btn btn-success','name'=>'reset'])}}
			</div>
		</div>
	{{Form::close() }}

	<table class="table">
		<tr>
			<th>№</th>
			<th>Название</th>
			<th>Код</th>
			<th>Бренд</th>
			<th>Цвет</th>
			<th></th>
			<th></th>
		</tr>
	@foreach($list as $key=> $color)
		<tr>
			<td class="width-50">{{$key+1}}</td>
			<td style="width: 450px;">{{$color->name}}</td>
			<td>{{$color->rn_code}}</td>
			<td class="width-50">
				@isset($color->brand)
					<?=$color->brand->getIcon();?>
				@endisset
			</td>
			<td class="width-50">
				<?= $color->getColorIcon();?>
			</td>
			<td class="width-50">
				<a href="{{ route($edit,['id'=>$color->id]) }}">
					<i class=" glyphicon glyphicon-cog"></i>
				</a>
			</td>
			<td class="width-50">
				<a href="{{ route($delete,['id'=>$color->id]) }}">
					<i class="text-danger glyphicon glyphicon-remove"></i>
				</a>
			</td>
		</tr>
	@endforeach
	</table>

	{{ $list->appends($url_get)->links() }}
@endsection