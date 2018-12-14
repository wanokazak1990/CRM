@extends ('layout')

@section('right')

	{{ Form::open(['method'=>'get','style'=>'padding-bottom:30px;']) }}
		<div class="row">
			<div class="col-sm-2">
				<label>&nbsp</label>
				<a class='form-control btn btn-danger' href="{{ route($route) }}">{{ $addTitle }}</a>
			</div>

			<div class="col-sm-2">
				@isset($brands)
					{{Form::label('title','Бренд:')}}
					{{Form::select('brand_id',$brands,'',['class'=>'form-control'])}}
				@endisset
			</div>

			<div class="col-sm-2">
				@isset($types)
					{{Form::label('title','Тип:')}}
					{{Form::select('type_id',$types,'',['class'=>'form-control'])}}
				@endisset
			</div>

			<div class="col-sm-2">
				@isset($transmissions)
					{{Form::label('title','Трансмиссия:')}}
					{{Form::select('transmission_id',$transmissions,'',['class'=>'form-control'])}}
				@endisset
			</div>

			<div class="col-sm-2">
				@isset($wheels)
					{{Form::label('title','Привод:')}}
					{{Form::select('wheel_id',$wheels,'',['class'=>'form-control'])}}
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
			<th>Бренд</th>
			<th>Тип</th>
			<th>Объём</th>
			<th>Мощность</th>
			<th>Кол-во кл-ов</th>
			<th>Трансмиссия</th>
			<th>Привод</th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
	@foreach($list as $key=>$motor)
		<tr>
			<td class="width-50">{{$key+1}}</td>
			<td class="width-50">
				@isset($motor->brand)
				<?= $motor->brand->getIcon(); ?>
				@endisset
			</td>
			<td>
				@isset($motor->type)
				{{ $motor->type->name }}
				@endisset
			</td>
			<td>{{ $motor->size }} л.</td>
			<td>{{ $motor->power }} л.с.</td>
			<td>{{ $motor->valve }} кл.</td>
			<td>
				@isset($motor->transmission)
				{{ $motor->transmission->name }}
				@endisset
			</td>
			<td>
				@isset($motor->wheel)
				{{ $motor->wheel->name }}
				@endisset
			</td>	
			<td class="text-right">{{ $motor->forAdmin() }}</td>		
			<td class="width-50">
				<a href="{{ route($edit,['id'=>$motor->id]) }}">
					<i class="glyphicon glyphicon-cog"></i>
				</a>
			</td>
			<td class="width-50">
				<a href="{{ route($delete,['id'=>$motor->id]) }}">
					<i class="text-danger glyphicon glyphicon-remove"></i>
				</a>
			</td>
		</tr>
	@endforeach
	</table>
@endsection