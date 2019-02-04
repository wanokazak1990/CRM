@section('modal_add_testdrive')
<div class="modal bd-example-modal-lg" id="addTestdriveModal" tabindex="-1" role="dialog" aria-labelledby="addTestdriveModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">

		<div class="modal-content">
			<div class="modal-header d-flex">
				<h5 class="modal-title" id="addTestdriveModalLabel">
					Добавить пробную поездку
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				@foreach($testdrive_cars as $car)
				<div class="input-group p-1">
					<div class="col-6 d-flex align-items-center justify-content-center">
						<div class="d-flex align-items-center justify-content-center" style="background-color: #ddd; width: 300px; height: 200px;">
							Картинка машины
						</div>
					</div>
					<div class="col-6">
						<div class="input-group border-bottom text-secondary">
							{{ $car->vin }}
						</div>

						<div class="input-group">
							<label class="h5">
								{{ $car->brand->name }} {{ $car->model->name }}
							</label>
						</div>

						<div class="input-group">
							<label class="font-weight-bold">
								Исполнение {{ $car->complect->code }}
							</label>
						</div>

						<div class="input-group">
							<label class="text-secondary">
								{{ $car->complect->motor->forAdmin() }}
							</label>
						</div>

						<div class="input-group border-bottom d-flex">
							<label class="flex-grow-1 font-weight-bold">
								Комплектация {{ $car->complect->name }}
							</label>
							<a href="#">Подробнее</a>
						</div>

						<div class="input-group">
							<button type="button" class="col-12 btn btn-warning wl_submit_testdrive" model_id="{{ $car->model->id }}" data-dismiss="modal">
								Оформить доверенность
							</button>
							<button type="button" class="col-12 btn btn-dark">Пройти анкетирование</button>
						</div>
					</div>
				</div>
				@endforeach
			</div>

		</div>
	</div>
</div>
@endsection