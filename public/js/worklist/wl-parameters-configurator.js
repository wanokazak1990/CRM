/**
 * Рабочий лист 
 * Параметры - Конфигуратор
 */


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
 * Подсчет полной цены автомобиля
 */
function totalPriceCfg(obj)
{
	var price = obj.closest('.wl_cfg_cars').find("#price");
	cfg_total = parseInt(price.attr('base')) + parseInt(price.attr('pack')) + parseInt(price.attr('dops'));
	obj.closest('.wl_cfg_cars').find("#cfg-full-price").html(cfg_number_format(cfg_total, 0, '', ' '));
}

/**
 * Преобразование цены в нужный формат
 */
function cfg_number_format(number,decimals,dec_point,thousands_sep)
{
	var i,j,kw,kd,km;
	if (isNaN(decimals = Math.abs(decimals))) {
		decimals = 2;
	}

	if (dec_point == undefined) {
		dec_point = ",";
	}
	
	if (thousands_sep == undefined) {
		thousands_sep = ".";
	}

	i = parseInt(number = (+number || 0).toFixed(decimals)) + "";
	
	if ((j = i.length) > 3) {
		j = j % 3;
	} else {
		j = 0;
	}

	km = (j ? i.substr(0, j) + thousands_sep : "");
	kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
	kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");
	
	return km + kw + kd;
}

/**
 * Получение списка комплектаций автомобиля
 */
function getComplectsCfg(elem, pastle = 0)
{	
	var model_id = elem.val();

	$.ajax({
		async: false,
		type: "POST",
		url: "/getcomplects",
		data: {'id':model_id},
		headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },
		success: function(param){
			var objs = JSON.parse(param);
			
			if (pastle == 0) 
				elem.closest('.wl_cfg_cars').find('.cfg_complect').html('');
			else 
				pastle.html("");

			var str = '';
			str += '<option value="off">Укажите параметр</option>';

			objs.forEach(function(obj,i){
				str += '<option value="' + obj.id + '">';
					str += obj.fullname;
				str += '</option>';
			});
			
			if (pastle == 0) 
				elem.closest('.wl_cfg_cars').find('.cfg_complect').html(str);
			else 
				pastle.html(str);
		},
		error: function(param)
		{
			log('Не удалось получить комплектации автомобиля');
		}
	})
}

/**
 * Получение информации о модели автомобиля
 */
function modelObjCfg(obj)
{
	var result = '';
	var formData = new FormData();
	formData.append('id',obj.val());
	$.ajax({
		async: false,
        type: "POST",
        url: '/getmodel',
        dataType : "json", 
        cache: false,
        contentType: false,
        processData: false, 
        data: formData,
        headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },
		success: function(param){
			obj.closest('.wl_cfg_cars').find("#cfg-model").html(param.name);
			obj.closest('.wl_cfg_cars').find("#cfg-img").attr('src','/storage/images/' + param.link + '/' + param.alpha);
			result = param;
		},
		error: function(param)
		{
			log('Не смог получить стоимость пакетов id = ' + val);
		}
	});

	return result;
}

/**
 * Формирование блока с доступными цветами автомобиля
 */
function getColorCfg(elem)
{
	var formData = new FormData();
	formData.append('complect_id',elem.val());
    $.ajax({
    	async: false,
        type: "POST",
        url: '/getcolor',
        dataType : "json", 
        cache: false,
        contentType: false,
        processData: false, 
        data: formData,
        headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },
        success: function(param)
        {	var mother = elem.closest('.wl_cfg_cars').find(".cfg-color");
        	var str = '';
        	mother.html("");
        	param.forEach(function(obj,i){
	        	mother.append('<button style="width:30px;height:30px;background:' + obj.web_code + 
	        		'" class="btn cfg-color-btn" color-name="' + obj.name + 
	        		'" type="button" color-id="' + obj.id + 
	        		'" color-code="' + obj.web_code + 
	        		'">&nbsp</button>');
        	});
        },
        error: function(msg){
            console.log('Не удалось получить цвета автомобиля');
        }
    });
}

/**
 * Формирование HTML-блока опции автомобиля
 */
