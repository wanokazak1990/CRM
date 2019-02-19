<h3>{{ $item['car_name'] }}</h3>

<div style="width: 100%;">
	<img src="{{ $item['img'] }}" style="width: 50%; height: auto; background-color: {{ $item['color_code'] }};">	
</div>

<table>
	<tr>
		<td>VIN номер</td>
		<td>{{ $item['car_vin'] }}</td>
	</tr>

	<tr>
		<td>Комплектация</td>
		<td>{{ $item['complect_name'] }}</td>
	</tr>

	<tr>
		<td>Исполнение</td>
		<td>{{ $item['complect_code'] }}</td>
	</tr>

	<tr>
		<td>Цена комплектации</td>
		<td>{{ $item['complect_price'] }}</td>
	</tr>

	<tr>
		<td>Цвет</td>
		<td>{{ $item['color_name'] }}</td>
	</tr>

	<tr>
		<td>Образец цвета</td>
		<td style="background-color: {{ $item['color_code'] }}"></td>
	</tr>

	<tr>
		<td>Код цвета</td>
		<td>{{ $item['color_rn_code'] }}</td>
	</tr>

	<tr>
		<td>Информация о машине</td>
		<td>
			@foreach($item['car_info'] as $key => $value)
			<div>{{ $value }}</div>
			@endforeach
		</td>
	</tr>

	<tr>
		<td>Установленное оборудование</td>
		<td>
			@foreach($item['installed'] as $key => $value)
			<div>{{ $value }}</div>
			@endforeach
		</td>
	</tr>

	<tr>
		<td>Доп. оборудование</td>
		<td>
			@foreach($item['dops'] as $key => $value)
			<div>{{ $value }}</div>
			@endforeach
		</td>
	</tr>

	<tr>
		<td>Цена доп. оборудования</td>
		<td>{{ $item['car_dopprice'] }}</td>
	</tr>

	<tr>
		<td>Опции</td>
		<td>{!! $item['options'] !!}</td>
	</tr>

	<tr>
		<td>Итоговая цена автомобиля</td>
		<td>{{ $item['fullprice'] }}</td>
	</tr>
</table>

<style>
	table {
		background-color: #eee;
	}
	td {
		background-color: #fff;
	}
</style>