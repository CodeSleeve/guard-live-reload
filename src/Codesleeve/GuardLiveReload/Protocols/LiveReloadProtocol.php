<?php namespace Codesleeve\GuardLiveReload\Protocols;

use Exception;
use SplObjectStorage;
use Codesleeve\GuardLiveReload\LiveReloadMonitor;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class LiveReloadProtocol implements MessageComponentInterface
{
    /**
     * This is the protocol we are sticking with for now
     * 
     * @var string
     */
    protected $protocol = "http://livereload.com/protocols/official-7";

    /**
     * Connections are stored so we can broadcast messages
     * Protocols are stored so that we know we have sent the handshake
     * 
     */
    public function __construct()
    {
        $this->connections = new SplObjectStorage;
        $this->protocols = array();
    }

    /**
     * When the connection opens we send 
     * @param  ConnectionInterface $conn [description]
     * @return [type]                    [description]
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $this->connections->attach($conn);
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
        $data = json_decode($msg);

        if (!array_key_exists($conn->resourceId, $this->protocols) || $data->command == 'hello')
        {
            return $this->helloCommand($conn);
        }

        switch ($data->command)
        {
            case 'url':
                $this->urlCommand($conn, $data);
                break; 

            case 'info':
                $this->infoCommand($conn, $data);
                break;

            default: 
                $this->unknownCommand($conn, $data);
                break;
 
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
        $this->connections->detach($conn);
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

    /**
     * Sends a hello command when server first recieves handshake from client
     * 
     * @param  ConnectionInterface $conn [description]
     * @return [type]                    [description]
     */
    public function helloCommand(ConnectionInterface $conn)
    {
        $protocol = $this->protocols[$conn->resourceId] = $this->protocol;
        $handshake = '{"command": "hello", "protocols": ["' . $protocol . '"], "serverName": "codesleeve-guard-live-reload"}';
        $conn->send($handshake);
    }

    /**
     * Sends an alert command from server to all clients
     * 
     * @param  ConnectionInterface $conn [description]
     * @return [type]                    [description]
     */
    public function alertCommand($msg)
    {
        $command = '{"command": "alert", "message": "' . $msg . '"}';

        foreach ($this->connections as $conn)
        {
            $conn->send($command);
        }
    }

    /**
     * Sends a reload command from server to client
     * 
     * @param  ConnectionInterface $conn [description]
     * @return [type]                    [description]
     */
    public function reloadCommand()
    {
        $command = '{"command": "reload", "path":"/some/fake/path/i/guess", "liveCSS": false}';

        foreach ($this->connections as $conn)
        {
            $conn->send($command);
        }
    }

    /**
     * Process a url command from client to server
     * 
     * @param  ConnectionInterface $conn [description]
     * @param  [type]              $data [description]
     * @return [type]                    [description]
     */
    public function urlCommand(ConnectionInterface $conn, $data)
    {
        $this->connections->detach($conn);
        $conn->currentUrl = $data->url;
        $this->connections->attach($conn);
    }

    /**
     * We want to capture the url from this command from client to server
     * 
     * @param  ConnectionInterface $conn [description]
     * @param  [type]              $data [description]
     * @return [type]                    [description]
     */
    public function infoCommand(ConnectionInterface $conn, $data)
    {
        $this->connections->detach($conn);
        $conn->currentUrl = $data->url;
        $this->connections->attach($conn);
    }

    /**
     * Process unknown command
     * 
     * @param  ConnectionInterface $conn [description]
     * @param  [type]              $data [description]
     * @return [type]                    [description]
     */
    public function unknownCommand(ConnectionInterface $conn, $data)
    {
        print "not sure how to process the command: {$data->command}" . PHP_EOL;
    }
}