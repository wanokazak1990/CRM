/**
 * Функция сохранения изменений в рабочем листе
 */
function wl_save_changes(){
	var wl_id = $('span[name=wl_id]').html();
	var workstr = $("#worksheet").find("form").serializeArray();
	workstr.push({'name':'wl_id','value':wl_id});

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
    			log('Рабочий лист обновлен');
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
 * Загрузка и отрисовка блоков сохраненных моделей при открытии блока
 * Если нет - отрисовка начального блока выбора модели
 */
$(document).on('click', 'a[href="#wsparam2"]', function() {
	if (!$(this).hasClass('collapsed'))
	{
		/* Блок открыт */
		$('#selectCarOptions input:checkbox').prop('checked', false);

		var wl_id = $('span[name="wl_id"]').html();

		if (wl_id != '-')
		{
			var formData = new FormData();
			formData.append('wl_id', wl_id);

			$.ajax({
				url: '/wlgetneedcars',
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
		    		$('#carsByNeeds').html(data.blocks);
		    		if (data.options != null)
		    		{
		    			for (var i = 0; i < data.options.length; i++)
			    		{
			    			$('#selectCarOptions input[type="checkbox"][value="'+data.options[i]+'"]').prop('checked', true);
			    		}
		    		}
		    	},
		    	error:function(xhr, ajaxOptions, thrownError){
		    		log('Не удалось загрузить машины по потребностям');
			    	log("Ошибка: code-"+xhr.status+" "+thrownError);
			    	log(xhr.responseText);
			    	log(ajaxOptions);
			    }
			});
		}
	}
	else
	{
		/* Блок закрыт */
	}
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
	formData.append('data', data);

	var wl_id = $('span[name="wl_id"]').html();

	if (wl_id != '-')
	{
		formData.append('wl_id', wl_id);
		formData.append('wl_need_option', wl_need_option);

		$.ajax({
			url: '/wlsaveneedcars',
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
	    		//log(data);
	    		$('#carsByNeeds').html(data.blocks);
	    		if (data.options != null)
	    		{
	    			for (var i = 0; i < data.options.length; i++)
		    		{
		    			$('#selectCarOptions input[type="checkbox"][value="'+data.options[i]+'"]').prop('checked', true);
		    		}
	    		}
	    	},
	    	error:function(xhr, ajaxOptions, thrownError){
	    		log('Не удалось записать в БД машины по потребностям');
		    	log("Ошибка: code-"+xhr.status+" "+thrownError);
		    	log(xhr.responseText);
		    	log(ajaxOptions);
		    }
		});
	}

	formData.append('wl_need_sum', wl_need_sum);

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
    		log('Не удалось подобрать автомобили по потребностям');
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


/**
 * ВКЛАДКА "АВТОМОБИЛЬ"
 * Получение данных о привязанном автомобиле при открытии вкладки
 */
$(document).on('click', '#worksheetTabs a[href="#worksheet-auto"]', function() {
	var wl_id = $('span[name="wl_id"]').html();

	if (wl_id != '-')
	{
		var formData = new FormData();
		formData.append('wl_id', wl_id);
		$.ajax({
			url: '/wlgetcar',
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
	    		if (data == 'null')
	    		{
	    			$("#wl_car_empty").css('display', 'block');
	    			$("#wl_car").css('display', 'none');
	    		}
	    		else
	    		{
	    			$("#wl_car_empty").css('display', 'none');
	    			$("#wl_car").css('display', 'block');
		    		$('#wl_car_vin').html(data.car_vin);	    	
		    		$('#wl_car_name').html(data.car_name);	    	
		    		$('.wl_car_complect_name').html(data.complect_name);	    	
		    		$('#wl_car_complect_code').html(data.complect_code);	    	
		    		$('#wl_car_info').html(data.car_info);	    	
		    		$('#wl_car_img').attr('src', data.img);	    	
		    		$('#wl_car_img').css('background-color', data.color_code);	    	
		    		$('#wl_car_color_name').html(data.color_name);	    	
		    		$('#wl_car_color_example').css('background', data.color_code);
		    		$('#wl_car_rn_code').html(data.color_rn_code);
		    		$('#wl_car_dops').html(data.dops);
		    		$('#wl_car_dopprice').html(data.car_dopprice);
		    		$('#wl_car_installed').html(data.installed);
		    		$('#wl_car_complect_price').html(data.complect_price);
		    		$('#wl_car_options').html(data.options);
		    		$('#wl_car_fullprice').html(data.fullprice);

		    		$('#wl_car_opencard').addClass('opencar');
		    		$('#wl_car_opencard').attr('car-id', data.car_id);
		    	}
	    	},
	    	error:function(xhr, ajaxOptions, thrownError){
	    		log('Не удалось загрузить информацию об автомобиле');
		    	log("Ошибка: code-"+xhr.status+" "+thrownError);
		    	log(xhr.responseText);
		    	log(ajaxOptions);
		    }
		});
	}
});


