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
		$numRecv = count($this->clients) - 1;
		echo sprintf('Пользователь %d отправил сообщение "%s" другим %d пользователям'."\n",
				$from->resourceId, $msg, $numRecv
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
		echo "Соединение {$conn->resourceId} разорвано";
	}

	public function onError(ConnectionInterface $conn, \Exception $e)
	{
		echo "Обнаружена ошибка, соединение разорвано";
		$conn->close();
	}
}