<?php namespace Codesleeve\GuardLiveReload\Protocols;

use Ratchet\Http\HttpServerInterface;
use Ratchet\ConnectionInterface;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;

class HttpFileProtocol implements HttpServerInterface
{
    public function __construct($base = null)
    {
        $this->file = array();
        $this->base = $base ?: realpath(__DIR__ . '/../../../../public');
    }

    public function onOpen(ConnectionInterface $conn, RequestInterface $request = null)
    {
        $this->log('open', $conn);

        $response = new Response(200, array(
            'Content-type' => 'text/javascript',
            'Content-Length' => $this->getFilesize($request->getPath())
        ));

        $response->setBody($this->getContent($request->getPath()));

        $conn->send((string) $response);
        $conn->close();
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        // do nothing
        $this->log('message', $from, $msg);
    }

    public function onClose(ConnectionInterface $conn)
    {
        // do nothing
        $this->log('close', $conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        // do nothing
        $this->log('error', $conn, $e->getMessage());
    }

    protected function getContent($path)
    {
        if (!array_key_exists($path, $this->file))
        {
            $this->file[$path] = file_get_contents($this->base . $path);
        }

        return $this->file[$path];
    }

    protected function getFilesize($path)
    {
        return filesize($this->base . $path);
    }

    protected function log($connectionType, $conn, $extra = "")
    {
        // print "{$connectionType}: {$conn->resourceId} {$extra}" . PHP_EOL;
    }    
}