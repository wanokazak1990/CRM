@extends('layout')



@section('right')
	{!! Form::open(array('files'=>'true')) !!}
		<div class="row">
			<div class="col-sm-4"> 
			{!! Form::label('title', 'Название:') !!}
				{!! Form::text('name', $option->name, ['class' => 'form-control']) !!}
			</div>

			<div class="col-sm-2"> 
			{!! Form::label('title','Раздел:') !!}
				{!! Form::select('parent_id',$parents, $option->parent_id,['class' => 'form-control']) !!}
			</div>

			@isset($brands)
				<div class="col-sm-12" style="padding-top: 30px;">
				<label>Применяемость:</label><br>
				@foreach ($brands as $key => $brand)
					<span class="brand-span">
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
			<!-- ----------------------------------------------------------------------- -->
			
			<div class="clearfix"></div>
			<div class="adding-control">
				<div class="col-sm-2"> 
				{!! Form::submit('Создать',	 ['class' => 'form-control btn btn-primary','name'=>'submit']) !!}
				</div>
				
				<div class="col-sm-2">
				{!! Form::submit('Отмена',	 ['class' => 'form-control btn-danger','name'=>'cansel']) !!}
				</div>
			</div>
		</div>
	{!! Form::close() !!}
@endsection