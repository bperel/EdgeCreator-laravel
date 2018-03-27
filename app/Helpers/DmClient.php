<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;

class DmClient
{
    private static $roles = ['rawsql', 'ec'];
    private $roleCredentials = [];

    public $DB_DM = 'db_dm';
    public $DB_EC = 'db_edgecreator';

    private static $chunkable_services = [
        '/coa/list/countries' => 50,
        '/coa/list/publications' => 10
    ];

    private $client;

    public function __construct($endpoint, $roleCredentials) {
        $this->client = new Client(['base_uri' => $endpoint]);

        foreach (self::$roles as $roleName) {
            if (!isset($roleCredentials[$roleName])) {
                throw new \Exception('Unknown role : '.$roleName);
            }
        }
        $this->roleCredentials = $roleCredentials;
    }

    /**
     * @param string $query
     * @param string $db
     * @return mixed|null
     * @throws \Exception
     */
    public function getQueryResults($query, $db)
    {
        return $this->getServiceResults('POST', '/rawsql', [
            'query' => $query,
            'db' => $db
        ], 'rawsql');
    }

    /**
     * @param string $method
     * @param string $path
     * @param array  $parameters
     * @param string $role
     * @param bool   $do_not_chunk
     * @return mixed|null
     * @throws \Exception
     */
    public function getServiceResults($method, $path, $parameters = [], $role = 'rawsql', $do_not_chunk = false) {
        switch ($method) {
            case 'GET':
                if (count($parameters) > 0) {
                    if (!$do_not_chunk && count($parameters) === 1 && isset(self::$chunkable_services[$path])) {
                        return self::get_chunkable_service_results($method, $path, $parameters, $role);
                    }
                    else {
                        $path .= '/' . implode('/', $parameters);
                    }
                }
                break;
            default:
        }

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Cache-Control' => ' no-cache',
            'x-dm-version' => ' 1.0',
        ];
        if (session()->has('user')) {
            $headers[] = 'x-dm-user: ' . session()->get('user');
            $headers[] = 'x-dm-pass: ' . session()->get('pass');
        }

        $url = '/dm-server' . $path;
        $responseBody = null;
        try {
            $response = $this->client->request($method, $url, [
                'auth' => [$role, $this->roleCredentials[$role]],
                'headers' => $headers,
                'form_params' => $parameters
            ]);
        }
        catch(ClientException $e) {
            dd($e->getRequest()->getHeaders());
            $statusCode = $e->getCode();
            throw new \Exception("Call to service $method $url failed, Response code = $statusCode, response buffer = {$e->getResponse()->getBody()}");
        }

        switch ($response->getStatusCode()) {
            case Response::HTTP_NO_CONTENT:
                return null;
                break;
            case Response::HTTP_OK:
            case Response::HTTP_CREATED:
                $responseBody = $response->getBody();
                $results = json_decode($responseBody);
                if (is_array($results) || is_object($results)) {
                    return $results;
                }
        }

        throw new \Exception("Call to service $method $url failed, Response code = {$response->getStatusCode()}, response buffer = $responseBody");
    }

    /**
     * @param string $method
     * @param string $path
     * @param array  $parameters
     * @param string $role
     * @return array|null|\stdClass
     * @throws \Exception
     */
    private function get_chunkable_service_results($method, $path, $parameters, $role) {
        $parameterListChunks = array_chunk(explode(',', $parameters[count($parameters) - 1]), self::$chunkable_services[$path]);
        $results = null;
        foreach ($parameterListChunks as $parameterListChunk) {
            $result = $this->getServiceResults($method, $path, [implode(',', $parameterListChunk)], $role, true);
            if (is_null($results)) {
                $results = $result;
            }
            else {
                if (is_object($result)) {
                    $results = (object)array_merge_recursive((array)$results, (array)$result);
                }
                else if (is_array($result)) {
                    $results = array_merge($results, $result);
                }
            }
        }
        return $results;
    }
}
