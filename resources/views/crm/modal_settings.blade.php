@section('modal_settings')
<div class="modal bd-example-modal-lg" id="settingsModal" tabindex="-1" role="dialog" aria-labelledby="settingsModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="settingsModalLabel">Настройки отображения полей</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<ul class="nav nav-tabs nav-justified" id="settingsTable" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="settingsTableSet-tab" data-toggle="tab" href="#settingsTableSet" role="tab" aria-controls="settingsTableSet" aria-selected="true">Все настройки</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="settingsTableList-tab" data-toggle="tab" href="#settingsTableList" role="tab" aria-controls="settingsTableList" aria-selected="false">Добавить настройку</a>
					</li>
				</ul>
				<div class="tab-content" id="settingsTableContent">
					<div class="tab-pane active" id="settingsTableSet" role="tabpanel" aria-labelledby="settingsTableSet-tab">
						<div id="currentSettingsList"></div>
					</div>
					<div class="tab-pane" id="settingsTableList" role="tabpanel" aria-labelledby="settingsTableList-tab">
						{{ Form::open(array('route' => 'savesetting')) }}
							<div class="row">
								<div class="col-6">
									<label>Название настройки:</label>
									<input type="text" name="settingName" class="form-control" placeholder="Название" required>	
								</div>
								<div class="col-6">
									<label>Уровень доступа:</label>
									<input type="number" min="1" max="10" name="settingLevel" class="form-control" placeholder="От 1 до 10" required>	
								</div>
							</div>
							<label>Доступные поля:</label>
							<div id="settingsFields"></div>
							<input name="saveSettings" type="submit" class="btn btn-info" value="Сохранить настройку">
						{{ Form::close() }}
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть окно</button>
			</div>
		</div>
	</div>
</div>
@endsection