/**
 * Кнопка "Снять резерв" во вкладке Автомобиль в Рабочем листе
 */
$(document).on('click', '#wl_car_remove', function() {
	var wl_id = $('span[name="wl_id"]').html();

	if (wl_id != '-')
	{
		var answer = confirm('Вы действительно хотите снять машину с резерва?');
		if (answer == true)
		{
			var formData = new FormData();
			formData.append('wl_id', wl_id);
			$.ajax({
				url: '/wlremovereserved',
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
		    		if (data == 'done') {
		    			$("#wl_car_empty").css('display', 'block');
		    			$("#wl_car").css('display', 'none');
		    			$('#crmTabs a[href="#stock"]').click();
		    		} else {
		    			$("#wl_car_empty").css('display', 'none');
		    			$("#wl_car").css('display', 'block');
		    		}
		    	},
		    	error:function(xhr, ajaxOptions, thrownError){
		    		log('Не удалось снять резерв');
			    	log("Ошибка: code-"+xhr.status+" "+thrownError);
			    	log(xhr.responseText);
			    	log(ajaxOptions);
			    }
			});
		}
	}
});


/**
 * Дополнительное оборудование
 * Открытие блока
 */
$(document).on('click', 'a[href="#wsparam4"]', function() {
	if (!$(this).hasClass('collapsed'))
	{
		// Блок открыт
		var wl_id = $('span[name="wl_id"]').html();

		if (wl_id != '-')
		{
			wlGetDops(wl_id);
		}
	}
	else
	{
		// Блок закрыт
	}
});

/**
 * Дополнительное оборудование
 * Кнопка "Установить"
 */
$(document).on('click', '#wl_dops_install', function() {
	var wl_id = $('span[name="wl_id"]').html();
	if (wl_id != '-')
	{
		var checked = [];
		$('#wl_dops_all input:checkbox:checked').each(function () {
			checked.push($(this).val());
		});

		if (checked.length > 0)
		{
			var sum = $('#wl_dops_offered').val();
			if (sum == '')
			{
				alert('Введите сумму предложенного оборудования!');
			}
			else
			{
				var answer = confirm('Вы уверены, что хотите установить выбранное оборудование в автомобиль?');
				if (answer == true)
				{
					var formData = new FormData();
					formData.append('wl_id', wl_id);
					formData.append('wl_dops', checked);
					formData.append('wl_dops_sum', sum);
					$.ajax({
						url: '/wlinstalldops',
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
				    		if (data == 'done')
				    		{
				    			$('#wl_dops_offered').val('');
				    			wlGetDops(wl_id);
				    		}
				    	},
				    	error:function(xhr, ajaxOptions, thrownError){
				    		log('Не удалось установить доп. оборудование');
					    	log("Ошибка: code-"+xhr.status+" "+thrownError);
					    	log(xhr.responseText);
					    	log(ajaxOptions);
					    }
					});
				}
			}
		}
	}
	
});


