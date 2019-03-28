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
 * Функция получения данных о машинах в конфигураторе
 * Используется при сохранении Рабочего листа
 */
function getCfgCars() {
	var cfg_cars = [];

	if ($('.wl_cfg_cars').length > 0)
	{
		$('.wl_cfg_cars').each(function (index) {
			var model_id = $(this).find('.cfg_model').val();
			var complect_id = $(this).find('.cfg_complect').val();

			if (model_id != null && complect_id != null)
			{
				var options = [];

				$(this).find('input[name="packs[]"]:checkbox:checked').each(function() {
					options.push($(this).val());
				});

				cfg_cars.push ({
					'cfg_model':model_id,
					'cfg_complect':complect_id,
					'cfg_color_id':$(this).find('#cfg_color_id').val(),
					'options':options
				});
			}
			else
				return null;

		});
	}

	return JSON.stringify(cfg_cars);
}

/**
 * Функция получения данных о Продуктах ОФУ
 * Используется при сохранении Рабочего листа
 */
function getOfuProducts() {
	if ($('#ofu_products').html() == '')
		return null;
	else
	{
		var ofu_products = [];

		if ($('.ofu-block').length > 0)
		{
			$('.ofu-block').each(function (index) {
				var author = $(this).find('.ofu-block-authors').val();
				var product = $(this).find('.ofu-block-products').val();

				if (author != null && product != null)
				{
					ofu_products[index] = {
						'author':author,
						'product':product,
						'partner':$(this).find('.ofu-block-partners').val(),
						'price':$(this).find('.ofu-block-price').val(),
						'creation_date':$(this).find('.ofu-block-creation-date').val(),
						'end_date':$(this).find('.ofu-block-end-date').val(),
						'profit':$(this).find('.ofu-block-profit').val(),
						'profit_date':$(this).find('.ofu-block-profit-date').val()
					};
				}
			});
		}

		return JSON.stringify(ofu_products);
	}
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

		    		if (data.purchase_type != null)
		    			$('#wl_need_purchase_type').val(data.purchase_type);
		    		
		    		if (data.pay_type != null)
		    			$('#wl_need_pay_type').val(data.pay_type);

		    		if (data.firstpay != null)
		    			$('#wl_need_firstpay').val(data.firstpay);

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
 * Кнопка "Поиск на складе"
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

	/*var wl_need_sum = $('#wl_need_sum').val();*/
	var purchase_type = $('#wl_need_purchase_type').val();
	var pay_type = $('#wl_need_pay_type').val();
	var firstpay = $('#wl_need_firstpay').val();

	var formData = new FormData();
	formData.append('data', data);

	var wl_id = $('span[name="wl_id"]').html();

	if (wl_id != '-')
	{
		formData.append('wl_id', wl_id);
		formData.append('wl_need_option', wl_need_option);
		formData.append('purchase_type', purchase_type);
		formData.append('pay_type', pay_type);
		formData.append('firstpay', firstpay);

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

	/*formData.append('wl_need_sum', wl_need_sum);*/

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
		var check_selected_car = checkSelectedCar(wl_id);
		if (check_selected_car == true)
		{
			alert('У текущего рабочего листа уже зарезервирован автомобиль!');
		}
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
	}
});


/**
 * Конфигуратор
 * Открытие блока
 */
$(document).on('click', 'a[href="#wsparam3"]', function() {
	if (!$(this).hasClass('collapsed'))
	{
		// Блок открыт

		var wl_id = $('span[name="wl_id"]').html();

		if (wl_id != '-')
		{
			var formData = new FormData();
			formData.append('wl_id', wl_id);
			$.ajax({
				//async: false,
				url: '/wlgetcfgcars',
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
					
					var block = $('.wl_cfg_cars').first().clone();
					clearCfgBlock(block);

					if (data.length == 0)
					{
						$('#wl_cfg_car_blocks').html(block);
					}
					else
					{
						$('#wl_cfg_car_blocks').html('');
						var color_btns = [];
						var all_options = [];

						for (i = 0; i < data.length; i++)
						{
							var car = block.clone();
							

							var car_model = car.find('.cfg_model');
							car_model.val(data[i].model_id);
							getComplectsCfg(car_model);
							modelObjCfg(car_model);

							var car_complect = car.find('.cfg_complect');
							car_complect.val(data[i].complect_id);
							carCountInStock(car_complect);
							selectComplect(car_complect);

							var car_checkbox = car.find('.wl_cfg_checkbox input[type="checkbox"]');
							car_checkbox.addClass('cfg_check_car');
							car_checkbox.attr('cfg-id', data[i].id);

							if (data[i].color_id != null)
								color_btns.push(car.find('.cfg-color button[color-id="'+data[i].color_id+'"]'));

							var options = JSON.parse(data[i].options);
							if (options.length != 0)
							{
								options.forEach(function(val, index) {
									all_options.push(car.find('input[value="'+val+'"]'));
								});
							}

							$('#wl_cfg_car_blocks').append(car);
						}

						if (color_btns.length != 0)
						{
							color_btns.forEach(function(elem, index) {
								elem.trigger('click');
							});
						}

						if (all_options.length != 0)
						{
							all_options.forEach(function(elem, index) {
								elem.trigger('click');
							});
						}

						$('#wl_cfg_count').html($('.wl_cfg_cars').length);
					}

				},
				error:function(xhr, ajaxOptions, thrownError){
					log('Не удалось получить сохраненные в Конфигураторе машины');
			    	log("Ошибка: code-"+xhr.status+" "+thrownError);
			    	log(xhr.responseText);
			    	log(ajaxOptions);
			    }
			});
		}

	}
	else
	{
		// Блок закрыт
	}
});


