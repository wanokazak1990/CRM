<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<!-- Font Awesome -->
	<script defer src="https://use.fontawesome.com/releases/v5.5.0/js/all.js" integrity="sha384-GqVMZRt5Gn7tB9D9q7ONtcp4gtHIUEW/yG7h98J7IpE3kpi+srfFyyB/04OV6pG0" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="/public/fonts/icofont/icofont.min.css">
	<link href='/css/crm.css' rel='stylesheet' type='text/css'>
	<link href='/fonts/renault/font.css' rel='stylesheet' type='text/css'>
	<title>{{ $title }}</title>
</head>
<body>
<!-- Блок для перекрытия -->
<div id="disableContent"></div>

<!-- HEADER -->
<div class="container-fluid bg-ice d-flex align-items-center" id="header">
	<!-- <button class="btn btn-info" onclick="send();">Отправить (ТЕСТ WEBSOCKET)</button>
	<button type="button" id="test_load_worklist" class="btn btn-danger">Тестовая загрузка РЛ</button>
	<a href="{{ route('getpdf') }}" target="_blank" class="btn btn-warning">Test PDF</a> -->

    @guest
        <li><a href="{{ route('login') }}">Login</a></li>
        <li><a href="{{ route('register') }}">Register</a></li>
    @else
    	<!--ID пользователя-->
    	<input type="hidden" id="auth_user_id" value="{{ Auth::user()->id }}">

    	<div class="input-group d-flex align-items-center">
    		<div class="d-flex flex-grow-1 text-white align-items-center">{{ Auth::user()->name }} on-line</div>

    		<div class="d-flex align-items-center mr-3">
    			<a href="javascript://" class="text-white d-flex align-items-center">
    				<i class="icofont-alarm"></i>
    			</a>
    		</div>

    		<div class="d-flex align-items-center mr-3">
    			<a href="javascript://" class="text-white d-flex align-items-center" onclick="location.reload();" title="Обновить">
    				<i class="icofont-refresh"></i>
    			</a>
    		</div>

    		<div class="d-flex align-items-center">
    			<a href="{{ route('logout') }}"
		        	class="text-white d-flex align-items-center"
		        	title="Выход" 
		            onclick="event.preventDefault();
		                     document.getElementById('logout-form').submit();"
		        >
		            <i class="icofont-close" style="font-size: 22px;"></i>
		        </a>
    		</div>
    	</div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    @endguest
</div>
<!-- /HEADER -->



<!-- СКРЫТЫЙ БЛОК СПРАВА -->
@section('worklist')
@show

<!-- МОДАЛЬНОЕ ОКНО НАСТРОЕК -->
@section('modal_settings')
@show

<!-- МОДАЛЬНОЕ ОКНО КАРТОЧКИ АВТОМОБИЛЯ -->
@section('modal_autocard')
@show

<!-- МОДАЛЬНОЕ ОКНО ДОБАВЛЕНИЕ ПРОБНОЙ ПОЕЗДКИ -->
@section('modal_add_testdrive')
@show

<!-- МОДАЛЬНОЕ ОКНО ОПЦИЙ И ДО -->
@section('modal_options')
@show

