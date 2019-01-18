function addAlertTraffic(param)

{	
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

			if(param.response===1)//если это возврат обратно на рецепшон
			{
				//modal.find(".button-div").css('display','none');
				modal.find(".hidden-button").css('display','block');
				modal.find(".show-button").css('display','none');
				//modal.find(".button-div").removeClass('hidden-button');
				modal.find('.t_timer').remove();
			}
			if(param.response!==1)//если это отправленная модаль для манагера
			{
				modal.find(".hidden-button").css('display','none');
				modal.find(".show-button").css('display','block');
		      	var timerId = setInterval(function() {
				  var current_timer =modal.find('.t_timer').html();
				  modal.find('.t_timer').html(current_timer-1);
				}, 1000);

				setTimeout(function() {
				  clearInterval(timerId);
				  $(".traffic_modal[traffic_id='"+param.traffic_id+"']").remove();
				  close(param);
				}, response_time);
			}

		},
		error: function(){
			alert(0)
		}
	})


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
		alert('трафик будет принят');
	})
};


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