/**
 * Конфигуратор
 * Создание заявки на автомобиль
 */
$(document).on('click', '#wl_cfg_create_request', function() {
	var wl_id = $('span[name="wl_id"]').html();

	if (wl_id != '-')
	{
		var check_selected_car = checkSelectedCar(wl_id);
		if (check_selected_car == true)
		{
			alert('У текущего рабочего листа уже зарезервирован автомобиль!');
		}
		else
		{
			if ($('.wl_cfg_checkbox input[type="checkbox"]:checked').length != 1)
			{
				alert('Необходимо выбрать один автомобиль для создания заявки');
			}
			else
			{
				var checked_car = $('.wl_cfg_checkbox input[type="checkbox"]:checked').first();
				var formData = new FormData();
				formData.append('wl_id', wl_id);
				
			    if (checked_car.hasClass('cfg_check_car'))
			    {
			    	var cfg_id = checked_car.attr('cfg-id');
					formData.append('cfg_id', cfg_id);
			    }
			    else
			    {
			    	var car = checked_car.closest('.wl_cfg_cars');
					var model_id = car.find('.cfg_model').val();
					var complect_id = car.find('.cfg_complect').val();

					if (model_id != null && complect_id != null)
					{
						var options = [];

						car.find('input[name="packs[]"]:checkbox:checked').each(function() {
							options.push($(this).val());
						});

						var cfg_car = {
							'cfg_model':model_id,
							'cfg_complect':complect_id,
							'cfg_color_id':car.find('#cfg_color_id').val(),
							'options':options
						};

						formData.append('cfg_car', JSON.stringify(cfg_car));
					}
			    }

				$.ajax({
					url: '/cfgcreaterequest',
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
							$('.ws-param').collapse('hide');
							alert("Заявка создана\nАвтомобиль зарезервирован");
						}
					},
					error:function(xhr, ajaxOptions, thrownError){
						log('Не удалось создать заявку на автомобиль');
						log("Ошибка: code-"+xhr.status+" "+thrownError);
						log(xhr.responseText);
						log(ajaxOptions);
					}
				});
			}
		}
	}
});

/**
 * Функция проверки, есть ли у рабочего листа привязанный (зарезервированный) автомобиль
 */
function checkSelectedCar(worklist_id) {
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
	    		if (data == '0')
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
		    		$('#wl_car_fullprice').html(data.fullprice + ' ['+ data.car_sale +'] ['+data.dop_sale+']');

		    		$('#wl_car_opencard').addClass('opencar');
		    		$('#wl_car_opencard').attr('car-id', data.car_id);

		    		$('#wl_car_company').html('');
		    		for (i in data.company)
		    		{
			    		$('#wl_car_company').append('<li>'+data.company[i].company.name+'</li>');
		    		}
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
 * ВКЛАДКА "АВТОМОБИЛЬ"
 * Кнопка "Снять резерв"
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
			$('#wl_dops_offered').val('');
			$('#wl_dops_all input:checkbox').prop('checked', false);
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

/**
 * Дополнительное оборудование
 * Функция получения сохраненной информации по доп. оборудованию 
 */
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
    		if (data.offered_dops != null)
    		{
    			for (var i = 0; i < data.offered_dops.length; i++)
	    		{
	    			$('#wl_dops_all input[type="checkbox"][value="'+data.offered_dops[i]+'"]').prop('checked', true);
	    		}
    		}
    		if (data.offered_price != null)
    		{
    			$('#wl_dops_offered').val(data.offered_price);
    		}
    	},
    	error:function(xhr, ajaxOptions, thrownError){
    		log('Не удалось получить данные о доп. оборудовании автомобиля');
	    	log("Ошибка: code-"+xhr.status+" "+thrownError);
	    	log(xhr.responseText);
	    	log(ajaxOptions);
	    }
	});
}


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
 * Открытие блока
 */
$(document).on('click', 'a[href="#wsparam7"]', function() {
	if (!$(this).hasClass('collapsed'))
	{
		// Блок открыт
		var wl_id = $('span[name="wl_id"]').html();
		if (wl_id != '-')
		{
			var formData = new FormData();
			formData.append('wl_id', wl_id);
			$.ajax({
				url: '/wlgetoffers',
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
		    		if (data != '0')
		    		{
		    			var table = $('#wl_offers_list');
		    			table.html('');

		    			data.forEach(function(item, index) {
		    				str = '<tr><td style="width: 20%;">'+item.creation_date+'</td><td>'+item.vins
		    					+'</td><td style="width: 20%;"><a href="javascript://" id="'+item.offer_id+'" class="open-offer">Открыть</a></td></tr>';
			    			table.append(str);
		    			});
		    		}
		    		else
		    		{
		    			var table = $('#wl_offers_list');
		    			table.html('');
		    			table.html('Коммерческих предложений еще не создавалось');
		    		}
		    	},
		    	error:function(xhr, ajaxOptions, thrownError){
		    		log('Не удалось получить архив коммерческих предложений');
			    	log("Ошибка: code-"+xhr.status+" "+thrownError);
			    	log(xhr.responseText);
			    	log(ajaxOptions);
			    }
			});
		}
	}
	else
	{
		// Блок закрыт
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
		let form = $('#get-pdf')
		let action = '/createoffer/' + id;
		form.attr('action', action);
		form.html('');
		form.append('<input name="_token" value="'+$('meta[name="csrf-token"]').attr('content')+'" type="hidden">');

		if ($('.check-car:checked').length > 0)
		{	
			$('.check-car:checked').each(function(){
			    form.append('<input type="hidden" name="cars_ids[]" value="'+$(this).val()+'">');
			});
		}

		if ($('.cfg_check_car:checked').length > 0)
		{
			$('.cfg_check_car:checked').each(function() {
			    form.append('<input type="hidden" name="cfg_cars[]" value="'+$(this).attr('cfg-id')+'">');
			});
		}

		form.submit();
	}
});

