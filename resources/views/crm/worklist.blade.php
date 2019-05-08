@section('worklist')
<div id="hidden_panel" class="container">
<div class="row">
	<!-- Вкладки боковой панели -->
	<ul class="nav nav-tabs nav-justified right-menu" id="hiddenTab" role="tablist">
		<li class="nav-item ">
			<a class="nav-link active" id="log-tab" data-toggle="tab" href="#log" role="tab" aria-controls="log" aria-selected="true">Журнал</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="trafficList-tab" data-toggle="tab" href="#trafficList" role="tab" aria-controls="trafficList" aria-selected="true">Лист трафика</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="worksheet-tab" data-toggle="tab" href="#worksheet" role="tab" aria-controls="worksheet" aria-selected="true">Рабочий лист</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="task-tab" data-toggle="tab" href="#task" role="tab" aria-controls="task" aria-selected="true">Задача</a>
		</li>
	</ul>

	
	

	<!-- Контент вкладок -->
	<div class="tab-content container" style="width: 100%; padding-left: 0; padding-right: 0;">
		<!-- Вкладка Журнал -->
		<div class="tab-pane active" id="log" role="tabpanel" aria-labelledby="log-tab">
			<!-- Панель иконок -->
			<div class="border-bottom input-group align-items-center sticky-icons" style="height: 42px;">
				<div class="d-flex flex-grow-1 align-items-center">
					<!-- <a href="javascript://" id="closing" class="px-3 text-dark d-flex align-items-center">
						<i class="icofont-simple-right" style="font-size: 20px;"></i>
					</a> -->
					<span class="text-success d-flex align-items-center px-3"><input type="checkbox" autocomplete="off" class="mr-1"> Только мои</span>
				</div>

				<div class="d-flex align-items-center">
					<a href="javascript://" class="px-3 text-dark d-flex align-items-center">
						<i class="icofont-filter" style="font-size: 20px;"></i>
					</a>
					<a href="javascript://" class="px-3 text-dark d-flex align-items-center">
						<i class="icofont-print" style="font-size: 20px;"></i>
					</a>
				</div>
			</div>

			<table class="table table-bordered table-hover table-sm">
				<thead>
					<tr>
						<th>Дата</th>
						<th>Время</th>
						<th>Тип</th>
						<th>Действие</th>
						<th>Клиент</th>
						<th>Автомобиль</th>
						<th>Сотрудник</th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
		</div>

		<!-- Вкладка Лист трафика -->
		<div class="tab-pane" id="trafficList" role="tabpanel" aria-labelledby="trafficList-tab">

			<!-- Панель иконок -->
			<div class="border-bottom input-group align-items-center sticky-icons" style="height: 42px;">
				<div class="d-flex flex-grow-1 align-items-center">
					<!-- <a id="closing" href="javascript://" class="px-3 text-dark d-flex align-items-center">
						<i class="icofont-simple-right" style="font-size: 20px;"></i>
					</a> -->
				</div>
				<div class="d-flex align-items-center">
					<a href="javascript://" class="px-3 text-dark d-flex align-items-center">
						<i class="icofont-save" style="font-size: 20px;"></i>
					</a>
					<a href="javascript://" class="px-3 text-dark d-flex align-items-center">
						<i class="icofont-ui-delete" style="font-size: 20px;"></i>
					</a>
				</div>
			</div>

			<!-- Заголовок -->
			<div class="d-flex align-items-center text-secondary px-3" style="height: 48px; background-color: #eaeaea; position: sticky; z-index: 3; top: 84px;">
				<span class="h3 flex-grow-1" style="margin-bottom: 0;">Новый трафик</span>
				<span class="h3" style="margin-bottom: 0;">{{ date('d.m.Y') }}</span>
			</div>

			<!-- Контент вкладки -->
			<div style="overflow-y: auto;">
				<!-- Форма вкладки -->
				{!! Form::open(['class' => 'px-3']) !!}
				{{ csrf_field() }}

				<div class="my-3">
					<div class="input-group no-gutters">
						<div class="col-4">
							<input type="text" name="client_name" class="form-control" placeholder="Имя">
						</div>
						<div class="col-4">
							<input type="text" name="client_secondname" class="form-control" placeholder="Отчество">
						</div>
						<div class="col-4">
							<input type="text" name="client_lastname" class="form-control" placeholder="Фамилия">
						</div>
					</div>
					<div class="input-group no-gutters">
						<div class="col-6">
							<input type="text" name="client_phone" class="form-control phone_mask" placeholder="Телефон">
						</div>
						<div class="col-6">
							<input type="text" name="client_email" class="form-control" placeholder="Email">
						</div>
					</div>
					<div class="input-group no-gutters">
						<div class="col-12">
							<input type="text" name="comment" class="form-control" placeholder="Комментарий">
						</div>
					</div>
				</div>

				<span class="h4">Канал</span>
				<div class="input-group btn-group-toggle mb-3" data-toggle="buttons">
					@foreach($traffic_types as $traffic)
					<div class="col-3 btn btn-light"><input type="radio" name="traffic_type" value="{{ $traffic->id }}" autocomplete="off"> {{ $traffic->name }}</div>
					@endforeach
				</div>
				
				<span class="h4">Спрос</span>
				<div class="input-group btn-group-toggle mb-3" data-toggle="buttons">
					@foreach($models as $key => $model)
					<div class="col-3 btn btn-light"><input type="radio" name="model" value="{{ $key }}" autocomplete="off"> {{ $model }}</div>
					@endforeach
				</div>

				<span class="h4">Зона контакта</span>
				<div class="input-group btn-group-toggle mb-3" data-toggle="buttons">
					
					<div class="col-3 btn btn-light">
						<input type="radio" name="area_id" autocomplete="off" value="0"> 
						Неизвестно
					</div>

					@foreach(App\crm_city_list::pluck('name','id') as $id=>$city)
					<div class="col-3 btn btn-light">
						<input type="radio" name="area_id" autocomplete="off" value="{{$id}}"> 
						{{$city}}
					</div>
					@endforeach

				</div>

				<span class="h4">Действие</span>
				<div class="mb-3">
					<div class="input-group">
						<input name="action_date" type="text" class="col-3 form-control calendar" title="Назначенная дата">
						<input name="action_time" type="time" class="col-3 form-control" title="Назначенное время">
					</div>
					<div class="input-group btn-group-toggle" data-toggle="buttons">
						@foreach($assigned_actions as $key => $action)
						  <div class="col-3 btn btn-light"><input type="radio" name="assigned_action" value="{{ $key }}" autocomplete="off"> {{ $action }}</div>
						@endforeach
					</div>
				</div>

				<span class="h4">Менеджер</span>
				<div class="input-group btn-group-toggle mb-3" data-toggle="buttons">
					@foreach($users as $key => $user)
						<div class="col-3 btn btn-light" >
							<input type="radio" name="manager" value="{{ $key }}" autocomplete="off" > 
							{{ $user }}
						</div>
					@endforeach
				</div>

				<div class="input-group justify-content-center p-3 no-gutters">
					<button type="button" id="traffic_submit" name="traffic_submit" class="btn btn-primary col-4">
						Зарегистрировать трафик
					</button>
				</div>
				{!! Form::close() !!}
			</div>

			
		</div>










		
		<!-- Вкладка Рабочий лист -->
		<div class="tab-pane" id="worksheet" role="tabpanel" aria-labelledby="worksheet-tab">
			{{ Form::open() }}
			<!-- Панель иконок -->
			<div class="border-bottom input-group align-items-center sticky-icons" style="height: 42px;">
				<div class="d-flex flex-grow-1 align-items-center pl-3" style="margin-bottom: 0;">
					<!-- <a id="closing" href="javascript://" class="px-3 text-dark d-flex align-items-center">
						<i class="icofont-simple-right" style="font-size: 20px;"></i>
					</a> -->
					<span id="worklist-vin" class="h4" style="margin-bottom: 0;">
						VIN-nomer
					</span>
				</div>

				<div class="d-flex align-items-center">
					<a href="javascript://" class="px-3 text-dark d-flex align-items-center" id="wl_save_changes" title="Сохранить рабочий лист">
						<i class="icofont-save" style="font-size: 20px;"></i>
					</a>
					<a href="javascript://" class="px-3 text-dark d-flex align-items-center">
						<i class="icofont-ui-delete" style="font-size: 20px;"></i>
					</a>
				</div>
			</div>

			<div class="d-flex px-3 align-items-center" style="height: 48px; background-color: #eaeaea; position: sticky; z-index: 3; top: 84px;">
				<span class="h3 flex-grow-1" id="worklist-number" style="margin-bottom: 0;">
					Рабочий лист №<span name="wl_id">-</span>
				</span>

				<span class="h3" id="worklist-date" style="margin-bottom: 0;">
					<span name="wl_addingday">-</span>
				</span>
			</div>

			<!-- Основной блок рабочего листа -->
			<div class="px-3">
				<div class="mb-2">
					<div class="input-group no-gutters d-flex align-items-end">
						<div class="col-3">Трафик</div>
						<div class="col-3">Спрос</div>
						<div class="col-3">Менеджер</div>
						<div class="col-3 trafic_action_block">
							<div class="input-group">
								<input name="traffic_action_date" type="text" class="col-6 text-danger form-control calendar">
								<input name="traffic_action_time" type="time" class="col-6 text-danger form-control" >
							</div>
						</div>
					</div>

					<div class="input-group">
						<input name="traffic_type" type="text" class="col-3 form-control edit-traffic-modal" value="-" readonly="">
						<input name="traffic_model" type="text" class="col-3 form-control edit-traffic-modal" value="-" readonly="">
						<input name="wl_manager" type="text" class="col-3 form-control edit-traffic-modal" value="-" readonly="">
						{{Form::select('traffic_action',App\crm_assigned_action::pluck('name','id'),'',['class'=>'col-3 form-control'])}}
					</div>
				</div>
				
				<div class="mb-2">
					<div class="input-group no-gutters d-flex align-items-end">
						<div class="col-3">Тип контакта</div>					
						<div class="col-9">Контакт</div>
					</div>

					<div class="input-group">
						{{Form::select('client_type',App\crm_client_type::pluck('name','id'),1,['class'=>'col-3 form-control'])}}
						<input name="client_name" type="text" class="col-3 form-control" placeholder="Имя">
						<input name="client_secondname" type="text" class="col-3 form-control" placeholder="Отчество">
						<input name="client_lastname" type="text" class="col-3 form-control" placeholder="Фамилия">
					</div>
				</div>

				<div>
					<div class="input-group no-gutters d-flex align-items-end">
						<div class="col-3">Телефон</div>
						<div class="col-3">Почта</div>
						<div class="col-3">Маркер</div>
					</div>

					<div wl_block='contacts' id="wl_contacts" class="mb-2">
						<div class="input-group no-gutters contact-row">
							<div class="col-3">
								<input type="text" class="form-control phone_mask" placeholder="Введите номер" name='contact_phone[]'>
							</div>
							<div class="col-3">
								<input type="text" class="form-control" placeholder="Введите Email" name='contact_email[]'>
							</div>
							<div class="col-3">
								{!! Form::select(
									'contact_marker[]',
									App\crm_worklist_marker::pluck('name','id'), 
									'', 
									['class' => 'form-control']) 
								!!}
							</div>							
							<div class="col-3 d-flex align-items-center">
								<div class="input-group no-gutters">
									<div class="col-6 d-flex justify-content-center">
										<a href="javascript://" class="text-dark" id="wl_contacts_delete" title="Удалить контакт">
											<i class="icofont-close"></i>
										</a>
									</div>
									<div class="col-6 d-flex justify-content-center">
										<a href="javascript://" class="text-dark" id="wl_contacts_add" title="Добавить контакт">
											<i class="icofont-plus-circle"></i>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row py-2 ws-header">
						<div class="col-12 d-flex justify-content-between">
							<a data-toggle="collapse" href="#worklistMoreInfo" class="default-link" aria-expanded="false" aria-controls="worklistMoreInfo">Еще о клиенте</a>
						</div>
					</div>

					<div id="worklistMoreInfo" class="collapse pb-3 mt-2 ws-tab-content">
						<div class="input-group h4">Еще о клиенте</div>
						<div class="input-group no-gutters d-flex align-items-end">
							<div class="col-3">Зона контакта</div>
							<div class="col-6">Адрес прописки</div>
							<div class="col-3">Дата рождения</div>
						</div>

						<div class="input-group mb-2">
							{{Form::select('client_area',App\crm_city_list::pluck('name','id'),'',['class'=>'col-3 form-control'])}}
							<input name="client_address" type="text" class="col-6 form-control" placeholder="Адрес">
							<input name="client_birthday" type="text" class="col-3 form-control calendar">
						</div>
						
						<div class="input-group no-gutters d-flex align-items-end">
							<div class="col-6">Паспорт</div>
							<div class="col-6">Водительское удостоверение</div>
						</div>

						<div class="input-group">
							<input name="client_passport_serial" type="text" class="col-2 form-control" placeholder="Серия">
							<input name="client_passport_number" type="text" class="col-2 form-control" placeholder="Номер">
							<input name="client_passport_giveday" type="text" class="col-2 form-control calendar" placeholder="Дата выдачи">
							<input name="client_drive_number" type="text" class="col-6 form-control" placeholder="Номер">
							<input name="client_drive_giveday" type="text" class="col-3 form-control calendar" placeholder="Дата выдачи">
						</div>
					</div>

					<div class="row py-2 ws-header">
						<div class="col-12 d-flex justify-content-between">
							<a data-toggle="collapse" href="#worklistDocuments" class="default-link" aria-expanded="false" aria-controls="worklistDocuments">Рабочие документы</a>
						</div>
					</div>

					<div id="worklistDocuments" class="collapse pb-3 mt-2 ws-tab-content">
						<div class="input-group h4">Рабочие документы</div>
						<div class="input-group no-gutters">
							<div class="col-6">
								<div class="input-group">
									<a href="javascript://" class="default-link">Доверенность на тест-драйв</a>
								</div>
								<div class="input-group">
									<a href="javascript://" class="default-link">Направление в гостиницу</a>
								</div>
								<div class="input-group">
									<a href="javascript://" class="default-link">Приветственная табличка</a>
								</div>
								<div class="input-group">
									<a href="javascript://" class="default-link">Заявка на сервис</a>
								</div>
							</div>
							<div class="col-6">
								<div class="input-group">
									<a href="javascript://" class="default-link">Заявление на зачет денег</a>
								</div>
								<div class="input-group">
									<a href="javascript://" class="default-link">Заявление на расторжение договора</a>
								</div>
								<div class="input-group">
									<a href="javascript://" class="default-link">Заявление на возврат денег</a>
								</div>
								<div class="input-group">
									<a href="javascript://" class="default-link">Уведомление о задержке поставки</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<hr>

			<!-- Блок с вкладками в рабочем листе -->
			<div class="container">
				<div class="row">
					<!-- nav tabs -->
					<ul class="nav nav-tabs nav-justified" id="worksheetTabs" role="tablist" style="width: 100%;">
						<li class="nav-item">
							<a class="nav-link active" id="worksheet-funnel-tab" data-toggle="tab" href="#worksheet-funnel" role="tab" aria-controls="worksheet-funnel" aria-selected="true">Образ клиента</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="worksheet-parameters-tab" data-toggle="tab" href="#worksheet-parameters" role="tab" aria-controls="worksheet-parameters" aria-selected="true">Параметры</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="worksheet-comments-tab" data-toggle="tab" href="#worksheet-comments" role="tab" aria-controls="worksheet-comments" aria-selected="true">Комментарии</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="worksheet-auto-tab" data-toggle="tab" href="#worksheet-auto" role="tab" aria-controls="worksheet-auto" aria-selected="true">Автомобиль</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="worksheet-design-tab" data-toggle="tab" href="#worksheet-design" role="tab" aria-controls="worksheet-design" aria-selected="true">Оформление</a>
						</li>
					</ul>
					<!-- Tab panes -->
					<div class="tab-content" style="width: 100%;">
						<!-- Рабочий лист Вкладка Образ клиента -->
						<div class="tab-pane active" id="worksheet-funnel" role="tabpanel" aria-labelledby="worksheet-funnel-tab">
							<p class="h3">образ клиента</p>
						</div>
						<!-- Рабочий лист Вкладка Параметры -->
						<div class="tab-pane mt-2" id="worksheet-parameters" role="tabpanel" aria-labelledby="worksheet-parameters-tab">
							<!-- 
							Пробная поездка
							Заголовок 
							-->
							<div class="px-3 py-2 d-flex ws-header">
								<span class="flex-grow-1">
									<a class="default-link" data-toggle="collapse" href="#wsparam1" aria-expanded="false" aria-controls="wsparam1">Пробная поездка</a>
								</span>
								<span><i class="icofont-ui-press text-success"></i></span>
							</div>
							<!-- 
							Пробная поездка
							Контент 
							-->
							<div id="wsparam1" class="pb-3 mt-2 mx-3 collapse ws-param ws-tab-content">
								<div class="input-group h4">Пробная поездка</div>
								<a href="javascript://" class="text-primary" data-toggle="modal" data-target="#addTestdriveModal">
									<i class="icofont-plus-circle"></i> Добавить
								</a>
								<div class="input-group" id="testdriveCars">
								</div>								
							</div>
							<!-- 
							Подбор по потребностям
							Заголовок 
							-->
							<div class="px-3 py-2 d-flex ws-header">
								<span class="flex-grow-1">
									<a class="default-link" data-toggle="collapse" href="#wsparam2" aria-expanded="false" aria-controls="wsparam2">Подбор по потребностям</a>
								</span>
								<span><i class="icofont-ui-press text-warning"></i></span>
							</div>
							<!-- 
							Подбор по потребностям
							Контент 
							-->
							<div class="pb-3 mt-2 mx-3 collapse ws-param ws-tab-content" id="wsparam2">
								<div class="input-group h4">Подбор по потребностям</div>
								<a href="javascript://" class="default-link" id="addSelectedCar"><i class="icofont-plus-circle"></i> Добавить</a>

								<div class="input-group pb-3" id="carsByNeeds">
									<div class="col-3 border">
										<div class="d-flex">
											<label class="flex-grow-1">Выберите модель</label>
											<a href="javascript://" class="removeSelectedCar text-danger">
												<i class="icofont-close"></i>
											</a>
										</div>
										{!! Form::select('wl_need_model',App\oa_model::pluck('name','id'),'', ['class' => 'wl_need_model form-control'])!!}
										{!! Form::select('wl_need_transmission',App\type_transmission::pluck('name','id'),'', ['class' => 'wl_need_transmission form-control'])!!}
										{!! Form::select('wl_need_wheel',App\type_wheel::pluck('name','id'),'', ['class' => 'wl_need_wheel form-control'])!!}
											
									</div>									
								</div>

								<div class="input-group no-gutters pb-3" id="selectCarOptions">
									@foreach($options_list as $id => $option)
										<span class="col-6"><input type="checkbox" value="{{ $id }}"> {{ $option }}</span>
									@endforeach
								</div>

								<div class="input-group no-gutters">
									<label class="col-3">Формат покупки</label>
									<label class="col-3">Срок покупки</label>
									<label class="col-3">Способ оплаты</label>
									<label class="col-3">Первый взнос</label>
								</div>

								<div class="input-group no-gutters">
									<div class="col-3">
										<select class="form-control" id="wl_need_purchase_type">
											<option value="1">Неизвестно</option>
											<option value="2">Из наличия</option>
											<option value="3">Из поставки</option>
										</select>
									</div>
									<div class="col-3">
										<input type="text" id="wl_need_pay_date" class="form-control" placeholder="Дата">
									</div>
									<div class="col-3">
										<select class="form-control" id="wl_need_pay_type">
											<option value="1">Неизвестно</option>
											<option value="2">Наличными</option>
											<option value="3">В кредит</option>
											<option value="4">В лизинг</option>
										</select>
									</div>
									<div class="col-3">
										<input type="text" id="wl_need_firstpay" class="form-control" placeholder="Сумма, р.">
									</div>
								</div>

								<div class="input-group">
									<input type="button" id="getListByNeeds" class="col-3 offset-6 btn btn-primary" value="Поиск на складе">
									<input type="button" id="wl_need_reserve" class="col-3 btn btn-success" value="Зарезервировать">
								</div>
							</div>
							<!-- 
							Конфигуратор
							Заголовок 
							-->
							<div class="px-3 py-2 d-flex ws-header">
								<span class="flex-grow-1">
									<a class="default-link" data-toggle="collapse" href="#wsparam3" aria-expanded="false" aria-controls="wsparam3">Конфигуратор</a>
								</span>
								<span><i class="icofont-ui-press text-warning"></i></span>
							</div>
							<!-- 
							Конфигуратор
							Контент 
							-->
							<div id="wsparam3" class="pb-3 mt-2 mx-3 collapse ws-param ws-tab-content">
								<div class="input-group h4">Конфигуратор</div>
								<div class="input-group no-gutters mb-3">
									<div class="col-12 d-flex">
										<span class="flex-grow-1 font-weight-bold">
											Виртуальный автомобиль (<span id="wl_cfg_count">0</span>):
										</span>
										<a href="javascript://" id="wl_cfg_add"><i class="icofont-plus-circle"></i></a>
									</div>
								</div>

								<div id="wl_cfg_car_blocks">
									<div class="wl_cfg_cars border-bottom border-dark mb-3">

										<input type="hidden" id="price" base="0" pack="0" dops="0">

										<div class="input-group no-gutters">
											<div class="col-4"><label>Модель</label></div>
											<div class="col-8 d-flex">
												<div class="flex-grow-1"><label>Комплектация</label></div>
												<a href="javascript://" class="wl_cfg_del"><i class="icofont-close"></i></a>
											</div>
										</div>

										<div class="input-group no-gutters mb-2">
											<div class="col-12">
												<div class="input-group">
													{!! Form::select('cfg_model',App\oa_model::pluck('name','id'),'', ['class' => 'col-4 form-control cfg_model'])!!}
													{!! Form::select('cfg_complect',array(),'', ['class' => 'col-8 form-control cfg_complect'])!!}
												</div>
											</div>
										</div>

										<div class="input-group no-gutters mb-3 display" style="display: none;">
											<div class="col-4 wl_cfg_checkbox">
												<span><input type="checkbox"> Выбрать</span>
											</div>
											<div class="col-8 wl_cfg_res">
											</div>
										</div>

										<div class="input-group no-gutters wl_cfg_carinfo display" style="display: none;">
											<!-- Левый блок -->
											<div class="col-6">
												<img id="cfg-img" src='' style="width: 100%;height: auto;">
												{!! Form::hidden('cfg_color_id','',['id'=>'cfg_color_id'])!!}
												<div class="input-group d-flex justify-content-center cfg-color">
												</div>
												<hr>
												<div class="input-group no-gutters">
													<div class="col-12 font-weight-bold clear-html" id="cfg-complect-code"></div>
													<div class="text-secondary">
														<div id="cfg-motor-type" class="clear-html"></div>
														<div id="cfg-motor-size" class="clear-html"></div>
														<div id="cfg-motor-transmission" class="clear-html"></div>
														<div id="cfg-motor-wheel" class="clear-html"></div>
													</div>
												</div>

											</div>
											<!-- Правый блок -->
											<div class="col-6">
												<div class="d-flex align-items-center justify-content-center">
													<div align="center" class="h5">
														<div id="cfg-model" class="clear-html"></div>
														<div id="cfg-full-price" class="clear-html"></div>
														Прогноз 02.01.2019
													</div>
												</div>

												<div class="d-flex border-bottom">
													<label class="flex-grow-1 font-weight-bold clear-html" id="cfg-complect-name"></label>
													<a href="javascript://" class="cfg-more">Подробнее</a>
												</div>

												<div id="cfg-complect-option" class="clear-html" style="font-size: 12px; background-color: #eee;"></div>

												<div class="h5 text-right clear-html" id="cfg-base-price"></div>

												<div class="border-bottom font-weight-bold">
													Выберите опционное оборудование
												</div>
												<div class="clear-html cfg-pack-block"></div>
											</div>
										</div>
									</div>
								</div>

								

								<div class="input-group no-gutters">
									<div class="col-3 offset-9">
										<button type="button" id="wl_cfg_create_request" class="btn btn-primary btn-block">Создать заявку</button>
									</div>
								</div>
							</div>
							<!-- 
							Дополнительное оборудование
							Заголовок 
							-->
							<div class="px-3 py-2 d-flex ws-header">
								<span class="flex-grow-1">
									<a class="default-link" data-toggle="collapse" href="#wsparam4" aria-expanded="false" aria-controls="wsparam4">Дополнительное оборудование</a>
								</span>
								<span><i class="icofont-ui-press text-warning"></i></span>
							</div>
							<!-- 
							Дополнительное оборудование
							Контент 
							-->
							<div id="wsparam4" class="pb-3 mt-2 mx-3 collapse ws-param ws-tab-content">
								<div class="input-group h4">Дополнительное оборудование</div>
								<div class="input-group no-gutters">
									<div class="col-12 d-flex justify-content-between">
										<label style="color: #75c1ff;" class="font-weight-bold">Цена комплекта ДО:</label>
										<label style="color: #75c1ff;" class="font-weight-bold">Маржа ДО:</label>
									</div>
									<div class="col-12 d-flex justify-content-between">
										<span style="color: #75c1ff;" class="h2">46 000 р.</span>
										<span style="color: #75c1ff;" class="h2">18 400 р.</span>
									</div>
								</div>

								<div class="input-group no-gutters pb-3 d-flex align-items-center">
									<div class="col-6">
										<label class="font-weight-bold m-0">Установленное оборудование:</label>
									</div>
									<div class="col-3">
										<input type="text" id="wl_dops_dopprice" class="form-control" value="0" disabled>
									</div>
									<div class="col-3 d-flex justify-content-center">
										<input type="checkbox" class="mr-1"> Выделить в КП
									</div>
								</div>

								<div class="input-group no-gutters" id="wl_dops_list">
								</div>

								<hr>

								<div class="input-group no-gutters pb-3 d-flex align-items-center">
									<div class="col-6">
										<label class="font-weight-bold m-0">Предложенное оборудование:</label>
									</div>
									<div class="col-3">
										<input type="number" min="0" id="wl_dops_offered" name="wl_dops_offered" class="form-control" placeholder="Сумма, р.">
									</div>
									<div class="col-3">
										<a href="javascript://" id="wl_dops_install" class="btn btn-primary btn-block">Установить</a>
									</div>
								</div>

								<div class="input-group no-gutters" id="wl_dops_all">
								</div>

							</div>


							<!-------------------------------->
							<!--АВТОМОБИЛЬ КЛИЕНТА ЗАГОЛОВОК-->
							<!-------------------------------->
							<div class="px-3 py-2 d-flex ws-header" id="old-car">

								<span class="flex-grow-1">
									<a class="default-link" data-toggle="collapse" href="#wsparam5" aria-expanded="false" aria-controls="wsparam5">Автомобиль клиента</a>
								</span>
								<span><i class="icofont-ui-press text-danger"></i></span>
							</div>
							<!--АВТОМОБИЛЬ КЛИЕНТА КОНТЕНТ-->
							<div id="wsparam5" class="old-car pb-3 mt-2 mx-3 collapse ws-param ws-tab-content"></div>
							<!-------------------------------->
							<!-------------------------------->
							<!-------------------------------->
							
							

							<!---------------------------------->														
							<!--ПРОГРАММА ЛОЯЛЬНОСТИ ЗАГОЛОВОК-->
							<!---------------------------------->
							<div class="px-3 py-2 d-flex ws-header" id="loyalty_program">
								<span class="flex-grow-1">
									<a class="default-link" data-toggle="collapse" href="#wsparam6" aria-expanded="false" aria-controls="wsparam6">Программа лояльности</a>
								</span>
								<span>
									<a href="javascript://" id="open-pv-vidget" class="default-link">Виджет</a>
									<i class="icofont-ui-press text-success"></i>
								</span>
							</div>
							<!--ПРОГРАММА ЛОЯЛЬНОСТИ КОНТЕНТ-->
							<div id="wsparam6" class="pb-3 mt-2 mx-3 collapse loyalty_program ws-param ws-tab-content"></div>							
							<!---------------------------------->
							<!---------------------------------->
							<!---------------------------------->



							<!-- 
							КОММЕРЧЕСКОЕ ПРЕДЛОЖЕНИЕ
							ЗАГОЛОВОК
							-->
							<div class="px-3 py-2 d-flex ws-header">
								<span class="flex-grow-1">
									<a class="default-link" data-toggle="collapse" href="#wsparam7" aria-expanded="false" aria-controls="wsparam7">Коммерческое предложение</a>
								</span>
								<span><i class="icofont-ui-press text-success"></i></span>
							</div>
							<!-- 
							КОММЕРЧЕСКОЕ ПРЕДЛОЖЕНИЕ
							КОНТЕНТ
							-->
							<div id="wsparam7" class="pb-3 mt-2 mx-3 collapse ws-param ws-tab-content">
								<div class="input-group h4">Коммерческое предложение</div>
								<table id="wl_offers_list" class="table table-bordered table-sm" style="table-layout: fixed; width: 100%;">
								</table>
								
								<div class="input-group mt-3">
									<div class="col-3 d-flex align-items-center"><span><input type="checkbox"> My Renault</span></div>
									<div class="col-6">
										<button type="button" id="create_offer" class="btn btn-primary btn-block">Создать Коммерческое предложение</button>
									</div>
								</div>
							</div>
						</div>
						<!-- Рабочий лист Вкладка  Комментарии -->
						<div class="py-3 px-3 tab-pane" id="worksheet-comments" role="tabpanel" aria-labelledby="worksheet-comments-tab">
							
							<div class="input-group mb-3">
								<span class="col-12 font-weight-bold">Новая запись:</span>
								<textarea id="wl_new_comment" class="form-control col-12" style="resize: none;" placeholder="Комментарий"></textarea>
							</div>

							<div class="input-group mb-3 d-flex justify-content-between">
								<button type="button" class="col-3 btn btn-success">Задача</button>
								<button id="wl_create_comment" type="button" class="col-3 btn btn-primary">Записать</button>
							</div>

							<div class="input-group">
								<div class="col-12 d-flex">
									<span class="flex-grow-1 font-weight-bold">Комментарии:</span>
									<a href="javascript://"><i class="icofont-print"></i></a>
								</div>
								<div id="wl_comments_list" class="col-12 border" style="min-height: 200px; max-height: 500px; overflow-y: scroll;"></div>
							</div>

						</div>
						<!-- Рабочий лист Вкладка  Автомобиль -->
						<div class="py-3 tab-pane" id="worksheet-auto" role="tabpanel" aria-labelledby="worksheet-auto-tab">
							
							<div id="wl_car_empty" class="alert alert-info">
								Нет зарезервированного автомобиля, либо не загружен рабочий лист.
							</div>

							<div id="wl_car" style="display: none;">
								<div class="col-6 offset-3">
									<img id="wl_car_img" src='' style="width: 100%;height: auto;">	
								</div>

								<div class="input-group border-bottom">
									<div class="col-4 text-secondary" id="wl_car_vin">
									</div>

									<div class="col-4 text-secondary">
										Этап поставки
									</div>

									<div class="col-4 text-secondary">
										Цена продажи
									</div>
								</div>

								<div class="input-group">
									<div class="col-4 h5">
										<span id="wl_car_name"></span>
										<br>
										<span class="wl_car_complect_name"></span>
									</div>
									<div class="col-4 h5">
										А/м в наличии<br>
										Склад Овен-авто
									</div>
									<div class="col-4 h5">
										<div id="wl_car_fullprice"></div>
									</div>
								</div>

								<div class="input-group">
									<div class="col-4">
										<label>Исполнение <span id="wl_car_complect_code"></span></label>
										<ul class="list-unstyled text-secondary" id="wl_car_info">
										</ul>
									</div>

									<div class="col-4">
										<label>Комплектация <span class="wl_car_complect_name"></span></label>
										<ul class="list-unstyled text-secondary border-bottom" id="wl_car_installed">
										</ul>
										<div class="text-right h5 text-secondary" id="wl_car_complect_price">
										</div>
									</div>

									<div class="col-4">
										<label>Цвет автомобиля</label>
										<div class="input-group text-secondary no-gutters">
											<div class="col-12 border-bottom border-warning" id="wl_car_color_name">
											</div>

											<div class="col-12 d-flex">
												<div class="flex-grow-1" id="wl_car_rn_code">
												</div>
												<div id="wl_car_color_example" style="width: 20px; height: 20px; border-radius: 100%;">
												</div>
											</div>
										</div>

										<label>Опционное оборудование</label>
										<div id="wl_car_options">
										</div>

										<label>Дополнительное оборудование</label>
										<ul class="list-unstyled text-secondary border-bottom" id="wl_car_dops">
										</ul>

										<div class="text-right h5 text-secondary" id="wl_car_dopprice">
										</div>

										<label>Компании</label>
										<ul id="wl_car_company" class="list-unstyled text-secondary">
										</ul>

									</div>
								</div>

								<div class="input-group">
									<div class="col-3">
										<button type="button" id="wl_car_opencard" class="btn btn-warning btn-block">Открыть карточку</button>
									</div>
									<div class="col-3">
										<button type="button" class="btn btn-success btn-block">Отложить еще один</button>
									</div>
									<div class="col-3">
										<button type="button" class="btn btn-primary btn-block">Отложить другой</button>
									</div>
									<div class="col-3">
										<button type="button" id="wl_car_remove" class="btn btn-danger btn-block">Снять резерв</button>
									</div>
								</div>
							</div>
						</div>
						<!-- Рабочий лист Вкладка  Оформление -->
						<div class="tab-pane mt-2" id="worksheet-design" role="tabpanel" aria-labelledby="worksheet-design-tab">
							
							<!-- ПЛАТЕЖИ ЗАГОЛОВОК -->
							<div class="px-3 py-2 d-flex ws-header" id="client_pays">
								<a class="default-link" data-toggle="collapse" href="#wsdesign1" aria-expanded="false" aria-controls="wsdesign1">Платежи</a>
							</div>
							<!-- /ПЛАТЕЖИ ЗАГОЛОВОК -->

							<!-- ПЛАТЕЖИ КОНТЕНТ -->
							<div id="wsdesign1" class="pb-3 mt-2 mx-3 collapse client_pays ws-registration ws-tab-content"></div>
							<!-- /ПЛАТЕЖИ КОНТЕНТ -->

							
							<!-- ДОГОВОРЫ ЗАГОЛОВОК-->
							<div class="px-3 py-2 d-flex ws-header" id="client_contract">
								<a class="default-link" data-toggle="collapse" href="#wsdesign2" aria-expanded="false" aria-controls="wsdesign2">Договоры</a>
							</div>
							<!-- /ОФОРМЛЕНИЕ ЗАГОЛОВОК-->

							<!-- ДОГОВОРЫ КОНТЕНТ-->
							<div id="wsdesign2" class="pb-3 mt-2 mx-3 collapse client_contract ws-registration ws-tab-content"></div>
							<!-- /ДОГОВОРЫ КОНТЕНТ-->

							<!-- 
							ПРОДУКТЫ ОФУ
							ЗАГОЛОВОК
							-->
							<div class="px-3 py-2 d-flex ws-header">
								<a class="default-link" data-toggle="collapse" href="#wsdesign3" aria-expanded="false" aria-controls="wsdesign3">Продукты ОФУ</a>
							</div>
							<!-- 
							ПРОДУКТЫ ОФУ
							КОНТЕНТ
							-->
							<div id="wsdesign3" class="pb-3 mt-2 mx-3 collapse ws-registration ws-tab-content">
								<div class="input-group h4">Продукты ОФУ</div>
								<div class="input-group no-gutters">
									<div class="col-12 d-flex">
										<span class="font-weight-bold flex-grow-1">Выявленные потребности</span>
										<span class="font-weight-bold ">Бюджет Клиента</span>
									</div>
								</div>

								<div class="input-group no-gutters">
									<div class="col-12 d-flex justify-content-end">
										<span id="wl_service_budget" class="font-weight-bold">0 р.</span>
									</div>
								</div>

								<div id="services_list" class="mb-3">
								</div>

								<div class="input-group no-gutters" style="color: #aaa;">
									<div class="col-12 font-weight-bold" id="marzha">
										Рассчетная маржа ОФУ:
									</div>
									<div class="col-12 h2">
										0 р.
									</div>
								</div>

								<hr>

								<div class="input-group no-gutters">
									<div class="col-12 d-flex">
										<span class="flex-grow-1 font-weight-bold">Оформление продуктов ОФУ (<span id="ofu_products_count">0</span>)</span>
										<a href="javascript://" id="ofu_add_block"><i class="icofont-plus-circle"></i></a>
									</div>
								</div>

								<div id="ofu_products">
								</div>

								<hr>

								<div class="input-group no-gutters">
									<div class="col-12 d-flex">
										<span class="font-weight-bold flex-grow-1" style="color: #75c1ff;">Доходность ОФУ:</span>
										<span class="font-weight-bold" style="color: #75c1ff;">Маржа ОФУ:</span>
									</div>
									<div class="col-12 d-flex">
										<span class="h2 flex-grow-1" style="color: #75c1ff;">0%</span>
										<span class="h2" style="color: #75c1ff;">0 р.</span>
									</div>
								</div>
								

							</div>

							

							<!----------------------------> 
							<!--КРЕДИТЫ ЗАГОЛОВОК НАЧАЛО-->
							<!---------------------------->
							<div class="px-3 py-2 d-flex ws-header" id="client_kredit">
								<a class="default-link" data-toggle="collapse" href="#wsdesign4" aria-expanded="false" aria-controls="wsdesign4">
									Кредиты
								</a>
							</div>
							<div id="wsdesign4" class="pb-3 mt-2 mx-3 collapse client_kredit ws-registration ws-tab-content"></div>
							<!----------------------------> 
							<!--КРЕДИТЫ КОНТЕНТ КОНЕЦ----->
							<!---------------------------->
						</div>

					</div>
				</div>
			</div><!-- /Блок c вкладками -->
			{{ Form::close() }}
		</div><!-- /Рабочий лист -->
		
		<!-- Вкладка Задача -->
		<div class="tab-pane" id="task" role="tabpanel" aria-labelledby="task-tab">
			<h3 align="center">Задача</h3>
		</div>
	</div>
</div>
</div>

<form id="get-pdf" target="_blank" method="POST"></form>
@endsection