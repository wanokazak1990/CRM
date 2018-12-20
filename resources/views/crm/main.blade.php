@extends('crm')

@extends('crm.worklist')

@section('main')
<!-- Nav tabs -->
<ul class="nav nav-tabs nav-justified bg-info" id="crmTabs" role="tablist" style="width: 100%; height: 42px;">
	<li class="nav-item">
		<a class="nav-link active" id="traffic-tab" data-toggle="tab" href="#traffic" role="tab" aria-controls="traffic" aria-selected="true">Трафик</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="contacts-tab" data-toggle="tab" href="#contacts" role="tab" aria-controls="contacts" aria-selected="false">Контакты</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="reserves-tab" data-toggle="tab" href="#reserves" role="tab" aria-controls="reserves" aria-selected="false">Резервы</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="deals-tab" data-toggle="tab" href="#deals" role="tab" aria-controls="deals" aria-selected="false">Сделки</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="refusals-tab" data-toggle="tab" href="#refusals" role="tab" aria-controls="refusals" aria-selected="false">Отказы</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="indicators-tab" data-toggle="tab" href="#indicators" role="tab" aria-controls="indicators" aria-selected="false">Показатели</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="demo-tab" data-toggle="tab" href="#demo" role="tab" aria-controls="demo" aria-selected="false">Демо</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="archive-tab" data-toggle="tab" href="#archive" role="tab" aria-controls="archive" aria-selected="false">Архив</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="payments-tab" data-toggle="tab" href="#payments" role="tab" aria-controls="payments" aria-selected="false">Платежи</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="receipts-tab" data-toggle="tab" href="#receipts" role="tab" aria-controls="receipts" aria-selected="false">Поступления</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="more-tab" data-toggle="tab" href="#more" role="tab" aria-controls="more" aria-selected="false">Еще</a>
	</li>
</ul>

<!-- Icons panel -->
<div class="border" style="width: 100%; height: 40px;">
	<button type="button" class="btn btn-light" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-cog"></i></button>
	<button type="button" class="btn btn-light"><i class="fas fa-search"></i></button>
	<button type="button" class="btn btn-light"><i class="fas fa-car-side"></i></button>
	<button type="button" class="btn btn-light"><i class="fas fa-user-check"></i></button>
	<button type="button" class="btn btn-light"><i class="fas fa-caret-square-down"></i></button>
	<button type="button" class="btn btn-light"><i class="fas fa-filter"></i></button>
	<div style="float: right;">
		<button type="button" class="btn btn-light"><i class="fas fa-user-plus"></i></button>
		<button id="opening" type="button" class="btn btn-light"><i class="fas fa-arrow-circle-left"></i></button>			
	</div>
</div>

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Настройки отображения</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<ul class="nav nav-tabs nav-justified" id="settingsTable" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="settingsTableSet-tab" data-toggle="tab" href="#settingsTableSet" role="tab" aria-controls="settingsTableSet" aria-selected="true">Настройки</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="settingsTableList-tab" data-toggle="tab" href="#settingsTableList" role="tab" aria-controls="settingsTableList" aria-selected="false">Список полей</a>
					</li>
				</ul>
				<div class="tab-content" id="settingsTableContent">
					<div class="tab-pane active" id="settingsTableSet" role="tabpanel" aria-labelledby="settingsTableSet-tab">
						<ul>
							<li>Основные настройки</li>
							<li>Список полей RENAULT
								<ul>
									<li>Поля Васи</li>
									<li>Поля Феди</li>
								</ul>
							</li>
							<li>Список полей LADA
								<ul>
									<li>Поля Пупы</li>
									<li>Поля Лупы</li>
								</ul>
							</li>
						</ul>
					</div>
					<div class="tab-pane" id="settingsTableList" role="tabpanel" aria-labelledby="settingsTableList-tab">
						<div class="row">
							<div class="col-6">
								<label>Название настройки:</label>
								<input type="text" class="form-control" placeholder="Название">	
							</div>
							<div class="col-6">
								<label>Уровень доступа:</label>
								<input type="text" class="form-control" placeholder="Уровень">	
							</div>
						</div>
						<label>Доступные поля:</label>
						<div id="settingsFields"></div>
						<button type="button" class="btn btn-info">Сохранить настройку</button>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть окно</button>
			</div>
		</div>
	</div>
