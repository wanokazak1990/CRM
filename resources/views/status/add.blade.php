@extends('layout')



@section('right')
	{!! Form::open(array('files'=>'true')) !!}
	<div class="row">
		<div class="col-sm-2"> 
		{!! Form::label('title', 'Название:') !!}
			{!! Form::text('name', $status->name, ['class' => 'form-control']) !!}
		</div>
		<!-- ----------------------------------------------------------------------- -->
		
		<div class="clearfix"></div>
		<div class="adding-control">
			<div class="col-sm-2"> 
			{!! Form::submit('Создать',	 ['class' => 'form-control btn btn-primary','name'=>'submit']) !!}
			</div>
			
			<div class="col-sm-2">
			{!! Form::submit('Отмена',	 ['class' => 'form-control btn btn-danger','name'=>'cansel']) !!}
			</div>
		</div>
	</div>
	{!! Form::close() !!}
@endsection