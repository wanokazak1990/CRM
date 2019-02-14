/**
 * Функция сохранения изменений в рабочем листе
 */
function wl_save_changes(){
	var wl_id = $('span[name=wl_id]').html();
	var workstr = $("#worksheet").find("form").serialize()+'&wl_id='+wl_id;
	var worksheet = JSON.stringify(workstr);

	$.ajax({
		url: '/wlsavechanges',
		type: 'POST',
        data: {'worksheet': worksheet},
		headers: {
        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    	},
    	success: function(data){
    		if (data == 1)
    		{
    			log('Рабочий лист обновлен');
    			alert('Рабочий лист обновлен');	
    		}
    	},
    	error:function(xhr, ajaxOptions, thrownError){
    		log('Не могу принять трафик');
	    	log("Ошибка: code-"+xhr.status+" "+thrownError);
	    	log(xhr.responseText);
	    	log(ajaxOptions)
	    	log('ddd')
	    }
	});
}


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
	$(this).closest('.input-group').clone().appendTo('#wl_contacts');
});


/**
 * Удалить строку контакта
 * Если остается одна строка, то очищает данные в ней
 */
$(document).on('click', '#wl_contacts_delete', function() {
	var contact_count = $('#wl_contacts .input-group').length;

	if (contact_count > 1)
		$(this).closest('.input-group').remove();
	else
	{
		$('input[name="contact_phone[]"]').val('');
		$('input[name="contact_email[]"]').val('');
		$('select[name="contact_marker[]"] option:first').prop('selected', true);
	}

});

/**
 * ПРОБНАЯ ПОЕЗДКА
 * Добавить машину в Пробную поездку (при нажатии "Оформить доверенность" в модальном окне)
 */