</div>

<!-- Tab panes -->
<div id="crmTabPanels" class="tab-content border-top border-info" style="width: 100%; height: calc(100% - 82px); overflow-x: auto;">
	<!-- Трафик -->
	<div class="tab-pane active" id="traffic" role="tabpanel" aria-labelledby="traffic-tab">
		<table class="table table-bordered table-hover table-sm">
			<thead>
				<tr>
					<th>№</th>
					@foreach($fieldlist as $field)
						@if($field->type_id == 1)
							<th>{{ $field->name }}</th>
						@endif
					@endforeach
				</tr>
			</thead>
			<tbody>
				@foreach($traffics as $key => $traffic)
				<tr>
					<td>{{ $key+1 }}</td>
					<td>{{ date('d.m.Y H:i:s', $traffic->creation_date) }}</td>
					<td>{{ $traffic->traffic_type->name }}</td>
					<td>{{ $traffic->model->name }}</td>
					<td>{{ $traffic->client->name }}</td>
					<td>{{ $traffic->comment }}</td>
					<td>{{ $traffic->manager->name }}</td>
					<td>{{ $traffic->admin->name }}</td>
					<td>{{ $traffic->assigned_action->name }}</td>
					<td>{{ date('d.m.Y', $traffic->action_date) }}</td>
					<td>{{ date('H:i', $traffic->action_time) }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<!-- Контакты -->
	<div class="tab-pane" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
		<table class="table table-bordered table-hover table-sm">
			<thead>
				<tr>
					<th>№</th>
					@foreach($fieldlist as $field)
						@if($field->type_id == 2)
							<th>{{ $field->name }}</th>
						@endif
					@endforeach
				</tr>
			</thead>
			<tbody>
				@foreach($clients as $key => $client)
				<tr>
					<td>{{ $key + 1 }}</td>
					<td>{{ $client->name }}</td>
					<td>{{ $client->phone }}</td>
					<td>{{ $client->email }}</td>
					@foreach($traffics as $traffic)
						@if($client->id == $traffic->client_id)
						<td>{{ $traffic->model->name }}</td>
						<td>{{ $traffic->manager->name }}</td>
						<td>{{ $traffic->assigned_action->name }}</td>
						<td>{{ date('d.m.Y', $traffic->action_date) }}  {{ date('H:i', $traffic->action_time) }}</td>
						@endif
					@endforeach
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<!-- Резервы -->
	<div class="tab-pane" id="reserves" role="tabpanel" aria-labelledby="reserves-tab">
		<div class="input-group">
			<span class="col-2 border-left">Количество: <b>N</b></span>
			<span class="col-2 border-left">Сумма: <b>SUM</b></span>
		</div>
		<table class="table table-bordered table-hover table-sm" style="white-space: nowrap;">
			<thead>
				<tr>
					<th>№</th>
					<th></th>
					@foreach($fieldlist as $field)
						@if($field->type_id == 3)
							<th>{{ $field->name }}</th>
						@endif
					@endforeach							
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>1</td>
					<td><input type="checkbox"></td>
					<td>Резерв >7 дн.</td>
					<td>Оригинал</td>
					<td>Отгружен</td>

					<td></td>
					<td></td>
					<td>Июль</td>
					<td>13.09.2018</td>
					<td>13.09.2018</td>
					<td>13.09.2018</td>
					<td>05.11.2018</td>
					<td>Тольятти</td>
					<td>19.11.2018</td>
					<td></td>
					<td></td>
					<td>ВТБ (22)</td>
					<td>596806 р.</td>
					<td>596806 р.</td>
					<td>60000 р.</td>
					<td>50000 р.</td>
					<td>10000 р.</td>
					<td></td>
					<td></td>
					<td>19.11.2018</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>23.11.2018</td>

					<td>19.11.2018</td>

					<td>2018</td>
					<td>LADA</td>
					<td>LARGUS</td>
					<td>Norma Climate 1.6(87) 5MT 5 мест +Glonass</td>
					<td>М</td>
					<td>283</td>
					<td>Темно-коричневый (кашемир)</td>
					<td>XTAKS035LK1147405</td>
					<td>1761948</td>
					<td>622900 р.</td>
					<td>12000 р.</td>
					<td>0 р.</td>
					<td>0 р.</td>
					<td>634900 р.</td>
				</tr>
				<tr>
					<td>2</td>
					<td><input type="checkbox"></td>
					<td>Свободный</td>
					<td>Оригинал</td>
					<td>Склад</td>

					<td></td>
					<td></td>
					<td>Январь</td>
					<td>Доп. квота</td>
					<td>Доп. квота</td>
					<td>21.03.2018</td>
					<td></td>
					<td>Тольятти</td>
					<td>02.05.2018</td>
					<td>05.05.2018</td>
					<td>Кульчицкий</td>
					<td>ВТБ (22)</td>
					<td>679410 р.</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>03.05.2018</td>
					<td>03.05.2019</td>
					<td>365</td>
					<td>02.05.2020</td>
					<td>23.04.2018</td>
					<td>03.05.2018</td>

					<td>225</td>

					<td>2018</td>
					<td>LADA</td>
					<td>XRAY</td>
					<td>Luxe Prestige 1.6(106) 5МТ</td>
					<td>Нет</td>
					<td>221</td>
					<td>Белый (ледниковый)</td>
					<td>XTAGAB110J1099840</td>
					<td>1560800</td>
					<td>784900 р.</td>
					<td>0 р.</td>
					<td>4201 р.</td>
					<td>74101 р.</td>
					<td>715000 р.</td>
				</tr>
			</tbody>
		</table>
	</div>
	<!-- Сделки -->
	<div class="tab-pane" id="deals" role="tabpanel" aria-labelledby="deals-tab">
		<table class="table table-bordered table-hover table-sm" style="white-space: nowrap;">
			<thead>
				<tr>
					<th>№</th>
					<th></th>
					@foreach($fieldlist as $field)
						@if($field->type_id == 3)
							<th>{{ $field->name }}</th>
						@endif
					@endforeach							
				</tr>
			</thead>
		</table>
	</div>
	<!-- Отказы -->
	<div class="tab-pane" id="refusals" role="tabpanel" aria-labelledby="refusals-tab">
		<table class="table table-bordered table-hover table-sm">
			<thead>
				<tr>
					<th>№</th>
					@foreach($fieldlist as $field)
						@if($field->type_id == 2)
							<th>{{ $field->name }}</th>
						@endif
					@endforeach
				</tr>
			</thead>
		</table>
	</div>
	<!-- Показатели -->
	<div class="tab-pane" id="indicators" role="tabpanel" aria-labelledby="indicators-tab">
		<h3 align="center">Показатели</h3>
	</div>
	<!-- Демо -->
	<div class="tab-pane" id="demo" role="tabpanel" aria-labelledby="demo-tab">
		<table class="table table-bordered table-hover table-sm" style="white-space: nowrap;">
			<thead>
				<tr>
					<th>№</th>
					<th></th>
					@foreach($fieldlist as $field)
						@if($field->type_id == 3)
							<th>{{ $field->name }}</th>
						@endif
					@endforeach							
				</tr>
			</thead>
		</table>
	</div>
	<!-- Архив -->
	<div class="tab-pane" id="archive" role="tabpanel" aria-labelledby="archive-tab">
		<h3 align="center">Архив</h3>
	</div>
	<!-- Платежи -->
	<div class="tab-pane" id="payments" role="tabpanel" aria-labelledby="payments-tab">
		<h3 align="center">Платежи</h3>
	</div>
	<!-- Поступления -->
	<div class="tab-pane" id="receipts" role="tabpanel" aria-labelledby="receipts-tab">
		<h3 align="center">Поступления</h3>
	</div>
	<!-- Еще -->
	<div class="tab-pane" id="more" role="tabpanel" aria-labelledby="more-tab">
		<h3 align="center">Еще</h3>
	</div>
</div>
@endsection