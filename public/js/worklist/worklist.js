/**
 * Рабочий лист
 */

/**
 * Функция сохранения изменений в рабочем листе
 */
function wl_save_changes(){
	var wl_id = $('span[name=wl_id]').html();
	var workstr = $("#worksheet").find("form").serializeArray();
	workstr.push({'name':'wl_id','value':wl_id});


	var cfg_cars = getCfgCars();
	if (cfg_cars != null)
		workstr.push({'name':'cfg_cars','value':cfg_cars});

	var ofu_products = getOfuProducts();
	if (ofu_products != null)
		workstr.push({'name':'ofu_products','value':ofu_products});

	
	$.ajax({
		url: '/wlsavechanges',
		type: 'POST',
        data: workstr,
		headers: {
        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    	},
    	success: function(data){
    		if (data == 1)
    		{
				$('.ws-param').collapse('hide');
				$('.ws-registration').collapse('hide');
    			log('Рабочий лист обновлен');
    			refreshContent()
    			alert('Рабочий лист обновлен');	
    		}
    	},
    	error:function(xhr, ajaxOptions, thrownError){
    		log('Не удалось сохранить рабочий лист');
	    	log("Ошибка: code-"+xhr.status+" "+thrownError);
	    	log(xhr.responseText);
	    	log(ajaxOptions)
	    	log('ddd')
	    }
	});
};

/**
 * Функция проверки, есть ли у рабочего листа привязанный (зарезервированный) автомобиль
 */
function checkWorklistOnCar(worklist_id) {
	var formData = new FormData();
	formData.append('worklist_id', worklist_id);
	
	var result = false;

	$.ajax({
		async: false,
		url: '/wlcheckselectedcar',
		type: 'POST',
		data: formData,
		dataType : "json", 
		cache: false,
		contentType: false,
		processData: false, 
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		success: function(data){
			if (data == '1')
				result = true;
			else
				result = false;
		},
		error:function(xhr, ajaxOptions, thrownError){
			log('Не удалось проверить рабочий лист на наличие привязанного автомобиля');
			log("Ошибка: code-"+xhr.status+" "+thrownError);
			log(xhr.responseText);
			log(ajaxOptions);
		}
	});

	return result;
};

/**
 * Функция проверки, зарезервирован ли автомобиль за каким-либо рабочим листом
 */
function checkCarOnWorklist(car_id) {
	var formData = new FormData();
	formData.append('car_id', car_id);
	
	var result = false;

	$.ajax({
		async: false,
		url: '/wlcheckselectedcar',
		type: 'POST',
		data: formData,
		dataType : "json", 
		cache: false,
		contentType: false,
		processData: false, 
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		success: function(data){
			if (data == '1')
				result = true;
			else
				result = false;
		},
		error:function(xhr, ajaxOptions, thrownError){
			log('Не удалось проверить рабочий лист на наличие привязанного автомобиля');
			log("Ошибка: code-"+xhr.status+" "+thrownError);
			log(xhr.responseText);
			log(ajaxOptions);
		}
	});

	return result;
};


/**
 * Сохранить изменения в рабочем листе
 */
$(document).on('click', '#wl_save_changes', function() {
	wl_save_changes();
})


/** 
 * Добавить новую строку для ввода контактов 
 */
$(document).on('click', '#wl_contacts_add', function() {
	$(this).closest('.contact-row').clone().appendTo('#wl_contacts');
});


/**
 * Удалить строку контакта
 * Если остается одна строка, то очищает данные в ней
 */
$(document).on('click', '#wl_contacts_delete', function() {
	var contact_count = $('#wl_contacts .contact-row').length;

	if (contact_count > 1)
		$(this).closest('.contact-row').remove();
	else
	{
		$('input[name="contact_phone[]"]').val('');
		$('input[name="contact_email[]"]').val('');
		$('select[name="contact_marker[]"] option:first').prop('selected', true);
	}

});

/**
 * Редактирование трафика (модальное окно)
 * Открытие модального окна при двойном клике на поля Трафик, Спрос, Менеджер
 * во вкладке Рабочий лист
 */
