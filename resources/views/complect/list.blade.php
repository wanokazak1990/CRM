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
				@isset($models)
				{{Form::label('title','Модели:')}}
				{{Form::select('model_id',$models,'',['class'=>'form-control'])}}
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

	<div class="col-sm-12">Нашлось: {{$list->total()}}</div>
	
	<table class="table">
	@foreach($list as $key=>$complect)
		<tr style="color: <?=($complect->status<2)?'#cdcdcd;':'#333';?>">
			<td class="width-50">
				@if($complect->status>1)
					{{(($list->currentPage()-1)*$list->perPage())+$key+1}}
				@else
					n/a
				@endif
			</td>
			<td class="width-50">
				@isset($complect->brand)
					<?=$complect->brand->getIcon();?>
				@endisset
			</td>
			<td>
				@isset($complect->model)
					{{ $complect->model->name }}
				@endisset
			</td>
			<td>{{ $complect->name }}</td>
			<td>{{ $complect->code }}</td>
			<td>
				{{number_format($complect->price,0,'',' ')}} руб.
			</td>
			<td>
				@isset($complect->motor)
					{{ $complect->motor->name() }}
				@endisset
			</td>

			<td class="width-50 
				@if($complect->complectCount()==0)
					danger
				@elseif($complect->complectCount()>0 && $complect->complectCount()<5)
					info
				@else
					success
				@endif
				text-center"> {{$complect->complectCount()}}
			</td>
			
			<td class="width-50">	
				{{Form::text('sort',$complect->sort,['class'=>'form-control sort','data-id'=>$complect->id, 'data-type'=>get_class($complect)])}}
			</td>

			<td class="width-50">
				<a href="{{ route($edit,['id'=>$complect->id]) }}"><i class="glyphicon glyphicon-cog"></i></a>
			</td>
			<td class="width-50">
				<a href="{{ route($delete,['id'=>$complect->id]) }}"><i class="text-danger glyphicon glyphicon-remove"></i></a>
			</td>
		</tr>
	@endforeach
	</table>

	{{$list->appends($url_get)->links()}}
@endsection