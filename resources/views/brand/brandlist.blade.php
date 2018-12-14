@extends('layout')

@section('right')
	<a href="{{ route($route) }}">{{ $addTitle }}</a>
	<table class="table">
	@foreach ($list as $key=>$item)
		<tr>
			<td class="width-50">{{$key+1}}</td>
	    	<td class="width-100">{{ $item->name }}</td>
	    	<td>
	    		@if($item->icon)
	    			<?= $item->getIcon(); ?>
	    		@endif
	    	</td>
	    	<td class="width-50">
	    		<a title="Изменить" href="{{ route($edit, array('id'=>$item->id)) }}">
	    			<i class=" glyphicon glyphicon-cog"></i>	
	    		</a>
	    	</td>
	    	<td class="width-50">
	    		<a title="Удалить" href="{{ route($delete, array('id'=>$item->id)) }}">
	    			<i class="text-danger glyphicon glyphicon-remove"></i>
	    		</a>
	    	</td>
		</tr>
	@endforeach
	</table>
@endsection