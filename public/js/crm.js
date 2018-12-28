function log(val)
{
	console.log(val);
}

$(document).ready(function() {

	$(document).on('click','#crmTabs a',function(){
		var obj = $("#crmTabPanels").find("div[aria-labelledby='"+$(this).attr('id')+"']");
		if(obj.find('table').html()=='' || obj.find('table').html()===undefined)
		{
			var formData = new FormData();
			formData.append('model',$(this).attr('model-name'));
			$.ajax({
				type: 'POST',
				url: '/crmgetcontent',
				dataType : "json", 
		        cache: false,
		        contentType: false,
		        processData: false, 
		        data: formData,
		        headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    },
			    success:function(param){
			    	var str = '';
			    	log(param);

			    	str += '<tr>';
			    	param['titles'].forEach(function(item,i){
			    		str += '<th>'+item.name+'</th>';		    		
			    	});
			    	str += '</tr>';

			    	param['list'].data.forEach(function(item,i){			    		
			    		str += '<tr>';
			    		for(var index in item) {
			    			if(index=='id') continue; 
						    str += '<td>'+item[index]+'</td>'; 
						}
			    		str += '</tr>';
			    	})
			    	
			    	obj.find('table').html(str);
			    	obj.append(param['links']);
			    },
			    error:function(param){
			    	log(1);
			    }
			});
		}
		else
			log("Вкладка не пуста, наверное пользователь в ней уже что то делал, поэтому ничего не догружаем");
	})





	$(document).on('click','#crmTabPanels .tab-pane .pagination a',function(e){
		e.preventDefault();
		var link = $(this);
		var obj = $(this).closest('.tab-pane');
		var mas = link.attr('href').split('/');
		var mas = mas[mas.length-1].split('?');
		$.ajax({
			type: 'POST',
			url: '/crmgetcontent?'+mas[mas.length-1],
			dataType : "json", 
	        cache: false,
	        contentType: false,
	        processData: false, 
	        headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    },
		    success:function(param){
		    	var str = '';
		    	log(param)

		    	str += '<tr>';
		    	param['titles'].forEach(function(item,i){
		    		str += '<th>'+item.name+'</th>';		    		
		    	});
		    	str += '</tr>';
		    	
		    	param['list'].data.forEach(function(item,i){
		    		str += '<tr>';
		    			for(var index in item) { 
		    				if(index=='id') continue;
						    str += '<td>'+item[index]+'</td>'; 
						}
		    		str += '</tr>';
		    	})
		    	
		    	obj.find('table').html(str);
		    	if(param['links']!==undefined)
		    	{
		    		obj.find('.pagination').remove();
		    		obj.append(param['links']);
		    	}
		    },
		    error:function(param){
		    	log(1);
		    }
		});
	})
























	// Открытие боковой панели
	$(document).on('click', '#opening', function() {
		$(this).blur();
		$('#hidden_panel').css('right', '0');
		$('#disableContent').css('display', 'block');
	});

	// Закрытие боковой панели с помощью кнопки
	$(document).on('click', '#closing', function() {
		$(this).blur();
		$('#hidden_panel').css('right', '-50%');
		$('#disableContent').css('display', 'none');
	});

	// Закрытие боковой панели путем нажатия на рабочую область
	$(document).on('click', '#disableContent', function() {
		$('#hidden_panel').css('right', '-50%');
		$('#disableContent').css('display', 'none');
	});

	/** 
	 * Отправка id открытой вкладки через AJAX, для получения в Настройках полей соответствующих полей для отображения
	 * Осуществляется при первоначальной загрузке страницы
	 */
	$('#crmTabPanels').children().each(function () {
		if ($(this).hasClass('active'))
		{
			var tab_id = $(this).attr('id');
			
			var formData = new FormData();
			formData.append('tab_id', tab_id);

			$.ajax({
				type: 'POST',
				url: '/getcurrentsettings',
				dataType : "json", 
		        cache: false,
		        contentType: false,
		        processData: false, 
		        data: formData,
		        headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    },
			    success: function(data) {
					if (data == '0')
					{
						$('#currentSettingsList').html('<div class="alert alert-warning" role="alert">Настройки не найдены</div>');
					}
					else
					{
						$('#currentSettingsList').html(data);
					}
				},
				error: function() {
					alert('Ошибка получения настроек для вкладки');
				}
			});

			$.ajax({
				type: 'POST',
				url: '/getcurrentfields',
				dataType : "json", 
		        cache: false,
		        contentType: false,
		        processData: false, 
		        data: formData,
		        headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    },
				success: function(data) {
					if (data == '0')
					{
						$('#settingsFields').html('<div class="alert alert-warning" role="alert">Поля не найдены</div>');
					}
					else
					{
						$('#settingsFields').html(data);
					}
				},
				error: function() {
					alert('Ошибка получения названий вкладок');
				}
			});

			$('.'+tab_id+'-td').css('display', 'none');

			$('.'+tab_id+'-head').each(function () {
				var head_id = $(this).attr('id');
				$('#'+head_id+'.'+tab_id+'-td').removeAttr('style');
			});			
		}
	});

	/** 
	 * Отправка id открытой вкладки через AJAX для получения в Настройках полей соответствующих полей для отображения
	 * Осуществляется при переходе на одну из вкладок
	 */ 
	$(document).on('click', '#crmTabs', function() {
		$('#crmTabPanels').children().each(function () {
			if ($(this).hasClass('active'))
			{
				var tab_id = $(this).attr('id');

				var formData = new FormData();
				formData.append('tab_id', tab_id);
				
				$.ajax({
					type: 'POST',
					url: '/getcurrentsettings',
					dataType : "json", 
			        cache: false,
			        contentType: false,
			        processData: false, 
			        data: formData,
			        headers: {
				        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    },
				    success: function(data) {
						if (data == '0')
						{
							$('#currentSettingsList').html('<div class="alert alert-warning" role="alert">Настройки не найдены</div>');
						}
						else
						{
							$('#currentSettingsList').html(data);
						}
					},
					error: function() {
						alert('Ошибка получения настроек для вкладки');
					}
				});

				$.ajax({
					type: 'POST',
					url: '/getcurrentfields',
					dataType : "json", 
			        cache: false,
			        contentType: false,
			        processData: false, 
			        data: formData,
			        headers: {
				        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    },
					success: function(data) {
						if (data == '0')
						{
							$('#settingsFields').html('<div class="alert alert-warning" role="alert">Поля не найдены</div>');
						}
						else
						{
							$('#settingsFields').html(data);
						}	
					},
					error: function() {
						alert('Ошибка получения названий вкладок');
					}
				});

				$('.'+tab_id+'-td').css('display', 'none');

				$('.'+tab_id+'-head').each(function () {
					var head_id = $(this).attr('id');
					$('#'+head_id+'.'+tab_id+'-td').removeAttr('style');
				});		
			}
		});
	});

	/**
	 * Настройки отображения
	 * Список полей
	 * Отметить все поля / снять отметку со всех полей
	 */
	$(document).on('click', '#checkAllFields', function() {
		if ($(this).is(':checked'))
		{
			$('input[name="fieldsCheckbox[]"]').each(function() {
				$(this).prop('checked', true);
			});
		}
		else
		{
			$('input[name="fieldsCheckbox[]"]').each(function() {
				$(this).prop('checked', false);
			});
		}
	});

});