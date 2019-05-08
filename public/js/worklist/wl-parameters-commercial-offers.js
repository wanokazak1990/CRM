/**
 * КОММЕРЧЕСКОЕ ПРЕДЛОЖЕНИЕ
 * Открытие блока
 */
$(document).on('shown.bs.collapse', '#wsparam7', function() {
	var wl_id = $('span[name="wl_id"]').html();
	if (wl_id != '-')
	{
		var formData = new FormData();
		formData.append('wl_id', wl_id);

		var parameters = formData;
		var url = '/wlgetoffers';

		$.when(ajaxWithFiles(parameters, url)).then(function(data){
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
		});
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
		if ($('.check-car:checked').length == 0 && $('.cfg_check_car:checked').length == 0)
		{
			alert("Коммерческое предложение не создано.\nВыберите одну или несколько машин из Автосклада и/или Конфигуратора.");
		}
		else
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
	}
	else
		alert("Для создания Коммерческого предложения необходимо загрузить рабочий лист!");
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