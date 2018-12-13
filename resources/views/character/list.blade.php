@extends ('layout')

@section('right')
<a href="{{ route($route) }}">{{ $addTitle }}</a>
<table class="table">
	@foreach($list as $key => $char)
	<tr>
		<td class="width-50">{{ $key + 1 }}</td>
		<td>{{ $char->name }}</td>	
		<td class="width-50">
			<a href="{{ route($edit, array('id'=>$char->id)) }}">
				<i class=" glyphicon glyphicon-cog"></i>
			</a>
		</td>	
		<td class="width-50">
			<a href="{{ route($delete, array('id'=>$char->id)) }}">
				<i class="text-danger glyphicon glyphicon-remove"></i>
			</a>
		</td>	
	</tr>
	@endforeach
</table>
@endsection