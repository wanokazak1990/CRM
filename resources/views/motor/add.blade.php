@extends('layout')



@section('right')
	{!! Form::open(array('files'=>'true')) !!}
		<div class="row">
			<div class="col-sm-2"> 
				{!! Form::label('title', 'Бренд:') !!}
					{!! Form::select('brand_id',$parts['brands'],$motor->brand_id, ['class'=>'form-control'] ) !!}
			</div>

			<div class="col-sm-2"> 
				{!! Form::label('title', 'Тип:') !!}
					{!! Form::select('type_id',$parts['types'],$motor->type_id, ['class'=>'form-control']) !!}
			</div>

			<div class="col-sm-2"> 
				{!! Form::label('title', 'Трансмиссия:') !!}
					{!! Form::select('transmission_id',$parts['transmissions'],$motor->transmission_id, ['class'=>'form-control']) !!}
			</div>

			<div class="col-sm-2"> 
				{!! Form::label('title', 'Привод:') !!}
					{!! Form::select('wheel_id',$parts['wheels'],$motor->wheel_id, ['class'=>'form-control']) !!}
			</div>

			<div class="col-sm-2">
				{!! Form::label('title','Мощность:')!!}
					{!! Form::range('power',60,300,$motor->power, ['class'=>'form-control']) !!}
			</div>

			<div class="col-sm-2">
				{!! Form::label('title','Объём:')!!}
					{!! Form::range('size',1,5,$motor->size, ['class'=>'form-control','step'=>'0.1']) !!}
			</div>

			<div class="col-sm-2">
				{!! Form::label('title','Клапана:')!!}
					{!! Form::select('valve',[8=>8,16=>16],$motor->valve, ['class'=>'form-control']) !!}
			</div>

		</div>
		<!-- ----------------------------------------------------------------------- -->
		
		{!! Form::submit('Создать',	 ['class' => 'form-control','name'=>'submit']) !!}
		{!! Form::submit('Отмена',	 ['class' => 'form-control','name'=>'cansel']) !!}

	{!! Form::close() !!}
@endsection