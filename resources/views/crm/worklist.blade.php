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

						<input name="traffic_type" type="text" class="col-3 form-control" value="-" disabled>
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
							<div id="wsparam1" class="collapse">
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
							<div class="collapse" id="wsparam2">
								<a href="#" class="text-primary"><i class="fas fa-plus-circle"></i> Добавить</a>

								<div class="input-group" id="carsByNeeds">
									<div class="col-3 border">
										<div class="text-right">
											<a href=""><i class="fas fa-trash-alt text-danger"></i></a>
										</div>
										<input type="text" class="form-control text-center" value="DUSTER" disabled>
										<select class="form-control">
											<option>Механическая</option>
											<option>Автомат</option>
										</select>
										<select class="form-control">
											<option>Полный (4WD)</option>
											<option>Половинчатый</option>
										</select>
									</div>
									<div class="col-3 border">
										<div class="text-right">
											<a href=""><i class="fas fa-trash-alt text-danger"></i></a>
										</div>
										<input type="text" class="form-control text-center" value="KAPTUR" disabled>
										<select class="form-control">
											<option>Выбрать</option>
										</select>
										<select class="form-control">
											<option>Выбрать</option>
										</select>
									</div>
								</div>
								<br>
								<div class="input-group">
									<div class="col-3">
										<span><input type="checkbox"> Оборудование</span><br>
										<span><input type="checkbox"> Оборудование</span><br>
										<span><input type="checkbox"> Оборудование</span><br>
										<span><input type="checkbox"> Оборудование</span><br>
										<span><input type="checkbox"> Оборудование</span><br>
									</div>
									<div class="col-3">
										<span><input type="checkbox"> Оборудование</span><br>
										<span><input type="checkbox"> Оборудование</span><br>
										<span><input type="checkbox"> Оборудование</span><br>
										<span><input type="checkbox"> Оборудование</span><br>
										<span><input type="checkbox"> Оборудование</span><br>
									</div>
									<div class="col-3">
										<span><input type="checkbox"> Оборудование</span><br>
										<span><input type="checkbox"> Оборудование</span><br>
										<span><input type="checkbox"> Оборудование</span><br>
										<span><input type="checkbox"> Оборудование</span><br>
										<span><input type="checkbox"> Оборудование</span><br>
									</div>
									<div class="col-3">
										<span><input type="checkbox"> Оборудование</span><br>
										<span><input type="checkbox"> Оборудование</span><br>
										<span><input type="checkbox"> Оборудование</span><br>
										<span><input type="checkbox"> Оборудование</span><br>
										<span><input type="checkbox"> Оборудование</span><br>
									</div>
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
									<input type="text" class="col-3 form-control" placeholder="Сумма, р.">
									<input type="text" class="col-3 form-control" placeholder="Сумма, р.">
								</div>
								<div class="input-group">
									<input type="button" class="col-3 offset-6 btn btn-outline-success" value="Показать варианты">
									<input type="button" class="col-3 btn btn-outline-primary" value="Резервировать">
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
							<div id="wsparam3" class="collapse">
								<div class="input-group">
									<div class="col-3"><label>Модель</label></div>
									<div class="col-6"><label>Комплектация</label></div>
								</div>
								<div class="input-group">
									<select class="col-3 form-control">
										<option>DUSTER</option>
									</select>
									<select class="col-6 form-control">
										<option>Privilege 2.0 (143) 4WD МКП</option>
										<option>Not Privilege 2.0 (143) 4WD МКП</option>
									</select>
									<div class="col-3 d-flex align-items-center justify-content-center">
										<a href="#" class="btn btn-light"><i class="fas fa-times"></i></a>
										<a href="#" class="btn btn-light"><i class="fas fa-plus-circle"></i></a>
									</div>
								</div>
								<hr>
								<div class="input-group">
									<!-- Левый блок -->
									<div class="col-6">
										<div style="width: 100%; height: 200px; background-color: #ddd;">
											Тут типа картинка машины
										</div>
										<div class="input-group d-flex justify-content-center">
											<button class="btn btn-primary">1</button>
											<button class="btn btn-light">2</button>
											<button class="btn btn-warning">3</button>
											<button class="btn btn-danger">4</button>
										</div>
										<hr>
										<div class="input-group no-gutters">
											<div class="col-12 font-weight-bold">Исполнение E2GB4AG</div>
											<p class="text-secondary">
												Двигатель бензиновый 16-клапанный<br>
												Рабочий объем 2.0 л. (143 л. с.)<br>
												КПП механическая, шестиступенчатая<br>
												Привод подключаемый, полный
											</p>
										</div>

									</div>
									<!-- Правый блок -->
									<div class="col-6">
										<div class="d-flex align-items-center justify-content-center">
											<p align="center" class="h5">
												Renault Duster<br>
												1 028 980 руб.<br>
												Прогноз 02.01.2019
											</p>
										</div>

										<div class="d-flex border-bottom">
											<label class="flex-grow-1 font-weight-bold">Комплектация Privilege</label>
											<a href="#">Подробнее</a>
										</div>
										<div class="h5 text-right">1 012 990 руб.</div>

										<div class="border-bottom font-weight-bold">
											Выберите опционное оборудование
										</div>
										<div class="input-group no-gutters">
											<div class="col-12">Система ЭРА-ГЛОНАСС</div>
											<div class="col-12 d-flex no-gutters">
												<div class="col-6">
													<input type="checkbox"> CALL1+TCU3G2
												</div>
												<div class="col-6 h5 text-right">
													11 990 руб.
												</div>
											</div>
										</div>
										<div class="input-group no-gutters">
											<div class="col-12">Окраска кузова металлик</div>
											<div class="col-12 d-flex no-gutters">
												<div class="col-6">
													<input type="checkbox" checked> PM
												</div>
												<div class="col-6 h5 text-right">
													15 990 руб.
												</div>
											</div>
										</div>
										<div class="input-group no-gutters">
											<div class="col-12">
												Пакет Безопасность 2
												<br>
												Подушки безопасности передние боковые | Подушка безопасности переднего пассажира
											</div>
											<div class="col-12 d-flex no-gutters">
												<div class="col-6">
													<input type="checkbox"> PKSECB
												</div>
												<div class="col-6 h5 text-right">
													16 990 руб.
												</div>
											</div>
										</div>
										
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
							<div id="wsparam4" class="collapse">
								<div class="input-group">
									<label class="col-3">Установлено</label>
									<label class="col-3">Предложено</label>
									<label class="col-3">Заказ-наряд</label>
								</div>

								<div class="input-group">
									<div class="col-3">
										<input type="text" class="form-control" value="21 500" disabled>
									</div>
									<div class="col-3">
										<input type="text" class="form-control" placeholder="Введи" value="25 000">
									</div>
									<div class="col-3">
										<input type="text" class="form-control" value="37 200" disabled>
									</div>
									<div class="col-3 bg-light d-flex align-items-center">
										<span><input type="checkbox"> Разделить в КП</span>
									</div>
								</div>
								
								<hr>

								<div class="input-group">
									<label class="col-12">Установленное оборудование:</label>
								</div>

								<div class="input-group">
									<span class="col-3"><input type="checkbox"> Оборудование</span>
									<span class="col-3"><input type="checkbox"> Оборудование</span>
									<span class="col-3"><input type="checkbox"> Оборудование</span>
									<span class="col-3"><input type="checkbox"> Оборудование</span>
									<span class="col-3"><input type="checkbox"> Оборудование</span>
									<span class="col-3"><input type="checkbox"> Оборудование</span>
								</div>

								<hr>

								<div class="input-group">
									<label class="col-9">Предложенное оборудование:</label>
									<a href="#" class="col-3 text-primary">Установить</a>
								</div>

								<div class="input-group">
									<span class="col-3"><input type="checkbox"> Оборудование</span>
									<span class="col-3"><input type="checkbox"> Оборудование</span>
									<span class="col-3"><input type="checkbox"> Оборудование</span>
									<span class="col-3"><input type="checkbox"> Оборудование</span>
									<span class="col-3"><input type="checkbox"> Оборудование</span>
									<span class="col-3"><input type="checkbox"> Оборудование</span>
									<span class="col-3"><input type="checkbox"> Оборудование</span>
									<span class="col-3"><input type="checkbox"> Оборудование</span>
									<span class="col-3"><input type="checkbox"> Оборудование</span>
									<span class="col-3"><input type="checkbox"> Оборудование</span>
									<span class="col-3"><input type="checkbox"> Оборудование</span>
									<span class="col-3"><input type="checkbox"> Оборудование</span>
								</div>

							</div>
							<!-- 
							АВТОМОБИЛЬ КЛИЕНТА
							ЗАГОЛОВОК 
							-->
							<div class="p-2 bg-info d-flex">
								<span class="flex-grow-1">
									<a class="text-white" data-toggle="collapse" href="#wsparam5" aria-expanded="false" aria-controls="wsparam5">Автомобиль клиента</a>
								</span>
								<span><i class="fas fa-circle text-danger"></i></span>
							</div>
							<!-- 
							АВТОМОБИЛЬ КЛИЕНТА
							КОНТЕНТ
							-->
							<div id="wsparam5" class="collapse">
								<div class="input-group">
									<div class="col-3"><input type="checkbox"> Нет авто</div>
									<div class="col-3"><input type="checkbox"> Есть авто</div>
									<div class="col-3"><input type="checkbox"> Трейд-ин</div>
									<div class="col-3"><input type="checkbox"> Утилизация</div>
								</div>
								<div class="input-group">
									<input type="text" class="col-3 form-control" placeholder="Марка">
									<input type="text" class="col-3 form-control" placeholder="Модель">
									<input type="text" class="col-3 form-control" placeholder="Год по ПТС">
									<input type="text" class="col-3 form-control" placeholder="Пробег">
								</div>
								<hr>

								<div class="input-group">
									<label class="col-3">Владельцы</label>
									<label class="col-3">Оценка клиента</label>
								</div>
								<div class="input-group">
									<input type="text" class="col-3 form-control" placeholder="Количество" value="2">
									<input type="text" class="col-3 form-control" placeholder="Руб.">
									<button type="button" class="col-3 btn btn-success">Фотографии</button>
									<button type="button" class="col-3 btn btn-primary">Отдать на оценку</button>
								</div>
								<br>
								
								<div class="input-group">
									<div class="col-3 offset-3 text-center">
										<label>Анализ рынка</label>
									</div>
									<div class="col-3 text-center">
										<label>Акт осмотра</label>
									</div>
									<div class="col-3 text-center">
										<label>Акт диагностики</label>
									</div>
								</div>
								<div id="CLIENT_AUTO_NESKOLKO_MASHIN_ANALIZ_VSE_TAKOE">
									<div id="ANALIZ_111" class="border p-1">
										<div class="input-group">
											<div class="col-3 d-flex align-items-center justify-content-end">
												<a href="#"><i class="fas fa-times"></i></a>
											</div>
											<div class="col-3 text-center">
												<a href="#" class="btn btn-primary"><i class="fas fa-clipboard-list"></i></a>
											</div>
											<div class="col-3 text-center">
												<a href="#" class="btn btn-primary"><i class="fas fa-clipboard-list"></i></a>
											</div>
											<div class="col-3 text-center">
												<a href="#" class="btn btn-primary disabled"><i class="fas fa-clipboard-list"></i></a>
											</div>
										</div>
										<div class="input-group">
											<input type="text" class="col-3 form-control text-center" value="DUSTER" disabled>
											<input type="text" class="col-3 form-control-plaintext text-center" value="350 000 руб." disabled>
											<input type="text" class="col-3 form-control-plaintext text-center" value="345 000 руб." disabled>
											<input type="text" class="col-3 form-control-plaintext text-center text-secondary" value="Сумма, руб." disabled>
										</div>
									</div>
									<div id="ANALIZ_222" class="border p-1">
										<div class="input-group">
											<div class="col-3 d-flex align-items-center justify-content-end">
												<a href="#"><i class="fas fa-times"></i></a>
											</div>
											<div class="col-3 text-center">
												<a href="#" class="btn btn-primary disabled"><i class="fas fa-clipboard-list"></i></a>
											</div>
											<div class="col-3 text-center">
												<a href="#" class="btn btn-primary disabled"><i class="fas fa-clipboard-list"></i></a>
											</div>
											<div class="col-3 text-center">
												<a href="#" class="btn btn-primary disabled"><i class="fas fa-clipboard-list"></i></a>
											</div>
										</div>
										<div class="input-group">
											<input type="text" class="col-3 form-control text-center" value="KAPTUR" disabled>
											<input type="text" class="col-3 form-control-plaintext text-center text-secondary" value="Сумма, руб." disabled>
											<input type="text" class="col-3 form-control-plaintext text-center text-secondary" value="Сумма, руб." disabled>
											<input type="text" class="col-3 form-control-plaintext text-center text-secondary" value="Сумма, руб." disabled>
										</div>
									</div>
								</div>

							</div>							
							<!-- 
							ПРОГРАММА ЛОЯЛЬНОСТИ
							ЗАГОЛОВОК
							-->
							<div class="p-2 bg-info d-flex">
								<span class="flex-grow-1">
									<a class="text-white" data-toggle="collapse" href="#wsparam6" aria-expanded="false" aria-controls="wsparam6">Программа лояльности</a>
								</span>
								<span><i class="fas fa-circle text-success"></i></span>
							</div>
							<!-- 
							ПРОГРАММА ЛОЯЛЬНОСТИ
							КОНТЕНТ
							-->
							<div id="wsparam6" class="collapse">
								<label class="font-weight-bold">Сервисы:</label>
								
								<div class="input-group">
									<span class="col-9"><input type="checkbox" checked> Гарантия Renault Extra (4 года)</span>
									<input type="text" class="col-3 form-control" value="+ 12 900 руб.">
								</div>
								<div class="input-group">
									<span class="col-9"><input type="checkbox"> Гарантия Renault Extra (5 лет)</span>
									<input type="text" class="col-3 form-control" value="+ 19 900 руб.">
								</div>
								<div class="input-group">
									<span class="col-9"><input type="checkbox"> Комплимент за Тест-драйв</span>
									<button type="button" class="col-3 btn btn-outline-success">Сертификат</button>
								</div>
								<div class="input-group">
									<span class="col-9"><input type="checkbox" checked> Оплата дороги до автосалона</span>
									<input type="text" class="col-3 form-control" value="-650 руб.">
								</div>
								
								<label class="font-weight-bold">Акции:</label>
								
								<div class="input-group">
									<span class="col-9"><input type="checkbox" checked> Проживание в гостиннице</span>
									<button type="button" class="col-3 btn btn-outline-success">Направление</button>
								</div>
								<div class="input-group">
									<span class="col-9"><input type="checkbox"> Подарок постоянному клиенту</span>
								</div>

								<label class="font-weight-bold">Скидки:</label>
								
								<div class="input-group">
									<span class="col-9"><input type="checkbox" checked> Скидка на аксессуары</span>
									<span class="col-3 d-flex align-items-center">-8500 р.</span>
								</div>
								<div class="input-group">
									<span class="col-9"><input type="checkbox" checked> Выгода по Трейд-ин 90 000 руб.</span>
									<span class="col-3 d-flex align-items-center">-90 000</span>
								</div>

								<label class="font-weight-bold">Подарки:</label>

								<div class="input-group">
									<span class="col-9"><input type="checkbox" checked> Дорожный набор, защитная сетка</span>
								</div>
								<div class="input-group">
									<span class="col-3 offset-6 font-weight-bold">Итого сумма скидок:</span>
									<span class="col-3 font-weight-bold">99 150 р.</span>
								</div>
							</div>
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
							<div id="wsparam7" class="collapse">
								<table class="table table-bordered table-sm table-striped" style="table-layout: fixed;" width="100%">
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
								<hr>
								<div class="input-group">
									<div class="col-3 d-flex align-items-center"><span><input type="checkbox"> My Renault</span></div>
									<div class="col-6">
										<button type="button" class="btn btn-outline-success btn-block">Создать Коммерческое предложение</button>
									</div>
								</div>
							</div>
						</div>
						<!-- Рабочий лист Вкладка  Комментарии -->
						<div class="tab-pane" id="worksheet-comments" role="tabpanel" aria-labelledby="worksheet-comments-tab">
							<p class="h3">комментарии</p>
						</div>
						<!-- Рабочий лист Вкладка  Автомобиль -->
						<div class="tab-pane" id="worksheet-auto" role="tabpanel" aria-labelledby="worksheet-auto-tab">
							
							<div class="input-group d-flex justify-content-center">
								<div style="width: 300px; height: 200px; background-color: #eee;" class="d-flex align-items-center justify-content-center">
									Типа картинка с машиной
								</div>
							</div>

							<div class="input-group border-bottom">
								<div class="col-4 text-secondary">
									X7LHSRGA561473715
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
									Renault Duster<br>
									Expression
								</div>
								<div class="col-4 h5">
									А/м в наличии<br>
									Склад Овен-авто
								</div>
								<div class="col-4 h5">
									910 000 руб.<br>
									<span class="text-warning">- 20 000 руб.</span>
								</div>
							</div>

							<div class="input-group">
								<div class="col-4">
									<div>
										<label>Исполнение E1FB2JA</label>
										<ul class="list-unstyled text-secondary">
											<li>Двигатель бензиновый 16-клапанный</li>
											<li>Рабочий объем 1.6 л. (114 л. с.)</li>
											<li>КПП механическая, пятиступенчатая</li>
											<li>Привод передний</li>
										</ul>
									</div>
									<div>
										<label>Комплектация Expression</label>
										<ul class="list-unstyled text-secondary">
											<li>Бамперы, окрашенные в цвет кузова</li>
											<li>Брызговики задние</li>
											<li>Брызговики передние</li>
											<li>Диски колес R16 штампованные стальные</li>
											<li>Защита картера двигателя</li>
										</ul>
									</div>
								</div>
								<div class="col-4">
									<ul class="list-unstyled text-secondary border-bottom">
										<li>Адаптация к запуску в холодном климате</li>
										<li>Аудиосистема (FM, USB, AUX, Bluetooth)</li>
										<li>Зеркала заднего вида с электроприводом и электроподогревом</li>
										<li>Иммобилайзер</li>
										<li>Рулевая колонка с регулировкой по высоте</li>
										<li>Рулевая колонка с усилителем управления</li>
										<li>Сиденье заднее с раздельной спинкой</li>
										<li>Стеклоподъемники передние с электроприводом</li>
										<li>Центральный замок с дистанционным управлением</li>
										<li>Антиблокировочная система тормозов (ABS)</li>
									</ul>
									<div class="text-right h5 text-secondary">
										849 990 руб.
									</div>
								</div>
								<div class="col-4">
									
									<label>Цвет автомобиля</label>
									<div class="input-group text-secondary no-gutters">
										<div class="col-12 border-bottom border-warning">
											Темный каштан
										</div>
										<div class="col-12 d-flex">
											<div class="flex-grow-1">
												D17
											</div>
											<div>
												Кружок с цветом
											</div>
										</div>
									</div>

									<label>Опционное оборудование</label>
									<div class="input-group text-secondary no-gutters">
										<div class="col-12 border-bottom">
											Окраска кузова металлик
										</div>
										<div class="col-12 d-flex">
											<div class="flex-grow-1">
												PM
											</div>
											<div class="h5">
												15 990 руб.
											</div>
										</div>
									</div>

									<label>Дополнительное оборудование</label>
									<ul class="list-unstyled text-secondary border-bottom">
										<li>Защитная сетка на решетку радиатора</li>
										<li>Коврики салона резиновые</li>
										<li>Коврик багажника резиновый</li>
									</ul>

									<div class="text-right h5 text-secondary">
										44 120 руб.
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
								<span class="flex-grow-1">
									<a class="text-white" data-toggle="collapse" href="#wsdesign1" aria-expanded="false" aria-controls="wsdesign1">Платежи</a>
								</span>
							</div>
							<!-- 
							ПЛАТЕЖИ
							КОНТЕНТ
							-->
							<div id="wsdesign1" class="collapse">
								<div class="input-group">
									<div class="col-3"><label>Сумма платежа</label></div>
									<div class="col-6"><label>Дата платежа</label></div>
									<div class="col-3"><label>Сумма остатка</label></div>
								</div>
								<div class="input-group">
									<input type="text" class="form-control col-3" placeholder="Сумма платежа" value="10 000">
									<input type="date" class="form-control col-3">
									<div class="col-3 d-flex align-items-center">
										<span><input type="checkbox"> Оплачено</span>
									</div>
									<div class="col-3 bg-light d-flex align-items-center">
										<span class="text-danger">690 000</span>
									</div>
								</div>
								<div class="input-group">
									<input type="text" class="form-control col-3" placeholder="Сумма платежа" value="240 000">
									<input type="date" class="form-control col-3">
									<div class="col-3 d-flex align-items-center">
										<span><input type="checkbox"> Оплачено</span>
									</div>
									<div class="col-3 bg-light d-flex align-items-center">
										<span class="text-danger">450 000</span>
									</div>
								</div>
							</div>
							<!-- 
							ОФОРМЛЕНИЕ
							ЗАГОЛОВОК
							-->
							<div class="p-2 bg-info d-flex">
								<span class="flex-grow-1">
									<a class="text-white" data-toggle="collapse" href="#wsdesign2" aria-expanded="false" aria-controls="wsdesign2">Оформление</a>
								</span>
								<span class="col-2"><a href="#" class="text-white">Обновить</a></span>
							</div>
							<!-- 
							ОФОРМЛЕНИЕ
							КОНТЕНТ
							-->
							<div id="wsdesign2" class="collapse">
								<div class="input-group">
									<div class="col-3"><label>Номер договора</label></div>
									<div class="col-3"><label>Дата договора</label></div>
									<div class="col-3"><label>Дата поставки</label></div>
									<div class="col-3"><label>Дата оплаты</label></div>
								</div>
								<div class="input-group">
									<input type="text" class="form-control col-3" placeholder="Дата договора" value="ДПс00123">
									<input type="date" class="form-control col-3">
									<input type="date" class="form-control col-3">
									<input type="date" class="form-control col-3">
								</div>
								<hr>
								<div class="input-group">
									<div class="col-3"><label>Дата выдачи</label></div>
									<div class="col-3"><label>Оформитель</label></div>
									<div class="col-3"><label>Дата продажи</label></div>
									<div class="col-3"><label>Дата рождения</label></div>
								</div>
								<div class="input-group">
									<input type="date" class="form-control col-3">
									<select class="form-control col-3">
										<option>Выбрать</option>
										<option>Вася</option>
										<option>Петя</option>
									</select>
									<input type="date" class="form-control col-3">
									<input type="date" class="form-control col-3">
								</div>
								<hr>
								<div class="input-group">
									<div class="col-3"><label>Анкета</label></div>
									<div class="col-6">
										<div class="progress">
											<div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</div>
								</div>
								<hr>
								<div class="input-group">
									<div class="col-3"><label>Скрипт VOC</label></div>
									<div class="col-3"><label>Дата опроса</label></div>
									<div class="col-6"><label>Решение РОП</label></div>
								</div>
								<div class="input-group">
									<select class="form-control col-3">
										<option>Промоутер</option>
										<option>2</option>
										<option>3</option>
									</select>
									<input type="date" class="form-control col-3">
									<select class="form-control col-3">
										<option>Выбрать</option>
										<option>Решение 1</option>
										<option>Решение 2</option>
									</select>
									<div class="col-3 d-flex align-items-center justify-content-center">
										<a href="#">Подать апелляцию</a>
									</div>
								</div>
							</div>
							<!-- 
							КРЕДИТОВАНИЕ И ЛИЗИНГ
							ЗАГОЛОВОК
							-->
							<div class="p-2 bg-info d-flex">
								<span class="flex-grow-1">
									<a class="text-white" data-toggle="collapse" href="#wsdesign3" aria-expanded="false" aria-controls="wsdesign3">Кредитование и лизинг</a>
								</span>
								<span class="col-1"><a href=""><i class="fas fa-plus-circle text-white"></i></a></span>
								<span class="col-2"><a href="#" class="text-white">Обновить</a></span>
							</div>
							<!-- 
							КРЕДИТОВАНИЕ И ЛИЗИНГ
							КОНТЕНТ
							-->
							<div id="wsdesign3" class="collapse">
								<div class="input-group">
									<div class="col-3"><label>Консультант</label></div>
									<div class="col-3"><label>Кредитор</label></div>
									<div class="col-3"><label>Первый взнос</label></div>
									<div class="col-3"><label>Сумма кредита</label></div>
								</div>
								<div class="input-group">
									<select class="col-3 form-control">
										<option>Пупкин</option>
										<option>Сидоров</option>
									</select>
									<select class="col-3 form-control">
										<option>РНБ</option>
										<option>Другой</option>
									</select>
									<input type="text" class="form-control col-3" placeholder="Первый взнос" value="250 000">
									<div class="col-3 bg-light d-flex align-items-center">
										<span class="text-danger">400 000</span>
									</div>
								</div>
								<hr>
								<div class="input-group">
									<div class="col-3"><label>Дата заявки</label></div>
									<div class="col-3"><label>Дата решения</label></div>
									<div class="col-3"><label>Решение</label></div>
									<div class="col-3"><label>Срок действия</label></div>
								</div>
								<div class="input-group">
									<input type="date" class="form-control col-3">
									<input type="date" class="form-control col-3">
									<select class="col-3 form-control">
										<option>Одобрен</option>
										<option>Не одобрен</option>
									</select>
									<input type="date" class="form-control col-3">
								</div>
								<hr>
								<div class="input-group">
									<div class="col-3"><label>Субсидия</label></div>
									<div class="col-3"><label>Дата подписания</label></div>
									<div class="col-3"><label>Комиссия</label></div>
									<div class="col-3"><label>Дата оплаты КВ</label></div>
								</div>
								<div class="input-group">
									<input type="text" class="form-control col-3" placeholder="Сумма, р." value="50 000">
									<input type="date" class="form-control col-3">
									<input type="text" class="form-control col-3" placeholder="Сумма, р.">
									<input type="date" class="form-control col-3">
								</div>
							</div>
							<!-- 
							СТРАХОВАНИЕ И СЕРВИСЫ
							ЗАГОЛОВОК
							-->
							<div class="p-2 bg-info d-flex">
								<span class="flex-grow-1">
									<a class="text-white" data-toggle="collapse" href="#wsdesign4" aria-expanded="false" aria-controls="wsdesign4">Страхование и сервисы</a>
								</span>
								<span class="col-1"><a href=""><i class="fas fa-plus-circle text-white"></i></a></span>
								<span class="col-2"><a href="#" class="text-white">Обновить</a></span>
							</div>
							<!-- 
							СТРАХОВАНИЕ И СЕРВИСЫ
							КОНТЕНТ
							-->
							<div id="wsdesign4" class="collapse">
								<div class="input-group">
									<div class="col-3"><label>Полис ОСАГО</label></div>
									<div class="col-3"><label>Полис КАСКО</label></div>
									<div class="col-3"><label>Полис СЖ</label></div>
									<div class="col-3"><label>Renault Extra</label></div>
								</div>
								<div class="input-group">
									<input type="text" class="form-control col-3" placeholder="Сумма, р.">
									<input type="text" class="form-control col-3" placeholder="Сумма, р.">
									<input type="text" class="form-control col-3" placeholder="Сумма, р.">
									<input type="text" class="form-control col-3" placeholder="Сумма, р.">
								</div>
								<div class="input-group">
									<input type="date" class="form-control col-3">
									<input type="date" class="form-control col-3">
									<input type="date" class="form-control col-3">
									<input type="date" class="form-control col-3">
								</div>
								<div class="input-group">
									<input type="text" class="form-control col-3" placeholder="Комиссия, р.">
									<input type="text" class="form-control col-3" placeholder="Комиссия, р.">
									<input type="text" class="form-control col-3" placeholder="Комиссия, р.">
									<input type="text" class="form-control col-3" placeholder="Комиссия, р.">
								</div>
								<div class="input-group">
									<input type="date" class="form-control col-3">
									<input type="date" class="form-control col-3">
									<input type="date" class="form-control col-3">
									<input type="date" class="form-control col-3">
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
@endsection