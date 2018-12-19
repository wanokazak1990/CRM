@extends ('layout')

@section('right')

		{{ Form::open(['method'=>'get','style'=>'padding-bottom:30px;']) }}
		<div class="row">
			<div class="col-sm-2">
				<label>&nbsp</label>
				<a class='form-control btn btn-danger' href="{{ route($route) }}">{{ $addTitle }}</a>
			</div>

			<div class="col-sm-2 col-sm-offset-2">
				@isset($brands)
					{{Form::label('title','Код:')}}
					{{Form::text('code','',['class'=>'form-control'])}}
				@endisset
			</div>

			<div class="col-sm-2">
				@isset($brands)
					{{Form::label('title','Бренд:')}}
					{{Form::select('brand_id',$brands,'',['class'=>'form-control'])}}
				@endisset
			</div>

			<div class="col-sm-2">
				@isset($models)
				{{Form::label('title','Модель:')}}
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
		<tr>
			<th>№</th>
			<th>Бренд</th>
			<th>Код</th>
			<th>Название</th>
			<th>Модели</th>
			<th>Состав</th>
			<th>Цена (руб.)</th>
			<th>Тип</th>
			<th></th>
			<th></th>
		</tr>
	@foreach($list as $key=> $pack)
		<tr>
			<td class="width-50">{{(($list->currentPage()-1)*$list->perPage())+$key+1}}</td>
			<td class="width-50">
				@isset($pack->brand)
					<?=$pack->brand->geticon();?>
				@endisset
			</td>
			<td class="width-150">{{ $pack->code }}</td>
			<td class="width-200">{{ $pack->name }}</td>
			<td class="font-12 width-150">
				@isset($pack->model)
					@foreach($pack->model as $model)
						@isset($model->model)
							{{$model->model->name}}<br>
						@endisset
					@endforeach
				@endisset
			</td>
			<td class="font-12">
				@isset($pack->option)
					@foreach($pack->option as $option)
						@isset($option->option)
							{{$option->option->name}}, 
						@endisset
					@endforeach
				@endisset
			</td>
			<td>{{ number_format($pack->price,0,'',' ') }}</td>
			<td>
				{{ !empty($pack->type)?"Цвет":""}}
			</td>
			<td class="width-50"><a href="{{ route($edit,['id'=>$pack->id]) }}"><i class="glyphicon glyphicon-cog"></i></a></td>
			<td class="width-50"><a href="{{ route($delete,['id'=>$pack->id]) }}"><i class="text-danger glyphicon glyphicon-remove"></i></a></td>
		</tr>
	@endforeach
	</table>

	{{ $list->appends($url_get)->links() }}
@endsection