function wlGetDops(wl_id)
{
	var formData = new FormData();
	formData.append('wl_id', wl_id);
	$.ajax({
		url: '/wlgetdops',
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
    		$('#wl_dops_dopprice').val(data.dopprice);		    	
    		$('#wl_dops_list').html(data.dops);		    	
    		$('#wl_dops_all').html(data.all_dops);
    	},
    	error:function(xhr, ajaxOptions, thrownError){
    		log('Не удалось получить данные о доп. оборудовании автомобиля');
	    	log("Ошибка: code-"+xhr.status+" "+thrownError);
	    	log(xhr.responseText);
	    	log(ajaxOptions);
	    }
	});
}


$(document).on('keyup', '#wl_dops_offered', function() {
	$('#wl_dops_sum').val(Number($(this).val()) + Number($('#wl_dops_dopprice').val()));
});


$(document).on('click', '#wl_create_comment', function(){
	$(this).blur();
	var text = $('#wl_new_comment').val();
	if (text != '')
	{
		$('#wl_comments_list').val($.trim("01.01.2019 в 00:00 Пупкин\n" + text + "\n" + $('#wl_comments_list').val()));
		$('#wl_new_comment').val('');
	}
});


/**
 * КОММЕРЧЕСКОЕ ПРЕДЛОЖЕНИЕ
 * Создание Коммерческого предложения по кнопке
 * id - идентификатор рабочего листа
 */
$(document).on('click', '#create_offer', function() {
	var id = $('span[name="wl_id"]').html();
	if (id != '-')
	{
		if ($('.check-car:checked').length > 0)
		{
			let form = $('#get-pdf')
			form.html('')
			form.append('<input name="_token" value="'+$('meta[name="csrf-token"]').attr('content')+'" type="hidden">')
			$('.check-car:checked').each(function(){
			    form.append('<input type="hidden" name="pdf_cars[]" value="'+$(this).val()+'">');
			});
			form.submit()
		}
		else
		{
			var url = '/createoffer/' + id;
			window.open(url);
		}
	}
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
 * Загрузка инфо об автомобиле клиента при открытии 
 * блока "Автомобиль клиента" в Рабочем листе (вкладка Параметры)
 */
$(document).on('click', 'a[href="#wsparam5"]', function() {
	if (!$(this).hasClass('collapsed'))
	{
		/* Блок открыт */

		var wl_id = $('span[name=wl_id]').html();

		// Если рабочий лист загружен, то получаем данные об автомобиле
		if (wl_id != '-')
		{
			var parameters = {'wl_id':wl_id};
			var url = '/worklist/client/oldcar';
			$.when(
				ajax(parameters,url)
					.then(function(data){
						$(".old-car").html(data)
					})
			)
		}
	}
	else
	{
		/* Блок закрыт */
	}
});



(function(){

	let wl_id = ''
	let modal = ''
	let photoContainer = ''

	$(document).on('click','.oldcar-photo',function(){
		wl_id = $('span[name=wl_id]').html()
		modal = $(this).closest('form').find('.photo-load')
		photoContainer = modal.find('.old-photo')
		modal.css('display','block')
		log(modal.attr('class'))
	})

	$(document).on('click','.close',function(){
		$(this).parent().css('display','none')
	})
	$(document).on('click','.search-photo',function(){
		$(this).parent().find('input').click();
	})
	$(document).on('click','.load',function(){		
		var formData = new FormData();
		var input = modal.find('[name="photo"]')
		files = input[0].files
		$.each( files, function( key, value ){
			formData.append( key, value );
		});
		formData.append('wl_id',wl_id)
		var parameters = formData
		var url = '/worklist/client/oldcar/photo'
		$.when(
			ajaxWithFiles(parameters,url)
				.then(function(data){
					for (i in data)
						photoContainer.append('<img src="'+data[i]+'">')
				})
		)
	})
})();


