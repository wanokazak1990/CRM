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
    			log('ok');
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
    		log('ok');
    		$('#hidden_panel').css('right', '0');
			$('#disableContent').css('display', 'block');
			$('#hiddenTab a[href="#worksheet"]').tab('show');
	    	worklistData(data);
    	},
    	error:function(xhr, ajaxOptions, thrownError){
    		log('Не удалось загрузить данные рабочего листа');
	    	log("Ошибка: code-"+xhr.status+" "+thrownError);
	    	log(xhr.responseText);
	    	log(ajaxOptions);
	    }
	});
 });