<!DOCTYPE html>
<html>
<head>
	<title>Коммерческое предложение</title>
</head>
<body>

@foreach($cars as $key => $car)
<div>
	<h3>{{ $car['car_name'] }}</h3>

	<div style="width: 100%;">
		<img src="{{ $car['img'] }}" style="width: 50%; height: auto; background-color: {{ $car['color_code'] }};">	
	</div>

	<div style="width: 100%; margin-bottom: 20px;">
		<span><b>VIN номер:</b> {{ $car['car_vin'] }}</span><br>
		<span><b>Комплектация:</b> {{ $car['complect_name'] }}</span><br>
		<span><b>Исполнение:</b> {{ $car['complect_code'] }}</span><br>
		<span><b>Цвет:</b> {{ $car['color_name'] }}</span><br>
		<span><b>Образец цвета:</b> <span style="color: {{ $car['color_code'] }};">{{ $car['color_name'] }}</span></span><br>
		<span><b>Код цвета:</b> {{ $car['color_rn_code'] }}</span><br>
		
		<span><b>Информация о машине:</b></span><br>
		<ol style="margin-bottom: 20px;">
			@foreach($car['car_info'] as $key => $value)
			<li>{{ $value }}</li>
			@endforeach
		</ol>

		<span><b>Установленное оборудование:</b></span><br>
		<ol style="margin-bottom: 20px;">
			@foreach($car['installed'] as $key => $value)
			<li>{{ $value }}</li>
			@endforeach
		</ol>

		<span><b>Цена автомобиля:</b> {{ $car['complect_price'] }}</span><br>

		<span><b>Доп. оборудование:</b></span><br>
		<ol style="margin-bottom: 20px;">
			@foreach($car['dops'] as $key => $value)
			<li>{{ $value }}</li>
			@endforeach
		</ol>

		<span><b>Цена доп. оборудования:</b> {{ $car['car_dopprice'] }}</span><br>

		<span><b>Опции:</b></span><br>
		{!! $car['options'] !!}
		<br>

		<span><b>Итоговая цена автомобиля:</b> {{ $car['fullprice'] }}</span>
	</div>
	<hr>
</div>
@endforeach

</body>
</html>