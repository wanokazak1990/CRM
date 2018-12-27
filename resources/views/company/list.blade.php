@extends('layout')



@section('right')
	
	{{ Form::open(['method'=>'get','style'=>'padding-bottom:30px;']) }}
		<div class="row">
			<div class="col-sm-2">
				<label>&nbsp</label>
				<a class='form-control btn btn-danger' href="{{ route($route) }}">{{ $addTitle }}</a>
			</div>

			<div class="col-sm-2 col-sm-offset-8">
				<label>&nbsp</label>
				{{ Form::submit('Экспорт',['class'=>'form-control btn btn-default','name'=>'export'])}}
			</div>
		</div>
	{{ Form::close() }}

	<table class="table">
		<tr>
			<th>№</th>
			<th>Название</th>
			<th colspan="2">Статус</th>
			<th>Сцен-ий</th>
			<th>Параметры</th>
			<th>Условия</th>
			<th>Начало</th>
			<th>Конец</th>
			<th title="Лояльность">Л-ть</th>
			<th title="Зависимость">З-ть</th>
			<th></th>
			<th></th>
		</tr>
	@foreach ($list as $key=>$item) 
		<tr>
			<td class="width-50">{{$key+1}}</td>
			
	    	<td style="white-space: nowrap;">{{ $item->name }}</td>
	    	<td class="width-50">
	    		@if(strtotime(date('d.m.Y'))>$item->day_out)
	    			<span class="text-danger">{{ $item->getStatus()[$item->status]}}</span>
	    		@elseif(strtotime(date('d.m.Y'))<$item->day_in)
	    			<span class="text-danger">{{ $item->getStatus()[$item->status]}}</span>
	    		@else
	    			<span class="text-success">{{ $item->getStatus()[$item->status]}}</span>
	    		@endif
	    	</td>
	    	<td class="width-50">
	    		
	    		@if(strtotime(date('d.m.Y'))>$item->day_out)
	    			<span class='size-25 text-danger glyphicon glyphicon-exclamation-sign' title="Истекла"></span>
	    		@elseif(strtotime(date('d.m.Y'))<$item->day_in)
	    			<span class='size-25 text-danger glyphicon glyphicon-exclamation-sign ' title="Не наступила"></span>
	    		@else
	    			<span class='size-25 text-success glyphicon glyphicon-ok-sign' title="Работает"></span>
	    		@endif
	    	</td>
	    	
	    	<td>{{ $item->getScenario()[$item->scenario]}}</td>
	    	<td>
	    		@if($item->scenario==1)
	    			{{$item->getRazdels()[$item->razdel]}}: {{$item->value}}% (огранич. {{number_format($item->max,0,'',' ')}} руб.) на
	    			@if($item->base)
	    				база 
	    			@endif
	    			@if($item->option)
	    				опции 
	    			@endif
	    			@if($item->dops)
	    				допы 
	    			@endif
	    		@endif
	    		@if($item->scenario==2)
	    			{{$item->getRazdels()[$item->razdel]}}: {{number_format($item->bydget,0,'',' ')}} руб.
	    		@endif
	    		@if($item->scenario==3)
	    			{{$item->getRazdels()[$item->razdel]}}: номенклатура
	    		@endif
	    		@if($item->scenario==4)
	    			{{$item->getRazdels()[$item->razdel]}}: {{$item->ofer}}
	    		@endif
	    	</td>
	    	<td class="size-10 ">
	    		@foreach($item->exception as $exception) 
	    			<div class="{{($exception->type)?'text-success':'text-danger'}}" >
    				-
	    			@if(!empty($exception->vin))
	    				{{$exception->vin}}
	    			@endif
	    			@isset($exception->getCurrentModel)
	    				{{$exception->getCurrentModel->name}}
	    			@endisset
	    			@isset($exception->getCurrentComplect)
	    				{{$exception->getComplectName()}}
	    			@endisset
	    			@isset($exception->getCurrentTransmission)
	    				{{$exception->getCurrentTransmission->name}}
	    			@endisset
	    			@isset($exception->getCurrentWheel)
	    				{{$exception->getCurrentWheel->name}}
	    			@endisset
	    			@isset($exception->getCurrentLocation)
	    				{{$exception->getCurrentLocation->name}}
	    			@endisset
	    			@if(!empty($exception->pricestart))
	    				цена от: {{number_format($exception->pricestart,0,'',' ')}} руб.
	    			@endif
	    			@if(!empty($exception->pricefinish))
	    				цена до: {{number_format($exception->pricefinish,0,'',' ')}} руб.
	    			@endif
	    		@endforeach
	    	</td>
	    	<td class="width-100">{{date('d.m.Y',$item->day_in)}}</td>
	    	<td class="width-100">{{date('d.m.Y',$item->day_out)}}</td>
	    	<td class="width-50">
				<span 
					class="glyphicon glyphicon-flash size-25 {{($item->main)?'text-success':'text-grey'}}" 
					title="Лояльность: {{($item->main)?'включена (убивает прочие)':'отключена (никого не убивает)'}}"
				>
				</span>
			</td>
			<td class="width-50">
				<span 
					class="glyphicon glyphicon-random size-25 {{($item->immortal)?'text-success':'text-grey'}}"
					title="Зависимость: {{($item->immortal)?'не зависима (никто не убьёт)':'зависима (убиваема)'}}"
				>
				</span>
			</td>
	    	<td class="width-50">
	    		<a href="{{ route($edit, array('id'=>$item->id)) }}"><i class="glyphicon glyphicon-cog"></i></a>
	    	</td>
	    	<td class="width-50">
	    		<a href="{{ route($delete, array('id'=>$item->id)) }}"><i class="text-danger glyphicon glyphicon-remove"></i></a>
	    	</td>
		</tr>
	@endforeach
	</table>
@endsection