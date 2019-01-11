<?php

namespace App\Classes\Socket;

use App\Classes\Socket\Base\BaseSocket;
use Ratchet\ConnectionInterface;

Class ChatSocket extends BaseSocket
{
	protected $clients;

	public function __construct()
	{
		$this->clients = new \SplObjectStorage;
	}

	public function onOpen(ConnectionInterface $conn)
	{
		$this->clients->attach($conn);
		echo "Новое соединение! ({$conn->resourceId})\n";
	}

	public function onMessage(ConnectionInterface $from,$msg)
	{
		$param = json_decode($msg);

		$user = $param->user;
		$data = $param->data;

		$numRecv = count($this->clients) - 1;
		
		echo sprintf('Пользователь %d отправил сообщение "%s" другому %d пользователю из %d '."\n",
				$from->resourceId, $data, $user, $numRecv
		);

		foreach ($this->clients as $key => $client) {
			if($from !== $client)
			{
				$client->send($msg);
			}
		}
	}

	public function onClose(ConnectionInterface $conn)
	{
		$this->clients->detach($conn);
		echo "Соединение {$conn->resourceId} разорвано \n";
	}

	public function onError(ConnectionInterface $conn, \Exception $e)
	{
		echo "Обнаружена ошибка, соединение разорвано \n";
		$conn->close();
	}
}