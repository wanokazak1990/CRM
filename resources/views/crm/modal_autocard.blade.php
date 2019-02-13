@section('modal_autocard')
<div class="modal bd-example-modal-lg" id="autocardModal" tabindex="-1" role="dialog" aria-labelledby="autocardModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		{{Form::open()}}
		<div class="modal-content">
			<div class="modal-header d-flex">
				<h5 class="modal-title flex-grow-1" id="autocardModalLabel">
						Карточка автомобиля 
						<span id="car_id"></span>
				</h5>

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
						{!! Form::hidden('id')!!}
						{!! Form::label('title', 'Тип поставки: *',['class' => 'col-4']) !!}
						{!! Form::label('title', 'Автор заказа: *',['class' => 'col-4']) !!}
						{!! Form::label('title', 'Дата (чего?): *',['class' => 'col-3']) !!}
						<div class="col-1 text-right">
							<a href="#"><i class="fas fa-times"></i></a>
						</div>
					</div>

					<div class="input-group">

						{!! Form::select(
							'delivery_type', 
							$delivery_types, 
							'',
							['class' => 'col-4 form-control']) 
						!!}

						{!! Form::select(
							'creator_id',
							App\user::pluck('name','id'),
							'', 
							['class' => 'col-4 form-control']) 
						!!}

						{!! Form::select(
							'logist_marker', 
							$logist_markers, 
							'', 
							['class' => 'col-4 form-control']) 
						!!}

					</div>

					
					<div class="input-group">

						{!! Form::label('title', 'Модель: *',['class' => 'col-4']) !!}
						{!! Form::label('title', 'Комплектация: *',['class' => 'col-4']) !!}
						
					</div>

					<div class="input-group">

						{!! Form::select('model_id',App\oa_model::pluck('name','id'),'', ['class' => 'col-4 form-control'])!!}
						{!! Form::select('complect_id',array(),'', ['class' => 'col-4 form-control'])!!}

					</div>

					<div class="input-group">
						{!! Form::label('title', 'Радиокод:',['class' => 'col-2']) !!}
						{!! Form::label('title', 'Выпуск:',['class' => 'col-2']) !!}
						{!! Form::label('title', 'VIN:',['class' => 'col-4']) !!}
						{!! Form::label('title', '№ заказа:',['class' => 'col-2']) !!}
						{!! Form::label('title', 'Обмен:',['class' => 'col-1']) !!}
						<div class="col-1 text-right">
							<a href="#"><i class="far fa-caret-square-right"></i></a>
						</div>
					</div>
					<div class="input-group">
						{!! Form::text('radio_code','',['class' => 'col-2 form-control'])!!}
						{!! Form::text('year','',['class' => 'col-2 form-control'])!!}
						{!! Form::text('vin','',['class' => 'col-4 form-control'])!!}
						{!! Form::text('order_number','',['class' => 'col-2 form-control'])!!}
						{!! Form::text('swap','',['class' => 'col-2 form-control'])!!}
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
						<!-- Вкладка Автомобиль -->
						<!-- Вкладка Автомобиль -->
						<!-- Вкладка Автомобиль -->
						<!-- Вкладка Автомобиль -->
						<div class="tab-pane active" id="autocardModalCar" role="tabpanel" aria-labelledby="autocardModalCar-tab">
							<div class="input-group">
								<!-- Левый блок -->
								<div class="col-6">
									
									<img id="car-img" src='' style="width: 100%;height: auto;">
									
									<div class="input-group d-flex justify-content-center" id="car-color">
										
									</div>
									<hr>
									<div class="input-group no-gutters">
										<div class="col-12 font-weight-bold" id="car-complect-code"></div>
										<div class="text-secondary">
											<div id="car-motor-type"></div>
											<div id="car-motor-size"></div>
											<div id="car-motor-transmission"></div>
											<div id="car-motor-wheel"></div>
										</div>
									</div>

								</div>
								<!-- Правый блок -->
								<div class="col-6">
									<div class="d-flex align-items-center justify-content-center">
										<div align="center" class="h5">
											<div id="car-model"></div>
											<div id="car-full-price"></div>
											Прогноз 02.01.2019
										</div>
									</div>

									<div class="d-flex border-bottom" >
										<label class="flex-grow-1 font-weight-bold" id="car-complect-name"></label>
										<a href="#" id="car-more">Подробнее</a>
									</div>

									<div id="complect-option"></div>

									<div class="h5 text-right" id="car-base-price"></div>

									<div class="border-bottom font-weight-bold">
										Выберите опционное оборудование
									</div>
									<div class="pack-block"></div>
									
								</div>
							</div>
						</div>

						<!-- Вкладка Логистика -->
						<!-- Вкладка Логистика -->
						<!-- Вкладка Логистика -->
						<!-- Вкладка Логистика -->
						<!-- Вкладка Логистика -->
						<div class="tab-pane" id="autocardModalLogistics" role="tabpanel" aria-labelledby="autocardModalLogistics-tab">
							<div class="input-group p-2 border-bottom">
								<label class="col-8">Дата заказа в производство</label>
								<div class="col-4">
									<input type="text" class="form-control calendar" name="date_order">
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
									<input type="text" class="form-control calendar" name="date_planned">
								</div>
							</div>

							<div class="input-group p-2 border-bottom">
								<label class="col-8">Дата уведомления о сборке</label>
								<div class="col-4">
									<input type="text" class="form-control calendar" name="date_notification">
								</div>
							</div>

							<div class="input-group p-2 border-bottom">
								<label class="col-8">Дата сборки фактическая</label>
								<div class="col-4">
									<input type="text" class="form-control calendar" name="date_build">
								</div>
							</div>

							<div class="input-group p-2 border-bottom">
								<label class="col-8">Дата готовности к отгрузке</label>
								<div class="col-4">
									<input type="text" class="form-control calendar" name="date_ready">
								</div>
							</div>

							<div class="input-group p-2 border-bottom">
								<label class="col-8">Локация цеха отгрузки</label>
								<div class="col-4">
									<input type="text" class="form-control" value="" disabled >
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
									<input type="text" class="form-control calendar" name="date_ship">
								</div>
							</div>
						</div>

						<!-- Вкладка Приемка -->
						<!-- Вкладка Приемка -->
						<!-- Вкладка Приемка -->
						<!-- Вкладка Приемка -->
						<!-- Вкладка Приемка -->
						<div class="tab-pane" id="autocardModalReception" role="tabpanel" aria-labelledby="autocardModalReception-tab">
							<div class="input-group p-2 border-bottom">
								<label class="col-8">Дата приемки на склад</label>
								<div class="col-4">
									<input type="text" class="form-control calendar" name="date_storage">
								</div>
							</div>

							<div class="input-group p-2 border-bottom">
								<label class="col-8">Дата предпродажной подготовки</label>
								<div class="col-4">
									<input type="text" class="form-control calendar" name="date_preparation">
								</div>
							</div>

							<div class="input-group p-2 border-bottom">
								<label class="col-8">Техник по приемке</label>
								<div class="col-4">
									{!! Form::select('technic',App\user::pluck('name','id'),'',['class'=>'form-control']) !!}
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
										<input type="text" class="form-control" value="" name="receipt_number">
									</div>
									<div class="col-4">
										<input type="text" class="form-control calendar" name="receipt_date">
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
										{!! Form::select('st_provision', [1,2,3],'',['class' => 'form-control']) !!}
									</div>
									<div class="col-4">
										<input type="text" class="form-control" name="st_delay">
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
										<input type="text" class="form-control" disabled name="estimated_purchase">
									</div>
									<div class="col-4">
										<input type="text" class="form-control" name="actual_purchase">
									</div>
								</div>
							</div>

							<div class="input-group p-2 border-bottom">
								<label class="col-8">Скидка при отгрузке</label>
								<div class="col-4">
									<input type="text" class="form-control" disabled name="shipping_discount">
								</div>
							</div>

							<div class="input-group p-2 border-bottom">
								<div class="col-4 d-flex">
									<label class="flex-grow-1">Детализация скидки</label>
									<a href="#">
										<i class="fas fa-plus-circle"></i>
									</a>
								</div>
								<div class="col-8 row">
									{!! Form::select('dc_type', [1,2,3],'',['class' => 'form-control col-6']) !!}
									<input type="text" class="form-control col-6" name="dc_sale">
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
										<input type="text" class="form-control calendar" name="pts_datepay">
									</div>
									<div class="col-4">
										<input type="text" class="form-control calendar" name="pts_datereception">
									</div>
									<div class="col-4">
										<input type="text" class="form-control calendar" name="debited_date">
									</div>
								</div>
							</div>
							
						</div>
						
						<!-- Вкладка Установка ДО -->
						<!-- Вкладка Установка ДО -->
						<!-- Вкладка Установка ДО -->
						<!-- Вкладка Установка ДО -->
						<!-- Вкладка Установка ДО -->
						<div class="tab-pane" id="autocardModalAdds" role="tabpanel" aria-labelledby="autocardModalAdds-tab">
							
							<div class="input-group border-bottom">
								<div class="col-8 d-flex align-items-center">Цена ДО по Заказ-наряду</div>
								<div class="col-4">
									<input type="text" class="form-control" value="" name="dopprice">
								</div>
							</div>

							@isset($dops)
								@foreach( $dops as $id => $dop )
									@if($id == 0)
										<div class="">
											<h4>{{App\option_parent::find($dop->parent_id)->name}}</h4>
										</div>
										<div class="column">
									@elseif($dops[$id]->parent_id != $dops[$id-1]->parent_id)
										</div>
										<div class="">
											<h4>{{App\option_parent::find($dop->parent_id)->name}}</h4>
										</div>
										<div class="column">
									@endif
											<label>
												<input type="checkbox" name="dops[]" value="{{$dop->id}}">
												{{mb_strimwidth($dop->name, 0, 40, "...")}}
											</label>
								@endforeach
										</div>
							@endisset

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
					<button type="button" id="savecar" class="btn btn-success col-3 offset-8" data-dismiss="modal">Сохранить</button>	
				</div>
			</div>
		{{Form::close()}}
		</div>
	</div>
</div>
@endsection