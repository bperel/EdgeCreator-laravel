<?php


namespace App\Exceptions;

use Exception;
use Psr\Http\Message\ResponseInterface;

class DmClientException extends Exception
{
    /**
     * DmClientException constructor.
     * @param string $method
     * @param string $url
     * @param string $statusCode
     * @param string $exceptionMessage
     * @param ResponseInterface $responseBody
     */
    public function __construct($method, $url, $statusCode, $exceptionMessage, $responseBody = null)
    {
        $message = "Call to service $method $url failed, Response code = $statusCode, message = $exceptionMessage";
        if (!is_null($responseBody)) {
            $message.=", Response body : {$responseBody->getBody()}";
        }
        parent::__construct($message);
    }
}
