@section('modal_autocard')
<div class="modal bd-example-modal-lg" id="autocardModal" tabindex="-1" role="dialog" aria-labelledby="autocardModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header d-flex">
				<h5 class="modal-title flex-grow-1" id="autocardModalLabel">Карточка автомобиля <span id="NUMBEROFCARHERE">00012345</span></h5>
				<div class="col-1 d-flex align-items-center justify-content-center">
					<a href="#" class="text-dark">
						<i class="fas fa-save"></i>
					</a>
				</div>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<!-- Основной контент карточки автомобиля -->
				<div>
					<div class="input-group">
						<label class="col-4">Тип поставки</label>
						<label class="col-4">Автор заказа</label>
						<label class="col-2">23.03.2018</label>
						<div class="col-2 text-right">
							<a href="#"><i class="fas fa-times"></i></a>
						</div>
					</div>
					<div class="input-group">
						{!! Form::select('delivery_types', $delivery_types, 1, ['class' => 'col-4 form-control']) !!}
						<input type="text" class="col-4 form-control" value="Калачикова" disabled>
						{!! Form::select('logist_markers', $logist_markers, '', ['class' => 'col-4 form-control']) !!}
					</div>

					<div class="input-group">
						<label class="col-4">Модель</label>
						<label class="col-8">Комплектация</label>
					</div>
					<div class="input-group">
						<select class="col-4 form-control">
							<option>DUSTER</option>
							<option>NOT DUSTER</option>
						</select>
						<select class="col-8 form-control">
							<option>Privilege 2.0 (143) 4WD МКП</option>
							<option>Not Privilege 2.0 (143) 4WD МКП</option>
						</select>
					</div>

					<div class="input-group">
						<label class="col-2">Радиокод</label>
						<label class="col-2">Год выпуска</label>
						<label class="col-4">VIN-номер</label>
						<label class="col-2">№ заказа</label>
						<label class="col-1">Обмен</label>
						<div class="col-1 text-right">
							<a href="#"><i class="far fa-caret-square-right"></i></a>
						</div>
					</div>
					<div class="input-group">
						<input type="text" class="col-2 form-control" value="7896">
						<input type="text" class="col-2 form-control" value="2018">
						<input type="text" class="col-4 form-control" value="X7LASRBA562214223">
						<input type="text" class="col-2 form-control" value="6828A">
						<input type="text" class="col-2 form-control">
					</div>
					<hr>
				</div>
				<!-- Блок вкладок -->
				<div>
					<!-- Вкладки -->
					<ul class="nav nav-tabs nav-justified" id="autocardModalTabs" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" id="autocardModalCar-tab" data-toggle="tab" href="#autocardModalCar" role="tab" aria-controls="autocardModalCar" aria-selected="true">Автомобиль</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="autocardModalLogistics-tab" data-toggle="tab" href="#autocardModalLogistics" role="tab" aria-controls="autocardModalLogistics" aria-selected="false">Логистика</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="autocardModalReception-tab" data-toggle="tab" href="#autocardModalReception" role="tab" aria-controls="autocardModalReception" aria-selected="false">Приемка</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="autocardModalAdds-tab" data-toggle="tab" href="#autocardModalAdds" role="tab" aria-controls="autocardModalAdds" aria-selected="false">Установка ДО</a>
						</li>
					</ul>
					<!-- Контент вкладок -->
					<div class="tab-content" id="autocardModalTabsContent">
						<!-- Вкладка Автомобиль -->
						<div class="tab-pane active" id="autocardModalCar" role="tabpanel" aria-labelledby="autocardModalCar-tab">
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
						</div>
						<!-- Вкладка Логистика -->
						<div class="tab-pane" id="autocardModalLogistics" role="tabpanel" aria-labelledby="autocardModalLogistics-tab">
							<div class="input-group p-2 border-bottom">
								<label class="col-8">Дата заказа в производство</label>
								<div class="col-4">
									<input type="date" class="form-control">
								</div>
							</div>

							<div class="input-group p-2 border-bottom">
								<div class="col-8 d-flex">
									<label class="flex-grow-1">Дата сборки планируемамя</label>
									<a href="#" class="text-dark">
										<i class="fas fa-plus-circle"></i>
									</a>
								</div>
								<div class="col-4">
									<input type="date" class="form-control">
									<input type="date" class="form-control">
								</div>
							</div>

							<div class="input-group p-2 border-bottom">
								<label class="col-8">Дата уведомления о сборке</label>
								<div class="col-4">
									<input type="date" class="form-control">
								</div>
							</div>

							<div class="input-group p-2 border-bottom">
								<label class="col-8">Дата сборки фактическая</label>
								<div class="col-4">
									<input type="date" class="form-control">
								</div>
							</div>

							<div class="input-group p-2 border-bottom">
								<label class="col-8">Дата готовности к отгрузке</label>
								<div class="col-4">
									<input type="date" class="form-control">
								</div>
							</div>

							<div class="input-group p-2 border-bottom">
								<label class="col-8">Локация цеха отгрузки</label>
								<div class="col-4">
									<input type="text" class="form-control" value="Тольятти" disabled>
								</div>
							</div>

							<div class="input-group p-2">
								<div class="col-8 d-flex">
									<label class="flex-grow-1">Дата отгрузки</label>
									<a href="#" class="text-dark">
										<i class="fas fa-plus-circle"></i>
									</a>
								</div>
								<div class="col-4">
									<input type="date" class="form-control">
									<input type="date" class="form-control">
								</div>
							</div>
						</div>
						<!-- Вкладка Приемка -->
						<div class="tab-pane" id="autocardModalReception" role="tabpanel" aria-labelledby="autocardModalReception-tab">
							<div class="input-group p-2 border-bottom">
								<label class="col-8">Дата приемки на склад</label>
								<div class="col-4">
									<input type="date" class="form-control">
								</div>
							</div>

							<div class="input-group p-2 border-bottom">
								<label class="col-8">Дата предпродажной подготовки</label>
								<div class="col-4">
									<input type="date" class="form-control">
								</div>
							</div>

							<div class="input-group p-2 border-bottom">
								<label class="col-8">Техник по приемке</label>
								<div class="col-4">
									<select class="form-control">
										<option>Кульчицкий</option>
										<option>Не Кульчицкий</option>
									</select>
								</div>
							</div>

							<div class="p-2 border-bottom">
								<div class="input-group">
									<label class="col-4 offset-4">Номер</label>
									<label class="col-4">Дата</label>
								</div>
								<div class="input-group">
									<label class="col-4">Приходная накладная</label>
									<div class="col-4">
										<input type="text" class="form-control" value="40338G1T">
									</div>
									<div class="col-4">
										<input type="date" class="form-control">
									</div>
								</div>
							</div>

							<div class="p-2 border-bottom">
								<div class="input-group">
									<label class="col-4 offset-4">Обеспечение</label>
									<div class="col-4 d-flex">
										<label class="flex-grow-1">Отсрочка платежа</label>
										<a href="#">
											<i class="fas fa-plus-circle"></i>
										</a>
									</div>
								</div>
								<div class="input-group">
									<label class="col-4">Условия отгрузки</label>
									<div class="col-4">
										{!! Form::select('provide_types', $provide_types, 2, ['class' => 'form-control']) !!}
									</div>
									<div class="col-4">
										<input type="date" class="form-control">
										<input type="date" class="form-control">
									</div>
								</div>
							</div>

							<div class="p-2 border-bottom">
								<div class="input-group">
									<label class="col-4 offset-4">Расчетный закуп</label>
									<label class="col-4">Фактический закуп</label>
								</div>
								<div class="input-group">
									<label class="col-4">Себестоимость</label>
									<div class="col-4">
										<input type="text" class="form-control" value="856 000 руб." disabled>
									</div>
									<div class="col-4">
										<input type="text" class="form-control" value="806 000 руб.">
									</div>
								</div>
							</div>

							<div class="input-group p-2 border-bottom">
								<label class="col-8">Скидка при отгрузке</label>
								<div class="col-4">
									<input type="text" class="form-control" value="50 000 руб." disabled>
								</div>
							</div>

							<div class="input-group p-2 border-bottom">
								<div class="col-4 d-flex">
									<label class="flex-grow-1">Детализация скидки</label>
									<a href="#">
										<i class="fas fa-plus-circle"></i>
									</a>
								</div>
								<div class="col-4">
									{!! Form::select('discount_details', $discount_details, 1, ['class' => 'form-control']) !!}
									{!! Form::select('discount_details', $discount_details, 3, ['class' => 'form-control']) !!}
								</div>
								<div class="col-4">
									<input type="text" class="form-control" value="40 000 руб.">
									<input type="text" class="form-control" value="10 000 руб.">
								</div>
							</div>
							
							<div class="p-2">
								<div class="input-group">
									<label class="col-4">Дата оплаты ПТС</label>
									<label class="col-4">Дата получения ПТС</label>
									<label class="col-4">Дата списания со счета</label>
								</div>
								<div class="input-group">
									<div class="col-4">
										<input type="date" class="form-control">
									</div>
									<div class="col-4">
										<input type="date" class="form-control">
									</div>
									<div class="col-4">
										<input type="date" class="form-control">
									</div>
								</div>
							</div>
							
						</div>
						<!-- Вкладка Установка ДО -->
						<div class="tab-pane" id="autocardModalAdds" role="tabpanel" aria-labelledby="autocardModalAdds-tab">
							
							<div class="input-group border-bottom">
								<div class="col-8 d-flex align-items-center">Цена ДО по Заказ-наряду</div>
								<div class="col-4">
									<input type="text" class="form-control" value="40 750 руб.">
								</div>
							</div>

							<div class="input-group border-bottom">
								<div class="col-12 text-primary">Основное оборудование</div>
								<div class="col-6"><input type="checkbox" checked> Автосигнализация с автозапуском</div>
								<div class="col-6"><input type="checkbox"> Оборудование</div>
								<div class="col-6"><input type="checkbox" checked> Ручная сигнализация с ручнозапуском</div>
								<div class="col-6"><input type="checkbox"> Оборудование</div>
								<div class="col-6"><input type="checkbox"> Оборудование</div>
								<div class="col-6"><input type="checkbox"> Оборудование</div>
								<div class="col-6"><input type="checkbox"> Оборудование</div>
							</div>

							<div class="input-group">
								<div class="col-12 text-primary">Прочее оборудование</div>
								<div class="col-6"><input type="checkbox" checked> Накладки порогов дверей</div>
								<div class="col-6"><input type="checkbox"> Оборудование</div>
								<div class="col-6"><input type="checkbox" checked> Подложки проемов окон</div>
								<div class="col-6"><input type="checkbox"> Оборудование</div>
								<div class="col-6"><input type="checkbox"> Оборудование</div>
								<div class="col-6"><input type="checkbox"> Оборудование</div>
								<div class="col-6"><input type="checkbox"> Оборудование</div>
								<div class="col-6"><input type="checkbox"> Оборудование</div>
								<div class="col-6"><input type="checkbox"> Оборудование</div>
								<div class="col-6"><input type="checkbox"> Оборудование</div>
								<div class="col-6"><input type="checkbox"> Оборудование</div>
								<div class="col-6"><input type="checkbox"> Оборудование</div>
								<div class="col-6"><input type="checkbox"> Оборудование</div>
							</div>

						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="input-group">
					<div class="col-1 d-flex align-items-center justify-content-center">
						<a href="#" class="text-dark">
							<i class="fas fa-trash-alt"></i>
						</a>
					</div>
					<button type="button" class="btn btn-success col-3 offset-8" data-dismiss="modal">Сохранить</button>	
				</div>
			</div>
		</div>
	</div>
</div>
@endsection