<?php

namespace App\Classes\Socket;

use App\Classes\Socket\Base\BaseSocket;
use Ratchet\ConnectionInterface;


use Illuminate\Support\Facades\Redis;

Class ChatSocket extends BaseSocket
{
	protected $clients;
	protected $users;

	protected $online = array();

	public function __construct()
	{
		$this->clients = [];//new \SplObjectStorage;
	}



	public function onOpen(ConnectionInterface $conn)
	{
		$this->clients[$conn->resourceId] = $conn;
		$this->loglist(1,$conn->resourceId);
	}



	public function onMessage(ConnectionInterface $from,$msg)
	{	
		/*
			Параметры сообщения
			*передаётся при открытие соединения
			- open_user = id авторизированного пользователя, которое !!!передаётся js на стороне клиента только при открытие соединения!!!
			
			*передаётся при отправке сообщения
			- user 		= id авторизированного пользователя, которму нужно передать сообщение
			- traffic_id= id созданного трафика
			- client 	= имя клиента, которое укзал автор трафика

			*передаётся при обработке сообщения
			- from 		= от кого сообщение
		*/
		$param = json_decode($msg);

		if(isset($param->open_user))
		{	
			//open_user передаётся только при подключении
			$this->online[$from->resourceId] = $param->open_user;
			$this->loglist(2, $from->resourceId, $param->open_user);
			return;
		}

		$user 			= $param->user;
		$client 		= $param->client;
		$traffic 	 	= $param->traffic_id;
		$param->from 	= $from->resourceId;
		$response 		= @$param->response;

		$fromConnection = @$from->resourceId; //id соединения клиента который отправил сообщение
		$fromUser = @$this->online[$fromConnection]; //id КЛИЕНТА который отправляет сообщение

		$toUser = @$param->user; // id клиента адресата
		$toConnection = @array_search($toUser,$this->online); //id соединения клиента адресата

		$message = array(
				'user'=>$toUser,
				'from'=>$fromUser,
				'traffic_id'=>$traffic,
				'client'=>$client,
				'response'=>$response
		);
		$message = json_encode($message);

		if($user)
		{
			$this->loglist(3,$fromConnection,$fromUser,$toConnection,$toUser,$message);

			$this->clients[$toConnection]->send($message); //отправляем адресату сообщение
		}
		else{
			foreach ($this->online as $key => $value) {
				if($key!=$from->resourceId)
				{
					$this->loglist(3,$fromConnection,$fromUser,$key,$value,$message);
					$this->clients[$key]->send($message);
				}
			}
		}
	}



	public function onClose(ConnectionInterface $conn)
	{
		//$this->clients->detach($conn);
		$this->loglist(4,$conn->resourceId,$this->online[$conn->resourceId]);
		unset($this->online[$conn->resourceId]);
		unset($this->clients[$conn->resourceId]);
	}



	public function onError(ConnectionInterface $conn, \Exception $e)
	{	
		$date = date('d.m H:i');
		echo "{$date} - Обнаружена ошибка, соединение разорвано \n";
		echo "--------------".$e->getMessage()."\n";
		$conn->close();
	}

	public function loglist($status = '',$connect_id='',$user_connect='',$addressat_connect='',$addressat_user='',$message='')
	{
		$date = date('d.m H:i');
		switch ($status) {
			case '1':
				echo "{$date} - Установлено новое соединение (номер соединения = {$connect_id}) \n";
				break;

			case '2':
				echo "{$date} - Для соединения {$connect_id} идентифицирован авторизованный пользователь с id = {$user_connect} \n";
				break;

			case '3':
				echo "{$date} - Отправка нового сообщения от пользователя id = {$user_connect} (соединение = {$connect_id})\n";
				echo "--------------к пользователю id = {$addressat_user} (соединение = {$addressat_connect}\n";
				echo "--------------Сообщение:".$message."\n";
				break;

			case '4':
				echo "{$date} - Соединение с номером = {$connect_id} разорвано\n";
				echo "{$date} - Пользователь с id = {$user_connect} отключен\n";
				break;
			
			default:
				echo "{$date} - Я работаю, на меня, что отправили, но я не знаю, что это ... (я не ошибка, а исключение не понятного результата) \n";
				break;
		}
	}
}