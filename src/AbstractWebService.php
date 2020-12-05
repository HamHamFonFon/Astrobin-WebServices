<?php
namespace AstrobinWs;

use AstrobinWs\Exceptions\WsException;
use AstrobinWs\GuzzleSingleton;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use http\Client\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Class AstrobinWebService
 * @package AppBundle\Astrobin
 */
abstract class AbstractWebService
{

    public const MAX_REDIRS = 10;
    public const LIMIT_MAX = 20;
    public const TIMEOUT = 30;

    protected $timeout;
    private $apiKey;
    private $apiSecret;

    protected static $headers = [
        'Accept' => GuzzleSingleton::APPLICATION_JSON,
        'Content-Type' => GuzzleSingleton::APPLICATION_JSON
    ];

    /** @var Client */
    private $client;


    /**
     * AbstractWebService constructor.
     */
    public function __construct()
    {
        $this->apiKey = getenv('ASTROBIN_API_KEY');
        $this->apiSecret = getenv('ASTROBIN_API_SECRET');
        $this->timeout = self::TIMEOUT;
        $this->buildFactory();
    }


    /**
     * Get Guzzle Instance
     */
    public function buildFactory(): void
    {
        $this->client = GuzzleSingleton::getInstance();
    }

    /** Return endpoint */
    abstract protected function getEndPoint(): string;

    /**
     * Build EndPoint
     * @param int|null $param
     *
     * @return string
     */
    private function buildEndpoint(?int $param): string
    {
        return (!is_null($param)) ? sprintf('/%s/%d', $this->getEndPoint(), $param) : $this->getEndPoint();
    }

    /**
     * @param int $id
     * @param array|null $queryParams
     *
     * @return Response
     * @throws WsException
     */
    protected function get(?int $id, ?array $queryParams): Response
    {
        return $this->buildRequest($id, null, $queryParams, null, GuzzleSingleton::METHOD_GET);
    }

    /**
     * NOT USED, just for example
     *
     * @param int $id
     * @param array $queryParams
     * @param array $body
     *
     * @throws WsException
     */
    protected function post(int $id, array $queryParams, array $body)
    {
        $this->buildRequest($id, $body, $queryParams, null, GuzzleSingleton::METHOD_POST);
    }

    /**
     * @param int $id
     * @param array $body
     * @param array|null $queryParams
     * @param array $headers
     * @param string $method
     *
     * @return
     * @throws WsException
     */
    private function buildRequest(?int $id, ?array $body, ?array $queryParams, ?array $headers, string $method)
    {
        if (is_null($this->apiKey) || is_null($this->apiSecret)) {
            throw new WsException("Astrobin Webservice : API key or API secret are null", 500, null);
        }

        $endPoint = $this->buildEndpoint($id);

        $astrobinParams = [
            'api_key' => $this->apiKey,
            'api_secret' => $this->apiSecret,
            'format' => 'json'
        ];

        $options = [
            'query' => array_filter(array_merge($astrobinParams, $queryParams))
        ];

        $options['headers'] = self::$headers;

        if (!is_null($body) && !empty($body)) {
            $options['body'] = $body;
        }

        try {
            /** @var ResponseInterface $request */
            $response = $this->client->request($method, $endPoint, $options);
        } catch (GuzzleException $e) {
            throw new WsException($e->getMessage(), $e->getCode(), null);
        }

        return $this->getResponse($response);
    }


    /**
     * Check response and jsondecode object
     *
     * @param ResponseInterface $response
     *
     * @return mixed|null
     * @throws WsException
     */
    public function getResponse(ResponseInterface $response): string
    {
        if (200 !== $response->getStatusCode()) {
            throw new WsException(sprintf('[Astrobin Response] Error response: %s', $response->getReasonPhrase()), 500, null);
        }

        /** @var StreamInterface $body */
        $body = $response->getBody();
        if (false === strpos($body, '{', 0)) {
            throw new WsException(sprintf("[Astrobin Response] Not a JSON valid format :\n %s", (string)$body), 500, null);
        }

        $contents = $body->getContents();
        if (empty($contents)) {
            throw new WsException(sprintf("[Astrobin Response] Empty response from endPoint \"%s\"", $this->getEndPoint()), 500, null);
        }
        return $contents;
    }
}
