/**
 * Рабочий лист
 * Оформление - Продукты ОФУ
 */


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
 * Функция отрисовки блока сервиса для Продуктов ОФУ
 */
function getServiceBlock(item)
{
	var block = '<div class="input-group no-gutters wl-ofu-services">'
		 + '<div class="col-9 d-flex align-items-center">'
			+ '<span class="flex-grow-1 d-flex align-items-center" style="color: #f5770a;">'
				+ '<input type="checkbox" class="mr-1" checked disabled> ' + item.name
			+ '</span>'
			+ '<a href="javascript://" description="' + item.text + '" class="wl-service-description pr-3">'
				+ '<i class="icofont-info-circle"></i>'
			+ '</a>'
		+ '</div>'
		+ '<div class="col-3"><input type="text" class="form-control wl-service-price" value="'+ item.sum +'" disabled></div>'
	+ '</div>';

	return block;
}

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
 * Функция получения пустого блока для добавления в Продуктах ОФУ
 */
function ofuAddBlock()
{
	var block = '';
	var wl_id = $('span[name=wl_id]').html();

	if (wl_id != '-')
	{
		var formData = new FormData();
		formData.append('wl_id', wl_id);

		$.ajax({
			async: false,
			url: '/ofuaddblock',
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
	    		block = $(data);
	    	},
	    	error:function(xhr, ajaxOptions, thrownError){
	    		log('Не удалось добавить блок продукта ОФУ');
		    	log("Ошибка: code-"+xhr.status+" "+thrownError);
		    	log(xhr.responseText);
		    	log(ajaxOptions);
		    }
		});
	}

	return block;
}

/**
 * Открытие блока
 */
$(document).on('shown.bs.collapse', '#wsdesign3', function() {
	var wl_id = $('span[name="wl_id"]').html();
	if (wl_id != '-')
	{
		var check_selected_car = checkWorklistOnCar(wl_id);
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

	    		getServiceSum();

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
 * Закрытие блока
 */
$(document).on('hide.bs.collapse', '#wsdesign3', function() {
	$('#services_list').html('');
	$('#ofu_products').html('');
	$('#ofu_products_count').html('0');
	$('#wl_service_budget').html('0 р.');
});

/**
 * Отображение описания сервиса
 */
$(document).on('click', '.wl-service-description', function() {
	alert($(this).attr('description'));
});

/**
 * Добавление нового пустого блока
 */
$(document).on('click', '#ofu_add_block', function() {
	var block = ofuAddBlock();
	$('#ofu_products').append(block);
	$('#ofu_products_count').html( $('.ofu-block').length );
});

/**
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
 * Рассчет даты окончания при вводе месяцев
 */
$(document).on('keyup', '.ofu-block-months', function() {
	var creation_date = $(this).closest('.ofu-block').find('.ofu-block-creation-date').val();
	var months = $(this).val();

	if (creation_date != '' && months != '')
	{
		var date_arr = creation_date.split('.');
		var temp_date = new Date(date_arr[2], (date_arr[1] - 1), date_arr[0]);
		
		temp_date.setMonth( temp_date.getMonth() + Number(months) );

		var day = temp_date.getDate();
		var month = temp_date.getMonth() + 1;
		var year = temp_date.getFullYear();

		if (day < 10)
			day = '0' + day;

		if (month < 10)
			month = '0' + month;

		var end_date = [day,month,year].join('.');

		$(this).closest('.ofu-block').find('.ofu-block-end-date').val(end_date);
	}
});