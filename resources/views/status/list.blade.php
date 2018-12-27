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
	@foreach($list as $key=>$status)
		<tr>
			<td class="width-50">{{$key+1}}</td>
			<td>{{$status->name}}</td>
			<td class="width-50"><a href="{{ route($edit,['id'=>$status->id]) }}"><i class="glyphicon glyphicon-cog"></i></a></td>
			<td class="width-50">
				<a class="{{($key>3)?'':'disabled'}}" href="{{ route($delete,['id'=>$status->id]) }}">
					<i class="{{($key>3)?'text-danger':'disabled'}} glyphicon glyphicon-remove"></i>
				</a>
			</td>
		</tr>
	@endforeach
	</table>
@endsection