var conn = new WebSocket('ws://cms.loc:8080');
conn.onopen = function (e)
{
	console.log('Соединение установлено!');
}

conn.onmessage = function(e)//получение сообщения
{
	//e.data - это данные которые отправил сервер
	var param = JSON.parse(e.data);
	var auth_user_id = $("#auth_user_id").val();
	if(auth_user_id == param.user)
	{
		$("body").append('<div class="alert_traffic_block animate-label-traffic">'+param.data);
		$(".alert_traffic_block").each(function(i,item){
			var left = 15+$(this).width()*(i)+15*i;  
			$(this).css('left',left+'px');
			log('Получен новый трафик');	
			refreshContent();	
		})
	}
}

function send(user,message)
{	
	var ToUser = user;
	var data = message;
	var json = {"user":ToUser,"data":data};
	var json = JSON.stringify(json);
	conn.send(json);
	console.log('Отправлено: '+json);
}