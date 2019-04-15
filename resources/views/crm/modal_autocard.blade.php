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
					<a href="javascript://" class="text-dark">
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
						<div class="col-4"> 
							<input type="text" name="logist_date" class="marker_date form-control" style="border: 0px;width: 100%;padding: 0px;pointer-events: none;">
							<a href="javascript://" class="clearer-marker" style="position: absolute;right: 8px;top:7px;"><i class="text-dark fa fa-times"></i></a>
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
						{!! Form::label('title', 'Выпуск:',['class' => 'col-4']) !!}
						{!! Form::label('title', 'VIN:',['class' => 'col-4']) !!}
						{!! Form::label('title', '№ заказа:',['class' => 'col-4']) !!}
					</div>
					<div class="input-group"> 

							{!! Form::text('year','',['class' => 'col-4 form-control'])!!}
							
							{!! Form::text('vin','',['class' => 'col-4 form-control'])!!}
							
							{!! Form::text('order_number','',['class' => 'col-4 form-control'])!!}
							 
					</div>

					<div class="input-group">

						{!! Form::label('title', 'Модель: *',['class' => 'col-4']) !!}
						{!! Form::label('title', 'Комплектация: *',['class' => 'col-8']) !!}
						
					</div>
					<div class="input-group">

						{!! Form::select('model_id',App\oa_model::pluck('name','id'),'', ['class' => 'col-4 form-control'])!!}
						{!! Form::select('complect_id',array(),'', ['class' => 'col-8 form-control'])!!}

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
										<a href="javascript://" id="car-more">Подробнее</a>
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
							<div class="input-group pt-3">
								<label class="col-8 form-control">Дата заказа в производство</label>
								<div class="col-4">
									<input type="text" class="form-control calendar disabled" name="date_order" placeholder="Дата">
									<a class="input-icon checkdate fa fa-calendar-plus" status="1" aria-hidden="true"></a>
								</div>
							</div>

							<div class="input-group  pt-3">
								<label class="col-8 form-control pr-0">
									Дата сборки планируемамя
									<a href="javascript://" class="text-dark label-adder" id="add-plan-date">
										<i class="fa fa-plus-circle"></i>
									</a>
								</label>
									
								<div class="col-4 pad-0">
									<div class="item item-block col-12">
										<input type="text" class="form-control calendar disabled" name="date_planned[]" placeholder="Дата">
										<a class="input-icon checkdate checkdate-block fa fa-calendar-plus" status="1" aria-hidden="true"></a>
									</div>
								</div>
							</div>

							<div class="input-group  pt-3">
								<label class="col-8 form-control">Дата уведомления о сборке</label>
								<div class="col-4 ">
									<input type="text" class="form-control calendar disabled" name="date_notification" placeholder="Дата">
									<a class="input-icon checkdate fa fa-calendar-plus" status="1" aria-hidden="true"></a>
								</div>
							</div>

							<div class="input-group  ">
								<label class="col-8 form-control">Дата сборки фактическая</label>
								<div class="col-4 ">
									<input type="text" class="form-control calendar disabled bt-0" name="date_build" placeholder="Дата">
									<a class="input-icon checkdate fa fa-calendar-plus" status="1" aria-hidden="true"></a>
								</div>
							</div>

							<div class="input-group  ">
								<label class="col-8 form-control">Дата готовности к отгрузке</label>
								<div class="col-4 ">
									<input type="text" class="form-control calendar disabled bt-0" name="date_ready" placeholder="Дата">
									<a class="input-icon checkdate fa fa-calendar-plus" status="1" aria-hidden="true"></a>
								</div>
							</div>

							<div class="input-group  ">
								<label class="col-8 form-control">Локация цеха отгрузки</label>
								<div class="col-4 ">
									<input type="text" class="form-control loc-city bt-0" value="" disabled >
								</div>
							</div>

							<div class="input-group pt-3">
								<label class="col-8 form-control">
									Дата отгрузки
									<a href="javascript://" class="text-dark label-adder" id="add-ship-date">
										<i class="fas fa-plus-circle"></i>			
									</a>
								</label>
								<div class="col-4 pad-0">
									<div class="item item-block col-12">
										<input type="text" class="form-control calendar disabled" name="date_ship[]" placeholder="Дата">
										<a class="input-icon checkdate checkdate-block fa fa-calendar-plus" status="1" aria-hidden="true"></a>
									</div>
								</div>
							</div>
						</div>

						<!-- Вкладка Приемка -->
						<!-- Вкладка Приемка -->
						<!-- Вкладка Приемка -->
						<!-- Вкладка Приемка -->
						<!-- Вкладка Приемка -->
						<div class="tab-pane" id="autocardModalReception" role="tabpanel" aria-labelledby="autocardModalReception-tab">
							<div class="input-group pt-3 ">
								<label class="col-8 form-control">Дата приемки на склад</label>
								<div class="col-4 ">
									<input type="text" class="form-control calendar disabled " name="date_storage" placeholder="Дата">
									<a class="input-icon checkdate fa fa-calendar-plus" status="1" aria-hidden="true"></a>
								</div>
							</div>

							<div class="input-group ">
								<label class="col-8 form-control">Дата предпродажной подготовки</label>
								<div class="col-4 ">
									<input type="text" class="form-control calendar disabled bt-0" name="date_preparation" placeholder="Дата">
									<a class="input-icon checkdate fa fa-calendar-plus" status="1" aria-hidden="true"></a>
								</div>
							</div>

							<div class="input-group ">
								<label class="col-8 form-control">Техник по приемке</label>
								<div class="col-4 ">
									{!! Form::select('technic',App\user::pluck('name','id'),'',['class'=>'form-control']) !!}
								</div>
							</div>

							<div class="input-group ">
								<label class="col-8 form-control">Радиокод</label>
								<div class="col-4">
									<input type="text" name="radio_code" class=" form-control bt-0" value="">
								</div>
							</div>

							<div class="pt-3">
								<div class="input-group">
									<label class="col-4 offset-4 sublabel">Номер</label>
									<label class="col-4 sublabel">Дата</label>
								</div>

								<div class="input-group">
									<label class="col-4 form-control">Приходная накладная</label>
									<div class="col-4 ">
										<input type="text" class="form-control" value="" name="receipt_number">
									</div>
									<div class="col-4 ">
										<input type="text" class="form-control calendar disabled" name="receipt_date" placeholder="Дата">
										<a class="input-icon checkdate checkdate-block fa fa-calendar-plus" status="1" aria-hidden="true"></a>
									</div>
								</div>
							</div>

							<div class="provision-block">
								<div class="input-group">
									<label class="col-4 offset-4 sublabel">Обеспечение</label>
									<label class="col-4 sublabel">Отсрочка платежа</label>										
								</div>
								<div class="input-group">
									<label class="col-4 form-control">
										Условия отгрузки
										<a href="javascript://" class=" text-dark label-adder">
											<i class="fas fa-plus-circle" id="provision-adder"></i>
										</a>
									</label>
									<div class="col-4">
										{!! Form::select('st_provision', App\crm_list_provision::pluck('name','id'),'',['class' => 'form-control']) !!}
									</div>
									<div class="col-4 provision-content">
										<div class="row item item-block" style="padding: 0 15px;position: relative;">
											
												<input 
													type="text" 
													class="form-control col-3" 
													style="border-right: 0px; " 
													name="st_delay[]"
													placeholder="Дни" 
												>
												<input 
													type="text" 
													class="form-control col-9 calendar disabled" 
													name="st_date[]"  
													style=""
													placeholder="Дата" 
												>
												<a class="input-icon checkdate checkdate-block fa fa-calendar-plus" status="1" aria-hidden="true"></a>
											
										</div>
									</div>
								</div>
							</div>

							<div class="pt-3">
								<div class="input-group">
									<label class="col-4 offset-4 sublabel">Расчетный закуп</label>
									<label class="col-4 sublabel">Фактический закуп</label>
								</div>
								<div class="input-group">
									<label class="col-4 form-control">Себестоимость</label>
									<div class="col-4">
										<input type="text" class="form-control" name="estimated_purchase" style="pointer-events: none;">
									</div>
									<div class="col-4">
										<input type="text" class="form-control" name="actual_purchase">
									</div>
								</div>
							</div>

							<div class="input-group ">
								<label class="col-8 form-control">Скидка при отгрузке</label>
								<div class="col-4">
									<input type="text" class="form-control bt-0" name="shipping_discount" style="pointer-events: none;">
								</div>
								<div class="col-4">
									<input type="text" class="form-control bt-0" name="detail_discount" style="pointer-events: none;">
								</div>
							</div>

							<div class="input-group discount-block">
								<label class="form-control col-4">
									Детализация скидки
									<a href="javascript://" class="text-dark label-adder">
										<i class="fa fa-plus-circle " id="discount-adder"></i>
									</a>
								</label>
									
								<div class="col-8  discount-content">
									<div class="item item-block row"> 
										<div class="col-6">
											{!! Form::select('dc_type[]', App\crm_discount_detail::pluck('name','id'),'',['class' => 'form-control']) !!}
										</div>
										<div class="col-6">
											<div class="row" style="padding: 0 15px;">
											<input type="text" name="dc_pack_percent[]" class="form-control col-3 bt-0 deleter">
											<input type="text" class="form-control bt-0 form-control col-9" name="dc_sale[]" style="border-left: 0px;">
											<a class="input-icon clearer-block fa fa-times"></a>
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<div class="pt-3">
								<div class="input-group">
									<label class="col-4 sublabel">Дата оплаты ПТС</label>
									<label class="col-4 sublabel">Дата получения ПТС</label>
									<label class="col-4 sublabel">Дата списания со счета</label>
								</div>
								<div class="input-group">
									<div class="col-4">
										<input type="text" class="form-control calendar disabled" name="pts_datepay" placeholder="Дата">
										<a class="input-icon checkdate  fa fa-calendar-plus" status="1" aria-hidden="true"></a>
									</div>
									<div class="col-4">
										<input type="text" class="form-control calendar disabled" name="pts_datereception" placeholder="Дата">
										<a class="input-icon checkdate  fa fa-calendar-plus" status="1" aria-hidden="true"></a>
									</div>
									<div class="col-4">
										<input type="text" class="form-control calendar disabled" name="debited_date" placeholder="Дата">
										<a class="input-icon checkdate  fa fa-calendar-plus" status="1" aria-hidden="true"></a>
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
							
							<div class="input-group border-bottom" style="padding-top: 20px; padding-bottom: 20px;">
								<label class="col-8 form-control"><big><b>Цена ДО по Заказ-наряду</b></big></label>
								<div class="col-4">
									<input type="text" class="form-control" value="" name="dopprice">
								</div>
							</div>

							@isset($dops)
								@foreach( $dops as $id => $dop )
									@if($id == 0)
										<div class="">
											<h5>{{App\option_parent::find($dop->parent_id)->name}}</h5>
										</div>
										<div class="column">
									@elseif($dops[$id]->parent_id != $dops[$id-1]->parent_id)
										</div>
										<div class="">
											<h5>{{App\option_parent::find($dop->parent_id)->name}}</h5>
										</div>
										<div class="column">
									@endif
											<label class="cardops-label">
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
						<a href="javascript://" class="text-dark">
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