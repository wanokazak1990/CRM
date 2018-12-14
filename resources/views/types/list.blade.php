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
		@foreach($list as $key => $type)
		<tr>
			<td class="width-50">{{ $key + 1 }}</td>
			<td class="width-200">{{ $type->name }}</td>
			<td class="width"><img src="{{ Storage::url('images/file_types/' . $type->icon) }}" width="100"></td>
			<td class="width-50">
				<a href="{{ route($edit, array('id'=>$type->id)) }}"><i class="glyphicon glyphicon-cog"></i></a>
			</td>
			<td class="width-50">
				<a href="{{ route($delete, array('id'=>$type->id)) }}"><i class="text-danger glyphicon glyphicon-remove"></i></a>
			</td>
		</tr>
		@endforeach
	</table>
@endsection