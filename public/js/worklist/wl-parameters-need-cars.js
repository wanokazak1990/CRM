/**
 * Рабочий лист
 * Параметры - Подбор по потребностям
 */


/**
 * Загрузка и отрисовка блоков сохраненных моделей при открытии блока
 * Если нет - отрисовка начального блока выбора модели
 */
$(document).on('shown.bs.collapse', '#wsparam2', function() {
	$('#selectCarOptions input:checkbox').prop('checked', false);

	var wl_id = $('span[name="wl_id"]').html();

	if (wl_id != '-')
	{
		var formData = new FormData();
		formData.append('wl_id', wl_id);

		var parameters = formData;
		var url = '/wlgetneedcars';

		$.when(ajaxWithFiles(parameters, url)).then(function(data){
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
		});
	}
});

/**
 * Добавление новой модели 
 */
$(document).on('click', '#addSelectedCar', function() {
	var clone = $('#carsByNeeds').find('.col-3').first().clone();
	clone.appendTo('#carsByNeeds');
});

/**
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

		var parameters = formData;
		var url = '/wlsaveneedcars';

		$.when(ajaxWithFiles(parameters, url)).then(function(data){
			$('#carsByNeeds').html(data.blocks);
    		if (data.options != null)
    		{
    			var arr = data.options.split(',');
    			for (var i = 0; i < arr.length; i++)
	    		{
	    			$('#selectCarOptions input[type="checkbox"][value="'+arr[i]+'"]').prop('checked', true);
	    		}
    		}
		});
	}

	var url = '/crmgetcarsbyneeds';

	$.when(ajaxWithFiles(parameters, url)).then(function(data){
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
	});
});

/**
 * Кнопка "Зарезервировать"
 */
$(document).on('click', '#wl_need_reserve', function() {
	$(this).blur();

	var wl_id = $('span[name="wl_id"]').html();

	if (wl_id == '-')
		alert('Рабочий лист не загружен!');
	else
	{
		var check_selected_car = checkWorklistOnCar(wl_id);
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
				check_selected_car = checkCarOnWorklist(car_id);
				if (check_selected_car == true)
				{
					alert('Выбранный автомобиль уже зарезервирован за другим рабочим листом!');
				}
				else
				{
					var formData = new FormData();
					formData.append('car_id', car_id);
					formData.append('wl_id', wl_id);

					var parameters = formData;
					var url = '/wlreservecar';

					$.when(ajaxWithFiles(parameters, url)).then(function(data){
						if (data == 'OK')
						{
							alert('Автомобиль зарезервирован.');
							wl_save_changes(); // Обновляем данные в рабочем листе
						}
						else
							alert('Не удалось зарезервировать автомобиль.');
					});
				}
			}
		}
	}
});