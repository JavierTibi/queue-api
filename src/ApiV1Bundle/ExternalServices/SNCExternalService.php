<?php
namespace ApiV1Bundle\ExternalServices;

use Symfony\Component\DependencyInjection\Container;
use Unirest\Request;

class SNCExternalService
{
    private $host = null;
    private $urls = [];

    public function __construct(Container $container)
    {
        $config = $container->getParameter('integration');
        $this->host = $config['host'];
        $this->urls = $config['urls'];
    }

    /**
     * Método POST
     *
     * @param $url
     * @param null $body
     * @return mixed
     */
    public function post($url, $body = null)
    {
        $request = $body ? Request\Body::json($body) : null;
        $response = Request::post($url, $this->getHeaders(), $request);
        return $response->body;
    }

    /**
     * Método PUT
     *
     * @param $url
     * @param $body
     * @return mixed
     */
    public function put($url, $body)
    {
        $request = $body ? Request\Body::json($body) : null;
        $response = Request::put($url, $this->getHeaders(), $request);
        return $response->body;
    }

    /**
     * Método DELETE
     *
     * @param $url
     * @param $body
     * @return mixed
     */
    public function delete($url, $body)
    {
        $request = $body ? Request\Body::json($body) : null;
        $response = Request::delete($url, $this->getHeaders(), $request);
        return $response->body;
    }

    public function get($url, $parameters = null)
    {
        $response = Request::get($url, $this->getHeaders(), $parameters);
        return $response->body;
    }

    /**
     * Componer una url
     *
     * @param $name
     * @param $additional
     * @return NULL|string
     */
    public function getUrl($name, $additional = null)
    {
        $url = null;
        if (isset($this->urls[$name])) {
            $url = $this->host . $this->urls[$name];
        }
        if ($url && $additional) {
            $url .= '/' . $additional;
        }
        return $url;
    }

    /**
     * Headers de la llamada a la API
     *
     * @return array
     */
    private function getHeaders()
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];
        return $headers;
    }
}
