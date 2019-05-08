/**
 * Рабочий лист
 * Автомобиль
 */


/**
 * Открытие вкладки
 * Получение данных о привязанном автомобиле
 */
$(document).on('click', '#worksheetTabs a[href="#worksheet-auto"]', function() {
	var wl_id = $('span[name="wl_id"]').html();

	if (wl_id != '-')
	{
		var formData = new FormData();
		formData.append('wl_id', wl_id);

		var parameters = formData;
		var url = '/wlgetcar';

		$.when(ajaxWithFiles(parameters, url)).then(function(data){
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
	    		$('#wl_car_fullprice').html(data.fullprice);

	    		$('#wl_car_opencard').addClass('opencar');
	    		$('#wl_car_opencard').attr('car-id', data.car_id);

	    		$('#wl_car_company').html('');
	    		for (i in data.company)
	    		{
		    		$('#wl_car_company').append('<li>'+data.company[i].company.name+'</li>');
	    		}
	    	}
		});
	}
});

/**
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

			var parameters = formData;
			var url = '/wlremovereserved';

			$.when(ajaxWithFiles(parameters, url)).then(function(data){
				if (data == 'done') {
	    			$("#wl_car_empty").css('display', 'block');
	    			$("#wl_car").css('display', 'none');
	    			$('#crmTabs a[href="#stock"]').click();
	    		} else {
	    			$("#wl_car_empty").css('display', 'none');
	    			$("#wl_car").css('display', 'block');
	    		}
			});
		}
	}
});