<!-- ОСНОВНОЙ КОНТЕНТ -->
<div id="main" class="container-fluid">
	<div class="row" style="height: 100%;">
		@section('nav-top')
			<!-- Основные вкладки -->
			<ul class="nav nav-tabs nav-justified bg-ice" id="crmTabs" role="tablist" style="width: 100%; height: 42px;">
				
				<li class="nav-item">
					<a 
						class="nav-link active" 
						id="clients-tab" 
						data-toggle="tab" 
						href="#clients" 
						role="tab" 
						aria-controls="clients" 
						aria-selected="true"
						model-name="_tab_client"
						field-id = "1"
					>
						Клиенты
					</a>
				</li>

				<li class="nav-item">
					<a 
						class="nav-link" 
						id="traffic-tab" 
						data-toggle="tab" 
						href="#traffic" 
						role="tab" 
						aria-controls="traffic" 
						aria-selected="false"
						model-name="_tab_traffic"
						field-id = "2"
					>
						Трафик
					</a>
				</li>

				<li class="nav-item">
					<a 
						class="nav-link" 
						id="stock-tab" 
						data-toggle="tab" 
						href="#stock" 
						role="tab" 
						aria-controls="stock" 
						aria-selected="false"
						model-name="_tab_stock"
						field-id = "3"
					>
						Автосклад
					</a>
				</li>

				<li class="nav-item">
					<a 
						class="nav-link" 
						id="deals-tab" 
						data-toggle="tab" 
						href="#deals" 
						role="tab" 
						aria-controls="deals" 
						aria-selected="false"
						field-id = "4"
					>
						Продажи
					</a>
				</li>

				<li class="nav-item">
					<a 
						class="nav-link" 
						id="receipts-tab" 
						data-toggle="tab" 
						href="#receipts" 
						role="tab" 
						aria-controls="receipts" 
						aria-selected="false"
						field-id = "5"
					>
						Поступления
					</a>
				</li>

				<li class="nav-item">
					<a 
						class="nav-link" 
						id="demo-tab" 
						data-toggle="tab" 
						href="#demo" 
						role="tab" 
						aria-controls="demo" 
						aria-selected="false"
						field-id = "6"
					>
						Демо
					</a>
				</li>

				<li class="nav-item">
					<a 
						class="nav-link" 
						id="archive-tab" 
						data-toggle="tab" 
						href="#archive" 
						role="tab" 
						aria-controls="archive" 
						aria-selected="false"
						field-id = "7"
					>
						Архив
					</a>
				</li>

				<li class="nav-item">
					<a 
						class="nav-link" 
						id="stats-tab" 
						data-toggle="tab" 
						href="#stats" 
						role="tab" 
						aria-controls="stats" 
						aria-selected="false"
						field-id = "8"
					>
						Показатели
					</a>
				</li>

			</ul>
		@show

		@section('')
			<!-- Панель иконок -->
			<div class="col-12">
				<div class="row align-items-center" style="height: 42px;">
					<div class="d-flex flex-grow-1 align-items-center">
						<!-- Настройки отображения полей -->
						<a href="javascript://" class="px-3 text-dark d-flex align-items-center" data-toggle="modal" data-target="#settingsModal">
							<i class="icofont-gear" style="font-size: 20px;"></i>
						</a>
						<!-- Загрузить -->
						<a href="javascript://" class="px-3 text-dark d-flex align-items-center">
							<i class="icofont-download" style="font-size: 20px;"></i>
						</a>
						<!-- Карточка автомобиля (сменить значок) -->
						<a href="javascript://" class="px-3 text-dark d-flex align-items-center" data-toggle="modal" data-target="#autocardModal">
							<i class="icofont-auto-mobile" style="font-size: 20px;"></i>
						</a>
						<!-- Поиск -->
						<a href="javascript://" class="px-3 text-dark d-flex align-items-center">
							<i class="icofont-ui-search" style="font-size: 20px;"></i>
						</a>
						<!-- Фильтр -->
						<a href="javascript://" class="px-3 text-dark d-flex align-items-center">
							<i class="icofont-filter" style="font-size: 20px;"></i>
						</a>
					</div>

					<div class="d-flex align-items-center">
						<!-- Новый трафик -->
						<a href="javascript://" class="px-3 text-dark d-flex align-items-center" id="openNewTraffic">
							<i class="icofont-ui-file" style="font-size: 20px;"></i>
						</a>
						<!-- Открыть боковую панель -->
						<a id="opening" a href="javascript://" class="px-3 text-dark d-flex align-items-center">
							<i class="icofont-simple-left" style="font-size: 20px;"></i>
						</a>
					</div>
				</div>
			</div>
			
		@show

		@section('main')

		@show
	</div>
</div>

<!-- FOOTER -->
<div id="footer" class="bg-ice d-flex align-items-center">
	<div class="container-fluid text-white">
		<div class="input-group d-flex align-items-center justify-content-between">
			<span id="stockInfo" class="font-weight-bold"></span>	
			<span>CRM "Учет"</span>
		</div>
	</div>	
