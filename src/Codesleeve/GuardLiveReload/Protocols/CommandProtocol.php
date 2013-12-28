<?php namespace Codesleeve\GuardLiveReload\Protocols;

use Exception;
use SplObjectStorage;
use Codesleeve\GuardLiveReload\LiveReloadMonitor;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class CommandProtocol implements MessageComponentInterface
{
    /**
     * Connections are stored so we can broadcast messages
     * Protocols are stored so that we know we have sent the handshake
     * 
     */
    public function __construct(LiveReloadProtocol $livereload)
    {
        $this->livereload = $livereload;
    }

    /**
     * When the connection opens we send 
     * @param  ConnectionInterface $conn [description]
     * @return [type]                    [description]
     */
    public function onOpen(ConnectionInterface $conn)
    {

    }

    /**
     * When we receive a message from client we need to process it
     * 
     * @param  ConnectionInterface $conn [description]
     * @param  [type]              $msg  [description]
     * @return [type]                    [description]
     */
    public function onMessage(ConnectionInterface $conn, $msg)
    {
        switch ($msg)
        {
            case 'reload': $this->livereload->reloadCommand(); break;
        }
    }

    /**
     * When a connection is closed, we remove it from our 
     * list of connections
     * 
     * @param  ConnectionInterface $conn [description]
     * @return [type]                    [description]
     */
    public function onClose(ConnectionInterface $conn)
    {

    }

    /**
     * If we experience errors, then we just close the connection
     * 
     * @param  ConnectionInterface $conn [description]
     * @param  Exception           $e    [description]
     * @return [type]                    [description]
     */
    public function onError(ConnectionInterface $conn, Exception $e)
    {
        $conn->close();
    }
}