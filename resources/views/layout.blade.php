<!DOCTYPE html>
<html>
	<head>
		@section('head')
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>CMS - {{ $title }}</title>
		<style>
			.disabled{
				pointer-events: none;
				color: #cdcdcd !important;
			}
			.delete-class{
				color: #cdcdcd;
			}
			.pad-0{
				padding: 0px !important;
			}
			.pad-l-0{
				padding-left: 0px !important;
			}
			.pad-r-0{
				padding-right: 0px !important;
			}
			.width-50{
				width: 50px;
			}
			.width-75{
				width: 75px;
			}
			.width-100{
				width: 100px;
			}
			.width-150{
				width: 150px;
			}
			.width-200{
				width: 200px;
			}

			.adding-control{
				padding-top: 30px;
				padding-bottom: 100px;
			}
			.size-9{
				font-size: 9px;
			}
			.size-10{
				font-size: 10px;
			}
			.size-12{
				font-size: 12px;
			}
			.size-14{
				font-size: 14px;
			}
			.size-25{
				font-size: 25px;
			}
			.text-grey{
				color: #dcdcdc;
			}
			table form{padding: 0px; margin: 0px;}
			.checkbox-td{padding-left: 0px !important;}
			.checkbox-td input{margin-right: 15px !important;display: inline-block;}
			.avacars{font-size: 14px;}
			.delete-button{background: transparent;border: 0px;padding: 0px;color:#f44;}
			.active-menu{color:#a55;}
			.active-menu:hover{color: #a55;}
			.left-menu{height: 100vh;overflow-y: scroll;position: fixed;top:0;left: 0; width: inherit;padding-left: 15px;background: #ddd;padding-top: 50px;}
			.left-menu ul{list-style-type: none;padding-left: 0px;}
			/*.color div:hover{background: #eee;}*/
			/*.color div,*/.option div,.dop div{
				overflow: hidden;
				white-space: nowrap;
			}
			.color label,.option label,.dop label{font-weight: normal;width: 100%;}
			.color label:hover,.option label:hover,.dop label:hover{background: #eee;}
			.option input,.dop input {margin-right: 15px !important;display: inline-block;}
			tr:hover{background: #eee;}
			th,td{vertical-align: middle !important;}
			.font-16{font-size: 16px;}
			.font-14{font-size: 14px;}
			.font-12{font-size: 12px;}
			.exep div {padding: 0;}
			.company-dop{display:none;position: absolute;top: 70px;left: 5%;width: 90%;min-height: 90vh;background: #fff;z-index: 100;box-shadow: 0 0 15px #000;border-radius:5px;z-index: 999}
			.navbar{background: #ddd !important;border: 0px !important;}

			.brand-span{display: inline-block;margin-right: 15px;}
			.brand-span label{font-weight: normal;display: flex;align-items: center;}
			.brand-span input{margin:0px !important;margin-right: 5px !important;display: inline-block;}
			.column{
				margin-bottom: 20px;
			    width: 100%;
			    text-align: justify;
			    column-count: 4 !important;
			    -moz-column-count: 4 !important;
			    -webkit-column-count: 4 !important;
			    column-gap: 20px  !important;
			    -moz-column-gap: 20px  !important;
			    -webkit-column-gap: 20px  !important;
			}
			.column label{display: block;}
			.green-tr{background: #afc !important;}
			.red-bg{background: #f00 !important;}
			.model img{display: block;width: 100px;margin:auto;}
			.model img:hover{background: #acf;}
			.model input{display: block;position: absolute;right: 15px;top: 5px;}
			.tooltip{white-space: normal !important; max-width: 300px !important;}
			.color input{margin-top: 10px !important;}
			.fixed-count-price{
				position: fixed; 
				right:0px;
				padding: 15px; 
				background: rgba(250,250,250,0.7); 
				z-index: 500;
				box-shadow: 0 0 10px #aaa;
				border-radius: 7px;
				margin-right: 15px;
				transition: background 0.5s linear,color 0.5s linear;
			}
			.fixed-count-price:hover{
				background: rgba(0,0,0,1);
				color:#fff;
			}
			.fixed-count-price tr:hover{color: #000}

			.reparation_param label{
				display: block;
				width: 100%
			}
		</style>
		@show
	</head>

	<body>
		<div id="app">
			@section('content')
	        <nav class="navbar navbar-default navbar-fixed-top">
	            <div class="container-fluid">
	                <div class="navbar-header">

	                    <!-- Collapsed Hamburger -->
	                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
	                        <span class="sr-only">Toggle Navigation</span>
	                        <span class="icon-bar"></span>
	                        <span class="icon-bar"></span>
	                        <span class="icon-bar"></span>
	                    </button>

	                    <!-- Branding Image -->
	                    <a class="navbar-brand" href="/home">
	                        "ОВЕН-АВТО" система управления контентом
	                    </a>
	                </div>

	                <div class="collapse navbar-collapse" id="app-navbar-collapse">
	                    <!-- Left Side Of Navbar -->
	                    <ul class="nav navbar-nav">
	                        &nbsp;
	                    </ul>

	                    <!-- Right Side Of Navbar -->
	                    <ul class="nav navbar-nav navbar-right">
	                        <!-- Authentication Links -->
	                        @guest
	                            <li><a href="{{ route('login') }}">Login</a></li>
	                            <li><a href="{{ route('register') }}">Register</a></li>
	                        @else
	                            <li class="dropdown">
	                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
	                                    {{ Auth::user()->name }} <span class="caret"></span>
	                                </a>

	                                <ul class="dropdown-menu">
	                                    <li>
	                                        <a href="{{ route('logout') }}"
	                                            onclick="event.preventDefault();
	                                                     document.getElementById('logout-form').submit();">
	                                            Выход
	                                        </a>

	                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
	                                            {{ csrf_field() }}
	                                        </form>
	                                    </li>
	                                </ul>
	                            </li>
	                        @endguest
	                    </ul>
	                </div>
	            </div>
	        </nav>

	        @show
	    </div>

		<div class="col-sm-3 col-lg-2">
			<div class="left-menu">
			@section('left')
			Добро пожаловать, <b>{{ auth()->user()->name }}</b><br>
			<span style="color: #888">Это главное меню системы управления контентом сайта и складом автомобилей.</span>
			<a href="{{ route('crm') }}" target="_blank">Перейти в CRM "Учет"</a>
			<h3>Меню</h3>

			<ul>
				<h4>Общие</h4>
				<li><a href="{{ route('brandlist') }}">Список брендов</a></li>
				<h4>Модельный ряд</h4>
				<li><a href="{{ route('countrylist') }}">Список стран производителей</a></li>
				<li><a href="{{ route('typemodellist') }}">Список типов автомобилей</a></li>
				<li><a href="{{ route('modellist') }}">Список моделей</a></li>
				<h4>Характеристики</h4>
				<li><a href="{{ route('characterlist') }}">Список характеристик</a></li>
				<h4>Файлы моделей</h4>
				<li><a href="{{ route('typeslist')}}">Список типов файлов</a></li>
				<li><a href="{{ route('fileslist')}}">Список файлов</a></li>
				<h4>Цветовая палитра</h4>
				<li><a href="{{ route('colorlist') }}">Список цветов</a></li>
				<h4>Основное и доп. оборудование</h4>
				<li><a href="{{ route('optparentlist') }}">Список разделов оборудования</a></li>
				<li><a href="{{ route('optionlist') }}">Список оборудования</a></li>
				<li><a href="{{ route('doplist') }}">Список доп. оборудования</a></li>
				<h4>Агрегаты</h4>
				<li><a href="{{ route('partmotorlist') }}">Список оборудования моторов</a></li>
				<li><a href="{{ route('motorlist') }}">Список моторов</a></li>
				<h4>Опции</h4>
				<li><a href="{{ route('packlist') }}">Список опций</a></li>
				<h4>Комплектации</h4>
				<li><a href="{{ route('complectlist') }}">Список комплектаций</a></li>
				<h4>Автомобили</h4>
				<li><a href="{{ route('carstatuslist') }}">Список статусов автомобилей</a></li>
				<li><a href="{{ route('carloclist') }}">Список поставок автомобилей</a></li>
				<li><a href="{{ route('carlist') }}">Список автомобилей</a></li>
				<li><a href="{{ route('carsold') }}">Проданные автомобили</a></li>
				<li><a href="{{ route('cararchive') }}">Архив автомобилей</a></li>
				<h4>Кредитные программы</h4>
				<li><a href="{{ route('kreditlist') }}">Список кредитов</a></li>
				<h4>Коммерческие акции</h4>
				<li><a href="{{ route('companylist') }}">Список компаний</a></li>
			</ul>
			@show
			</div>
		</div>
		

		
		<div class="col-sm-9 col-lg-10">
			<br><br>
			<h3>{{ $title }}</h3>
			@section('right')
			
			@show
		</div>
		
		@section('scripts')

			<script src="/js/jquery.js"></script>
			<script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script>
			<link rel="stylesheet" type="text/css" href="/lib/bootstrap/css/bootstrap.min.css">
			<script src="/lib/bootstrap/js/bootstrap.min.js"></script>
			
			<script src="/js/main.js"></script>
			<script src="/js/jquery-ui.js"></script><!--http://api.jqueryui.com/datepicker/-->
			<link href='/css/jquery-ui.css' rel='stylesheet' type='text/css'>
			<script src="/js/calendar-ui.js"></script>	
		@show
	</body>
</html>