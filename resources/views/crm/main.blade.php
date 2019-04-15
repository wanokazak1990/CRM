@extends('crm')

@extends('crm.worklist')

@extends('crm.modal_settings')

@extends('crm.modal_autocard')

@extends('crm.modal_add_testdrive')

@section('main')
	



<!-- Контент вкладок -->
<div id="crmTabPanels" class="tab-content border-top border-secondary" style="width: 100%; height: calc(100% - 84px); overflow-x: auto;">
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
		<div class="input-group">
			<span class="col-2 border-left">Количество: <b>N</b></span>
			<span class="col-2 border-left">Сумма: <b>SUM</b></span>
		</div>
		<table class="table table-bordered table-hover table-sm" style="white-space: nowrap;"></table>
	</div>

	<!-- Продажи -->
	<div class="tab-pane" id="deals" role="tabpanel" aria-labelledby="deals-tab">
		<div class="d-flex justify-content-center">
			<i class="fas fa-skull-crossbones text-warning" style="width: 200px; height: 300px;"></i>			
		</div>
	</div>

	<!-- Поступления -->
	<div class="tab-pane" id="receipts" role="tabpanel" aria-labelledby="receipts-tab">
		<div class="d-flex justify-content-center">
			<i class="fas fa-skull-crossbones text-danger" style="width: 200px; height: 300px;"></i>			
		</div>
	</div>

	<!-- Демо -->
	<div class="tab-pane" id="demo" role="tabpanel" aria-labelledby="demo-tab">
		<div class="d-flex justify-content-center">
			<i class="fas fa-skull-crossbones text-primary" style="width: 200px; height: 300px;"></i>			
		</div>
	</div>

	<!-- Архив -->
	<div class="tab-pane" id="archive" role="tabpanel" aria-labelledby="archive-tab">
		<div class="d-flex justify-content-center">
			<i class="fas fa-skull-crossbones text-success" style="width: 200px; height: 300px;"></i>			
		</div>
	</div>

	<!-- Показатели -->
	<div class="tab-pane" id="stats" role="tabpanel" aria-labelledby="stats-tab">
		<div class="d-flex justify-content-center">
			<i class="fas fa-skull-crossbones text-dark" style="width: 200px; height: 300px;"></i>			
		</div>
	</div>
</div>
@endsection