function getPackStringCfg(obj){
	var str = '<div class="input-group no-gutters">';

		if (obj.name != null) 
			str += '<div class="col-12">' + obj.name + '</div>';
		else 
			str += '<div class="col-12"></div>';

		str += '<div class="pack-desc">' + obj.optionlist + '</div>';

		str += '<div class="col-12 d-flex no-gutters">';
			str += '<div class="col-2">';
				str += '<div class="check">';
					str += '<label><input type="checkbox" code="' + obj.code + '" price="' + obj.price + '" name="packs[]" value="' + obj.id + '"></label>';
				str += '</div>';
			str += '</div>';

			str += '<div class="col-2">';
				str += obj.code;
			str += "</div>";

			str += '<div class="col-6 h5 text-right">';
				str += cfg_number_format(obj.price, 0, '', ' ') + ' руб.';
			str += '</div>';
		str += '</div>';
	str += '</div>';

	return str;
}

/**
 * Получение доступных опций для автомобиля
 */
function getPacksCfg(elem)
{
	var formData = new FormData();
	formData.append('complect_id',elem.val());
    $.ajax({
    	async: false,
        type: "POST",
        url: '/getpacks',
        dataType : "json", 
        cache: false,
        contentType: false,
        processData: false, 
        data: formData,
        headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },
		success: function(param){
			elem.closest('.wl_cfg_cars').find('.cfg-pack-block').html('');
			var str = '';
			param.forEach(function(obj,i){
				str += getPackStringCfg(obj);
			});
			elem.closest('.wl_cfg_cars').find('.cfg-pack-block').html(str);
		},
		error: function(param)
		{
			log('Не удалось получить пакеты');
		}
	})
}

/**
 * Получение информации о двигателе автомобиля
 */
function getMotorCfg(obj, id)
{
	var formData = new FormData();
	formData.append('complect_id',id);
	$.ajax({
        type: "POST",
        url: '/getmotor',
        dataType : "json", 
        cache: false,
        contentType: false,
        processData: false, 
        data: formData,
        headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },
	    success: function(data){
	    	obj.closest('.wl_cfg_cars').find("#cfg-motor-type").html('Двигатель '+data[0].type.name+' '+data[0].valve+'-клапанный');
	    	obj.closest('.wl_cfg_cars').find("#cfg-motor-size").html('Рабочий объем '+data[0].size+'л. ('+data[0].power+'л.с.)');
	    	obj.closest('.wl_cfg_cars').find("#cfg-motor-transmission").html('КПП '+data[0].transmission.name);
	    	obj.closest('.wl_cfg_cars').find("#cfg-motor-wheel").html('Привод '+data[0].wheel.name);
	    },
	    error: function(){
	    	log('Не смог получить стоимость комплектации id = '+id);
	    }
	})
}

/**
 * Получение информации о выбранной комплектации
 */
function complectPriceCfg(obj)
{
	var result = '';
	var formData = new FormData();
	formData.append('id',obj.val());
	$.ajax({
		async: false,
        type: "POST",
        url: '/complectprice',
        dataType : "json", 
        cache: false,
        contentType: false,
        processData: false, 
        data: formData,
        headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },
		success: function(param){
			getMotorCfg(obj, param.id);
			obj.closest('.wl_cfg_cars').find("#cfg-complect-code").html('Исполнение '+param.code);
			obj.closest('.wl_cfg_cars').find("#cfg-complect-name").html('Комплектация '+param.name);
			obj.closest('.wl_cfg_cars').find("#cfg-base-price").html(cfg_number_format(param.price,0,'',' '));
			result = param.price;
		},
		error: function(param)
		{
			log('Не смог получить стоимость комплектации id = ' + id);
		}
	});

	return result;
}

/**
 * Функция подсчета автомобилей на складе по модели и комплектации
 */
function carCountInStock(object) {
	var formData = new FormData();
	formData.append('model_id', object.closest('.wl_cfg_cars').find('.cfg_model').val());
	formData.append('complect_id', object.val());

	$.ajax({
		async: false,
		type: "POST",
        url: '/getcountcfgcar',
        dataType : "json", 
        cache: false,
        contentType: false,
        processData: false, 
        data: formData,
        headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },
		success: function(data){
			object.closest('.wl_cfg_cars').find('.wl_cfg_res').html('');
			if (data != 0)
			{
				var str = 'Ресурсы автосклада: ' + carCountString(data) + ', <a href="javascript://" class="wl_cfg_show">показать</a>?';
				object.closest('.wl_cfg_cars').find('.wl_cfg_res').append(str);
			}
			else
			{
				var str = 'Ресурсы автосклада: ' + carCountString(data);
				object.closest('.wl_cfg_cars').find('.wl_cfg_res').append(str);
			}
		},
		error:function(xhr, ajaxOptions, thrownError){
    		log('Не удалось получить количество автомобилей на складе по модели и комплектации');
	    	log("Ошибка: code-"+xhr.status+" "+thrownError);
	    	log(xhr.responseText);
	    	log(ajaxOptions);
	    }
	});
}



