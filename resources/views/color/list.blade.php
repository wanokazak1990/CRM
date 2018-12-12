@extends ('layout')

@section('right')
	<a href="{{ route($route) }}">{{ $addTitle }}</a>
	<table class="table">
	@foreach($list as $key=> $color)
		<tr>
			<td>{{$key+1}}</td>
			<td>{{$color->name}}</td>
			<td>{{$color->rn_code}}</td>
			<td>
				@isset($color->brand)
					<?=$color->brand->getIcon();?>
				@endisset
			</td>
			<td>
				<?= $color->getColorIcon();?>
			</td>
			<td><a href="{{ route($edit,['id'=>$color->id]) }}">Изменить</a>
			<td><a href="{{ route($delete,['id'=>$color->id]) }}">Удалить</a>
		</tr>
	@endforeach
	</table>

	{{ $list->links() }}
@endsection