@extends ('layout')

@section('right')
	

	{{ Form::open(['method'=>'get','style'=>'padding-bottom:30px;']) }}
		<div class="row">
			<div class="col-sm-2">
				<label>&nbsp</label>
				<a class='form-control btn btn-danger' href="{{ route($route) }}">{{ $addTitle }}</a>
			</div>

			<div class="col-sm-2 col-sm-offset-4">
				@isset($brands)
					{{Form::label('title','Бренд:')}}
					{{Form::select('brand_id',$brands,'',['class'=>'form-control'])}}
				@endisset
			</div>

			<div class="col-sm-2">
				@isset($parents)
				{{Form::label('title','Раздел:')}}
				{{Form::select('parent_id',$parents,'',['class'=>'form-control'])}}
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
			<th>Раздел</th>
			<th>@isset($brands) Бренд @endisset</th>
			<th></th>
			<th></th>
		</tr>
	@foreach($list as $key=>$option)
		<tr>
			<td>{{(($list->currentPage()-1)*$list->perPage())+$key+1}}</td>
			<td style="width: 70%">{{$option->name}}</td>
			<td>
				@isset($option->type)
					{{$option->type->name}}
				@endisset
			</td>
			<td class="font-12">
				@isset($option->brands)
					@foreach($option->brands as $brand)
						@isset($brand->getBrandObj)
							<?= $brand->getBrandObj->getIcon(); ?>
						@endisset
					@endforeach
				@endisset
			</td>
			<td class="width-50">
				<a href="{{ route($edit,['id'=>$option->id]) }}" title="Редактировать">
					<i class="glyphicon glyphicon-cog"></i>
				</a>
			</td>
			<td  class="width-50">
				<a href="{{ route($delete,['id'=>$option->id]) }}">
					<i class="text-danger glyphicon glyphicon-remove"></i>
				</a>
			</td>
		</tr>
	@endforeach
	</table>


	{{ $list->appends($url_get)->links() }}


@endsection