</div>
<!-- /FOOTER -->


<div class="traffic_modal original_traffic_modal" traffic_id="">
	<div class="col-12">
		<h3>
			Трафик №<span class="t_id"></span> 
			<span style="float:right" class="t_manager">{{Auth::user()->name}}</span>
		</h3>

		<hr/>

		<h3>
			<span class="t_date"></span>
			<span class="t_author"></span>
		</h3>

		<div class="row">
			<div class="col-4">
				<div>Тип трафика</div>
				<div class="param btn btn-block t_type"></div>
			</div>

			<div class="col-4">
				<div>Спрос</div>
				<div class="param btn btn-block t_model"></div>
			</div>

			<div class="col-4">
				<div>Зона трафика</div>
				<div class="param btn btn-block t_address"></div>
			</div>

			<div class="col-4">
				<div>Назначено</div>
				<div class="param btn btn-block t_action"></div>
			</div>

			<div class="col-8">
				<div>&nbsp</div>
				<div class="btn btn-block text-left t_name" style="font-size:24px;"></div>
			</div>

			<div class="col-4 t_timer">
			</div>

			<div class="w-100" style="height:30px;"></div>

			<!--НАЧАЛО КНОПКИ УПРАВЛЕНИЯ МОДАЛЬЮ ТРАФИКА-->
			<!--НАЧАЛО КНОПКИ УПРАВЛЕНИЯ МОДАЛЬЮ ТРАФИКА-->
			<!--НАЧАЛО КНОПКИ УПРАВЛЕНИЯ МОДАЛЬЮ ТРАФИКА-->
				<div class="col-4 button-div hidden-button">
					<button class="btn btn-block btn-danger traffic_deny">
						Отказать
					</button>
				</div>
				<div class="col-4 button-div hidden-button">
					<button class="btn btn-block btn-default traffic_toall">
						Отдать другим
					</button>
				</div>
				<div class="col-4 button-div hidden-button">
					<button class="btn btn-block btn-success traffic_resend">
						Повторить
					</button>
				</div>
				<div class="col-4 button-div show-button">
					<button class="btn btn-block btn-success traffic_apply">
						Принять
					</button>
				</div>
			<!--КОНЕЦ КНОПКИ УПРАВЛЕНИЯ МОДАЛЬЮ ТРАФИКА-->
			<!--КОНЕЦ КНОПКИ УПРАВЛЕНИЯ МОДАЛЬЮ ТРАФИКА-->
			<!--КОНЕЦ КНОПКИ УПРАВЛЕНИЯ МОДАЛЬЮ ТРАФИКА-->

		</div>
	</div>
</div>


<div id="pv-modal">
	<a class="close icofont-close"></a>
	<div class="pv-price">
		<div class="row">
			<h4 class="col-12 text-center">
				Ваш автомобиль
			</h4>
		</div>
		<div class="pv-list row">
			<div class="col-6">Комплектация:</div>
			<div class="col-6 text-right"><span id="pv-base">0</span> руб.</div>
		</div>
		<div class="pv-list row">
			<div class="col-6">Опции:</div>
			<div class="col-6 text-right"><span id="pv-pack">0</span> руб.</div>
		</div>
		<div class="pv-list row">
			<div class="col-6">Аксессуары:</div>
			<div class="col-6 text-right"><span id="pv-dops">0</span> руб.</div>
		</div>
		<div class="row">
			<div class="col text-right"><span id="pv-total">0</span> руб.</div>
		</div>
	</div>
	<div class="pv-programms">
		<div class="row">
			<h4 class="col text-center">Персональные условия</h4>
		</div>
		<div class="default row">
			<div class="col-10 pv-name"></div>
			<div class="col-2 icon text-right"><i class="icofont-check text-success" style="font-size: 20px;"></i></div>
		</div>
	</div>
</div>

<!-- <div id="car-option-modal">
	<a class="close fa fa-times"></a>
	<h5 class="title-option-modal"></h5>
	<div class="car-option-content"></div>
	<div class="footer-modal"></div>
