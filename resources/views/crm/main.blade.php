@extends('crm')

@extends('crm.worklist')

@extends('crm.modal_settings')

@extends('crm.modal_autocard')

@extends('crm.modal_add_testdrive')

@extends('crm.modal_options')

@section('main')
	



<!-- Контент вкладок -->
<div id="crmTabPanels" class="tab-content" style="width: 100%; height: calc(100% - 84px); overflow-x: auto;">
	<!-- Клиенты -->
	<div class="tab-pane active" id="clients" role="tabpanel" aria-labelledby="clients-tab">
		<table class="table table-bordered table-hover table-sm"></table>
	</div>

	<!-- Трафик -->
	<div class="tab-pane" id="traffic" role="tabpanel" aria-labelledby="traffic-tab">
		<table class="table table-bordered table-hover table-sm"></table>
	</div>
	
	<!-- Автосклад -->
	<div class="tab-pane" id="stock" role="tabpanel" aria-labelledby="stock-tab">
		<table class="table table-bordered table-hover table-sm" style="white-space: nowrap;"></table>
	</div>

	<!-- Продажи -->
	<div class="tab-pane" id="deals" role="tabpanel" aria-labelledby="deals-tab">
		<div class="d-flex justify-content-center">
			<i class="icofont-pay" style="font-size: 200px;"></i>			
		</div>
	</div>

	<!-- Поступления -->
	<div class="tab-pane" id="receipts" role="tabpanel" aria-labelledby="receipts-tab">
		<div class="d-flex justify-content-center">
			<i class="icofont-truck-alt" style="font-size: 200px;"></i>			
		</div>
	</div>

	<!-- Демо -->
	<div class="tab-pane" id="demo" role="tabpanel" aria-labelledby="demo-tab">
		<div class="d-flex justify-content-center">
			<i class="icofont-file-document" style="font-size: 200px;"></i>			
		</div>
	</div>

	<!-- Архив -->
	<div class="tab-pane" id="archive" role="tabpanel" aria-labelledby="archive-tab">
		<div class="d-flex justify-content-center">
			<i class="icofont-archive" style="font-size: 200px;"></i>			
		</div>
	</div>

	<!-- Показатели -->
	<div class="tab-pane" id="stats" role="tabpanel" aria-labelledby="stats-tab">
		<div class="d-flex justify-content-center">
			<i class="icofont-chart-growth" style="font-size: 200px;"></i>			
		</div>
	</div>
</div>
@endsection