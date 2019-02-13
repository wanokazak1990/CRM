function log(val)
{
	console.log(val);
}

$(document).ready(function() {


	//ЗАГРУЗКА ПЕРВОЙ СТРАНИЦЫ ПРИ ЗАГРУЗКЕ СТРАНИЦЫ
	$(function(){
		getContent($("#crmTabs a:first"));
	})





	//КЛИК ПО ССЫЛКАМ ПАГИНАТОРА
	$(document).on('click','#crmTabPanels .tab-pane .pagination a',function(e){
		e.preventDefault();
		getContent($(this))
	})




	//КЛИК ПО ССЫЛКАМ НАВИГАЦИИ
	//аттрибут id ссылки в навигации (клиенты, трафик, автосклад и ...) равен аттрибуту aria-labelledby контент-полей вкладок. тоесть
	//к ссылке клиенты у которой id = 1, привязано контент-поле с аттрибутом aria-labelledby = 1
	$(document).on('click','#crmTabs a',function(e){
		e.preventDefault();
		var content_area = $("#crmTabPanels").find("div[aria-labelledby='"+$(this).attr('id')+"']");//текущая вкладка
		var content_table = content_area.find('table');//таблица во вкладке
		//if(content_table.html()=='' || content_table.html()===undefined)//если во вкладке пусто, то достаём контент
		//{
			getContent($(this));
		//}
	})






	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	//ADD TRAFFIC///////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	$(document).on("click","#traffic_submit",function(e){
		var Form = $(this).closest('form');
		var data = Form.serialize();

		var required_params = ['assigned_action','client_name'];
		
		if(validateForm(Form,required_params)!==true)
		{
			alert(validateForm(Form,required_params));
			return 0;
		}

		$.ajax({
			type: 'POST',
			url: '/trafficadd',
			dataType : "json", 
	        data: data,
	        headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    },
		    success: function(data, xhr,ajaxOptions, thrownError)
		    {
		    	if(data.status===1){//если трафик создан, нет ошибок и статус = 1
		    		send(data.data); //отправляем сообщение 
		    		refreshContent(); //обновляем контент
		    		Form[0].reset(); //чистим форму создание трафика
		    		Form.find(".active").each(function(){
		    			$(this).removeClass('active');
		    		})
		    	}
		    	else{
		    		log('На сервере какие-то проблемы. Трафик не создан. ');
		    		log("Ошибка: code-"+xhr.status+" "+thrownError);
			    	log(xhr.responseText);
			    	log(ajaxOptions)
		    	}
		    },
		    error:function(xhr, ajaxOptions, thrownError){
		    	log("Ошибка: code-"+xhr.status+" "+thrownError);
		    	log(xhr.responseText);
		    	log(ajaxOptions)
		    }
		});
		
	});
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	//END TRAFFIC///////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////






	//КЛИК ПО ВКЛАДКЕ ЖУРНАЛ АКТИВЕН
	$('body').on('click','#log-tab',function(){

		getJournal();
		
	})









	$("body").on('click','#savecar',function(){
		
	})


















	// Открытие боковой панели
	$(document).on('click', '#opening', function() {
		$(this).blur();
		$('#hidden_panel').css('right', '0');
		$('#disableContent').css('display', 'block');
		
		if($("#log-tab").hasClass('active'))
			getJournal();
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

})










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

