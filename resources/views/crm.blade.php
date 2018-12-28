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
<div style="z-index: 1;">
	<div id="header" class="bg-info d-flex align-items-center">
		<span class="text-white">{{auth()->user()->name}} сейчас онлайн</span>
	</div>
</div>
<!-- /HEADER -->

<!-- СКРЫТЫЙ БЛОК СПРАВА -->
@section('worklist')
@show

<!-- МОДАЛЬНОЕ ОКНО НАСТРОЕК -->
@section('modal_settings')
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

						<button type="button" class="btn btn-light" data-toggle="modal" data-target="#exampleModal">
							<i class="fas fa-cog"></i>
						</button>

						<button type="button" class="btn btn-light">
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

<script src="/js/jquery.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/bootstrap-4/css/bootstrap.min.css">
<script src="/lib/bootstrap-4/js/bootstrap.min.js"></script>
<script src="/js/main.js"></script>
<script src="/js/jquery-ui.js"></script><!--http://api.jqueryui.com/datepicker/-->
<link href='/css/jquery-ui.css' rel='stylesheet' type='text/css'>
<script src="/js/calendar-ui.js"></script>
<script src="/js/crm.js"></script>

</body>
</html>