$(document).on('dblclick', '.edit-traffic-modal', function() {
	var wl_id = $('span[name="wl_id"]').html();
	if (wl_id != '-')
	{
		$('#edit_traffic_modal').addClass('d-flex');
		$('#edit_traffic_modal').removeClass('hide-block');

		var formData = new FormData();
		formData.append('wl_id', wl_id);

		var parameters = formData;
		var url = '/traffic/getworklistinfo';

		$.when(ajaxWithFiles(parameters, url)).then(function(data){
			$('#edit_traffic_modal input[name="traffic_type_modal"][value="'+data.type+'"]').closest('.btn-light').trigger('click');
			$('#edit_traffic_modal input[name="traffic_car_modal"][value="'+data.model+'"]').closest('.btn-light').trigger('click');
			$('#edit_traffic_modal input[name="area_id_modal"][value="'+data.area+'"]').closest('.btn-light').trigger('click');
		});
	}
});

/**
 * Редактирование трафика (модальное окно)
 * Закрытие модального окна
 */
$(document).on('click', '#edit_traffic_modal a', function() {
	$('#edit_traffic_modal').addClass('hide-block');
	$('#edit_traffic_modal').removeClass('d-flex');
});

/**
 * Редактирование трафика (модальное окно)
 * Кнопка "Обновить трафик"
 */
$(document).on('click', '#traffic_modal_update', function() {
	$(this).blur();

	var wl_id = $('span[name="wl_id"]').html();
	var type = $('#edit_traffic_modal input[name="traffic_type_modal"]:checked').val();
	var model = $('#edit_traffic_modal input[name="traffic_car_modal"]:checked').val();
	var area = $('#edit_traffic_modal input[name="area_id_modal"]:checked').val();

	var formData = new FormData();
	formData.append('wl_id', wl_id);
	formData.append('type', type);
	formData.append('model', model);
	formData.append('area', area);

	var parameters = formData;
	var url = '/traffic/updateworklistinfo';

	$.when(ajaxWithFiles(parameters, url)).then(function(data){
		if (data == "1")
			$('#edit_traffic_modal a').trigger('click');
	});
});


/**
 * Загрузка рабочего листа из Автосклада
 */
 $(document).on('click', '.car-worklist', function() {
 	$(this).blur();
	
	var wl_id = $(this).attr('worklist-id');
	var formData = new FormData();
	formData.append('wl_id', wl_id);
	$.ajax({
		url: '/wlloaddata',
		type: 'POST',
        data: formData,
        dataType : "json", 
        cache: false,
        contentType: false,
        processData: false, 
		headers: {
        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    	},
    	success: function(data){
    		log('Рабочий лист с id '+wl_id+' успешно загружен');
    		$('#hidden_panel').css('right', '0');
			$('#disableContent').css('display', 'block');
			$('#hiddenTab a[href="#worksheet"]').tab('show');

			$('.ws-param').collapse('hide');
			$('.ws-registration').collapse('hide');

			clearContactBlock();
	    	worklistData(data);

	    	// Очистка лишних блоков в Подборе по потребностям
	    	var count = $('#carsByNeeds .col-3').length;
	    	if (count > 1)
	    	{
	    		while (count != 1) {
	    			$('#carsByNeeds .col-3').first().remove();
	    			count--;
	    		}
	    	}
	    	// Выбор интересующей модели как модели по умолчанию в Подборе по потребностям
	    	$('#carsByNeeds .col-3 select option:contains("'+data['traffic_model']+'")').prop('selected', true);
	    	
    	},
    	error:function(xhr, ajaxOptions, thrownError){
    		log('Не удалось загрузить данные рабочего листа');
	    	log("Ошибка: code-"+xhr.status+" "+thrownError);
	    	log(xhr.responseText);
	    	log(ajaxOptions);
	    }
	});
 });

/**
 * Очистка блока контактов
 */
function clearContactBlock() {
	var contacts = $('#wl_contacts .contact-row');
	var count = contacts.length;
	if ( count > 1 )
	{
		while (count != 1) {
			contacts.last().remove();
			count--;
		}
	}
};


/**
 * Автосклад
 * Функции для работы с кнопками Опций и ДО
 */
