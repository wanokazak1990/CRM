var conn = new WebSocket('ws://new1.loc:8080');

conn.onopen = function (e)
{
	console.log('Соединение установлено!');//ЛОГ ЧТО ВСЁ НОРМ
	var my_id = $("#auth_user_id").val();//БЕРУ id ПОЛЬЗОВАТЕЛЯ КОТОРЫЙ ОТКРЫЛ СОКЕТ
	var data = {'open_user':my_id};//СОЗДАЮ ОБЪЕКТ ДЛЯ ОТПРАВКИ СООБЩЕНИЯ СРАЗУ ПОСЛЕ КОНЕКТА
	registerUser(data);
}

conn.onmessage = function(e)//получение сообщения
{
	//e.data - это данные которые отправил сервер

	
	var param = JSON.parse(e.data);
	log('Полученно '+JSON.stringify(param));
	addAlertTraffic(param);
}

function send(message)
{	
	var json = JSON.stringify(message);
	conn.send(json);
	console.log('Отправлено: '+json);
}

function registerUser(message)
{
	var json = JSON.stringify(message);
	
	conn.send(json);
}