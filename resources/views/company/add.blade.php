@extends('layout')



@section('right')
	
	@if ($errors->any())
	    <div class="alert alert-danger">
	        <ul>
	            @foreach ($errors->all() as $error)
	                <li>{{ $error }}</li>
	            @endforeach
	        </ul>
	    </div>
	@endif

	{!! Form::open(array('files'=>'true')) !!}
		<div class="col-sm-2"> 
			{!! Form::label('title','Начало:*') !!}
				{!! Form::text('day_in',($company->day_in)?date('d.m.Y',$company->day_in):'',['class'=>'form-control calendar'])!!}
		</div>

		<div class="col-sm-2"> 
			{!! Form::label('title','Конец:*') !!}
				{!! Form::text('day_out',($company->day_out)?date('d.m.Y',$company->day_out):'',['class'=>'form-control calendar'])!!}
		</div>

		<div class="col-sm-2">
			{!! Form::label('title','Статус:*') !!}
			{!! Form::select('status',[0=>'Неактивна','1'=>'Активна'],$company->status,['class'=>'form-control'])!!}
		</div>

		<div class="col-sm-2">
			{!! Form::label('title','Таймер:*') !!}
			{!! Form::select('timer',[0=>'Отключен','1'=>'Включен'],$company->timer,['class'=>'form-control' ])!!}
		</div>

		<div class="clearfix"></div>

		<div class="col-sm-2">
			{!! Form::label('title','Раздел:*') !!}
			{!! Form::select('razdel',$company->getRazdels(), $company->razdel,['class'=>'form-control' ])!!}
		</div>

		<div class="col-sm-2">
			{!! Form::label('title','Название:*') !!}
			{!! Form::text('name',$company->name,['class'=>'form-control']) !!}
		</div>

		<div class="col-sm-2">
			{!! Form::label('title','Сценарий:*') !!}
			{!! Form::select('scenario',$company->getScenario(), $company->scenario,['class'=>'form-control' ])!!}
		</div>

		<div class="col-sm-2">
			{!! Form::label('title','Лояльность:*') !!}
			{!! Form::select('main',$company->getMains(), $company->main,['class'=>'form-control' ])!!}
		</div>

		<div class="col-sm-2">
			{!! Form::label('title','Зависимость:*') !!}
			{!! Form::select('immortal',$company->getImmortals(), $company->immortal,['class'=>'form-control' ])!!}
		</div>

		<div class="clearfix"></div>

		<div class="data">
			@if($company->scenario==1)
				<div class="col-sm-12"><h4>Параметры расчёта</h4></div>
				<div class="col-sm-2">
					<label>
						<input type="checkbox" value="1" name="base" {{($company->base)?"checked":""}}>
						Включить на базу
					</label>

					<label>
						<input type="checkbox" value="1" name="option" {{($company->option)?"checked":""}}>
						Включить на опции
					</label>

					<label>
						<input type="checkbox" value="1" name="dop" {{($company->dop)?"checked":""}}>
						Включить на допы
					</label>
				</div>

				<div class="col-sm-2">
					<label>Значение(%):</label>
					<input type="range" name="value" value="{{$company->value}}" min="0" max="100" step="0.5" class="form-control">
				</div>

				<div class="col-sm-2">
					<label>Ограничение скидки:</label>
					<input type="text" name="max" value="{{$company->max}}" class="form-control">
				</div>
			@endif

			@if($company->scenario==2)
				<div class="col-sm-12"><h4>Параметры бюджета</h4></div>
				<div class="col-sm-2">
					<label>Сумма:</label>
					<input type="text" name="bydget" value="{{$company->bydget}}" class="form-control">
				</div>
			@endif

			@if($company->scenario==3)
				<div class="col-sm-12"><h4>Параметры номенклатуры</h4></div>
				<div class="col-sm-2">
					<label>ДО:</label>
					<button type="button" class="open-dop form-control">Выбрать ДО</button>
				</div>
			@endif
		</div>

		<div class="company-dop">
			<div class="col-sm-8">
				<h4>Список номенклатуры</h4>
			</div>
			<div class="col-sm-4 text-right">
				<h4><button type="button" class="close">x</button></h4>
			</div>
			<div class="col-sm-12"></div> 
			<div class="dop"> 
			@isset($dops)
				@foreach($dops as $id=>$name)
					<div class="col-sm-4">
						<label>
							<input 
								type="checkbox" 
								name="dops[]" 
								value="{{$id}}"
								@if($company->dops->contains('dop_id',$id))
									checked
								@endif
							>
							{{$name}}
						</label>
					</div>
				@endforeach
			@endisset
			</div>
		</div>

		<div class="clearfix"></div>

		<div class='pos_exeptions'>
			<div class="col-sm-12"><h4>Включение:</h4></div>
			@isset($company->exception)
				<?php $i=0; ?>
				@foreach($company->exception as $key=>$excep)
					@if($excep->type==1)
						<div class="exep">
							<div class="col-sm-1" style="padding-left: 15px;">
								<label style="color: #fff;">&</label>
								@if($i==0)
									<button type="button" class="clone form-control">Добавить</button>
								@endif
								<input type="hidden" name="type[{{$key}}]" value="{{$excep->type}}">
							</div>
							<div class="col-sm-2">
								{{ ($i==0)?Form::label('title','VIN:'):Form::label('title',' ')}}
								{{Form::text("vin[$key]",($excep->vin!='null')?$excep->vin:"",['class'=>'form-control'])}}					
							</div>
							<div class="col-sm-1" >
								{{	($i==0)?Form::label('title','Модель:'):Form::label('title',' ')}}
								{{ Form::select("model_id[$key]",$models,$excep->model_id,['class'=>'form-control model'])}}
							</div>
							<div class="col-sm-2">
								{{	($i==0)?Form::label('title','Комплектация:'):Form::label('title',' ')}}
								{{ Form::select("complect_id[$key]",$excep->getComplect(),$excep->complect_id,['class'=>'form-control complect'])}}
							</div>
							<div class="col-sm-1">
								{{	($i==0)?Form::label('title','Трансмиссия:'):Form::label('title',' ')}}
								{{ 	Form::select("transmission_id[$key]",$transmissions,$excep->transmission_id,['class'=>'form-control '])}}
							</div>
							<div class="col-sm-1">
								{{	($i==0)?Form::label('title','Привод:'):Form::label('title',' ')}}
								{{ 	Form::select("wheel_id[$key]",$wheels,$excep->wheel_id,['class'=>'form-control '])}}
							</div>
							<div class="col-sm-1">
								{{	($i==0)?Form::label('title','Этап поставки:'):Form::label('title',' ')}}
								{{ 	Form::select("location_id[$key]",$locations,$excep->location_id,['class'=>'form-control '])}}
							</div>
							<div class="col-sm-1">
								{{ ($i==0)?Form::label('title','Цена от (руб.):'):Form::label('title',' ')}}
								{{Form::text("pricestart[$key]",($excep->pricestart)?$excep->pricestart:"",['class'=>'form-control'])}}					
							</div>
							<div class="col-sm-1">
								{{ ($i==0)?Form::label('title','Цена до (руб.):'):Form::label('title',' ')}}
								{{Form::text("pricefinish[$key]",($excep->pricefinish)?$excep->pricefinish:"",['class'=>'form-control'])}}					
							</div>
							<div class="col-sm-1" style="padding-right: 15px;">
								@if($i==0)<label style="color: #fff;">&</label>@endif
								@if($i>0)<label style="color: #fff;"></label>@endif
								<button type="button" class="delete form-control">Удалить</button>
							</div>
						</div>
						<?php $i++;?>
					@endif
				@endforeach
			@endisset

			@if(!$company->exception->contains('type',1))
			<div class="exep">
				<div class="col-sm-1" style="padding-left: 15px;">
					<label style="color: #fff;">&</label>
					<button type="button" class="clone form-control">Добавить</button>
					<input type="hidden" name="type[2001]" value="1">
				</div>
				<div class="col-sm-2">
					{{Form::label('title','VIN:')}}
					{{Form::text('vin[2001]','',['class'=>'form-control'])}}					
				</div>
				<div class="col-sm-1" >
					{{	Form::label('title','Модель:')}}
					{{ Form::select('model_id[2001]',$models,'',['class'=>'form-control model'])}}
				</div>
				<div class="col-sm-2">
					{{	Form::label('title','Комплектация:')}}
					{{ Form::select('complect_id[2001]',[],'',['class'=>'form-control complect'])}}
				</div>
				<div class="col-sm-1">
					{{	Form::label('title','Трансмиссия:')}}
					{{ 	Form::select('transmission_id[2001]',$transmissions,'',['class'=>'form-control '])}}
				</div>
				<div class="col-sm-1">
					{{	Form::label('title','Привод:')}}
					{{ 	Form::select('wheel_id[2001]',$wheels,'',['class'=>'form-control '])}}
				</div>
				<div class="col-sm-1">
					{{	Form::label('title','Этап поставки:')}}
					{{ 	Form::select('location_id[2001]',$locations,'',['class'=>'form-control '])}}
				</div>
				<div class="col-sm-1">
					{{Form::label('title','Цена от (руб.):')}}
					{{Form::text('pricestart[2001]','',['class'=>'form-control'])}}					
				</div>
				<div class="col-sm-1">
					{{Form::label('title','Цена до (руб.):')}}
					{{Form::text('pricefinish[2001]','',['class'=>'form-control'])}}					
				</div>
				<div class="col-sm-1" style="padding-right: 15px;">
					<label style="color: #fff;">&</label>
					<button type="button" class="delete form-control">Удалить</button>
				</div>
			</div>
			@endif
		</div>

		<div class='pos_exeptions'>
			<div class="col-sm-12"><h4>Исключение: </h4></div>
			@isset($company->exception)
				<?php $i=0;?>
				@foreach($company->exception as $key=>$excep)
					@if($excep->type==0)
					<div class="exep">
						<div class="col-sm-1" style="padding-left: 15px;">
							<label style="color: #fff;">&</label>
							@if($i==0)<button type="button" class="clone form-control">Добавить</button>@endif
							<input type="hidden" name="type[{{$key}}]" value="{{$excep->type}}">
						</div>
						<div class="col-sm-2">
							{{ ($i==0)?Form::label('title','VIN:'):Form::label('title',' ')}}
							{{Form::text("vin[$key]",($excep->vin!='null')?$excep->vin:"",['class'=>'form-control'])}}					
						</div>
						<div class="col-sm-1" >
							{{ ($i==0)?Form::label('title','Модель:'):Form::label('title',' ')}}
							{{ Form::select("model_id[$key]",$models,$excep->model_id,['class'=>'form-control model'])}}
						</div>
						<div class="col-sm-2">
							{{ ($i==0)?Form::label('title','Комплектация:'):Form::label('title',' ')}}
							{{ Form::select("complect_id[$key]",$excep->getComplect(),$excep->complect_id,['class'=>'form-control complect'])}}
						</div>
						<div class="col-sm-1">
							{{ ($i==0)?Form::label('title','Трансмиссия:'):Form::label('title',' ')}}
							{{ 	Form::select("transmission_id[$key]",$transmissions,$excep->transmission_id,['class'=>'form-control '])}}
						</div>
						<div class="col-sm-1">
							{{ ($i==0)?Form::label('title','Привод:'):Form::label('title',' ')}}
							{{ 	Form::select("wheel_id[$key]",$wheels,$excep->wheel_id,['class'=>'form-control '])}}
						</div>
						<div class="col-sm-1">
							{{ ($i==0)?Form::label('title','Этап поставки:'):Form::label('title',' ')}}
							{{ 	Form::select("location_id[$key]",$locations,$excep->location_id,['class'=>'form-control '])}}
						</div>
						<div class="col-sm-1">
							{{ ($i==0)?Form::label('title','Цена от (руб.):'):Form::label('title',' ')}}
							{{Form::text("pricestart[$key]",($excep->pricestart)?$excep->pricestart:"",['class'=>'form-control'])}}					
						</div>
						<div class="col-sm-1">
							{{ ($i==0)?Form::label('title','Цена до (руб.):'):Form::label('title',' ')}}
							{{Form::text("pricefinish[$key]",($excep->pricefinish)?$excep->pricefinish:"",['class'=>'form-control'])}}					
						</div>
						<div class="col-sm-1" style="padding-right: 15px;">
							@if($i==0)<label style="color: #fff;">&</label>@endif
							@if($i>0)<label style="color: #fff;"></label>@endif
							<button type="button" class="delete form-control">Удалить</button>
						</div>
					</div>
					<?php $i++;?>
					@endif
				@endforeach
			@endisset

			@if(!$company->exception->contains('type',0))
			<div class="exep">
				<div class="col-sm-1" style="padding-left: 15px;">
					<label style="color: #fff;">&</label>
					<button type="button" class="clone form-control">Добавить</button>
					<input type="hidden" name="type[2000]" value="0">
				</div>
				<div class="col-sm-2">
					{{Form::label('title','VIN:')}}
					{{Form::text('vin[2000]','',['class'=>'form-control'])}}					
				</div>
				<div class="col-sm-1" >
					{{	Form::label('title','Модель:')}}
					{{ Form::select('model_id[2000]',$models,'',['class'=>'form-control model'])}}
				</div>
				<div class="col-sm-2">
					{{	Form::label('title','Комплектация:')}}
					{{ Form::select('complect_id[2000]',[],'',['class'=>'form-control complect'])}}
				</div>
				<div class="col-sm-1">
					{{	Form::label('title','Трансмиссия:')}}
					{{ 	Form::select('transmission_id[2000]',$transmissions,'',['class'=>'form-control '])}}
				</div>
				<div class="col-sm-1">
					{{	Form::label('title','Привод:')}}
					{{ 	Form::select('wheel_id[2000]',$wheels,'',['class'=>'form-control '])}}
				</div>
				<div class="col-sm-1">
					{{	Form::label('title','Этап поставки:')}}
					{{ 	Form::select('location_id[2000]',$locations,'',['class'=>'form-control '])}}
				</div>
				<div class="col-sm-1">
					{{Form::label('title','Цена от (руб.):')}}
					{{Form::text('pricestart[2000]','',['class'=>'form-control'])}}					
				</div>
				<div class="col-sm-1">
					{{Form::label('title','Цена до (руб.):')}}
					{{Form::text('pricefinish[2000]','',['class'=>'form-control'])}}					
				</div>
				<div class="col-sm-1" style="padding-right: 15px;">
					<label style="color: #fff;">&</label>
					<button type="button" class="delete form-control">Удалить</button>
				</div>
			</div>
			@endisset
		</div>

		<div class="clearfix"></div>
		<div class="col-sm-12"><h4>Виджет:</h4></div>
		<div class="col-sm-4">
			<ul>

    			<li>&lt;model&gt; - идентификатор модели</li>
    			<li>&lt;bydjet&gt; - идентификатор суммы бюджета</li>
    			<li>&lt;procent&gt; - идентификатор суммы скидки</li>
    			<li>&lt;vin&gt; - vin машины</li>
    			<li>&lt;nomen&gt; - номенклатура</li>

    		</ul>
		</div>
		<div class="col-sm-8">
			{{ Form::label('title','Заголовок')}}
			{{Form::text('title',$company->title,['class'=>'form-control'])}}

			{{ Form::label('title','Описание')}}
			{{Form::textarea('text',$company->text,['class'=>'form-control'])}}

			{{ Form::label('title','Офер')}}
			{{Form::text('ofer',$company->ofer,['class'=>'form-control'])}}
		</div>
		<div class="clearfix"></div>

		<div class="col-sm-2"> 
		{!! Form::submit('Создать',	 ['class' => 'form-control','name'=>'submit']) !!}
		</div>
		
		<div class="col-sm-2">
		{!! Form::submit('Отмена',	 ['class' => 'form-control','name'=>'cansel']) !!}
		</div>

		{{ csrf_field() }}
	{!! Form::close() !!}
@endsection