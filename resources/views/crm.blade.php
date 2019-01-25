<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<!-- Font Awesome -->
	<script defer src="https://use.fontawesome.com/releases/v5.5.0/js/all.js" integrity="sha384-GqVMZRt5Gn7tB9D9q7ONtcp4gtHIUEW/yG7h98J7IpE3kpi+srfFyyB/04OV6pG0" crossorigin="anonymous"></script>
	<link href='/css/crm.css' rel='stylesheet' type='text/css'>
	<title>{{ $title }}</title>
</head>
<body>
<!-- Блок для перекрытия -->
<div id="disableContent"></div>

<!-- HEADER -->
<div class="container-fluid bg-info d-flex align-items-center" id="header">
	<button onclick="send();">Отправить (ТЕСТ WEBSOCKET)</button>

    @guest
        <li><a href="{{ route('login') }}">Login</a></li>
        <li><a href="{{ route('register') }}">Register</a></li>
    @else

    	<!--ID пользователя-->
    	<input type="hidden" id="auth_user_id" value="{{ Auth::user()->id }}">

        <a href="{{ route('logout') }}"
            onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();"
        >
            Выход
        </a>

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

<!-- ОСНОВНОЙ КОНТЕНТ -->
<div id="main" class="container-fluid">
	<div class="row" style="height: 100%; overflow-x: auto;">
		<div class="col" style="padding-left: 0; padding-right: 0;">

			@section('nav-top')
				<!-- Основные вкладки -->
				<ul class="nav nav-tabs nav-justified bg-info" id="crmTabs" role="tablist" style="width: 100%; height: 42px;">
					
					<li class="nav-item">
						<a 
							class="nav-link active" 
							id="clients-tab" 
							data-toggle="tab" 
							href="#clients" 
							role="tab" 
							aria-controls="clients" 
							aria-selected="false"
							model-name="_tab_client"
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
							aria-selected="true"
							model-name="_tab_traffic"
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
						>
							Показатели
						</a>
					</li>

				</ul>
			@show

			@section('')
				<!-- Панель иконок -->
				<div class="border row" >

					<div class="col-sm-6">

						<button type="button" class="btn btn-light" data-toggle="modal" data-target="#settingsModal">
							<i class="fas fa-cog"></i>
						</button>

						<button type="button" class="btn btn-light" data-toggle="modal" data-target="#autocardModal">
							<i class="fas fa-search"></i>
						</button>

						<button type="button" class="btn btn-light">
							<i class="fas fa-caret-square-down"></i>
						</button>

						<button type="button" class="btn btn-light">
							<i class="fas fa-filter"></i>
						</button>

					</div>

					<div class="col-sm-6 text-right">

						<button type="button" class="btn btn-light">
							<i class="fas fa-user-plus"></i>
						</button>

						<button id="opening" type="button" class="btn btn-light">
							<i class="fas fa-arrow-circle-left"></i>
						</button>

					</div>

				</div>
			@show

			@section('main')

			@show

		</div>
	</div>
</div>

<!-- FOOTER -->
<div id="footer" class="bg-info d-flex align-items-center">
	<div class="container-fluid text-right text-white">
		CRM "Учет"	
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










<script src="/js/jquery.js"></script>

<script>
	var response_time = 5*1000;//время которое отображается сообщение
	var manager = $("#auth_user_id").val(); //id пользователя 
</script>

<link rel="stylesheet" type="text/css" href="/lib/bootstrap-4/css/bootstrap.min.css">
<script src="/lib/bootstrap-4/js/bootstrap.min.js"></script>
<script src="/js/main.js"></script>
<script src="/js/jquery-ui.js"></script><!--http://api.jqueryui.com/datepicker/-->
<link href='/css/jquery-ui.css' rel='stylesheet' type='text/css'>
<script src="/js/calendar-ui.js"></script>

<script src="/js/function-content.js"></script>
<script src="/js/crm.js"></script>
<script src="/js/socket/trafficsocket.js"></script>
<script src="/js/traffic-label.js"></script>
<script src="/js/car-add.js"></script>

</body>
</html>