</div> -->


<div id="edit_traffic_modal" class="w-50 h-100 align-items-center justify-content-center hide-block">
	<div class="container p-3 border shadow">
		<div class="input-group d-flex justify-content-between">
			<span class="h5">Редактирование трафика</span>
			<a href="javascript://" class="text-secondary">
				<i class="fas fa-times" style="width: 21px; height: 21px;"></i>
			</a>
		</div>

		<div class="w-100 border-top"></div>

		<span class="h4 text-secondary">Канал</span>
		<div class="input-group btn-group-toggle mb-3" data-toggle="buttons">
			@foreach($traffic_types as $traffic)
			<div class="col-3 btn btn-light"><input type="radio" name="traffic_type_modal" value="{{ $traffic->id }}" autocomplete="off"> {{ $traffic->name }}</div>
			@endforeach
		</div>
		
		<span class="h4 text-secondary">Спрос</span>
		<div class="input-group btn-group-toggle mb-3" data-toggle="buttons">
			@foreach($models as $key => $model)
			<div class="col-3 btn btn-light"><input type="radio" name="traffic_car_modal" value="{{ $key }}" autocomplete="off"> {{ $model }}</div>
			@endforeach
		</div>

		<span class="h4 text-secondary">Зона контакта</span>
		<div class="input-group btn-group-toggle mb-3" data-toggle="buttons">
			<div class="col-3 btn btn-light">
				<input type="radio" name="area_id_modal" autocomplete="off" value="0"> 
				Неизвестно
			</div>

			@foreach(App\crm_city_list::pluck('name','id') as $id=>$city)
			<div class="col-3 btn btn-light">
				<input type="radio" name="area_id_modal" autocomplete="off" value="{{$id}}"> 
				{{$city}}
			</div>
			@endforeach
		</div>

		<div class="input-group d-flex justify-content-end">
			<button type="button" id="traffic_modal_update" class="btn btn-primary col-3">Обновить трафик</button>
		</div>
	</div>
</div>







<script src="/js/jquery.js"></script>

<script>
	var response_time = 5*1000;//время которое отображается сообщение
	var manager = $("#auth_user_id").val(); //id пользователя 
</script>

<link rel="stylesheet" type="text/css" href="/lib/bootstrap-4/css/bootstrap.min.css">
<script src="/lib/bootstrap-4/js/bootstrap.min.js"></script>
<!--script src="https://unpkg.com/axios/dist/axios.min.js"></script-->
<script src="/js/main.js"></script>
<script src="/js/jquery-ui.js"></script><!--http://api.jqueryui.com/datepicker/-->
<link href='/css/jquery-ui.css' rel='stylesheet' type='text/css'>
<script src="/js/calendar-ui.js"></script>

<script src="/js/function-content.js"></script>
<script src="/js/crm.js"></script>
<script src="/js/socket/trafficsocket.js"></script>
<script src="/js/traffic-label.js"></script>
<script src="/js/car-add.js"></script>
<script src="/js/phone.js"></script>

<script src="/js/worklist/worklist.js"></script>
<script src="/js/worklist/wl-auto.js"></script>
<script src="/js/worklist/wl-comments.js"></script>
<script src="/js/worklist/wl-parameters-additional-equip.js"></script>
<script src="/js/worklist/wl-parameters-commercial-offers.js"></script>
<script src="/js/worklist/wl-parameters-configurator.js"></script>
<script src="/js/worklist/wl-parameters-loyalty.js"></script>
<script src="/js/worklist/wl-parameters-need-cars.js"></script>
<script src="/js/worklist/wl-parameters-oldcar.js"></script>
<script src="/js/worklist/wl-parameters-testdrives.js"></script>
<script src="/js/worklist/wl-registration-contracts.js"></script>
<script src="/js/worklist/wl-registration-credits.js"></script>
<script src="/js/worklist/wl-registration-ofu-products.js"></script>
<script src="/js/worklist/wl-registration-payments.js"></script>

</body>
</html>