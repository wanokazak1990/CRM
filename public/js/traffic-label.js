function addAlertTraffic(param)
{
	var status = 0;

	modal = $(".original_traffic_modal").clone();
	traffic_id = param.traffic_id;
	$.ajax({
		url: '/gettraffic',
		type: 'POST',
		data: {'id':traffic_id},
		headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },
		success: function(data){
			var obj = JSON.parse(data);
			
			modal.removeClass("original_traffic_modal");
			modal.attr("traffic_id",param.traffic_id);
			modal.find('.t_id').html(obj.id);
			modal.find('.t_date').html(obj.date);
			modal.find('.t_author').html(obj.author);
			modal.find('.t_type').html(obj.type);
			modal.find('.t_model').html(obj.model);
			modal.find('.t_address').html(obj.address);
			modal.find('.t_action').html(obj.action);
			modal.find('.t_name').html(obj.client);
			modal.find('.t_timer').html(response_time/1000);			

			modal.appendTo("body");

		},
		error: function(){
			alert(0)
		}
	})

	if(param.response===1)//если это возврат обратно на рецепшон
	{
		modal.find(".hidden-button").css('display','block');
		modal.find(".show-button").css('display','none');
		modal.find('.t_timer').remove();
	}
	if(param.response!==1)//если это отправленная модаль для манагера
	{
		modal.find(".hidden-button").css('display','none');
		modal.find(".show-button").css('display','block');
      	var timerId = setInterval(function() {
		  var current_timer =$(".traffic_modal[traffic_id='"+param.traffic_id+"']").find('.t_timer').html();
		  $(".traffic_modal[traffic_id='"+param.traffic_id+"']").find('.t_timer').html(current_timer-1);
		}, 1000);

		setTimeout(function() {
		  clearInterval(timerId);
		  $(".traffic_modal[traffic_id='"+param.traffic_id+"']").remove();
		  if(status==0)
		  	close(param);
		}, response_time);
	}

	//ОТКЛОНИТЬ МОДАЛЬНОЕ ОКНО ТРАФИКА 
	modal.on('click','.traffic_deny',function(){
		$(".traffic_modal[traffic_id='"+param.traffic_id+"']").remove();
		modal='';
	})


	//ОТПРАВИТЬ ВСЕМ МОДАЛЬНОЕ ОКНО ТРАФИКА
	modal.on('click','.traffic_toall',function(){
		param.from = 0;
		close(param);
		$(".traffic_modal[traffic_id='"+param.traffic_id+"']").remove();
		modal='';
	})


	//ПОВТОР ОТПРАВКИ МОДАЛЬНОГО ОКНА
	modal.on('click','.traffic_resend',function(){
		close(param);
		$(".traffic_modal[traffic_id='"+param.traffic_id+"']").remove();
		modal='';
	})


	//ПРИНЯТЬ ТРАФИК
	modal.on('click','.traffic_apply',function(){
		status = 1;

		var formData = new FormData();
		formData.append('traffic_id',param.traffic_id);
		formData.append('manager_id',manager);
		
		$.ajax({
			url: '/create/worklist',
			type: 'POST',
			dataType : "json", 
	        cache: false,
	        contentType: false,
	        processData: false, 
	        data: formData,
			headers: {
	        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    	},
	    	success: function(data){
	    		$('#hidden_panel').css('right', '-50%');//закрыть
	    		$('#disableContent').css('display', 'none');//закрыть
	    		setTimeout(function(){//пауза что бы панель закрылась
	    			$('#hidden_panel').css('right', '0');//после паузы открываем панель
	    			$('#disableContent').css('display', 'block');
	    			$('#hiddenTab a[href="#worksheet"]').tab('show');
	    		},500)
	    			    		
	    		log('ПРИНЯЛ');

	    		worklistData(data);
	    	},
	    	error:function(xhr, ajaxOptions, thrownError){
	    		log('Не могу принять трафик');
		    	log("Ошибка: code-"+xhr.status+" "+thrownError);
		    	log(xhr.responseText);
		    	log(ajaxOptions)
		    	log('ddd')
		    }
		})
		$(".traffic_modal[traffic_id='"+param.traffic_id+"']").remove();
		modal='';
	})
};

function worklistData(data)
{
	var work = $("#worksheet");//вкладка рабочего листа
	for(key in data){ //прохожу по всем элеентам которые вернул php при аякс запросе, кей это название параметра
		val = data[key];//значение параметра
		if(typeof(val)==='string' || typeof(val)==='number'){//если значение строка или чило
			var elem = work.find('[name="'+key+'"]');//получаю элемент хтмл с именем как у параметра
			var tag = elem[0].tagName.toLowerCase();//узнаю тег этого элемента
			if(tag=='span' ){//если спан 
				elem.html(val);//записываю в него
			}
			if(tag=='input'){//если инпут
				if(elem.attr('type')=='text')
					elem.val(val);//записываю в него				
				if(elem.attr('type')=='time')
					elem.val(val);//записываю в него
			}
			if(tag=='select'){
				elem.find('option').each(function(){
					if($(this).val()==val)
						$(this).attr("selected", "selected");
				})
			}
		}
		else if(typeof(val)==='object' || typeof(val)==='array'){//если массив или объект
			var block = work.find('[wl_block="'+key+'"]');//ищу контейнер для этих данных
			var subblock = block.find(".input-group");
			for(index in val)//проходусь по строкам массива
			{
				var subval = val[index];//текущая строка массива
				if(index==0){
					for(name in subval){//прохожу по строке
						
						var znachenie = subval[name];
						var elem = block.find('[name="'+name+'[]"]');
						var tag = elem[0].tagName.toLowerCase();
						if(tag=='span' ){//если спан 
							elem.html(znachenie);//записываю в него
						}
						if(tag=='input'){//если инпут
							if(elem.attr('type')=='text')
								elem.val(znachenie);//записываю в него				
							if(elem.attr('type')=='time')
								elem.val(znachenie);//записываю в него
						}
						if(tag=='select'){
							elem.find('option').each(function(){
								if($(this).val()==znachenie)
									$(this).attr("selected", "selected");
							})
						}
					}
				}
			}
		}
	}
}

function close(param){
	var response = 1
	if(param.response == '1')
	{
		response = 0;
	}
	var message = {
		'traffic_id':param.traffic_id,
		'user':param.from,
		'client':param.client,
		'response':response
	};
	send(message);
}