$(document).on('click', '.wl_submit_testdrive', function() {
	var model_id = $(this).attr('model_id');
	var wl_id = $('span[name="wl_id"]').html();
	
	var formData = new FormData();
	formData.append('wl_id', wl_id);
	formData.append('model_id', model_id);
	$.ajax({
		url: '/wladdtestdrive',
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
    		$('#testdriveCars').html(data);    		
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
 * ПРОБНАЯ ПОЕЗДКА
 * При открытии блока - загрузка и отображение выбранных машин для тест-драйва
 */
$(document).on('click', 'a[href="#wsparam1"]', function() {
	if (!$(this).hasClass('collapsed'))
	{
		// Блок открыт
		//alert('Открыли');

		var wl_id = $('span[name="wl_id"]').html();
		
		var formData = new FormData();
		formData.append('wl_id', wl_id);
		$.ajax({
			url: '/wlloadtestdrive',
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
	    		$('#testdriveCars').html(data);
	    	},
	    	error:function(xhr, ajaxOptions, thrownError){
	    		log('Не удалось загрузить данные пробных поездок');
		    	log("Ошибка: code-"+xhr.status+" "+thrownError);
		    	log(xhr.responseText);
		    	log(ajaxOptions);
		    }
		});

	}
	else
	{
		// Блок закрыт
		//alert('Закрыли');
	}
});


/**
 * ПРОБНАЯ ПОЕЗДКА
 * Удаление машины из Пробной поездки по нажатию на иконку удаления
 * Получение обновленного списка машин для Пробной поездки
 */
$(document).on('click', '.wl_del_testdrive', function() {
	var testdrive_id = $(this).attr('id');

	var formData = new FormData();
	formData.append('testdrive_id', testdrive_id);
	$.ajax({
		url: '/wldeltestdrive',
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
	    	$('#testdriveCars').html(data);
    	},
    	error:function(xhr, ajaxOptions, thrownError){
    		log('Не удалось удалить данные пробной поездки');
	    	log("Ошибка: code-"+xhr.status+" "+thrownError);
	    	log(xhr.responseText);
	    	log(ajaxOptions);
	    }
	});
});


/**
 * ПОДБОР ПО ПОТРЕБНОСТЯМ
 * Добавление новой модели 
 */
$(document).on('click', '#addSelectedCar', function() {
	var clone = $('#carsByNeeds').find('.col-3').first().clone();
	clone.appendTo('#carsByNeeds');
});


/**
 * ПОДБОР ПО ПОТРЕБНОСТЯМ
 * Удаление модели (если одна - сброс селектов) 
 */
$(document).on('click', '.removeSelectedCar', function() {
	var count = $('#carsByNeeds .col-3').length;

	if (count > 1)
		$(this).closest('.col-3').remove();
	else
	{
		$('#carsByNeeds .col-3 select').prop('selectedIndex', 0);
	}
});


/**
 * ПОДБОР ПО ПОТРЕБНОСТЯМ
 * Кнопка "Найти в автоскладе"
 */
$(document).on('click', '#getListByNeeds', function() {
	$(this).blur();

	function Car(){
		this.model = '',
		this.transmission = '',
		this.wheel = ''
	};

	var data = [];
	$('#carsByNeeds .border').each(function() {
		var obj = new Car();
		obj.model = $(this).find('.wl_need_model').val();
		obj.transmission = $(this).find('.wl_need_transmission').val();
		obj.wheel = $(this).find('.wl_need_wheel').val();
		data.push(obj);
	});
	data = JSON.stringify(data);

	var wl_need_option = [];
	$('#selectCarOptions input:checkbox:checked').each(function() {
		wl_need_option.push($(this).val());
	});

	var wl_need_sum = $('#wl_need_sum').val();

	var formData = new FormData();
	formData.append('wl_need_option', wl_need_option);
	formData.append('wl_need_sum', wl_need_sum);
	formData.append('data', data);

	$.ajax({
		url: '/crmgetcarsbyneeds',
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
    		// Закрываем боковую панель
			$('#hidden_panel').css('right', '-50%');
			$('#disableContent').css('display', 'none');
			// Переходим на вкладку Автосклад
			$('#crmTabs a[href="#stock"]').tab('show');
			// Вставляем полученные данные в таблицу
    		var parent = $("#crmTabPanels").find("div[aria-labelledby='stock-tab']");
    		parent.find('table').html("");
	    	getTitleContent(parent,data['titles']);		    
	    	getDataContent(parent,data['list']);		    	
	    	getPaginationContent(parent,data['links']);  
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
 * ПОДБОР ПО ПОТРЕБНОСТЯМ
 * Кнопка "Зарезервировать"
 */
$(document).on('click', '#wl_need_reserve', function() {
	$(this).blur();

	var wl_id = $('span[name="wl_id"]').html();

	if (wl_id == '-')
		alert('Рабочий лист не загружен!');
	else
	{
		if ($('.check-car:checked').length != 1)
			alert('Необходимо выбрать одну машину!');
		else
		{
			var car_id = $('.check-car:checked').val();
			
			var formData = new FormData();
			formData.append('car_id', car_id);
			formData.append('wl_id', wl_id);
			$.ajax({
				url: '/wlreservecar',
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
					if (data == 'OK')
					{
						alert('Автомобиль зарезервирован.');
						wl_save_changes(); // Обновляем данные в рабочем листе
					}
					else
						alert('Не удалось зарезервировать автомобиль.');
				},
				error:function(xhr, ajaxOptions, thrownError){
					log('Автомобиль не зарезервирован');
			    	log("Ошибка: code-"+xhr.status+" "+thrownError);
			    	log(xhr.responseText);
			    	log(ajaxOptions);
			    }
			});
		}
	}
});


$(document).on('click', '#worksheetTabs a[href="#worksheet-auto"]', function() {
	alert('Открыта вкладка "Автомобиль"');
});


/**
 * ТЕСТОВАЯ ЗАГРУЗКА РАБОЧЕГО ЛИСТА ПО НАЖАНИЮ НА БОЛЬШУЮ КРАСНУЮ КНОПКУ В ШАПКЕ САЙТА
 */
 $(document).on('click', '#test_load_worklist', function() {
 	$(this).blur();
	
	var wl_id = 69;
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