@extends ('layout')

@section('right')
	<div class="col-sm-4">
		<div>
			<h5>Новая трансмиссия</h5>
			{!! Form::open() !!}

				<div class="col-sm-9 pad-0" >
					{{ Form::text('name', '', ['class' => 'form-control', 'required'=>'required']) }}
					{{ Form::hidden('hidden','1') }}
				</div>
				<div class="col-sm-3 pad-0"> 
					{!! Form::submit('Создать',['class' => 'form-control','name'=>'submit']) !!}
				</div>

			{!! Form::close() !!}
		</div>
	</div>

	<div class="col-sm-4">
		<div>
			<h5>Новый привод</h5>
			{!! Form::open() !!}

				<div class="col-sm-9 pad-0" >
					{{ Form::text('name', '', ['class' => 'form-control', 'required'=>'required']) }}
					{{ Form::hidden('hidden','2') }}
				</div>
				<div class="col-sm-3 pad-0"> 
					{!! Form::submit('Создать',['class' => 'form-control','name'=>'submit']) !!}
				</div>

			{!! Form::close() !!}
		</div>
	</div>

	<div class="col-sm-4">
		<div>
			<h5>Новый тип</h5>
			{!! Form::open() !!}

				<div class="col-sm-9 pad-0" >
					{{ Form::text('name', '', ['class' => 'form-control', 'required'=>'required']) }}
					{{ Form::hidden('hidden','3') }}
				</div>
				<div class="col-sm-3 pad-0"> 
					{!! Form::submit('Создать',['class' => 'form-control','name'=>'submit']) !!}
				</div>

			{!! Form::close() !!}
		</div>
	</div>

	<div class="clearfix"></div>

	<div class="col-sm-4 ">
		<table class="table">
		<h3>Трансмиссии</h3>
		@foreach($transmissions as $trans)
			<tr>
				<td>{{$trans->name}}</td>
				<td class="text-right">
					{!! Form::open() !!}
						{!! Form::hidden('_method','delete') !!}
						{!! Form::hidden('hidden','1') !!}
						{!! Form::hidden('id',$trans->id) !!}
						<button type="submit" class="delete-button"><i class="text-danger glyphicon glyphicon-remove"></i></button>
					{!! Form::close() !!}
				</td>
			</tr>
		@endforeach
		</table>
	</div>

	<div class="col-sm-4">
		<table class="table">
		<h3>Привода</h3>
		@foreach($wheels as $wheel)
			<tr>
				<td>{{$wheel->name}}</td>
				<td class="text-right">
					{!! Form::open() !!}
						{!! Form::hidden('_method','delete') !!}
						{!! Form::hidden('hidden','2') !!}
						{!! Form::hidden('id',$wheel->id) !!}
						<button type="submit" class="delete-button"><i class="text-danger glyphicon glyphicon-remove"></i></button>
					{!! Form::close() !!}
				</td>
			</tr>
		@endforeach
		</table>
	</div>


	<div class="col-sm-4">
		<table class="table">
		<h3>Типы моторов</h3>
		@foreach($motortypes as $type)
			<tr>
				<td>{{$type->name}}</td>
				<td class="text-right">
					{!! Form::open() !!}
						{!! Form::hidden('_method','delete') !!}
						{!! Form::hidden('hidden','3') !!}
						{!! Form::hidden('id',$type->id) !!}
						<button type="submit" class="delete-button"><i class="text-danger glyphicon glyphicon-remove"></i></button>
					{!! Form::close() !!}
				</td>
			</tr>
		@endforeach
		</table>
	</div>

@endsection