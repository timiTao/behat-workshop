<?php
namespace Helpers;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Client
{
    /** @var ClientInterface */
    private $client;
    /** @var array */
    private $headers = [];
    /** @var  RequestInterface */
    private $request;
    /** @var  ResponseInterface */
    private $response;
    /** @var  array */
    private $query;
    /** @var array */
    private $placeHolders = [];

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function printResponse()
    {
        $request = $this->request;
        $response = $this->response;

        return sprintf(
            "METHOD => %s \n URL => %s \n BODY => %s\n HEADERS => %s \n RESPONSE  %d:\n%s",
            $request->getMethod(),
            (string)($request->getUri()),
            (string)$request->getBody(),
            (string)print_r(
                $request->getHeaders(),
                true
            ),
            $response->getStatusCode(),
            (string)$response->getBody()
        );
    }

    public function setPlaceHolder(string $key, string $value)
    {
        $this->placeHolders[$key] = $value;
    }

    public function removeHeader(string $headerName)
    {
        if (array_key_exists(
            $headerName,
            $this->headers
        )) {
            unset($this->headers[$headerName]);
        }
    }

    public function setQuery(array $query = null)
    {
        $this->query = $query;
    }

    public function setQueryParam(string $name, string $value)
    {
        $this->query[$name] = $value;
    }

    public function removeQueryParam(string $queryParam)
    {
        if (array_key_exists($queryParam, $this->query)) {
            unset($this->query[$queryParam]);
        }
    }

    public function sendJsonRequest(string $url, string $method)
    {
        $headers = $this->getHeaders();
        if (!isset($headers['Accept'])) {
            $this->addHeader(
                'Accept',
                'application/json'
            );
        }
        if (!isset($headers['Content-type'])) {
            $this->addHeader(
                'Content-type',
                'application/json'
            );
        }
        $this->sendRequest(
            $url,
            $method
        );

        return json_decode(
            $this->getResponse()
                ->getBody(),
            true
        );
    }

    public function sendJsonARequestWithValues(string $method, string $url, array $post)
    {
        $headers = $this->getHeaders();
        if (!isset($headers['Accept'])) {
            $this->addHeader(
                'Accept',
                'application/json'
            );
        }
        if (!isset($headers['Content-type'])) {
            $this->addHeader(
                'Content-type',
                'application/json'
            );
        }

        $url = $this->prepareUrl($url);
        $fields = [];
        foreach ($post as $key => $val) {
            $fields[$key] = $this->replacePlaceHolder($val);
        }
        $bodyOption = [
            'body' => json_encode($fields),
        ];
        $this->request =
            new Request(
                $method,
                $url,
                $this->headers,
                $bodyOption['body']
            );

        $this->sendFinalRequest();
    }

    public function addHeader(string $name, string $value)
    {
        if (isset($this->headers[$name])) {
            if (!is_array($this->headers[$name])) {
                $this->headers[$name] = [$this->headers[$name]];
            }
            $this->headers[$name][] = $value;
        } else {
            $this->headers[$name] = $value;
        }
    }

    public function sendRequest(string $url, string $method)
    {
        $url = $this->prepareUrl($url);
        $this->request = new Request(
            $method,
            $url,
            $this->headers
        );

        $this->sendFinalRequest();
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function sendARequestWithValues(string $method, string $url, array $post)
    {
        $url = $this->prepareUrl($url);
        $fields = [];
        foreach ($post as $key => $val) {
            $fields[$key] = $this->replacePlaceHolder($val);
        }
        $bodyOption = [
            'body' => json_encode($fields),
        ];
        $this->request =
            new Request(
                $method,
                $url,
                $this->headers,
                $bodyOption['body']
            );

        $this->sendFinalRequest();
    }

    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    protected function replacePlaceHolder(string $string)
    {
        foreach ($this->placeHolders as $key => $val) {
            $string = str_replace(
                $key,
                $val,
                $string
            );
        }

        return $string;
    }

    protected function getHeaders(): array
    {
        return $this->headers;
    }

    private function prepareUrl(string $url): string
    {
        return ltrim($this->replacePlaceHolder($url), '/');
    }

    private function sendFinalRequest()
    {
        try {
            $this->response = $this->getClient()
                ->send(
                    $this->request,
                    ['query' => $this->query]
                );
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            if (null === $this->response) {
                throw $e;
            }
        }
        $this->query = null;
    }

    private function getClient(): ClientInterface
    {
        if (null === $this->client) {
            throw new \RuntimeException(
                'Client has not been set in WebApiContext'
            );
        }

        return $this->client;
    }
}