/**
 * КОММЕРЧЕСКОЕ ПРЕДЛОЖЕНИЕ
 * Открытие Коммерческого предложения из списка (архива) коммерческих предложений
 * в Рабочем листе
 */
$(document).on('click', '.open-offer', function() {
	var offer_id = $(this).attr('id');

	let form = $('#get-pdf')
	let action = '/openoffer';
	form.attr('action', action);
	form.html('');
	form.append('<input name="_token" value="'+$('meta[name="csrf-token"]').attr('content')+'" type="hidden">');
	form.append('<input type="hidden" name="offer_id" value="'+offer_id+'">');
	form.submit();
});


/**
 * Продукты ОФУ
 * Открытие блока
 */
$(document).on('shown.bs.collapse', '#wsdesign3', function() {
	var wl_id = $('span[name="wl_id"]').html();
	if (wl_id != '-')
	{
		var check_selected_car = checkSelectedCar(wl_id);
		if (check_selected_car == true)
		{
			var formData = new FormData();
			formData.append('wl_id', wl_id);

			var parameters = formData 
			var url = '/getofudata'
			$.when(ajaxWithFiles(parameters,url).then(function(data){
				// Вставляем блоки с сервисами, которые подходят к зарезервированной машине
				if (data.services != '')
				{
					data.services.forEach(function(item, index) {
		    			var block = getServiceBlock(item);
						$('#services_list').append(block);
		    		});
				}
				else
					$('#services_list').html('<span class="ofu_empty_services font-weight-bold font-italic text-primary d-flex justify-content-center">Не найдено сервисов для привязанной машины</span>');

				// Отмечаем сервисы, которые были выбраны в Программе лояльности
				if (data.checked_services != '')
				{
					data.checked_services.forEach(function(item, index) {
	    				var checkbox = $('input[class="wl-service-check"][id="'+item.company_id+'"]');
	    				checkbox.prop('checked', true);
	    				checkbox.closest('span').css('color', '#f5770a');
						checkbox.closest('.input-group').find('input[type="number"]').removeAttr('disabled');
						checkbox.closest('.input-group').find('input[type="number"]').val(item.sum);
	    			});
	    			
	    			getServiceSum();
				}

				// Вставляем блоки продуктов
				if ($('.ofu_empty_services').length > 0)
					$('#ofu_products').html('');
				else
				{
					$('#ofu_products').html(data.ofu_products);
					$('#ofu_products_count').html( $('.ofu-block').length );
				}
			}));
		}
	}
});

/**
 * Продукты ОФУ
 * Закрытие блока
 */
$(document).on('hide.bs.collapse', '#wsdesign3', function() {
	$('#services_list').html('');
	$('#ofu_products').html('');
	$('#ofu_products_count').html('0');
	$('#wl_service_budget').html('0 р.');
});

/**
 * Функция отрисовки блока сервиса для Продуктов ОФУ
 */
function getServiceBlock(item)
{
	var block = '<div class="input-group wl-ofu-services">'
		 + '<div class="col-9 d-flex align-items-center">'
			+ '<span class="flex-grow-1">'
				+ '<input type="checkbox" class="wl-service-check" id="'+ item.id +'"> ' + item.name
			+ '</span>'
			+ '<a href="javascript://" description="' + item.text + '" class="wl-service-description">'
				+ '<i class="fas fa-question-circle"></i>'
			+ '</a>'
		+ '</div>'
		+ '<input type="text" class="col-3 form-control wl-service-price" value="0" disabled>'
	+ '</div>';

	return block;
}

/**
 * Продукты ОФУ
 * Отображение описания сервиса
 */
$(document).on('click', '.wl-service-description', function() {
	alert($(this).attr('description'));
});

/**
 * Продукты ОФУ
 * Изменение цвета и блокировка/разблокировка инпута при выборе сервиса
 */
$(document).on('click', '.wl-service-check', function() {
	if ($(this).is(':checked'))
	{
		$(this).closest('span').css('color', '#f5770a');
		$(this).closest('.input-group').find('input[type="text"]').removeAttr('disabled');
		var id = $(this).attr('id');
		
		var block = ofuAddBlock();
		block.find('.ofu-block-products').val(id);
	    		
		$('#ofu_products').append(block);
		$('#ofu_products_count').html( $('.ofu-block').length );
	}
	else
	{
		$(this).closest('span').removeAttr('style');
		$(this).closest('.input-group').find('input[type="text"]').attr('disabled', 'disabled');
		$(this).closest('.input-group').find('input[type="text"]').val(0);
		getServiceSum();
	}
});


/**
 * Продукты ОФУ
 * Подсчет Бюджета Клиента при изменении полей с ценами сервиса
 */
