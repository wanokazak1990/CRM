@extends('layout')



@section('right')
	{!! Form::open(array('files'=>'true')) !!}

		<div class="col-sm-2"> 
		{!! Form::label('title', 'Начало:*') !!}
			{!! Form::text('day_in', ($kredit->day_in)?date('d.m.Y',$kredit->day_in):'', ['required'=>'required', 'class' => 'calendar form-control']) !!}
		</div>

		<div class="col-sm-2"> 
		{!! Form::label('title', 'Конец:*') !!}
			{!! Form::text('day_out', ($kredit->day_out)?date('d.m.Y',$kredit->day_out):'', ['required'=>'required','class' => 'calendar form-control']) !!}
		</div>

		<div class="clearfix"></div>

		<div class="col-sm-4"> 
		{!! Form::label('title', 'Название:*') !!}
			{!! Form::text('name', $kredit->name, ['required'=>'required','class' => 'form-control']) !!}
		</div>

		<div class="col-sm-2">
			{!! Form::label('title','Баннер') !!}<br>
					@isset($kredit->banner)
						<img src="{{ Storage::url(('kredit').'/'.$kredit->banner) }}" style="height: 100px;">
					@endisset
					{!! Form::file('banner') !!}
		</div>

		<div class="clearfix"></div>

		<div class="col-sm-2"> 
		{!! Form::label('title', 'Ставка(%):') !!}
			{!! Form::range('rate',0,20, $kredit->rate, ['step'=>'0.1','class' => 'form-control']) !!}
		</div>

		<div class="col-sm-2">
			{!! Form::label('title', 'Срок кредита (лет):') !!}
			{!! Form::select('period',[1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7],$kredit->period,['class' => 'form-control']) !!}
		</div>

		<div class="col-sm-2"> 
		{!! Form::label('title', 'Платёж (руб.):') !!}
			{!! Form::text('pay', $kredit->pay, ['class' => 'form-control']) !!}
		</div>

		<div class="col-sm-2"> 
		{!! Form::label('title', 'Первый взнос(%):') !!}
			{!! Form::range('contibution',0,100, $kredit->contibution, ['step'=>'1','class' => 'form-control']) !!}
		</div>

		<div class="col-sm-2"> 
		{!! Form::label('title', 'Дополнительно:') !!}
			{!! Form::text('dopoption', $kredit->dopoption, ['class' => 'form-control']) !!}
		</div>

		<div class="clearfix"></div>

		<div class="col-sm-12">
			{!! Form::label('title', 'Дисклеймер:') !!}
			{!! Form::textarea('disklamer', $kredit->disklamer, ['class' => 'form-control']) !!}
		</div>

		<div class="clearfix"></div>

		<div class="col-sm-2">
			{!! Form::label('title', 'Бренд:*') !!}
			{!! Form::select('brand_id',$brands,$kredit->brand_id,['required'=>'required','class' => 'form-control']) !!}
		</div>
		<div class="col-sm-12">
			<h3>Доступные модели</h3>
			<div class="model">
				@isset($models)
				@foreach($models as $key => $model)
					<span>
						<label>
							<input 
								type="checkbox" 
								name="pack_model[]" 
								value="{{$model->id}}"
								<?php
									if(isset($kredit->model) && $kredit->model->contains('model_id',$model->id)) : 
										echo "checked";
									endif;
								?>
							>
							{{ $model->name }}
						</label>
					</span>
				@endforeach
			@endisset
			</div>
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