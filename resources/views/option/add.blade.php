@extends('layout')



@section('right')
	{!! Form::open(array('files'=>'true')) !!}
		@isset($brands)
			<div>
			@foreach ($brands as $key => $brand)
				<span>
					<label>
						<input 
							type="checkbox" 
							name="brands[]" 
							value="{{$key}}"
							<?php if(isset($option->brands) && $option->brands->contains('brand_id',$key)) echo "checked";?>
						>
						{{$brand}}
					</label>
				</span>
			@endforeach
			</div>
		@endisset

		<div class="col-sm-10"> 
		{!! Form::label('title', 'Название:') !!}
			{!! Form::text('name', $option->name, ['class' => 'form-control']) !!}
		</div>

		<div class="col-sm-2"> 
		{!! Form::label('title','Раздел') !!}
			{!! Form::select('parent_id',$parents, $option->parent_id,['class' => 'form-control']) !!}
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