(function() {
	let modal = $("#optionsModal");
	let content = modal.find('.modal-body');
	let title = modal.find('.modal-title');

	$(document).on('click','.stock-button[mind="pack"]',function(){
		let mind = $(this).attr('mind');
		let id = $(this).attr('car-id');
		let url = '/get/car/packs';
			
		let parameters = {'id':id, 'mind':mind};
		$.when(ajax(parameters,url)).then(function(data){					
			str = '';
			title.html('Опционное оборудование')
			data = JSON.parse(data)
			for (i in data) {
				var pack = data[i].pack;
				str += '<b>Код пакета:</b> '+pack.code+'<br>';
				for (k in pack.option)
				{
					str += (parseInt(k) + parseInt(1)) + ') ' + pack.option[k].option.name + '<br>';
				}
				if (i<(data.length-1))
					str += '<hr>';
			}

			//beginFade(modal);

			if (!str)
				str = '<div class="text-center">На автомобиле не установлено опционного оборудования</div>';

			content.html(str);
		});
	});

	$(document).on('click','.stock-button[mind="dop"]',function(){
		let mind = $(this).attr('mind');
		let id = $(this).attr('car-id');
		let url = '/get/car/dops';
		title.html('Дополнительное оборудование');
			
		let parameters = {'id':id, 'mind':mind};
		$.when(ajax(parameters,url)).then(function(data){
			str = '';
			data = JSON.parse(data);log(data);
			if (data.install)
			{
				str += '<div><b>Установлено</b></div>';						
				for (i in data.install) {
					str += (parseInt(i) + parseInt(1)) + ') ' + data.install[i].dop.name + '<br>';
				}
			}
			if (data.offered)
			{
				str += '<hr><div><b>Предложено</b></div>'
				for (i in data.offered) {
					str += (parseInt(i) + parseInt(1)) + ') ' + data.offered[i].name + '<br>';
				}
			}

			//beginFade(modal);

			if (!str)
				str = '<div class="text-center">На автомобиле не установлено дополнительного оборудования</div>';

			content.html(str);
		});
	});

	/* Отключил т. к. добавил готовое модальное окно
	function beginFade() {
		if(modal.attr('timer'))
		{
			modal.attr('timer',parseFloat(modal.attr('timer'))+0.03)
		}
		else 
		{
			modal.css('display','block')
			modal.attr('timer',0)
		}
		let interval= parseFloat(modal.attr('timer'))
		modal.css('opacity',interval)
		if(interval<1.03)
			setTimeout(beginFade,1)
		else
			modal.removeAttr('timer')
	}
	*/
})();


/**
 * Раскрытие блоков во вкладках "Параметры" и "Оформление"
 * Изменение названия блока на надпись "Свернуть раздел"
 */
$(document).on('show.bs.collapse', '.ws-tab-content', function() {
	var tab_id = $(this).attr('id');
	$('a[data-toggle="collapse"][aria-controls="'+tab_id+'"]').html('Свернуть раздел <i class="icofont-simple-up">');
	$('a[data-toggle="collapse"][aria-controls="'+tab_id+'"]').closest('.ws-header').css('background-color', '#eee');
});

/**
 * Закрытие блоков во вкладках "Параметры" и "Оформление"
 * Установка первоначального названия блока
 */
$(document).on('hide.bs.collapse', '.ws-tab-content', function() {
	var tab_id = $(this).attr('id');
	$('a[data-toggle="collapse"][aria-controls="'+tab_id+'"]').html(getTabName(tab_id));
	$('a[data-toggle="collapse"][aria-controls="'+tab_id+'"]').closest('.ws-header').removeAttr('style');
});

/**
 * Получение названия блока по его id
 */
function getTabName(tab_id)
{
	switch(tab_id) {
		case 'wsparam1':
			return 'Пробная поездка';
			break;

		case 'wsparam2':
			return 'Подбор по потребностям';
			break;
		
		case 'wsparam3':
			return 'Конфигуратор';
			break;
		
		case 'wsparam4':
			return 'Дополнительное оборудование';
			break;
		
		case 'wsparam5':
			return 'Автомобиль клиента';
			break;
		
		case 'wsparam6':
			return 'Программа лояльности';
			break;
		
		case 'wsparam7':
			return 'Коммерческое предложение';
			break;

		case 'wsdesign1':
			return 'Платежи';
			break;

		case 'wsdesign2':
			return 'Договоры';
			break;

		case 'wsdesign3':
			return 'Продукты ОФУ';
			break;

		case 'wsdesign4':
			return 'Кредиты';
			break;

		case 'worklistMoreInfo':
			return 'Еще о клиенте';
			break;

		case 'worklistDocuments':
			return 'Рабочие документы';
			break;
	}
}