$(document).on('change', '.wl-service-price', function() {
	$(this).val(normalizeNumberValue($(this).val()));
	getServiceSum();
});

/**
 * Функция корректировки атрибута "value" у input[type="number"]
 */
function normalizeNumberValue(value)
{
	if (value == '')
		value = 0;

	if (value[0] == '0' && value.length > 1)
		value = value.substr(1);

	return value;
}

/**
 * Функция подсчета Бюджета Клиента в Продуктах ОФУ
 */
function getServiceSum()
{
	var sum = 0;

	$('.wl-service-price').each(function() {
		sum = sum + Number($(this).val());
	});

	$('#wl_service_budget').html(sum + ' р.');
}

/**
 * Продукты ОФУ
 * Добавление нового пустого блока
 */
$(document).on('click', '#ofu_add_block', function() {
	var block = ofuAddBlock();
	$('#ofu_products').append(block);
	$('#ofu_products_count').html( $('.ofu-block').length );
});

/**
 * Функция получения пустого блока для добавления в Продуктах ОФУ
 */
function ofuAddBlock()
{
	var block = '';

	$.ajax({
		async: false,
		url: '/ofuaddblock',
		type: 'POST',
		headers: {
        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    	},
    	success: function(data){
    		block = $(data);
    	},
    	error:function(xhr, ajaxOptions, thrownError){
    		log('Не удалось добавить блок продукта ОФУ');
	    	log("Ошибка: code-"+xhr.status+" "+thrownError);
	    	log(xhr.responseText);
	    	log(ajaxOptions);
	    }
	});

	return block;
}

/**
 * Продукты ОФУ
 * Удаление блока продукта (рассчета)
 */
$(document).on('click', '.ofu-remove-block', function() {
	if ($('.ofu-block').length > 1)
	{
		$(this).closest('.ofu-block').remove();
		$('#ofu_products_count').html( $('.ofu-block').length );
	}
	else
	{
		var block = $(this).closest('.ofu-block');
		block.find('input[type="text"]').val('');
		block.find('select').prop('selectedIndex', 0);
		$('#ofu_products_count').html('0');
	}
});

/**
 * Продукты ОФУ
 * Выбор продукта в списке Продукт в блоке рассчета
 */
