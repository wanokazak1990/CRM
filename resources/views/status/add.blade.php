@extends('layout')



@section('right')
	{!! Form::open(array('files'=>'true')) !!}

		<div class="col-sm-2"> 
		{!! Form::label('title', 'Название:') !!}
			{!! Form::text('name', $status->name, ['class' => 'form-control']) !!}
		</div>
		<!-- ----------------------------------------------------------------------- -->
		
		<div class="clearfix"></div>

		<div class="col-sm-2"> 
		{!! Form::submit('Создать',	 ['class' => 'form-control','name'=>'submit']) !!}
		</div>
		
		<div class="col-sm-2">
		{!! Form::submit('Отмена',	 ['class' => 'form-control','name'=>'cansel']) !!}
		</div>

	{!! Form::close() !!}
@endsection