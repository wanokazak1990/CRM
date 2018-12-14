@extends ('layout')

@section('right')
	
	{{ Form::open(['method'=>'get','style'=>'padding-bottom:30px;']) }}
		<div class="row">
			<div class="col-sm-2">
				<label>&nbsp</label>
				<a class='form-control btn btn-danger' href="{{ route($route) }}">{{ $addTitle }}</a>
			</div>
		</div>
	{{ Form::close() }}

	<table class="table">
	@foreach($list as $key=>$loc)
		<tr>
			<td class="width-50">{{$key+1}}</td>
			<td>{{$loc->name}}</td>
			<td class="width-50">
				<a href="{{ route($edit,['id'=>$loc->id]) }}">
					<i class="glyphicon glyphicon-cog"></i>
				</a>
			</td>
			<td class="width-50">
				<a href="{{ route($delete,['id'=>$loc->id]) }}">
					<i class="text-danger glyphicon glyphicon-remove"></i>
				</a>
			</td>
		</tr>
	@endforeach
	</table>
@endsection