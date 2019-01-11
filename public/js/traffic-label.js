$("body").on("click",".alert_traffic_block",function(){
	$(this).removeClass("animate-label-traffic");
	traffic_id = $(this).find('a').attr('traffic_id');
	$.ajax({
		url: '/gettraffic',
		type: 'POST',
		data: {'id':traffic_id},
		headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },
		success: function(data){
			var obj = JSON.parse(data);
			var div = '<div class="traffic_modal">';
				div += '<div class="col-12">';
					div += '<h3>Трафик №'+obj.id+' <span style="float:right">'+obj.manager+'</span></h3>';
					div += '<hr/>';
					div += '<h3>'+obj.date+' '+obj.author+'</h3>';
					div += '<div class="row">';

						div += '<div class="col-4">';
							div += '<div>Тип трафика</div>';
							div += '<div class="param btn btn-block">'+obj.type+'</div>';
						div += '</div>';

						div += '<div class="col-4">';
							div += '<div>Спрос</div>';
							div += '<div class="param btn btn-block">'+obj.model+'</div>';
						div += '</div>';

						div += '<div class="col-4">';
							div += '<div>Зона трафика</div>';
							div += '<div class="param btn btn-block">'+obj.address+'</div>';
						div += '</div>';

						div += '<div class="col-4">';
							div += '<div>Назначено</div>';
							div += '<div class="param btn btn-block">'+obj.action+'</div>';
						div += '</div>';

						div += '<div class="col-8">';
							div += '<div>&nbsp</div>';
							div += '<div class="btn btn-block text-left" style="font-size:24px;">'+obj.client+'</div>';
						div += '</div>';

						div += '<div class="w-100" style="height:30px;"></div>';

						div += '<div class="col-4">';
							div += '<button class="btn btn-block btn-success" id="traffic_deny">Принять</button>';
						div += '</div>';

						div += '<div class="col-4">';
							div += '<button class="btn btn-block btn-danger" id="traffic_deny">Отказаться</button>';
						div += '</div>';

					div += '</div>';
				div += '</div>';
			div+="</div>";

			$("body").append(div);
		},
		error: function(){
			alert(0)
		}
	})
});