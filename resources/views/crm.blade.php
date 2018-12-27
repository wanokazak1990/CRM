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