$(document).on('change', '.ofu-block-products', function() {
	var product_id = $(this).val();

	$('.wl-service-check').each(function() {
		if ($(this).attr('id') == product_id)
		{
			if (!$(this).is(':checked'))
			{
				$(this).prop('checked', true);
				$(this).closest('span').css('color', '#f5770a');
				$(this).closest('.input-group').find('input[type="number"]').removeAttr('disabled');
			}
		}
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



(function(){//БЛОК ФУНКЦИЙ ДЛЯ ОТКРЫТИЯ И СОХРАНЕНИЯ БЛОКА СТАРЫЙ АВТОМОБИЛЬ КЛИЕНТА
	let wl_id = '' //ид рабочего листа
	let modal = '' //объект модали
	let photoContainer = '' //блок где хранятся загруженные фото
	$(document).on('click','.oldcar-photo',function(){ //клик по вкладке авто клиента
		wl_id = $('span[name=wl_id]').html() //беру ид раблиста
		modal = $(this).closest('form').find('.photo-load') //нахжу меню модаль
		photoContainer = modal.find('.old-photo') //в модали нахожу блок фото
		modal.css('display','block') //делаю модаль видимой
		log(modal.attr('class'))
	})
	$(document).on('click','.close',function(){ //клик по крестику модали
		$(this).parent().css('display','none') //прячу модаль
	})
	$(document).on('click','.search-photo',function(){//клик по кнопке фотографии
		$(this).parent().find('input').click(); //прогрпммно кликаю по инпутфайл и вызываю его окно выбора
	})
	$(document).on('click','.load',function(){		//загрузка фоток
		var formData = new FormData(); //пустой объект формдата, тк передаю фаилы
		var input = modal.find('[name="photo"]') //нахожу объект который хранит фаилы
		files = input[0].files //записываю выбранные фаилы  переменную
		$.each( files, function( key, value ){ //из переменой записываю фаилы в объект формдата перебором
			formData.append( key, value );
		});
		formData.append('wl_id',wl_id) //записываю в объект ид раб листа
		var parameters = formData //готовые данные для отправки
		var url = '/worklist/client/oldcar/photo' //адрес отправки
		$.when( 
			ajaxWithFiles(parameters,url) //аякс функция для отправки фаилов (хранится в мейн.жс)
				.then(function(data){
					for (i in data) //обхожу полученный ответ
						photoContainer.append('<img src="'+data[i]+'">')//и дозаписываю в блок фотографий
				})
		)
	})
})();



(function(){//Блок функций для работы с программой лояльности
	let wl_id = ''
	let container = ''
	let loyalty = $('.loyalty_program')
	let typeProgram = ['Скидки','Подарки','Сервисы']
	let modal = $("#pv-modal")
	let sale = 0;
	var repartion = 0;
	let dopsale = 0;
	let doprepartion = 0;

	$(document).on('click','a[href="#wsparam6"]',function(){
		if (!$(this).hasClass('collapsed'))
		{
			// Блок открыт
			wl_id = $('span[name=wl_id]').html(); //беру ид раблиста
			if (wl_id != '' && wl_id != '-')
			{
				let url = '/worklist/loyalty/program'
				let parameters = {'wl_id':wl_id}
				$.when(
					ajax(parameters,url)
						.then(function(data){
							sale = 0;
							repartion = 0;
							dopsale = 0;
							doprepartion = 0;
							loyalty.html('')
							modal.find(".pv-action").remove()
							writeProgram(JSON.parse(data))
							priceVidget()
						})
				)
			}
		}
		else
		{
			// Блок закрыт
		}
	});

	$(document).on('click','#open-pv-vidget',function(){
		priceVidget();
	});

	function priceVidget()
	{
		url = '/worklist/car/price'
		let parameters = {'wl_id':wl_id}
		$.when(
			ajax(parameters,url)
				.then(function(data){					
					var price = JSON.parse(data)
					modal.css('display','block')
					modal.find('#pv-base').html(number_format(price.base,0,'',' '))
					modal.find('#pv-pack').html(number_format(price.pack,0,'',' '))
					modal.find('#pv-dops').html(number_format(price.dops,0,'',' '))
					modal.find('#pv-total').html(number_format(price.total,0,'',' '))
				})
		)		
	}
	//значок вопроса у компании
	$(document).on('click','.loyalty-block a',function(){
		text = $(this).attr('title')
		alert(text);
	})
	//включение акции и проверка возможности её работы
	$(document).on('change','.loyalty-block input[type="checkbox"]',function(){
		let itemElem = $(this)
		let itemBlock = itemElem.closest('.loyalty-block')
		let itemNomen = itemBlock.find('.nomenal')
		let itemReturn = itemBlock.find('.return')
		let itemName = itemBlock.find('.loyalty-name')
		let itemStatus = ($(this).prop("checked"))

		if(itemStatus){
			makeSale(itemElem,itemNomen.val(),'+')
			makeRepartion(itemElem,itemReturn.val(),'+')

			itemNomen.removeAttr('disabled')
			itemReturn.removeAttr('disabled')
			itemElem.closest('label').addClass('green-check')
			disabledOther(itemElem)
			var appended = modal.find('.default').clone()
			appended.addClass('pv-action')
			modal.find('.pv-programms').append(appended)
			appended.removeClass('default')
			appended.find('.pv-name').html(itemElem.attr('text'))
			appended.attr('data-id',itemElem.val())
		}
		else{
			makeSale(itemElem,itemNomen.val(),'-')
			makeRepartion(itemElem,itemReturn.val(),'-')
			itemReturn.attr('disabled','disabled')
			itemNomen.attr('disabled','disabled')
			itemElem.closest('label').removeClass('green-check')
			enabledOther(itemElem)
			modal.find('[data-id="'+itemElem.val()+'"]').remove()
		}
	})

	$(document).on('keyup','.nomenal',function(){
		sale = 0
		dopsale = 0
		var val = nonSpace($(this).val())
		$(this).val(number_format(val,0,'',' '))
		$('.loyalty-block').find('.nomenal').each(function(){
			if($(this).prop('disabled')==false)					
				makeSale($(this).closest('.loyalty-block').find('input[type="checkbox"]'),$(this).val(),'+')			
		})
	})

	$(document).on('keyup','.return',function(){
		repartion = 0
		doprepartion = 0
		var val = nonSpace($(this).val())
		$(this).val(number_format(val,0,'',' '))
		$('.loyalty-block').find('.return').each(function(){
			if($(this).prop('disabled')==false)					
				makeRepartion($(this).closest('.loyalty-block').find('input[type="checkbox"]'),$(this).val(),'+')			
		})
	})

	function makeSale(obj,val,opt)
	{	
		if(obj.attr('razdel')==1 || obj.attr('razdel')==4)
		{
			if(opt=='+')
				sale += parseInt(nonSpace(val))
			else if(opt=="-")
				sale -= parseInt(nonSpace(val))
			$('[name="loyalty_sale"]').val(number_format(sale,0,'',' ')+" руб.")
		}
		if(obj.attr('razdel')==2)
		{
			if(opt=='+')
				dopsale += parseInt(nonSpace(val))
			else if(opt=="-")
				dopsale -= parseInt(nonSpace(val))
			$('[name="loyalty_dop_sale"]').val(number_format(dopsale,0,'',' ')+" руб.")
		}
	}

	function makeRepartion(obj,val,opt)
	{			
		if(obj.attr('razdel')==1 || obj.attr('razdel')==4)
		{
			if(opt=='+')
				repartion += parseInt(nonSpace(val))
			else if(opt=="-")
				repartion -= parseInt(nonSpace(val))
			$('[name="loyalty_repartion"]').val(number_format(repartion,0,'',' ')+" руб.")
		}
		if(obj.attr('razdel')==2)
		{
			if(opt=='+')
				doprepartion += parseInt(nonSpace(val))
			else if(opt=="-")
				doprepartion -= parseInt(nonSpace(val))
			$('[name="loyalty_dop_repartion"]').val(number_format(doprepartion,0,'',' ')+" руб.")
		}
	}

	function disabledOther(itemElem)
	{
		let all = $('.loyalty-block input[type="checkbox"]');
		log(all)
		if(itemElem.attr('main')==1)
			all.each(function(){
				var current = $(this)
				if(current.val() != itemElem.val() && current.attr('immortal')!=1){
					current.closest('.loyalty-block').find('.nomenal').attr('disabled','disabled')
					current.closest('.loyalty-block').find('.return').attr('disabled','disabled')
					current.prop('disabled',true)
					if(current.prop('checked')==true){
						makeSale(current,current.closest('.loyalty-block').find('.nomenal').val(),'-')
						makeRepartion(current,current.closest('.loyalty-block').find('.return').val(),'-')
					}
					current.prop('checked', false)					
					current.closest('label').removeClass('green-check')
					current.closest('label').addClass('red-check')
					modal.find('[data-id="'+current.val()+'"]').remove()	
					//log('disabled')				
				}
				//log(current.closest('.loyalty-block').find('a').attr('title'))
			})
	}
	function enabledOther(itemElem)
	{
		let all = $('.loyalty-block input[type="checkbox"]');
		if(itemElem.attr('main')==1)
			all.each(function(){
				var current = $(this)
				if(current.val() != itemElem.val() && current.attr('immortal')!=1){
					current.closest('.loyalty-block').find('.nomenal').attr('disabled','disabled')
					current.prop('disabled',false)
					current.prop('checked', false);
					current.closest('label').removeClass('green-check')
					current.closest('label').removeClass('red-check')
				}
			})
	}
	function writeProgram(data){
		
		
		
		for (i in data) {
			if(i == 0)
			{
				var str = '<div class="row" style="padding:15px;"><div class="col-4">'
					str += '<label style="margin:0px;">Скидка на авто (итого):</label>'
					str += '<input type="text" name="loyalty_sale" style="pointer-events:none;border:0px;" value="0 руб.">'
				str += '</div>'


				str += '<div class="col-4">'
					str += '<label style="margin:0px;">Возмещение на авто (итого):</label>'
					str += '<input type="text" name="loyalty_repartion" style="pointer-events:none;border:0px;" value="0 руб.">'
				str += '</div></div>'
				loyalty.append(str)
			}

			if(i==1)
			{
				var str = '<div class="row" style="padding:15px;"><div class="col-4">'
					str += '<label style="margin:0px;">Скидка на допы (итого):</label>'
					str += '<input type="text" name="loyalty_dop_sale" style="pointer-events:none;border:0px;" value="0 руб.">'
				str += '</div>'


				str += '<div class="col-4">'
					str += '<label style="margin:0px;">Возмещение на допы (итого):</label>'
					str += '<input type="text" name="loyalty_dop_repartion" style="pointer-events:none;border:0px;" value="0 руб.">'
				str += '</div></div>'
				loyalty.append(str)
			}

			for(k in data[i]) {
				var str = ''
				if(k==0){
					str += '<div class="input-group">'
		                str += '<label class="col-6 font-weight-bold">'+typeProgram[i]+':</label>'
		                str += '<label class="col-3">Номинал (руб.)</label>'
		                str += '<label class="col-3">Возмещение (руб.)</label>'
		            str += '</div>'
				}
				str += '<div class="row">'
					str += '<div class="input-group loyalty-block">'
						str += '<div class="col-6 d-flex align-items-center">'
							
							str += '<span class="col-2">'
								str += '<div class="check">';
									str += '<label>'
										str += '<input '+
											'type="checkbox" '+
											'name="loyalty[company_id][]" '+
											'value="'+data[i][k].id+'" '+
											'immortal="'+data[i][k].immortal+'" '+
											'main="'+data[i][k].main+'" '+
											'type="'+data[i][k].razdel+'" '+
											'text="'+data[i][k].name+'" '+
											'razdel="'+data[i][k].razdel+'" '
										str += '>'										
									str += '</label>';
								str += '</div>';
							str += '</span>'

							str += '<div class="col-9 loyalty-name">'
								str += data[i][k].name
							str += '</div>'

							str += '<a href="#" class="col-1" title="'+data[i][k].text+'">'
		                        str += '<i class="fas fa-question-circle" ></i>'
		                    str += '</a>'

		                str += '</div>'

		            	str += '<input name="loyalty[sum]['+data[i][k].id+']" type="text" class="col-3 form-control nomenal" placeholder="" '		   
			            	if(data[i][k].selected!=null)
			            		str += " value = '"+number_format(data[i][k].selected.sum,0,'',' ')+"' "
			            	else
			            		str += 'disabled value="'+ number_format(data[i][k].sum,0,'',' ')+'" '
		            	str += '>'

		            	str += '<input type="hidden" value="'+data[i][k].razdel+'" name="loyalty[razdel]['+data[i][k].id+']" >'

		                str += '<input name="loyalty[rep]['+data[i][k].id+']" type="text" class="col-3 form-control return" placeholder="" '
			            	if(data[i][k].selected!=null)
			            		str += " value = '"+number_format(data[i][k].selected.rep,0,'',' ')+"' "
			            	else
			            		str += 'disabled value="'+ number_format(data[i][k].repsum,0,'',' ')+'" '
			            str += '>'

		            str += '</div>'
		        str += '</div>'
		        loyalty.append(str)
		    }
		}

		for( i in data)
		{
			for(k in data[i])
			{
				if(data[i][k].selected!=null)
		        	$('.loyalty-block input[value="'+data[i][k].id+'"]').click()
			}
		}

	}

})();

(function(){//КНОПКИ ПАКЕТ и ДОПЫ В ТАБЛИЦУ АВТОСКЛАД
	let modal = $("#car-option-modal")
	let content = modal.find('.car-option-content')
	let title = modal.find('.title-option-modal')

	$(document).on('click','.stock-button[mind="pack"]',function(){
		let mind = $(this).attr('mind')
		let id = $(this).attr('car-id')
		let url = '/get/car/packs'
			
		let parameters = {'id':id,'mind':mind}
		$.when(
			ajax(parameters,url)
				.then(function(data){					
					str = ''
					title.html('Опционное оборудование')
					data = JSON.parse(data)
					for(i in data){
						var pack = data[i].pack
						str += '<b>Код пакета:</b> '+pack.code+'<br>'
						for(k in pack.option)
						{
							str += (parseInt(k)+parseInt(1)) +') '+pack.option[k].option.name + '<br>'
						}
						if(i<(data.length-1))
							str += '<hr>'
					}
					beginFade(modal)
					if(!str)
						str = '<div class="text-center">На автомобиле не установлено опционного оборудования</div>'
					content.html(str)
				})
		)
	})

	$(document).on('click','.stock-button[mind="dop"]',function(){
		let mind = $(this).attr('mind')
		let id = $(this).attr('car-id')
		let url = '/get/car/dops'
		title.html('Дополнительное оборудование')
			
		let parameters = {'id':id,'mind':mind}
		$.when(
			ajax(parameters,url)
				.then(function(data){
					str = ''
					data = JSON.parse(data);log(data)
					if(data.install)
					{
						str += '<div><b>Установлено</b></div>'						
						for(i in data.install){
							str += (parseInt(i)+parseInt(1)) +') '+data.install[i].dop.name+'<br>'
						}
					}
					if(data.offered)
					{
						str += '<hr><div><b>Предложено</b></div>'
						for(i in data.offered){
							str += (parseInt(i)+parseInt(1)) +') '+data.offered[i].name+'<br>'
						}
					}

					beginFade(modal)
					if(!str)
						str = '<div class="text-center">На автомобиле не установлено дополнительного оборудования</div>'
					content.html(str)
				})
		)
	})

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

})();

(function(){//ВСЁ ДЛЯ ВКЛАДКИ ПЛАТЕЖИ В МЕНЮ ОФОРМЛЕНИЕ
	let link = $(document).find('#client_pays')
	let block = $(document).find('.client_pays')

	
	
	//нажатие на заголовок вкладки ПЛАТЕЖИ
	link.on('click',function(){
		block.html('')
		let worklist_id = $('span[name=wl_id]').html() //беру ид раблиста
		let parameters = {'worklist_id':worklist_id}
		let url = '/get/worklist/pays'
		$.when(
			ajax(parameters,url)
				.then(function(data){
					block.append(data)
					block.find('[name="wl_pay_date[]"]').datepicker()
				})
			)
	})

	//добавление новой строки с вводом суммы даты и платежа
	block.on('click','#adder_pay',function(){
		var content = $(this).closest('.pay_content')
		var line = $(this).closest('.item').clone()
		if(line.find('[name="wl_pay_sum[]"]').val()){
			line.removeClass('item')
			line.find('#adder_pay').remove()
			line.find('input').val('')
			line.find('[type="checkbox"]').prop('checked',false)
			line.find('[name="wl_pay_date[]"]').datepicker()
			content.append(line)
		}
	})

	//Проверка что все поля заполнены нажатие на чекбокс
	block.on('change','[type="checkbox"]',function(){
		if($(this).prop('checked')==true)
		{
			var check = $(this)
			var line = check.closest('.input-group')
			line.find('[type="text"]').each(function(){
				if($(this).val()=='')
				{
					alert('Не заполненны поля')
					check.prop('checked',false)
					return false
				}
			})
		}
	})

	//удаление строки с инпутами
	block.on('click','.fa-times',function(){
		var line = $(this).closest('.input-group')
		if(line.find("#adder_pay").length)
		{
			line.find('input').val('')
			line.find('[type="checkbox"]').prop('checked',false)
		}
		else
			line.remove()
	})

	block.on('keyup','[name="wl_pay_sum[]"]',function(e){
		var text = 0
		var sum =0
		var total = block.find("#wl_pay_carprice").attr('data-price')
		
		if( (e.which>=96 && e.which<=105) || (e.which>=48 && e.which<=57) )
			text = text
		else
			$(this).val($(this).val().substring(0,$(this).val().length-1))

		block.find('[name="wl_pay_sum[]"]').each(function(){
			if($(this).val())
				sum+=parseInt($(this).val())
		})

		$(this).closest('.input-group').find('[name="wl_pay_debt[]"]').val(total-sum)
		block.find('#wl_pay_client').html(sum+' р.')
	})

	block.on('click','#adder_pay_pts',function(){log(1)
		var parent = $(this).closest('.info')
		var newInput = parent.find('.item').clone().removeClass('item')
		parent.append(newInput)
	})
})();

//////////////////////////////////////////
//БЛОК ДЛЯ ВКЛАДКИ ОФОРМЛЕНИЕ->КОНТРАКТЫ//
//////////////////////////////////////////
(function(){
	let link = $(document).find('#client_contract')
	let block = $(document).find('.client_contract')

	link.on('click',function(){
		block.html('')
		let worklist_id = $('span[name=wl_id]').html() //беру ид раблиста
		let parameters = {'worklist_id':worklist_id}
		let url = '/get/worklist/contracts'
		$.when(
			ajax(parameters,url)
				.then(function(data){
					block.append(data)
					block.find('.calendar').datepicker()
				})
			)
	})

	block.on('click','#adder_ship',function(){
		var parent = $(this).closest('.input-group')
		var newBlock = '<input type="text" class="form-control col-3 calendar" name="contract[ship][]">'
		parent.append(newBlock)
		$('[name="contract[ship][]"]').datepicker()
	})

	block.on('change','[name="contract[crash]"]',function(){
		if($(this).prop('checked')==true)
			$('[name="contract[date_crash]"]').prop('disabled',false)
		else
			$('[name="contract[date_crash]"]').prop('disabled',true)
	})

})();


/*ОФОРМЛЕНИЕ -> КРЕДИТЫ*/
(function(){
	let link = $(document).find('#wsdesign4')
	let block = $(document).find('.client_kredit')

	link.on('show.bs.collapse',function(){
		block.html('')
		let worklist_id = $('span[name=wl_id]').html() //беру ид раблиста
		let parameters = {'worklist_id':worklist_id}
		let url = '/get/worklist/kredit'
		$.when(
			ajax(parameters,url)
				.then(function(data){
					data = JSON.parse(data)
					block.append(data.html)
					for(i in data)
					{
						block.find('[name="wl_kredit['+i+']"]').val(data[i])
					}
					block.find('[name="wl_kredit[payment]"]').val(number_format(block.find('[name="wl_kredit[payment]"]').val(),0,'',' '))
					block.find('[name="wl_kredit[sum]"]').val(number_format(block.find('[name="wl_kredit[sum]"]').val(),0,'',' '))
					block.find('[name="wl_kredit[price]"]').val(number_format(block.find('[name="wl_kredit[price]"]').val(),0,'',' '))
					block.find('[name="wl_kredit[valid_date]"]').val(timeConverter(block.find('[name="wl_kredit[valid_date]"]').val()))
					block.find('[name="wl_kredit[margin_kredit]"]').val(number_format(block.find('[name="wl_kredit[margin_kredit]"]').val(),0,'',' '))
					block.find('[name="wl_kredit[margin_product]"]').val(number_format(block.find('[name="wl_kredit[margin_product]"]').val(),0,'',' '))
					block.find('[name="wl_kredit[margin_other]"]').val(number_format(block.find('[name="wl_kredit[margin_other]"]').val(),0,'',' '))
					block.find('.calendar').datepicker()
				})
			)
	})

	block.on('click','#adder_app',function(){
		var content = block.find('.kredit_app_content');
		var first = content.find('.app_block:first')
		var clone = first.clone()
		clone.find('[type="text"]').val('')
		clone.find('select').val('')
		clone.find('[type="checkbox"]').prop('checked',false)
		clone.addClass('pdt-20')
		clone.find('.calendar').removeClass('hasDatepicker').attr('id','')
		clone.find('input').each(function(){
			var name = $(this).attr('name')
			name = name.split(']')
			name = name.join('')
			name = name.split('[')
			name[2] = parseInt(name[2])+1
			var newName = name[0]+'['+name[1]+']'+'['+name[2]+']'+'['+name[3]+']'
			if(name[3]=='product')
				newName += '[]'
			$(this).attr('name',newName)
		})
		content.append(clone)
		content.find('.calendar').datepicker()
	})

	block.on('click','.deleter_app',function(){
		var delBlock = $(this).closest('.app_block')
		log(delBlock)
		if(block.find('.app_block').length>1)
			delBlock.remove()
		else
		{
			delBlock.find('input').val('')
			delBlock.find('select').val('')
			delBlock.removeClass('pdt-20')
		}
	})

	block.on('keyup','.kredit_payment',function(e){
		var text = 0
		if( (e.which>=96 && e.which<=105) || (e.which>=48 && e.which<=57 || e.which==8) )
			text = text
		else
			$(this).val($(this).val().substring(0,$(this).val().length-1))

		var payment = parseInt(nonSpace($(this).val()))
		var price = parseInt(nonSpace($('.kredit_price').val()))
		var sum = price-payment
		$(this).val(number_format(payment,0,'',' '))
		$('.kredit_sum').val(number_format(sum,0,'',' '))
	})

	block.on('keyup','.money',function(e){
		var text = 0
		log(e.which)
		if( (e.which>=96 && e.which<=105) || (e.which>=48 && e.which<=57) || e.which==8 )
			text = $(this).val()
		else
			text = $(this).val().substring(0,$(this).val().length-1)
		$(this).val(number_format(nonSpace(text),0,'',' '))
	})

	block.on('change','.status',function(){		
		$(this).closest('.app_block').find('.status_date').val(getCurrentDate())
	})

	block.on('keyup','.action_mon',function(e){
		var text = 0
		if( (e.which>=96 && e.which<=105) || (e.which>=48 && e.which<=57 || e.which==8) )
			text = text
		else
			$(this).val($(this).val().substring(0,$(this).val().length-1))

		if($(this).val().length>0)
			text = $(this).val()

		var date
		if($(this).closest('.app_block').find('.status_date').val().length==0)
			date = new Date()
		else
		{
			var str = $(this).closest('.app_block').find('.status_date').val()
			str = str.split('.')
			date = new Date(str[2],(str[1]-1),str[0])
		}
		date.setMonth(date.getMonth() + parseInt(text));
		var day = date.getDate()
		var mon = date.getMonth()+1
		var year = date.getFullYear()
		if(day<10)
			day='0'+day
		if(mon<10)
			mon='0'+mon
		var strDate = [day,mon,year].join('.')
		$(this).closest('.app_block').find('.action_date').val(strDate)
	})

})();