/**
 * Формирование строки вида "n автомобилей"
 */
function carCountString(count) {
	if (count >= 5 && count <= 20)
		return count + ' автомобилей';
	else
	{
		count = count.toString();
		switch (count.substr(count.length - 1, 1)) {
			case '2':
			case '3':
			case '4':
				return count + ' автомобиля';
				break;

			case '5': 
			case '6': 
			case '7': 
			case '8': 
			case '9': 
			case '0': 
				return count + ' автомобилей';
				break;

			default:
				return count + ' автомобиль';
		}
	}
}

/**
 * Функция получения пакетов, цвета и стартовой цены
 */
function selectComplect(object) {
	if (object.val() != 'off')
	{
		object.closest('.wl_cfg_cars').find("#price").attr('pack', 0);
		object.closest('.wl_cfg_cars').find("#price").attr('dops', 0);

		object.closest('.wl_cfg_cars').find(".cfg-pack-block input").each(function(){
			object.prop('checked',false);
		});

		object.closest('.wl_cfg_cars').find('#price').attr('base', complectPriceCfg(object));

		totalPriceCfg(object);
		getPacksCfg(object);
		getColorCfg(object);

		object.closest('.wl_cfg_cars').find('.display').removeAttr('style');
	}
}



/**
 * Функция удаления блока сконфигурированной машины
 * Если блоков несколько, то удаляет; если один - очистка от параметров
 */
function deleteCfgBlocks(object) {
	if ($('.wl_cfg_cars').length > 1)
	{
		object.remove();
		$('#wl_cfg_count').html($('.wl_cfg_cars').length);
	}
	else
	{
		clearCfgBlock(object);
	}
}

/**
 * Функция очистки блока сконфигурированной машины
 */
function clearCfgBlock(object) {
	object.find('.display').css('display', 'none');
	object.find('#cfg-img').attr('src', '');
	object.find('#cfg-img').css('background-color', '');
	object.find('.clear-html').html('');
	object.find('.cfg_model option:first').prop('selected', true);
	object.find('.wl_cfg_checkbox input[type="checkbox"]').prop('checked', false);
	object.find('.wl_cfg_checkbox input[type="checkbox"]').removeAttr('cfg-id');
	object.find('.wl_cfg_checkbox input[type="checkbox"]').removeClass('cfg_check_car');
	var option = object.find('.cfg_complect option:first').clone();
	object.find('.cfg_complect option').remove();
	object.find('.cfg_complect').append(option);
	$('#wl_cfg_count').html('0');
}

/**
 * Открытие блока
 */
$(document).on('shown.bs.collapse', '#wsparam3', function() {
	var wl_id = $('span[name="wl_id"]').html();

	if (wl_id != '-')
	{
		var formData = new FormData();
		formData.append('wl_id', wl_id);

		var parameters = formData;
		var url = '/wlgetcfgcars';

		$.when(ajaxWithFiles(parameters, url)).then(function(data){
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
		});
	}
});

/**
 * Выбор модели в конфигураторе
 * Получение комплектации выбранной модели
 */
$(document).on('change', '.cfg_model', function(){
	getComplectsCfg($(this));
	modelObjCfg($(this));
});

/**
 * Выбор комплектации в конфигураторе
 * Вызов функции получения пакетов, цвета и стартовой цены
 */
$(document).on('change','.cfg_complect',function(){
	carCountInStock($(this));
	selectComplect($(this));
	$('#wl_cfg_count').html($('.wl_cfg_cars').length);
});

/**
 * Нажатие на кнопку цвета автомобиля
 * Устанавливает выбранный цвет для конфигурируемой машины (неожиданно, правда?)
 */
