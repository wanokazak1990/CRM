<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- Font Awesome -->
	<script defer src="https://use.fontawesome.com/releases/v5.5.0/js/all.js" integrity="sha384-GqVMZRt5Gn7tB9D9q7ONtcp4gtHIUEW/yG7h98J7IpE3kpi+srfFyyB/04OV6pG0" crossorigin="anonymous"></script>
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

<!-- ОСНОВНОЙ КОНТЕНТ -->
<div id="main" class="container-fluid">
	<div class="row" style="height: 100%; overflow-x: auto;">
		<div class="col" style="">
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

<style>

html {
	height: 100%;
}

html,body {
	height: 100%;
	margin: 0;
	padding: 0;
}

body {
	min-height: 100%;
	position: relative;
}

#main {
	height: calc(100% - 112px);
	background-color: white; 
	z-index: 0;
}

#header {
	height: 56px; 
	padding-left: 16px;
}

#footer {
	width: 100%;
	height: 56px;
	z-index: 1; 
}

#disableContent {
	position: fixed; 
	width: 100%; 
	height: 100%; 
	background-color: gray; 
	opacity: 0.25;
	z-index: 2;
	display: none;
}

/* СКРЫТЫЙ БЛОК */
#hidden_panel {
    position: fixed; /* положение */
    overflow: auto; 
    z-index: 3;
    top: 0;
    right: -50%; /* отступ справа */
    background: white; /* цвет фона */
    width: 50%; 
    height: 100%; 

    -webkit-transition-duration: 0.3s; /* анимационное выдвижение для всех браузеров*/
    -moz-transition-duration: 0.3s;
    -o-transition-duration: 0.3s;
    transition-duration: 0.3s;
}
</style>

<script src="/js/jquery.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/bootstrap-4/css/bootstrap.min.css">
<script src="/lib/bootstrap-4/js/bootstrap.min.js"></script>
<script src="/js/main.js"></script>
<script src="/js/jquery-ui.js"></script><!--http://api.jqueryui.com/datepicker/-->
<link href='/css/jquery-ui.css' rel='stylesheet' type='text/css'>
<script src="/js/calendar-ui.js"></script>
<script>
$(document).ready(function() {
	$(document).on('click', '#opening', function() {
		$(this).blur();
		$('#hidden_panel').css('right', '0');
		$('#disableContent').css('display', 'block');
	});

	$(document).on('click', '#closing', function() {
		$(this).blur();
		$('#hidden_panel').css('right', '-50%');
		$('#disableContent').css('display', 'none');
	});

	$(document).on('click', '#disableContent', function() {
		$('#hidden_panel').css('right', '-50%');
		$('#disableContent').css('display', 'none');
	});
});
</script>

</body>
</html>