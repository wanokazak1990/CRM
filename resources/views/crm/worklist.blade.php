@section('worklist')
<div id="hidden_panel" class="container border-left border-info">
<div class="row">
	<!-- Nav tabs -->
	<ul class="nav nav-tabs nav-justified bg-info" id="hiddenTab" role="tablist" style="width: 100%;">
		<li class="nav-item">
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

	<!-- Icons panel -->
	<div class="border" style="width: 100%;">
		<button id="closing" type="button" class="btn btn-light"><i class="fas fa-arrow-circle-right"></i></button>			
	</div>

	<!-- Tab panes -->
	<div class="tab-content container" style="width: 100%; padding-left: 0; padding-right: 0;">
		<!-- Вкладка Журнал -->
		<div class="tab-pane active" id="log" role="tabpanel" aria-labelledby="log-tab">
			<table class="table table-bordered table-hover table-sm">
				<thead>
					<tr>
						<th>Дата</th>
						<th>Время</th>
						<th>Тип</th>
						<th>Действие</th>
						<th>Клиент</th>
						<th>Автомобиль</th>
						<th>Продавец</th>
					</tr>
				</thead>
				<tbody>
					@foreach($traffics as $traffic)
					<tr>
						<td>{{ date('d.m.Y', $traffic->action_date) }}</td>
						<td>{{ date('H:i:s', $traffic->action_time) }}</td>
						<td>{{ $traffic->traffic_type->name }}</td>
						<td>{{ $traffic->assigned_action->name }}</td>
						<td>{{ $traffic->client->name }}</td>
						<td>{{ $traffic->model->name }}</td>
						<td>{{ $traffic->manager->name }}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		<!-- Вкладка Лист трафика -->
		<div class="tab-pane" id="trafficList" role="tabpanel" aria-labelledby="trafficList-tab">
			<div class="">
				<span class="h3">Трафик №138</span>
				<span class="h3" style="float: right;">05 января 2018</span>
			</div>
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

			<span class="h4">Назначенный менеджер</span>
			<div class="input-group btn-group-toggle" data-toggle="buttons">
				@foreach($users as $key => $user)
				<div class="col-3 btn btn-outline-info"><input type="radio" name="manager" value="{{ $key }}" autocomplete="off"> {{ $user }}</div>
				@endforeach
			</div>
			<hr>

			<span class="h4">Адрес клиента</span>
			<div class="input-group btn-group-toggle" data-toggle="buttons">
				<div class="col-3 btn btn-outline-info"><input type="radio" name="client_address" autocomplete="off" value="Неизвестно"> Неизвестно</div>
				<div class="col-3 btn btn-outline-info"><input type="radio" name="client_address" autocomplete="off" value="Сыктывкар"> Сыктывкар</div>
				<div class="col-3 btn btn-outline-info"><input type="radio" name="client_address" autocomplete="off" value="Республика"> Республика</div>
				<div class="col-3 btn btn-outline-info"><input type="radio" name="client_address" autocomplete="off" value="Др. регион"> Др. регион</div>
			</div>
			<hr>
			
			<span class="h4">Данные клиента</span>
			<div>
				<div class="input-group">
					<input type="text" name="client_name" class="col-6 form-control" placeholder="ФИО">
					<input type="text" name="client_phone" class="col-3 form-control" placeholder="Телефон">
					<input type="text" name="client_email" class="col-3 form-control" placeholder="Email">
				</div>
				<div class="input-group">
					<select name="assigned_action" class="form-control col-6">
						<option selected disabled>Назначенное действие</option>
						@foreach($assigned_actions as $action)
						<option value="{{ $action->id }}"> {{ $action->name }} </option>
						@endforeach
					</select>
					<input name="action_date" type="date" class="col-3 form-control" title="Назначенная дата">
					<input name="action_time" type="time" class="col-3 form-control" title="Назначенное время">
				</div>
			</div>
			<hr>

			<span class="h4">Ваши комментарии</span>
			<textarea name="comment" class="form-control" cols="3" style="resize: none;" placeholder="Введите комментарий"></textarea>
			<hr>

			<div class="input-group">
				<div class="col-6">
					<input type="submit" name="traffic_submit" class="btn btn-outline-info btn-block" value="Создать трафик">
				</div>
				<div class="col-6">
					<button type="button" class="btn btn-outline-info btn-block">Создать трафик и рабочий лист</button>
				</div>
			</div>
			{!! Form::close() !!}
		</div>
		
		<!-- Вкладка Рабочий лист -->
		<div class="tab-pane" id="worksheet" role="tabpanel" aria-labelledby="worksheet-tab">
			<div>
				<span class="h3">Рабочий лист №138</span>
				<span class="h3" style="float: right;">05 января 2018</span>
			</div>
			<!-- Блок с фоткой слева и инпутами справа -->
			<div class="row" style="width: 100%;">
				<div class="col-4" align="center">
					<img src="/face.png">
					<div style="width: 100%;">
						<button type="button" class="btn btn-light"><i class="fas fa-camera"></i></button>
						<button type="button" class="btn btn-light"><i class="fas fa-folder-open"></i></button>
						<button type="button" class="btn btn-light"><i class="fas fa-search"></i></button>
						<button type="button" class="btn btn-light"><i class="fas fa-caret-square-up"></i></button>
						<button type="button" class="btn btn-light"><i class="fas fa-trash-alt"></i></button>
					</div>
				</div>
				<div class="col-8">
					<div class="input-group">
						<select class="form-control">
							<option>Быков</option>
							<option>Не Быков</option>
						</select>
						<select class="form-control">
							<option>Входящий звонок</option>
							<option>Не входящий звонок</option>
						</select>
					</div>
					<div class="input-group">
						<input type="text" class="form-control" placeholder="Клиент">
						<select class="form-control">
							<option>Физ</option>
							<option>Юр</option>
						</select>
					</div>
					<div class="input-group">
						<input type="text" class="form-control" placeholder="Телефон">
						<input type="text" class="form-control" placeholder="Почта">
					</div>
					<div class="input-group">
						<select class="form-control">
							<option>Республика</option>
							<option>Коми</option>
							<option>Запределье</option>
						</select>
						<input type="text" class="form-control" placeholder="Уточните адрес">
					</div>
					<div class="input-group">
						<select class="form-control">
							<option>Следующее событие</option>
							<option>Покупка колбасы</option>
							<option>Уничтожение колбасы</option>
						</select>
						<input class="form-control" type="date">
						<input class="form-control" type="text" placeholder="Время">
					</div>
				</div>
			</div>

			<!-- Блок с вкладками в рабочем листе -->
			<div class="container">
				<div class="row">
					<!-- nav tabs -->
					<ul class="nav nav-tabs nav-justified" id="worksheetTabs" role="tablist" style="width: 100%;">
						<li class="nav-item">
							<a class="nav-link active" id="worksheet-parameters-tab" data-toggle="tab" href="#worksheet-parameters" role="tab" aria-controls="worksheet-parameters" aria-selected="true">Параметры</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="worksheet-design-tab" data-toggle="tab" href="#worksheet-design" role="tab" aria-controls="worksheet-design" aria-selected="true">Оформление</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="worksheet-comments-tab" data-toggle="tab" href="#worksheet-comments" role="tab" aria-controls="worksheet-comments" aria-selected="true">Комментарии</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="worksheet-auto-tab" data-toggle="tab" href="#worksheet-auto" role="tab" aria-controls="worksheet-auto" aria-selected="true">Автомобиль</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="worksheet-funnel-tab" data-toggle="tab" href="#worksheet-funnel" role="tab" aria-controls="worksheet-funnel" aria-selected="true">Воронка РЛ</a>
						</li>
					</ul>
					<!-- Tab panes -->
					<div class="tab-content" style="width: 100%;">
						<!-- Рабочий лист Вкладка Параметры -->
						<div class="tab-pane active" id="worksheet-parameters" role="tabpanel" aria-labelledby="worksheet-parameters-tab">
							<div class="p-2 bg-info">
								<span class="text-white">Пробная поездка</span>
								<span style="float: right;">Прогресс 0%</span>
							</div>
							<div class="" style="width: 100%;">
								<div class="input-group">
									<div class="col-6">
										<label>Паспорт (серия, номер, дата выдачи)</label>
									</div>
									<div class="col-offset-6">
										<label>Водительское удостоверение</label>
									</div>
								</div>
								<div class="input-group">
									<input type="text" class="col-3 form-control" placeholder="Серия, номер">
									<input type="text" class="col-3 form-control" placeholder="Ввести дату">
									<input type="text" class="col-3 form-control" placeholder="Номер">
									<input type="text" class="col-3 form-control" placeholder="Ввести дату">
								</div>
								<hr>
								<div class="input-group">
									<div class="col-3">
										<label>Дата рождения</label>
									</div>
									<div class="col-9">
										<label>Автомобиль на тест-драйв</label>
									</div>
								</div>
								<div class="input-group">
									<input type="text" class="col-3 form-control" placeholder="Ввести дату">
									<select class="form-control col-6">
										<option>Выбрать</option>
										<option>Какой-либо пункт</option>
									</select>
									<div class="col-3 d-flex align-items-center justify-content-center">
										<a href="#">Распечатать</a>
									</div>
								</div>
							</div>
							<br>
							<div class="p-2 bg-info">
								<span class="text-white">Потребности Клиента</span>
								<span style="float: right;">Прогресс 25%</span>
							</div>
							<div>
								<div class="input-group">
									<div class="col-3">
										<label>Модель</label>
									</div>
									<div class="col-3">
										<label>Бюджет покупки</label>
									</div>
									<div class="col-3">
										<label>Форма покупки</label>
									</div>
									<div class="col-3">
										<label>Сроки покупки</label>
									</div>
								</div>
								<div class="input-group">
									<select class="form-control col-3">
										<option>DUSTER</option>
										<option>Еще какой-нибудь</option>
									</select>
									<input type="text" class="col-3 form-control" placeholder="Сумма, р.">
									<select class="form-control col-3">
										<option>Выбрать</option>
										<option>Вот такая</option>
									</select>
									<input type="text" class="col-3 form-control" placeholder="Ввести дату">
								</div>
							</div>
							<br>
							<div class="p-2 bg-info">
								<span class="text-white">Подбор автомобиля</span>
								<span style="float: right;">Прогресс 75%</span>
							</div>
							<div>
								<div class="input-group">
									<div class="col-3">
										<label>КПП</label>
									</div>
									<div class="col-3">
										<label>Привод</label>
									</div>
									<div class="col-6">
										<label>Этап поставки</label>
									</div>
								</div>
								<div class="input-group">
									<select class="form-control col-3">
										<option>Механическая</option>
										<option>Автомат</option>
									</select>
									<select class="form-control col-3">
										<option>Полный</option>
										<option>Передний</option>
										<option>Задний</option>
									</select>
									<select class="form-control col-3">
										<option>Выбрать</option>
										<option>Один из этапов</option>
									</select>
									<div class="col-3 d-flex align-items-center justify-content-center">
										<a href="#">Подобрать (5)</a>
									</div>
								</div>
							</div>
							<hr>
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
							<div class="p-2 bg-info input-group">
								<span class="col-6 text-white">Конфигуратор</span>
								<a href="#" class="col-3 text-white">Очистить</a>
								<a href="#" class="col-3 text-white">Создать заявку</a>
							</div>
							<div>
								<div class="input-group">
									<div class="col-6"><label>Комплектация</label></div>
									<div class="col-6"><label>Окраска кузова</label></div>
								</div>
								<div class="input-group">
									<select class="col-6 form-control">
										<option>Privilege 2.0 (143) 4WD МКП</option>
										<option>Not Privilege 2.0 (143) 4WD МКП</option>
									</select>
									<select class="col-3 form-control">
										<option>Выбрать</option>
										<option>Цвет кузова сииииний</option>
									</select>
									<div class="col-3 bg-light d-flex align-items-center justify-content-center">1 499 990 р.</div>
								</div>
								<hr>
								<div>
									<p class="h5">Система ЭРА-ГЛОНАСС</p>
									<div class="input-group">
										<span class="col-10"><input type="checkbox"></span>
										<span class="col-2">11 590 р.</span>
									</div>
								</div>
								<hr>
								<div>
									<p class="h5">Отделка сидений кожей</p>
									<div class="input-group">
										<span class="col-10"><input type="checkbox"></span>
										<span class="col-2">35 990 р.</span>
									</div>
								</div>
								<hr>
								<div>
									<p class="h5">Пакет Orange Style</p>
									<p>Комплект персонализации Orange (сиденья с текстильными оранжевыми вставками, оранжевая окантовка центральной консоли, коврики со светоотражающей оранжевой окантовкой)</p>
									<div class="input-group">
										<span class="col-10"><input type="checkbox"></span>
										<span class="col-2">13 990 р.</span>
									</div>
								</div>
							</div>

							<div class="p-2 bg-info">
								<span class="text-white">Дополнительное оборудование</span>
								<span style="float: right;">Прогресс 100%</span>
							</div>
							<p class="alert alert-success">Коврики салона, Коврик багажника, Автосигнализация, Зимняя резина, Колесные диски, Дорожный набор, Подкрылки, Фаркоп, Дефлекторы окон, Защита радиатора.</p>
							<div class="input-group">
								<div class="col-3"><label>Заказ-наряд</label></div>
								<div class="col-3"><label>Виртуальный з/н</label></div>
								<div class="col-3"><label><input type="checkbox"> Скидка на з/н</label></div>
								<div class="col-3"><label>ИТОГО з/н</label></div>
							</div>
							<div class="input-group">
								<div class="col-3 bg-light d-flex align-items-center">21 500</div>
								<div class="col-3">
									<input type="text" class="form-control" placeholder="Введи" value="25 000">
								</div>
								<div class="col-3">
									<input type="text" class="form-control" placeholder="Введи" value="9 300">
								</div>
								<div class="col-3 bg-light d-flex align-items-center">37 200</div>
							</div>
							<hr>
							<div class="input-group">
								<div class="col-3">
									<span><input type="checkbox"> Оборудование</span><br>
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
									<span><input type="checkbox"> Оборудование</span><br>
								</div>
								<div class="col-3">
									<span><input type="checkbox"> Оборудование</span><br>
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
									<span><input type="checkbox"> Оборудование</span><br>
								</div>
							</div>
							<br>
							<!-- 
							АВТОМОБИЛЬ КЛИЕНТА
							ЗАГОЛОВОК 
							-->
							<div class="p-2 bg-info">
								<span class="text-white">Автомобиль клиента</span>
								<span style="float: right;">Прогресс 0%</span>
							</div>
							<!-- 
							АВТОМОБИЛЬ КЛИЕНТА
							КОНТЕНТ
							-->
							<div>
								<div class="input-group">
									<div class="col-6"><input type="checkbox"> Нет автомобиля</div>
									<div class="col-3"><input type="checkbox"> Утилизация</div>
									<div class="col-3"><input type="checkbox"> Трейд-ин</div>
								</div>
								<hr>
								<div class="input-group">
									<div class="col-6" align="center">
										<img src="/cars.png">
										<div>
											<button type="button" class="btn btn-light"><i class="fas fa-camera"></i></button>
											<button type="button" class="btn btn-light"><i class="fas fa-folder-open"></i></button>
											<button type="button" class="btn btn-light"><i class="fas fa-search"></i></button>
											<button type="button" class="btn btn-light"><i class="fas fa-caret-square-up"></i></button>
											<button type="button" class="btn btn-light"><i class="fas fa-trash-alt"></i></button>
										</div>
									</div>
									<div class="col-6">
										<div class="input-group">
											<div class="col-6 d-flex align-items-center">Скидка 90 000 р.</div>
											<div class="col-6 d-flex align-items-center">Скидка 75 000 р.</div>
										</div>
										<div class="input-group">
											<input type="text" class="col-6 form-control" placeholder="Марка">
											<input type="text" class="col-6 form-control" placeholder="Модель">
										</div>
										<div class="input-group">
											<select class="col-6 form-control">
												<option>Год по ПТС</option>
												<option>1024</option>
												<option>2048</option>
											</select>
											<input type="text" class="col-6 form-control" placeholder="Пробег, км">
										</div>
										<select class="form-control">
											<option>Состояние со слов Клиента</option>
											<option>Норм</option>
											<option>Так себе</option>
											<option>Утиль</option>
										</select>
										<div class="input-group">
											<select class="col-6 form-control">
												<option>Записи в ПТС</option>
												<option>Запись 1</option>
												<option>Запись 2</option>
											</select>
											<select class="col-6 form-control">
												<option>Где обслуживал</option>
												<option>У бабушки в деревне</option>
												<option>У соседа алкаша</option>
											</select>
										</div>
									</div>
								</div>
								<hr>
								<div class="input-group">
									<div class="col-3">
										<input type="text" class="form-control" placeholder="Оценка Клиент, р.">
									</div>
									<div class="col-3 d-flex align-items-center justify-content-center"><i>Оценка Дилер, р.</i></div>
									<div class="col-3 d-flex align-items-center justify-content-center"><i>Закуп Дилера, р.</i></div>
									<div class="col-3 d-flex align-items-center justify-content-center">
										<a href="#">Жду оценку</a>
									</div>
								</div>
							</div>
							<br>
							<!-- 
							ФИНАНСОВЫЕ УСЛУГИ
							ЗАГОЛОВОК
							-->
							<div class="p-2 bg-info">
								<span class="text-white">Финансовые услуги</span>
								<span style="float: right;">Прогресс 0%</span>
							</div>
							<!-- 
							ФИНАНСОВЫЕ УСЛУГИ
							КОНТЕНТ
							-->
							<div>
								<div class="input-group">
									<div class="col-3"><label>Первый взнос</label></div>
									<div class="col-3"><label>Сумма кредита</label></div>
									<div class="col-6"><label>Субсидия</label></div>
								</div>
								<div class="input-group">
									<div class="col-3">
										<input type="text" class="form-control" placeholder="Сумма, р." value="250 000">
									</div>
									<div class="col-3 d-flex align-items-center justify-content-center"><i>Сумма, р.</i></div>
									<div class="col-3 d-flex align-items-center justify-content-center"><i>Сумма, р.</i></div>
									<div class="col-3 d-flex align-items-center justify-content-center">
										<a href="#">Жду одобрение</a>
									</div>
								</div>
								<div class="input-group">
									<div class="col-3"><input type="checkbox"> Страхование</div>
									<div class="col-9"><input type="checkbox"> Renault Extra</div>
								</div>
							</div>
							<br>
							<!-- 
							ПРОГРАММА ЛОЯЛЬНОСТИ
							ЗАГОЛОВОК
							-->
							<div class="p-2 bg-info">
								<span class="text-white">Программа лояльности</span>
								<span style="float: right;">Прогресс 0%</span>
							</div>
							<!-- 
							ПРОГРАММА ЛОЯЛЬНОСТИ
							КОНТЕНТ
							-->
							<div>
								<div class="input-group">
									<div class="col-3"><input type="checkbox"> Лизинг</div>
									<div class="col-3"><input type="checkbox"> Корпоративка</div>
									<div class="col-3"><input type="checkbox"> Партнёрка</div>
									<div class="col-3"><input type="checkbox"> Гарантия цены</div>
								</div>
								<div class="input-group">
									<div class="col-3 d-flex align-items-center"><i>Скидка 5%</i></div>
									<div class="col-3 d-flex align-items-center"><i>Скидка 3,5%</i></div>
									<div class="col-3 d-flex align-items-center"><i>Скидка 2%</i></div>
									<div class="col-3 d-flex align-items-center"><i>Скидка 2%</i></div>
								</div>
								<hr>
								<div class="input-group">
									<div class="col-3"><input type="checkbox"> Акция дилера 1</div>
									<div class="col-3"><input type="checkbox"> Акция дилера 2</div>
									<div class="col-3"><input type="checkbox"> Акция дилера 3</div>
									<div class="col-3"><input type="checkbox"> Акция дилера 4</div>
								</div>
								<div class="input-group">
									<div class="col-3 d-flex align-items-center"><i>Скидка, р.</i></div>
									<div class="col-3 d-flex align-items-center"><i>Скидка, р.</i></div>
									<div class="col-3 d-flex align-items-center"><i>Скидка, р.</i></div>
									<div class="col-3 d-flex align-items-center"><i>Скидка, р.</i></div>
								</div>
								<hr>
								<div class="input-group">
									<div class="col-3 d-flex align-items-center"><span><input type="checkbox"> My Renault</span></div>
									<div class="col-6">
										<button type="button" class="btn btn-outline-dark btn-block">Коммерческое предложение</button>
									</div>
								</div>
							</div>
							<!-- 
							АРХИВ КОММЕРЧЕСКИХ ПРЕДЛОЖЕНИЙ
							ЗАГОЛОВОК
							-->
							<div class="p-2 bg-info">
								<span class="text-white">Архив коммерческих предложений</span>
								<span style="float: right;">Прогресс 100%</span>
							</div>
							<!-- 
							АРХИВ КОММЕРЧЕСКИХ ПРЕДЛОЖЕНИЙ
							КОНТЕНТ
							-->
							<div>
								<table class="table table-bordered table-sm table-striped" style="table-layout: fixed;" width="100%">
									<tr>
										<td>20.01.2017 16:31</td>
										<td>X7LHSRGAN59685351</td>
										<td><a href="#">Смотреть</a></td>
									</tr>
									<tr>
										<td>18.01.2017 12:45</td>
										<td>X7LHSRGAN59685587, X7L5SRAT6335…</td>
										<td><a href="#">Смотреть</a></td>
									</tr>
									<tr>
										<td>16.01.2017 16:31</td>
										<td>X7LHSRGAN59685351, X7L5SRAT6597…</td>
										<td><a href="#">Смотреть</a></td>
									</tr>
									<tr>
										<td>15.01.2017 12:45</td>
										<td>X7LHSRGAN59685587, X7L5SRAT6321…</td>
										<td><a href="#">Смотреть</a></td>
									</tr>
								</table>
							</div>
						</div>
						<!-- Рабочий лист Вкладка  Оформление -->
						<div class="tab-pane" id="worksheet-design" role="tabpanel" aria-labelledby="worksheet-design-tab">
							<!-- 
							ПЛАТЕЖИ
							ЗАГОЛОВОК
							-->
							<div class="input-group border-bottom border-top border-info">
								<div class="col-9 p-2 bg-info">
									<span class="text-white">Платежи</span>
									<span style="float: right;"><a href="#"><i class="fas fa-plus-circle text-white"></i></a></span>
								</div>
								<div class="col-3 d-flex align-items-center">
									<a href="#" class="btn btn-info btn-block">Обновить</a>
								</div>
							</div>
							<!-- 
							ПЛАТЕЖИ
							КОНТЕНТ
							-->
							<div>
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
							<br>
							<!-- 
							ОФОРМЛЕНИЕ
							ЗАГОЛОВОК
							-->
							<div class="input-group border-bottom border-top border-info">
								<div class="col-9 p-2 bg-info">
									<span class="text-white">Оформление</span>
								</div>
								<div class="col-3 d-flex align-items-center">
									<a href="#" class="btn btn-info btn-block">Обновить</a>
								</div>
							</div>
							<!-- 
							ОФОРМЛЕНИЕ
							КОНТЕНТ
							-->
							<div>
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
									<div class="col-3"><label>VOC-RECO</label></div>
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
							<br>
							<!-- 
							КРЕДИТОВАНИЕ И ЛИЗИНГ
							ЗАГОЛОВОК
							-->
							<div class="input-group border-bottom border-top border-info">
								<div class="col-9 p-2 bg-info">
									<span class="text-white">Кредитование и лизинг</span>
									<span style="float: right;"><a href="#"><i class="fas fa-plus-circle text-white"></i></a></span>
								</div>
								<div class="col-3 d-flex align-items-center">
									<a href="#" class="btn btn-info btn-block">Обновить</a>
								</div>
							</div>
							<!-- 
							КРЕДИТОВАНИЕ И ЛИЗИНГ
							КОНТЕНТ
							-->
							<div>
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
							<br>
							<!-- 
							СТРАХОВАНИЕ И СЕРВИСЫ
							ЗАГОЛОВОК
							-->
							<div class="input-group border-bottom border-top border-info">
								<div class="col-9 p-2 bg-info">
									<span class="text-white">Страхование и сервисы</span>
									<span style="float: right;"><a href="#"><i class="fas fa-plus-circle text-white"></i></a></span>
								</div>
								<div class="col-3 d-flex align-items-center">
									<a href="#" class="btn btn-info btn-block">Обновить</a>
								</div>
							</div>
							<!-- 
							СТРАХОВАНИЕ И СЕРВИСЫ
							КОНТЕНТ
							-->
							<div>
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
						<!-- Рабочий лист Вкладка  Комментарии -->
						<div class="tab-pane" id="worksheet-comments" role="tabpanel" aria-labelledby="worksheet-comments-tab">
							<p class="h3">комментарии</p>
						</div>
						<!-- Рабочий лист Вкладка  Автомобиль -->
						<div class="tab-pane" id="worksheet-auto" role="tabpanel" aria-labelledby="worksheet-auto-tab">
							<p class="h3">автомобиль</p>
						</div>
						<!-- Рабочий лист Вкладка  Воронка РЛ -->
						<div class="tab-pane" id="worksheet-funnel" role="tabpanel" aria-labelledby="worksheet-funnel-tab">
							<p class="h3">воронка рл</p>
						</div>
					</div>
				</div>
			</div><!-- /Блок c вкладками -->
		</div><!-- /Рабочий лист -->
		
		<!-- Вкладка Задача -->
		<div class="tab-pane" id="task" role="tabpanel" aria-labelledby="task-tab">
			<h3 align="center">Задача</h3>
		</div>
	</div>
</div>
</div>
@endsection