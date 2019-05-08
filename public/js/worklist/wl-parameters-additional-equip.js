/**
 * Дополнительное оборудование
 * Открытие блока
 */
$(document).on('shown.bs.collapse', '#wsparam4', function() {
	var wl_id = $('span[name="wl_id"]').html();

	if (wl_id != '-')
	{
		$('#wl_dops_offered').val('');
		$('#wl_dops_all input:checkbox').prop('checked', false);
		wlGetDops(wl_id);
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

					var parameters = formData;
					var url = '/wlinstalldops';

					$.when(ajaxWithFiles(parameters, url)).then(function(data){
						if (data == 'done')
			    		{
			    			$('#wl_dops_offered').val('');
			    			wlGetDops(wl_id);
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

	var parameters = formData;
	var url = '/wlgetdops';

	$.when(ajaxWithFiles(parameters, url)).then(function(data){
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
	});
}