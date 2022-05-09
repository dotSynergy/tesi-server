<?php

declare(strict_types=1);

namespace App;

use Bloatless\WebSocket\Application\Application;
use Bloatless\WebSocket\Connection;

class DataSaver extends Application
{
    /**
    * @var EntityManager
    */
    protected $em;

    /**
     * @var array $clients
     */
    private array $clients = [];

    protected function __construct()
    {
        $em = DB\ModelManager::getInstance();
    }

    /**
     * Handles new connections to the application.
     *
     * @param Connection $connection
     * @return void
     */
    public function onConnect(Connection $connection): void
    {
        $id = $connection->getClientId();
        $this->clients[$id] = $connection;
    }

    /**
     * Handles client disconnects.
     *
     * @param Connection $connection
     * @return void
     */
    public function onDisconnect(Connection $connection): void
    {
        $id = $connection->getClientId();
        unset($this->clients[$id]);
    }

    /**
     * Handles incomming data/requests.
     * If valid action is given the according method will be called.
     *
     * @param string $data
     * @param Connection $client
     * @return void
     */
    public function onData(string $data, Connection $client): void
    {
        try {
            $decodedData = $this->decodeData($data);
            // check if action is valid
            if ($decodedData['action'] !== 'store') {
                return;
            }


            $s = new Models\SensorsData();
            $s->setId(0);

            $m = DB\ModelManager::getInstance();
            $m->persist($s);
            $m->flush();

        } catch (\RuntimeException $e) {
            // @todo Handle/Log error
        }
    }

    /**
     * Handles data pushed into the websocket server using the push-client.
     *
     * @param array $data
     */
    public function onIPCData(array $data): void
    {
        return;
    }

    /**
     * Echoes data back to client(s).
     *
     * @param string $text
     * @return void
     */
    private function actionEcho(string $text): void
    {
        $encodedData = $this->encodeData('echo', $text);
        foreach ($this->clients as $sendto) {
            $sendto->send($encodedData);
        }
    }
}