$(document).on('click','.cfg-color button',function(){
	$(this).closest('.wl_cfg_cars').find('#cfg-img').css('background',$(this).attr('color-code'));
	$(this).closest('.wl_cfg_cars').find('#cfg_color_id').val($(this).attr('color-id'));
	$(this).closest('.wl_cfg_cars').find(".cfg-color button").each(function(){$(this).removeClass('active')})
	$(this).addClass('active');
});

/**
 * Выбор доступной опции для машины
 * Изменяет подсветку нажатого чекбокса, высчитывает итоговую стоимость автомобиля
 */
$(document).on('click','.cfg-pack-block input',function(){
	if ($(this).prop('checked')) {
		$(this).closest('label').addClass('green-check');
		price = $(this).closest('.wl_cfg_cars').find('input[type="hidden"][id="price"]');
		sum = parseInt(price.attr('pack')) + parseInt($(this).attr('price'));
		price.attr('pack', sum); 
		totalPriceCfg($(this));
	} else {
		$(this).closest('label').removeClass('green-check');
		price = $(this).closest('.wl_cfg_cars').find('input[type="hidden"][id="price"]');
		diff = parseInt(price.attr('pack')) - parseInt($(this).attr('price'));
		price.attr('pack', diff); 
		totalPriceCfg($(this));
	}
});


/**
 * Кнопка "Подробнее" в конфигураторе
 * Отображает или скрывает установленное в комплекте оборудование
 */
$(document).on('click','.cfg-more',function(){
	var obj = $(this);
	if (!obj.attr('action')) {
		var current_complect = obj.closest('.wl_cfg_cars').find('.cfg_complect').val();
		var formData = new FormData();
		obj.attr('action','1');
		obj.html('Закрыть');
		formData.append('complect_id',current_complect);
		$.ajax({
			type: "POST",
	        url: '/complect/option',
	        dataType : "json", 
	        cache: false,
	        contentType: false,
	        processData: false, 
	        data: formData,
	        headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    },
			success: function(param){
				obj.closest('.wl_cfg_cars').find("#cfg-complect-option").html("");
				param.forEach(function(item,i){
					obj.closest('.wl_cfg_cars').find("#cfg-complect-option").append("<div>"+item.name+"</div>")
				})
			},
			error: function(){

			}
		})
	}
	else{
		obj.html('Подробнее');
		obj.closest('.wl_cfg_cars').find("#cfg-complect-option").html("");
		obj.removeAttr('action');
	}
})

/**
 * Показать машины в автоскладе по модели и комплектации
 */
$(document).on('click', '.wl_cfg_show', function() {
	var formData = new FormData();
	formData.append('model_id', $(this).closest('.wl_cfg_cars').find('.cfg_model').val());
	formData.append('complect_id', $(this).closest('.wl_cfg_cars').find('.cfg_complect').val());
	$.ajax({
		async: false,
		type: "POST",
        url: '/showcfgcars',
        dataType : "json", 
        cache: false,
        contentType: false,
        processData: false, 
        data: formData,
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
    		log('Не удалось показать автомобили на складе по модели и комплектации');
	    	log("Ошибка: code-"+xhr.status+" "+thrownError);
	    	log(xhr.responseText);
	    	log(ajaxOptions);
	    }
	});
});

/**
 * Добавление нового блока для конфигурации еще одного автомобиля
 */
$(document).on('click', '#wl_cfg_add', function() {
	clone = $('#wl_cfg_car_blocks').find('.wl_cfg_cars').first().clone();
	clearCfgBlock(clone);
	clone.appendTo('#wl_cfg_car_blocks');
});

/**
 * Удаление блока сконфигурированной машины
 */
$(document).on('click', '.wl_cfg_del', function() {
	var car = $(this).closest('.wl_cfg_cars');
	deleteCfgBlocks(car);
});

/**
 * Создание заявки на автомобиль
 */
$(document).on('click', '#wl_cfg_create_request', function() {
	var wl_id = $('span[name="wl_id"]').html();

	if (wl_id != '-')
	{
		var check_selected_car = checkWorklistOnCar(wl_id);
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

			    var parameters = formData;
				var url = '/cfgcreaterequest';

				$.when(ajaxWithFiles(parameters, url)).then(function(data){
					if (data == 'done')
					{
						$('.ws-param').collapse('hide');
						alert("Заявка создана\nАвтомобиль зарезервирован");
					}
				});
			}
		}
	}
});