@extends('crm')

@extends('crm.worklist')

@extends('crm.modal_settings')

@section('main')
<!-- Основные вкладки -->
<ul class="nav nav-tabs nav-justified bg-info" id="crmTabs" role="tablist" style="width: 100%; height: 42px;">
	<li class="nav-item">
		<a class="nav-link active" id="clients-tab" data-toggle="tab" href="#clients" role="tab" aria-controls="clients" aria-selected="false">Клиенты</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="traffic-tab" data-toggle="tab" href="#traffic" role="tab" aria-controls="traffic" aria-selected="true">Трафик</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="stock-tab" data-toggle="tab" href="#stock" role="tab" aria-controls="stock" aria-selected="false">Автосклад</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="deals-tab" data-toggle="tab" href="#deals" role="tab" aria-controls="deals" aria-selected="false">Продажи</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="receipts-tab" data-toggle="tab" href="#receipts" role="tab" aria-controls="receipts" aria-selected="false">Поступления</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="demo-tab" data-toggle="tab" href="#demo" role="tab" aria-controls="demo" aria-selected="false">Демо</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="archive-tab" data-toggle="tab" href="#archive" role="tab" aria-controls="archive" aria-selected="false">Архив</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="stats-tab" data-toggle="tab" href="#stats" role="tab" aria-controls="stats" aria-selected="false">Показатели</a>
	</li>
</ul>

<!-- Панель иконок -->
<div class="border" style="width: 100%; height: 40px;">
	<button type="button" class="btn btn-light" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-cog"></i></button>
	<button type="button" class="btn btn-light"><i class="fas fa-search"></i></button>
	<button type="button" class="btn btn-light"><i class="fas fa-caret-square-down"></i></button>
	<button type="button" class="btn btn-light"><i class="fas fa-filter"></i></button>
	<div style="float: right;">
		<button type="button" class="btn btn-light"><i class="fas fa-user-plus"></i></button>
		<button id="opening" type="button" class="btn btn-light"><i class="fas fa-arrow-circle-left"></i></button>			
	</div>
</div>

<!-- Контент вкладок -->
<div id="crmTabPanels" class="tab-content border-top border-info" style="width: 100%; height: calc(100% - 82px); overflow-x: auto;">
	<!-- Клиенты -->
	<div class="tab-pane active" id="clients" role="tabpanel" aria-labelledby="clients-tab">
		<table class="table table-bordered table-hover table-sm">
			<thead>
				<tr>
					<th>№</th>
					@foreach($test as $key => $item)
						@if($item->field == 'clients' && $item->active == 1)
							<th class="clients-head" id="{{ $item->f_id }}">{{ $item->field_name }}</th>
						@endif
					@endforeach
				</tr>
			</thead>
			@foreach($fieldlist as $field)
				@if($field->type_id == 2)
					@php
						$clients_fields_ids[] = $field->id;
					@endphp
				@endif
			@endforeach
			<tbody>
				@foreach($clients as $key => $client)
				<tr>
					<td>{{ $key + 1 }}</td>
					<td class="clients-td" id="{{ $clients_fields_ids[0] }}">{{ $client->name }}</td>
					<td class="clients-td" id="{{ $clients_fields_ids[1] }}">{{ $client->phone }}</td>
					<td class="clients-td" id="{{ $clients_fields_ids[2] }}">{{ $client->email }}</td>
					<td class="clients-td" id="{{ $clients_fields_ids[3] }}">
						@isset($client->traffic->model)
							{{ $client->traffic->model->name }}
						@endisset
					</td>
					<td class="clients-td" id="{{ $clients_fields_ids[4] }}">
						@isset($client->traffic->manager->name)
							{{ $client->traffic->manager->name }}
						@endisset
					</td>
					<td class="clients-td" id="{{ $clients_fields_ids[5] }}">
						@isset($client->traffic->assigned_action->name)
							{{ $client->traffic->assigned_action->name }}
						@endisset
					</td>
					<td class="clients-td" id="{{ $clients_fields_ids[6] }}">
						@isset($client->traffic->action_date)
							{{ date('d.m.Y', $client->traffic->action_date) }}
						@endisset
						@isset($client->traffic->action_time)
							{{ date('H:i', $client->traffic->action_time) }}
						@endisset
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	<!-- Трафик -->
	<div class="tab-pane" id="traffic" role="tabpanel" aria-labelledby="traffic-tab">
		<table class="table table-bordered table-hover table-sm">
			<thead>
				<tr>
					<th>№</th>
					@foreach($test as $item)
						@if($item->field == 'traffic' && $item->active == 1)
							<th class="traffic-head" id="{{ $item->f_id }}">{{ $item->field_name }}</th>
						@endif
					@endforeach
				</tr>
			</thead>
			@foreach($fieldlist as $field)
				@if($field->type_id == 1)
					@php
						$traffic_fields_ids[] = $field->id;
					@endphp
				@endif
			@endforeach
			<tbody>
				@foreach($traffics as $key => $traffic)
				<tr>
					<td>{{ $key+1 }}</td>
					<td class="traffic-td" id="{{ $traffic_fields_ids[0] }}">{{ date('d.m.Y H:i:s', $traffic->creation_date) }}</td>
					<td class="traffic-td" id="{{ $traffic_fields_ids[1] }}">
						@isset($traffic->traffic_type->name)
							{{ $traffic->traffic_type->name }}
						@endisset
					</td>
					<td class="traffic-td" id="{{ $traffic_fields_ids[2] }}">
						@isset($traffic->model->name)
							{{ $traffic->model->name }}
						@endisset
					</td>
					<td class="traffic-td" id="{{ $traffic_fields_ids[3] }}">
						@isset($traffic->client->name)
							{{ $traffic->client->name }}
						@endisset
					</td>
					<td class="traffic-td" id="{{ $traffic_fields_ids[4] }}">{{ $traffic->comment }}</td>
					<td class="traffic-td" id="{{ $traffic_fields_ids[5] }}">
						@isset($traffic->manager->name)
							{{ $traffic->manager->name }}
						@endisset
					</td>
					<td class="traffic-td" id="{{ $traffic_fields_ids[6] }}">
						@isset($traffic->admin->name)
							{{ $traffic->admin->name }}
						@endisset
					</td>
					<td class="traffic-td" id="{{ $traffic_fields_ids[7] }}">
						@isset($traffic->assigned_action->name)
							{{ $traffic->assigned_action->name }}
						@endisset
					</td>
					<td class="traffic-td" id="{{ $traffic_fields_ids[8] }}">{{ date('d.m.Y', $traffic->action_date) }}</td>
					<td class="traffic-td" id="{{ $traffic_fields_ids[9] }}">{{ date('H:i', $traffic->action_time) }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	
	<!-- Автосклад -->
	<div class="tab-pane" id="stock" role="tabpanel" aria-labelledby="stock-tab">
		<div class="input-group">
			<span class="col-2 border-left">Количество: <b>N</b></span>
			<span class="col-2 border-left">Сумма: <b>SUM</b></span>
		</div>
		<table class="table table-bordered table-hover table-sm" style="white-space: nowrap;">
			<thead>
				<tr>
					<th>№</th>
					<th></th>
					@foreach($test as $item)
						@if($item->field == 'stock' && $item->active == 1)
							<th class="stock-head" id="{{ $item->f_id }}">{{ $item->field_name }}</th>
						@endif
					@endforeach						
				</tr>
			</thead>
			@foreach($fieldlist as $field)
				@if($field->type_id == 1)
					@php
						$stock_fields_ids[] = $field->id;
					@endphp
				@endif
			@endforeach
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