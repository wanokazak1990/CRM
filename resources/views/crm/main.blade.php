@extends('crm')

@extends('crm.worklist')

@extends('crm.modal_settings')

@section('main')
	



<!-- Контент вкладок -->
<div id="crmTabPanels" class="tab-content border-top border-info" style="width: 100%; height: calc(100% - 82px); overflow-x: auto;">
	<!-- Клиенты -->
	<div class="tab-pane active" id="clients" role="tabpanel" aria-labelledby="clients-tab">
		<table class="table table-bordered table-hover table-sm">
			
			<tr>
				@foreach(App\_tab_client::getTitle() as $row)
					<th>{{$row->name}}</th>
				@endforeach
			</tr>
			
			@foreach($clientTab as $client)
				<tr client-id="{{$client->id}}">
					<td>{{$client->name}}</td>
					<td>{{$client->phone}}</td>
					<td>{{$client->email}}</td>
					<td>
						@isset($client->model)
							{{$client->model->name}}
						@endisset
					</td>
					<td>
						@isset($client->manager)
							{{$client->manager->name}}
						@endisset
					</td>
					<td>
						@isset($client->action)
							{{$client->action->name}}
						@endisset
					</td>
					<td>
						@if(!empty($client->action_date))
							{{$client->date()}}
						@endif
					</td>
				</tr>
			@endforeach
		</table>
		{{$clientTab->appends(['model'=>'_tab_client'])->links()}}
	</div>

	<!-- Трафик -->
	<div class="tab-pane" id="traffic" role="tabpanel" aria-labelledby="traffic-tab">
<<<<<<< HEAD
		<table class="table table-bordered table-hover table-sm"></table>
=======
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
>>>>>>> origin/master
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