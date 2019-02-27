@section('worklist')
<div id="hidden_panel" class="container border-left border-info">
<div class="row">
	<!-- Вкладки боковой панели -->
	<ul class="nav nav-tabs nav-justified bg-info right-menu" id="hiddenTab" role="tablist" style="width: 100%;">
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
			<div class="border input-group align-items-center">
				<div class="flex-grow-1">
					<button id="closing" type="button" class="btn btn-light"><i class="fas fa-arrow-circle-right"></i></button>
					<span class="text-success"><input type="checkbox" autocomplete="off"> Только мои</span>
				</div>
				<div>
					<button type="button" class="btn btn-light"><i class="fas fa-filter"></i></button>
					<button type="button" class="btn btn-light"><i class="fas fa-print"></i></button>
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
			<div class="border input-group align-items-center">
				<div class="flex-grow-1">
					<button id="closing" type="button" class="btn btn-light"><i class="fas fa-arrow-circle-right"></i></button>
				</div>
				<div>
					<button type="button" class="btn btn-light"><i class="fas fa-save"></i></button>
					<button type="button" class="btn btn-light"><i class="fas fa-trash-alt"></i></button>
				</div>
			</div>
			<!-- Заголовок -->
			<div class="d-flex">
				<span class="h3 flex-grow-1">Трафик №138</span>
				<span class="h3">05 января 2018</span>
			</div>
			<!-- Форма вкладки -->

			{!! Form::open() !!}
			{{ csrf_field() }}
			<span class="h4">Тип трафика</span>
			<div class="input-group btn-group-toggle" data-toggle="buttons">
				@foreach($traffic_types as $traffic)
				<div class="col-3 btn btn-outline-info"><input type="radio" name="traffic_type" value="{{ $traffic->id }}" autocomplete="off"> {{ $traffic->name }}</div>
				@endforeach
			</div>
			<hr>
			
			<span class="h4">Интересующая модель</span>
			<div class="input-group btn-group-toggle" data-toggle="buttons">
				@foreach($models as $key => $model)
				<div class="col-3 btn btn-outline-info"><input type="radio" name="model" value="{{ $key }}" autocomplete="off"> {{ $model }}</div>
				@endforeach
			</div>
			<hr>

			<span class="h4">Зона контакта</span>
			<div class="input-group btn-group-toggle" data-toggle="buttons">
				
				<div class="col-3 btn btn-outline-info">
					<input type="radio" name="area_id" autocomplete="off" value="0"> 
					Неизвестно
				</div>

				@foreach(App\crm_city_list::pluck('name','id') as $id=>$city)
				<div class="col-3 btn btn-outline-info">
					<input type="radio" name="area_id" autocomplete="off" value="{{$id}}"> 
					{{$city}}
				</div>
				@endforeach

			</div>

			<hr>

			<span class="h4">Назначенный менеджер</span>
			<div class="input-group btn-group-toggle" data-toggle="buttons">
				@foreach($users as $key => $user)
					<div class="col-3 btn btn-outline-info" >
						<input type="radio" name="manager" value="{{ $key }}" autocomplete="off" > 
						{{ $user }}
					</div>
				@endforeach
			</div>
			<hr>

			<span class="h4">Назначенное действие</span>
			<div>
				<div class="input-group">
					<input name="action_date" type="text" class="col-3 form-control calendar" title="Назначенная дата">
					<input name="action_time" type="time" class="col-3 form-control" title="Назначенное время">
				</div>
				<div class="input-group btn-group-toggle" data-toggle="buttons">
					@foreach($assigned_actions as $key => $action)
					  <div class="col-3 btn btn-outline-info"><input type="radio" name="assigned_action" value="{{ $key }}" autocomplete="off"> {{ $action }}</div>
					@endforeach
				</div>
			</div>
			<hr>


			<span class="h4">Клиент</span>
			<div>
				<div class="input-group">
					<input type="text" name="client_name" class="col-4 form-control" placeholder="Имя">
					<input type="text" name="client_secondname" class="col-4 form-control" placeholder="Отчество">
					<input type="text" name="client_lastname" class="col-4 form-control" placeholder="Фамилия">
				</div>
				<div class="input-group">
					<input type="text" name="client_phone" class="col-6 form-control" placeholder="Телефон">
					<input type="text" name="client_email" class="col-6 form-control" placeholder="Email">
				</div>
				<div class="input-group">
					<input type="text" name="comment" class="col-12 form-control" placeholder="Комментарий">
				</div>
			</div>

			<div class="input-group justify-content-center p-3 no-gutters">
				<button type="button" id="traffic_submit" name="traffic_submit" class="btn btn-outline-success col-4">
					Назначить трафик
				</button>
			</div>
			{!! Form::close() !!}
		</div>










		
		<!-- Вкладка Рабочий лист -->
		<div class="tab-pane" id="worksheet" role="tabpanel" aria-labelledby="worksheet-tab">
			{{Form::open() }}
			<!-- Панель иконок -->
			<div class="border input-group align-items-center">

				<div class="flex-grow-1">
					<button id="closing" type="button" class="btn btn-light"><i class="fas fa-arrow-circle-right"></i></button>
					<span id="worklist-vin">
						VIN-nomer
					</span>
				</div>

				<div>
					<button type="button" class="btn btn-light" id="wl_save_changes" title="Сохранить рабочий лист"><i class="fas fa-save"></i></button>
					<button type="button" class="btn btn-light"><i class="fas fa-trash-alt"></i></button>
				</div>

			</div>

			<div class="d-flex">

				<span class="h3 flex-grow-1" id="worklist-number">
					Рабочий лист №<span name="wl_id">-</span>
				</span>

				<span class="h3" id="worklist-date">
					<span name="wl_addingday">-</span>
				</span>

			</div>

			<!-- Основной блок рабочего листа -->
			<div>
				<div>
					<div class="input-group d-flex align-items-center">

						<label class="col-3">Трафик</label>
						<label class="col-3">Спрос</label>
						<label class="col-3">Менеджер</label>

						{{Form::select('traffic_action',App\crm_assigned_action::pluck('name','id'),'',['class'=>'col-3 form-control'])}}
						
					</div>

					<div class="input-group">

						<input name="traffic_type" type="text" class="col-3 form-control" value="-" readonly="">
						<input name="traffic_model" type="text" class="col-3 form-control" value="-" disabled>
						<input name="wl_manager" type="text" class="col-3 form-control" value="-" disabled>
						<div class="col-3 trafic_action_block">
							<div class="row">
								<input name="traffic_action_date" type="text" class="col-6 text-danger form-control calendar">
								<input name="traffic_action_time" type="time" class="col-6 text-danger form-control" >
							</div>
						</div>

					</div>
				</div>

				<hr>
				
				<div>
					<div class="input-group">

						<label class="col-3">Тип контакта</label>					
						<label class="col-9">Контакт</label>

					</div>

					<div class="input-group">
						{{Form::select('client_type',App\crm_client_type::pluck('name','id'),'',['class'=>'col-3 form-control'])}}
						
						<input name="client_name" type="text" class="col-3 form-control" placeholder="Имя">
						<input name="client_secondname" type="text" class="col-3 form-control" placeholder="Отчество">
						<input name="client_lastname" type="text" class="col-3 form-control" placeholder="Фамилия">

					</div>
				</div>

				<hr>

				<div>
					<div class="input-group">

						<label class="col-3">Телефон</label>
						<label class="col-3">Почта</label>
						<label class="col-3">Маркер</label>

						<div class="col-3 d-flex align-items-center justify-content-end">
							<a data-toggle="collapse" href="#worklistMoreInfo" aria-expanded="false" aria-controls="worklistMoreInfo">
								Еще
							</a>
						</div>

					</div>

					<div wl_block='contacts' id="wl_contacts">
						<div class="input-group">
							<input type="text" class="col-3 form-control" placeholder="Введите номер" name='contact_phone[]'>
							<input type="text" class="col-3 form-control" placeholder="Введите Email" name='contact_email[]'>
							
							{!! Form::select(
								'contact_marker[]',
								App\crm_worklist_marker::pluck('name','id'), 
								'', 
								['class' => 'col-3 form-control']) 
							!!}
							
							<div class="col-3 d-flex align-items-center">
								<a href="#" class="col-6" id="wl_contacts_delete"><i class="fas fa-times text-danger"></i></a>
								<a href="#" class="col-6" id="wl_contacts_add"><i class="fas fa-plus-circle text-primary"></i></a>
							</div>
						</div>
					</div>
				</div>

				<div id="worklistMoreInfo" class="collapse">
					<hr>

					<div class="input-group">
						<label class="col-3">Зона контакта</label>
						<label class="col-6">Адрес прописки</label>
						<label class="col-3">Дата рождения</label>
					</div>

					<div class="input-group">
						{{Form::select('client_area',App\crm_city_list::pluck('name','id'),'',['class'=>'col-3 form-control'])}}
						<input name="client_address" type="text" class="col-6 form-control" placeholder="Адрес">
						<input name="client_birthday" type="text" class="col-3 form-control calendar">
					</div>
					
					<div class="input-group">
						<label class="col-6">Паспорт</label>
						<label class="col-6">Водительское удостоверение</label>
					</div>

					<div class="input-group">
						<input name="client_passport_serial" type="text" class="col-2 form-control" placeholder="Серия">
						<input name="client_passport_number" type="text" class="col-2 form-control" placeholder="Номер">
						<input name="client_passport_giveday" type="text" class="col-2 form-control calendar" placeholder="Дата выдачи">
						<input name="client_drive_number" type="text" class="col-6 form-control" placeholder="Номер">
						<input name="client_drive_giveday" type="text" class="col-3 form-control calendar" placeholder="Дата выдачи">
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
						<div class="tab-pane" id="worksheet-parameters" role="tabpanel" aria-labelledby="worksheet-parameters-tab">
							<!-- 
							Пробная поездка
							Заголовок 
							-->
							<div class="p-2 bg-info d-flex">
								<span class="flex-grow-1">
									<a class="text-white" data-toggle="collapse" href="#wsparam1" aria-expanded="false" aria-controls="wsparam1">Пробная поездка</a>
								</span>
								<span><i class="fas fa-circle text-success"></i></span>
							</div>
							<!-- 
							Пробная поездка
							Контент 
							-->
							<div id="wsparam1" class="py-3 collapse ws-param">
								<a href="#" class="text-primary" data-toggle="modal" data-target="#addTestdriveModal"><i class="fas fa-plus-circle"></i> Добавить</a>
								<div class="input-group" id="testdriveCars">
								</div>								
							</div>
							<!-- 
							Подбор по потребностям
							Заголовок 
							-->
							<div class="p-2 bg-info d-flex">
								<span class="flex-grow-1">
									<a class="text-white" data-toggle="collapse" href="#wsparam2" aria-expanded="false" aria-controls="wsparam2">Подбор по потребностям</a>
								</span>
								<span><i class="fas fa-circle text-warning"></i></span>
							</div>
							<!-- 
							Подбор по потребностям
							Контент 
							-->
							<div class="py-3 collapse ws-param" id="wsparam2">
								<a href="#" class="text-primary" id="addSelectedCar"><i class="fas fa-plus-circle"></i> Добавить</a>

								<div class="input-group" id="carsByNeeds">
									<div class="col-3 border">
										<div class="d-flex">
											<label class="flex-grow-1">Выберите модель</label>
											<a href="#" class="removeSelectedCar"><i class="fas fa-trash-alt text-danger"></i></a>
										</div>
										{!! Form::select('wl_need_model',App\oa_model::pluck('name','id'),'', ['class' => 'wl_need_model form-control'])!!}
										{!! Form::select('wl_need_transmission',App\type_transmission::pluck('name','id'),'', ['class' => 'wl_need_transmission form-control'])!!}
										{!! Form::select('wl_need_wheel',App\type_wheel::pluck('name','id'),'', ['class' => 'wl_need_wheel form-control'])!!}
											
									</div>									
								</div>

								<br>

								<div class="input-group" id="selectCarOptions">
									@foreach($options_list as $id => $option)
										<span class="col-6"><input type="checkbox" value="{{ $id }}"> {{ $option }}</span>
									@endforeach
								</div>

								<br>
								<div class="input-group">
									<label class="col-3">Формат покупки</label>
									<label class="col-3">Форма оплаты</label>
									<label class="col-3">Первый взнос</label>
									<label class="col-3">Бюджет покупки</label>
								</div>
								<div class="input-group">
									<select class="col-3 form-control">
										<option>Из наличия</option>
									</select>
									<select class="col-3 form-control">
										<option>В кредит</option>
									</select>
									<input type="text" id="wl_need_firstpay" class="col-3 form-control" placeholder="Сумма, р.">
									<input type="text" id="wl_need_sum" class="col-3 form-control" placeholder="Сумма, р.">
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
							<div class="p-2 bg-info d-flex">
								<span class="flex-grow-1">
									<a class="text-white" data-toggle="collapse" href="#wsparam3" aria-expanded="false" aria-controls="wsparam3">Конфигуратор</a>
								</span>
								<span><i class="fas fa-circle text-warning"></i></span>
							</div>
							<!-- 
							Конфигуратор
							Контент 
							-->
							<div id="wsparam3" class="py-3 collapse ws-param">
								<div class="input-group">
									<div class="col-4"><label>Модель</label></div>
									<div class="flex-grow-1"><label>Комплектация</label></div>
									<a href="#"><i class="fas fa-times"></i></a>
								</div>
								<div class="input-group">
									{!! Form::select('cfg_model',App\oa_model::pluck('name','id'),'', ['class' => 'col-4 form-control'])!!}
									{!! Form::select('cfg_complect',array(),'', ['class' => 'col-8 form-control'])!!}
								</div>
								<hr>
								<div class="input-group">
									<!-- Левый блок -->
									<div class="col-6">
										<img id="cfg-img" src='' style="width: 100%;height: auto;">
										{!! Form::hidden('cfg_color_id','',['id'=>'cfg_color_id'])!!}
										<div class="input-group d-flex justify-content-center" id="cfg-color">
										</div>
										<hr>
										<div class="input-group no-gutters">
											<div class="col-12 font-weight-bold" id="cfg-complect-code"></div>
											<div class="text-secondary">
												<div id="cfg-motor-type"></div>
												<div id="cfg-motor-size"></div>
												<div id="cfg-motor-transmission"></div>
												<div id="cfg-motor-wheel"></div>
											</div>
										</div>

									</div>
									<!-- Правый блок -->
									<div class="col-6">
										<div class="d-flex align-items-center justify-content-center">
											<div align="center" class="h5">
												<div id="cfg-model"></div>
												<div id="cfg-full-price"></div>
												Прогноз 02.01.2019
											</div>
										</div>

										<div class="d-flex border-bottom">
											<label class="flex-grow-1 font-weight-bold" id="cfg-complect-name"></label>
											<a href="#" id="cfg-more">Подробнее</a>
										</div>

										<div id="cfg-complect-option"></div>

										<div class="h5 text-right" id="cfg-base-price"></div>

										<div class="border-bottom font-weight-bold">
											Выберите опционное оборудование
										</div>
										<div id="cfg-pack-block"></div>
										
										
									</div>
								</div>
								<div class="input-group">
									<button type="button" class="col-3 offset-6 btn btn-outline-success">Сравнить еще один</button>
									<button type="button" class="col-3 btn btn-outline-primary">Создать заявку</button>
								</div>
							</div>
							<!-- 
							Дополнительное оборудование
							Заголовок 
							-->
							<div class="p-2 bg-info d-flex">
								<span class="flex-grow-1">
									<a class="text-white" data-toggle="collapse" href="#wsparam4" aria-expanded="false" aria-controls="wsparam4">Дополнительное оборудование</a>
								</span>
								<span><i class="fas fa-circle text-warning"></i></span>
							</div>
							<!-- 
							Дополнительное оборудование
							Контент 
							-->
							<div id="wsparam4" class="py-3 collapse ws-param">
								<div class="input-group">
									<label class="col-3">Установлено</label>
									<label class="col-3">Предложено</label>
									<label class="col-3">Заказ-наряд</label>
								</div>

								<div class="input-group">
									<input type="text" id="wl_dops_dopprice" class="col-3 form-control" value="0" disabled>
									<input type="number" min="0" id="wl_dops_offered" name="wl_dops_offered" class="col-3 form-control" placeholder="Сумма, р.">
									<input type="text" id="wl_dops_sum" class="col-3 form-control" value="0" disabled>
									<div class="col-3 d-flex align-items-center">
										<span><input type="checkbox"> Разделить в КП</span>
									</div>
								</div>
								
								<hr>

								<div class="input-group">
									<label class="col-12">Установленное оборудование:</label>
								</div>

								<div class="input-group" id="wl_dops_list">
								</div>

								<hr>

								<div class="input-group">
									<label class="col-9">Предложенное оборудование:</label>
									<a href="#" id="wl_dops_install" class="col-3 text-primary">Установить</a>
								</div>

								<div class="input-group" id="wl_dops_all">
								</div>

							</div>


							<!-------------------------------->
							<!--АВТОМОБИЛЬ КЛИЕНТА ЗАГОЛОВОК-->
							<!-------------------------------->
							<div class="p-2 bg-info d-flex" id="old-car">

								<span class="flex-grow-1">
									<a class="text-white" data-toggle="collapse" href="#wsparam5" aria-expanded="false" aria-controls="wsparam5">Автомобиль клиента</a>
								</span>
								<span><i class="fas fa-circle text-danger"></i></span>
							</div>
							<!--АВТОМОБИЛЬ КЛИЕНТА КОНТЕНТ-->
							<div id="wsparam5" class="old-car py-3 collapse ws-param"></div>
							<!-------------------------------->
							<!-------------------------------->
							<!-------------------------------->
							
							

							<!---------------------------------->														
							<!--ПРОГРАММА ЛОЯЛЬНОСТИ ЗАГОЛОВОК-->
							<!---------------------------------->
							<div class="p-2 bg-info d-flex" id="loyalty_program">
								<span class="flex-grow-1">
									<a class="text-white" data-toggle="collapse" href="#wsparam6" aria-expanded="false" aria-controls="wsparam6">Программа лояльности</a>
								</span>
								<span>
									<a id="open-pv-vidget">Виджет</a>
									<i class="fas fa-circle text-success"></i>
								</span>
							</div>
							<!--ПРОГРАММА ЛОЯЛЬНОСТИ КОНТЕНТ-->
							<div id="wsparam6" class="py-3 collapse loyalty_program container ws-param"></div>							
							<!---------------------------------->
							<!---------------------------------->
							<!---------------------------------->



							<!-- 
							КОММЕРЧЕСКОЕ ПРЕДЛОЖЕНИЕ
							ЗАГОЛОВОК
							-->
							<div class="p-2 bg-info d-flex">
								<span class="flex-grow-1">
									<a class="text-white" data-toggle="collapse" href="#wsparam7" aria-expanded="false" aria-controls="wsparam7">Коммерческое предложение</a>
								</span>
								<span><i class="fas fa-circle text-success"></i></span>
							</div>
							<!-- 
							КОММЕРЧЕСКОЕ ПРЕДЛОЖЕНИЕ
							КОНТЕНТ
							-->
							<div id="wsparam7" class="py-3 collapse ws-param">
								<table class="table table-bordered table-sm" style="table-layout: fixed;" width="100%">
									<tr>
										<td>20.01.2017 16:31</td>
										<td>X7LHSRGAN59685351</td>
										<td><a href="#">Открыть</a></td>
									</tr>
									<tr>
										<td>18.01.2017 12:45</td>
										<td>X7LHSRGAN59685587, X7L5SRAT6335…</td>
										<td><a href="#">Открыть</a></td>
									</tr>
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
						<div class="py-3 tab-pane" id="worksheet-comments" role="tabpanel" aria-labelledby="worksheet-comments-tab">
							
							<div class="input-group">
								<span class="col-12 font-weight-bold">Новая запись</span>
								<textarea id="wl_new_comment" class="form-control col-12" style="resize: none;" placeholder="Введите текст комментария"></textarea>
							</div>

							<div class="input-group mb-3">
								<button type="button" class="col-3 offset-6 btn btn-primary" disabled>Новая команда</button>
								<button id="wl_create_comment" type="button" class="col-3 btn btn-primary">Задача</button>
							</div>

							<div class="input-group">
								<div class="col-12 d-flex">
									<span class="flex-grow-1 font-weight-bold">Комментарии</span>
									<a href="#"><i class="fa fa-print"></i></a>
								</div>
								<textarea id="wl_comments_list" class="form-control col-12" rows="20" style="resize: none; overflow-y: scroll;" readonly></textarea>
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
										<div id="wl_car_sale" class="text-warning">- 20 000 руб.</div>
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
						<div class="tab-pane" id="worksheet-design" role="tabpanel" aria-labelledby="worksheet-design-tab">
							<!-- 
							ПЛАТЕЖИ
							ЗАГОЛОВОК
							-->
							<div class="p-2 bg-info d-flex">
								<a class="text-white" data-toggle="collapse" href="#wsdesign1" aria-expanded="false" aria-controls="wsdesign1">Платежи</a>
							</div>
							<!-- 
							ПЛАТЕЖИ
							КОНТЕНТ
							-->
							<div id="wsdesign1" class="py-3 collapse">
								<div class="input-group">
									<span class="col-3">Сумма платежа</span>
									<span class="col-3">Дата платежа</span>
									<span class="col-6">Сумма остатка</span>
								</div>

								<div>
									<div class="input-group">
										<input type="text" class="form-control col-3" placeholder="Сумма платежа" value="10 000">
										<input type="text" class="form-control col-3" placeholder="Дата платежа" value="01.01.2019">
										<input type="text" class="form-control col-3" value="690 000" disabled>
										<div class="col-1 d-flex align-items-center">
											<span><input type="checkbox" checked></span>
										</div>
										<div class="col-1 d-flex align-items-center">
											<a href="#" class="text-danger"><i class="fas fa-times"></i></a>
										</div>
									</div>

									<div class="input-group">
										<input type="text" class="form-control col-3" placeholder="Сумма платежа" value="240 000">
										<input type="text" class="form-control col-3" placeholder="Дата платежа" value="05.01.2019">
										<input type="text" class="form-control col-3" value="450 000" disabled>
										<div class="col-1 d-flex align-items-center">
											<span><input type="checkbox"></span>
										</div>
										<div class="col-1 d-flex align-items-center">
											<a href="#" class="text-danger"><i class="fas fa-times"></i></a>
										</div>
										<div class="col-1 d-flex align-items-center">
											<a href="#" class="text-success"><i class="fas fa-plus-circle"></i></a>
										</div>
									</div>
								</div>

								<div class="input-group">
									<span class="col-12">График оплаты ПТС</span>
									<input type="text" class="col-3 form-control" placeholder="Дата" value="25.01.2019">
								</div>
							</div>
							<!-- 
							ОФОРМЛЕНИЕ
							ЗАГОЛОВОК
							-->
							<div class="p-2 bg-info d-flex">
								<a class="text-white" data-toggle="collapse" href="#wsdesign2" aria-expanded="false" aria-controls="wsdesign2">Оформление</a>
							</div>
							<!-- 
							ОФОРМЛЕНИЕ
							КОНТЕНТ
							-->
							<div id="wsdesign2" class="py-3 collapse">
								<div class="input-group">
									<span class="col-3">Оформитель</span>
									<span class="col-3">Номер договора</span>
									<span class="col-3">Дата договора</span>
									<span class="col-3">Тип договора</span>
								</div>

								<div class="input-group">
									<select class="form-control col-3">
										<option>Выбрать</option>
										<option selected>Вася</option>
										<option>Петя</option>
									</select>
									<input type="text" class="form-control col-3" placeholder="Номер договора" value="ДПс00123">
									<input type="text" class="form-control col-3" placeholder="Дата" value="01.01.2019">
									<input type="text" class="form-control col-3" placeholder="Тип" value="Предварительный">
								</div>
								
								<div class="input-group">
									<span class="col-12">Срок поставки</span>
									<input type="text" class="form-control col-3" value="15.02.2018">
								</div>

								<div class="input-group">
									<span class="col-3">Задолженность</span>
									<span class="col-3"><input type="checkbox" checked> Акцептовано</span>
								</div>

								<div class="input-group">
									<input type="text" class="col-3 form-control" value="450 000 р." disabled>
									<input type="text" class="col-9 form-control" style="font-style: italic;" value="Кредитный менеджер Вася 25.01.2019 16.37" disabled>
								</div>

								<div class="input-group">
									<span class="col-3">Оформитель</span>
									<span class="col-3">Дата выдачи</span>
									<span class="col-3">Дата продажи</span>
									<span class="col-3">Дата списания</span>
								</div>

								<div class="input-group">
									<select class="form-control col-3">
										<option>Выбрать</option>
										<option selected>Вася</option>
										<option>Петя</option>
									</select>
									<input type="text" class="form-control col-3" placeholder="Дата выдачи" value="25.01.2019">
									<input type="text" class="form-control col-3" placeholder="Дата продажи" value="25.01.2019">
									<input type="text" class="form-control col-3" placeholder="Дата списания" disabled>
								</div>

							</div>
							<!-- 
							КРЕДИТОВАНИЕ
							ЗАГОЛОВОК
							-->
							<div class="p-2 bg-info d-flex">
								<a class="text-white" data-toggle="collapse" href="#wsdesign3" aria-expanded="false" aria-controls="wsdesign3">Кредитование</a>
							</div>
							<!-- 
							КРЕДИТОВАНИЕ
							КОНТЕНТ
							-->
							<div id="wsdesign3" class="py-3 collapse">
								<div class="input-group">
									<div class="col-12 d-flex">
										<span class="flex-grow-1 font-weight-bold">Выявленные потребности</span>
										<a href="#"><i class="fas fa-comment-dots"></i></a>
									</div>
								</div>

								<div class="input-group">
									<span class="col-3">Форма оплаты</span>
									<span class="col-3">Первый взнос</span>
									<span class="col-3">Цена продажи</span>
									<span class="col-3">Сумма кредита</span>
								</div>

								<div class="input-group mb-3">
									<select class="col-3 form-control">
										<option>В кредит</option>
										<option>За почку</option>
									</select>
									<input type="text" class="col-3 form-control" placeholder="Сумма, р.">
									<input type="text" class="col-3 form-control" placeholder="Цена продажи" value="1 028 980 р." disabled>
									<input type="text" class="col-3 form-control" placeholder="Сумма кредита" value="1 028 980 р." disabled>
								</div>

								<div class="input-group">
									<div class="col-12 d-flex">
										<span class="flex-grow-1 font-weight-bold">Работа с заявками (2)</span>
										<a href="#"><i class="fas fa-plus-circle"></i></a>
									</div>
								</div>

								<div>
									<div class="mb-3">
										<div class="input-group">
											<span class="col-3">Консультант</span>
											<span class="col-3">Кредитор</span>
											<span class="col-3">Первый взнос</span>
											<span class="col-3">Сумма кредита</span>
										</div>

										<div class="input-group">
											<select class="col-3 form-control">
												<option>Вася</option>
												<option>Петя</option>
											</select>
											<select class="col-3 form-control">
												<option>РНБ</option>
												<option>Другой</option>
											</select>
											<input type="text" class="form-control col-3" placeholder="Первый взнос" value="400 000 р.">
											<input type="text" class="form-control col-3" value="628 980 р." disabled>
										</div>
										
										<div class="input-group">
											<span class="col-3">Дата заявки</span>
											<span class="col-3 text-danger">06.01.2019</span>
											<span class="col-3">Срок действия</span>
											<span class="col-3">Дата подписания</span>
										</div>

										<div class="input-group">
											<input type="text" class="form-control col-3" placeholder="Дата" value="05.01.2019">
											<select class="col-3 form-control">
												<option>Отказ</option>
											</select>
											<input type="text" class="form-control col-1" placeholder="Дни">
											<input type="text" class="form-control col-2" placeholder="Дата">
											<input type="text" class="form-control col-3" placeholder="Дата подписания">
										</div>

										<div class="input-group">
											<div class="col-12">
												<span><input type="checkbox" checked> КАСКО</span>	
												<span><input type="checkbox" checked> СЖ</span>	
												<span><input type="checkbox"> GAP</span>	
												<span><input type="checkbox" checked> Продленка</span>	
											</div>
										</div>
									</div>

									<div class="mb-3">
										<div class="input-group">
											<span class="col-3">Консультант</span>
											<span class="col-3">Кредитор</span>
											<span class="col-3">Первый взнос</span>
											<span class="col-3">Сумма кредита</span>
										</div>

										<div class="input-group">
											<select class="col-3 form-control">
												<option>Вася</option>
												<option>Петя</option>
											</select>
											<select class="col-3 form-control">
												<option>РНБ</option>
												<option>Другой</option>
											</select>
											<input type="text" class="form-control col-3" placeholder="Первый взнос" value="450 000 р.">
											<input type="text" class="form-control col-3" value="578 980 р." disabled>
										</div>
										
										<div class="input-group">
											<span class="col-3">Дата заявки</span>
											<span class="col-3 text-success">06.01.2019</span>
											<span class="col-3">Срок действия</span>
											<span class="col-3">Дата подписания</span>
										</div>

										<div class="input-group">
											<input type="text" class="form-control col-3" placeholder="Дата" value="06.01.2019">
											<select class="col-3 form-control">
												<option>Одобрен</option>
											</select>
											<input type="text" class="form-control col-1" placeholder="Дни" value="100">
											<input type="text" class="form-control col-2" placeholder="Дата" value="16.04.2019">
											<input type="text" class="form-control col-3" placeholder="Дата подписания" value="07.01.2019">
										</div>

										<div class="input-group">
											<div class="col-12">
												<span><input type="checkbox" checked> КАСКО</span>	
												<span><input type="checkbox"> СЖ</span>	
												<span><input type="checkbox" checked> GAP</span>	
												<span><input type="checkbox"> Продленка</span>	
											</div>
										</div>
									</div>
								</div>

								<div class="input-group">
									<div class="col-12 d-flex align-items-center">
										<span class="flex-grow-1 font-weight-bold">Комиссионное вознаграждение</span>
										<span><b>42 500 р.</b> (7,3%)</span>
									</div>
								</div>

								<div class="input-group">
									<span class="col-3">Дата валидации</span>
									<span class="col-3">КВ за кузов</span>
									<span class="col-3">КВ за продукты</span>
									<span class="col-3">Прочее КВ</span>
								</div>

								<div class="input-group">
									<input type="text" class="col-3 form-control" placeholder="Дата">
									<input type="text" class="col-3 form-control" placeholder="Сумма, р." value="7 500 р.">
									<input type="text" class="col-3 form-control" placeholder="Сумма, р." value="35 000 р.">
									<input type="text" class="col-3 form-control" placeholder="Сумма, р.">
								</div>

							</div>
							<!-- 
							СТРАХОВАНИЕ И СЕРВИСЫ
							ЗАГОЛОВОК
							-->
							<div class="p-2 bg-info d-flex">
								<a class="text-white" data-toggle="collapse" href="#wsdesign4" aria-expanded="false" aria-controls="wsdesign4">Страхование и Сервисы</a>
							</div>
							<!-- 
							СТРАХОВАНИЕ И СЕРВИСЫ
							КОНТЕНТ
							-->
							<div id="wsdesign4" class="py-3 collapse">
								
								<div class="input-group">
									<span class="col-6 font-weight-bold">Выявленные потребности</span>
									<span class="col-3">Стоимость</span>
									<div class="col-3 d-flex">
										<span class="flex-grow-1">Возмещение</span>
										<a href="#"><i class="fas fa-comment-dots"></i></a>
									</div>
								</div>

								<div class="mb-3">
									<div class="input-group">
										<div class="col-6 d-flex align-items-center">
											<span class="flex-grow-1">
												<input type="checkbox"> Разумное КАСКО за 30 900 р.
											</span>
											<a href="#">
												<i class="fas fa-question-circle"></i>
											</a>
										</div>
										<input type="text" class="col-3 form-control" value="0 р." disabled>
										<input type="text" class="col-3 form-control" value="0 р." disabled>
									</div>

									<div class="input-group">
										<div class="col-6 d-flex align-items-center">
											<span class="flex-grow-1 text-success">
												<input type="checkbox" checked> Гарантия Renault Extra (5 лет)
											</span>
											<a href="#">
												<i class="fas fa-question-circle"></i>
											</a>
										</div>
										<input type="text" class="col-3 form-control" value="12 900 р.">
										<input type="text" class="col-3 form-control" value="0 р." disabled>
									</div>

									<div class="input-group">
										<div class="col-6 d-flex align-items-center">
											<span class="flex-grow-1 text-success">
												<input type="checkbox" checked> ОСАГО
											</span>
											<a href="#">
												<i class="fas fa-question-circle"></i>
											</a>
										</div>
										<input type="text" class="col-3 form-control" value="8 570 р.">
										<input type="text" class="col-3 form-control" value="0 р." disabled>
									</div>
								</div>

								<div class="input-group">
									<div class="col-12 d-flex">
										<span class="flex-grow-1 font-weight-bold">Работа с продуктами (2)</span>
										<a href="#"><i class="fas fa-plus-circle"></i></a>
									</div>
								</div>

								<div>
									<div class="mb-3">
										<div class="input-group">
											<span class="col-3">Консультант</span>
											<span class="col-3">Продукт</span>
											<span class="col-3">Партнер</span>
											<span class="col-3">Стоимость продукта</span>
										</div>

										<div class="input-group">
											<select class="col-3 form-control">
												<option>Вася</option>
												<option>Петя</option>
											</select>
											<select class="col-3 form-control">
												<option>Гарантия Renault Extra (5 лет)</option>
												<option>Другой</option>
											</select>
											<select class="col-3 form-control">
												<option>РЕНО РОССИЯ</option>
												<option>Другой</option>
											</select>
											<input type="text" class="form-control col-3" placeholder="Стоимость" value="12 900 р.">
										</div>
										
										<div class="input-group">
											<span class="col-3">Дата оформления</span>
											<span class="col-3">Дата окончания</span>
											<span class="col-3">КВ за продукт</span>
											<span class="col-3">Дата выплаты КВ</span>
										</div>

										<div class="input-group">
											<input type="text" class="form-control col-3" placeholder="Дата" value="25.01.2019">
											<input type="text" class="form-control col-1" placeholder="Мес." value="48">
											<input type="text" class="form-control col-2" placeholder="Дата" value="20.01.2023">
											<input type="text" class="form-control col-3" placeholder="Сумма, р." value="645 р.">
											<input type="text" class="form-control col-3" placeholder="Сумма, р.">
										</div>
									</div>

									<div class="mb-3">
										<div class="input-group">
											<span class="col-3">Консультант</span>
											<span class="col-3">Продукт</span>
											<span class="col-3">Партнер</span>
											<span class="col-3">Стоимость продукта</span>
										</div>

										<div class="input-group">
											<select class="col-3 form-control">
												<option>Вася</option>
												<option>Петя</option>
											</select>
											<select class="col-3 form-control">
												<option>ОСАГО</option>
												<option>Другой</option>
											</select>
											<select class="col-3 form-control">
												<option>Альфа Страх</option>
												<option>Другой</option>
											</select>
											<input type="text" class="form-control col-3" placeholder="Стоимость" value="8 570 р.">
										</div>
										
										<div class="input-group">
											<span class="col-3">Дата оформления</span>
											<span class="col-3">Дата окончания</span>
											<span class="col-3">КВ за продукт</span>
											<span class="col-3">Дата выплаты КВ</span>
										</div>

										<div class="input-group">
											<input type="text" class="form-control col-3" placeholder="Дата" value="25.01.2019">
											<input type="text" class="form-control col-1" placeholder="Мес." value="12">
											<input type="text" class="form-control col-2" placeholder="Дата" value="20.01.2020">
											<input type="text" class="form-control col-3" placeholder="Сумма, р." value="857 р.">
											<input type="text" class="form-control col-3" placeholder="Сумма, р." value="01.03.2019">
										</div>
									</div>

								</div>

								<div class="input-group mb-3">
									<a href="#" class="col-3">Анкета</a>
									<div class="col-6">
										Тут типа результат анкеты в звездочках от 1 до 10
									</div>
								</div>

								<div class="input-group">
									<a href="#" class="col-3">Скрипт VOC</a>
									<span class="col-3">Дата опроса</span>
									<span class="col-3">Решение РОП</span>
								</div>

								<div class="input-group align-items-center">
									<select class="col-3 form-control">
										<option class="text-success">Промоутер</option>
										<option>Не промоутер</option>
									</select>
									<input type="text" class="form-control col-3" placeholder="Дата">
									<select class="col-3 form-control">
										<option>Выбрать</option>
										<option>1</option>
										<option>2</option>
									</select>
									<a href="#" class="col-3">Подать апелляцию</a>
								</div>

							</div>
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

<form id="get-pdf" target="_blank" method="POST" action="/createoffer"></form>
@endsection