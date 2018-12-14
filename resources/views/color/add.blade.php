@extends('layout')



@section('right')
	{!! Form::open(array('files'=>'true')) !!}
		<div class="row"> 
			<div class="col-sm-2"> 
				{!! Form::label('title', 'Бренд:') !!}
				{!! Form::select('brand_id',$brands,$color->brand_id,['class' => 'form-control']) !!}
			</div>
			<div class="col-sm-2"> 
				{!! Form::label('title', 'Название:') !!}
				{!! Form::text('name', $color->name, ['class' => 'form-control']) !!}
			</div>
			<div class="col-sm-2">
				{!! Form::label('title','Код производителя') !!}
				{!! Form::text('rn_code',$color->rn_code, ['class' => 'form-control']) !!}
			</div>
			<div class="col-sm-2">
				{!! Form::label('title','Web-код цвета') !!}
				{!! Form::text('web_code',$color->web_code, ['class' => 'form-control']) !!}
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