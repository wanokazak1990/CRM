$(document).ready(function() {
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