@extends('layout')



@section('right')
	{!! Form::open(array('files'=>'true')) !!}

		{!! Form::label('title', 'Бренд:') !!}
			{!! Form::select('brand_id',$brands,$color->brand_id) !!}
		<br>
		{!! Form::label('title', 'Название:') !!}
			{!! Form::text('name', $color->name, ['class' => 'form-control']) !!}
		<br>
		{!! Form::label('title','Код производителя') !!}
			{!! Form::text('rn_code',$color->rn_code, ['class' => 'form-control']) !!}
		<br>
		{!! Form::label('title','Web-код цвета') !!}
			{!! Form::text('web_code',$color->web_code, ['class' => 'form-control']) !!}
		<br>
		<!-- ----------------------------------------------------------------------- -->
		
		{!! Form::submit('Создать',	 ['class' => 'form-control','name'=>'submit']) !!}
		{!! Form::submit('Отмена',	 ['class' => 'form-control','name'=>'cansel']) !!}

	{!! Form::close() !!}
@endsection