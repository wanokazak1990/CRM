/**
 * Рабочий лист
 * Параметры - Пробная поездка
 */


/**
 * При открытии блока - загрузка и отображение выбранных машин для тест-драйва
 */
$(document).on('shown.bs.collapse', '#wsparam1', function() {
	var wl_id = $('span[name="wl_id"]').html();
	
	var formData = new FormData();
	formData.append('wl_id', wl_id);

	var parameters = formData;
	var url = '/wlloadtestdrive';

	$.when(ajaxWithFiles(parameters, url)).then(function(data){
		$('#testdriveCars').html(data);
	});
});

/**
 * Загрузка и отображение доступных для тестдрайва машин в модальном окне
 * при нажатии на кнопку Добавить
 */
$(document).on('show.bs.modal', '#addTestdriveModal', function() {

	var url = '/wlgettestdrivecars';
	$.when(ajaxWithFiles('', url)).then(function(data){
		if (data != '0')
			$('#addTestdriveModal .modal-body').html(data);
		else
			$('#addTestdriveModal .modal-body').html('<div class="d-flex justify-content-center alert alert-info">Автомобилей для тест-драйва не найдено</div>');
	});
});

/**
 * Добавить машину в Пробную поездку (при нажатии "Оформить доверенность" в модальном окне)
 */
$(document).on('click', '.wl_submit_testdrive', function() {
	var model_id = $(this).attr('model_id');
	var wl_id = $('span[name="wl_id"]').html();
	
	var formData = new FormData();
	formData.append('wl_id', wl_id);
	formData.append('model_id', model_id);

	var parameters = formData;
	var url = '/wladdtestdrive';

	$.when(ajaxWithFiles(parameters, url)).then(function(data){
		$('#testdriveCars').html(data);
	});

});

/**
 * Удаление машины из Пробной поездки по нажатию на иконку удаления
 * Получение обновленного списка машин для Пробной поездки
 */
$(document).on('click', '.wl_del_testdrive', function() {
	var testdrive_id = $(this).attr('id');

	var formData = new FormData();
	formData.append('testdrive_id', testdrive_id);

	var parameters = formData;
	var url = '/wldeltestdrive';

	$.when(ajaxWithFiles(parameters, url)).then(function(data){
		$('#testdriveCars').html(data);
	});
});