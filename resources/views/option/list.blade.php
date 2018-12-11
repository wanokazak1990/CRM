@extends ('layout')

@section('right')
	<a href="{{ route($route) }}">{{ $addTitle }}</a>
	<table class="table">
	@foreach($list as $key=>$option)
		<tr>
			<td>{{(($list->currentPage()-1)*20)+$key+1}}</td>
			<td>{{$option->name}}</td>
			<td>
				@isset($option->type)
					{{$option->type->name}}
				@endisset
			</td>
			<td class="font-12">
				@isset($option->brands)
					@foreach($option->brands as $brand)
						@isset($brand->getBrandObj)
							{{ $brand->getBrandObj->name }}
						@endisset
					@endforeach
				@endisset
			</td>
			<td><a href="{{ route($edit,['id'=>$option->id]) }}">Изменить</a>
			<td><a href="{{ route($delete,['id'=>$option->id]) }}">Удалить</a>
		</tr>
	@endforeach
	</table>


	{{ $list->links() }}


@endsection