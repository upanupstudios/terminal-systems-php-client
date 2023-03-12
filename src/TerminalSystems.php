<?php

namespace Upanupstudios\TerminalSystems\Php\Client;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;

class TerminalSystems
{
  /**
   * The REST API URL.
   *
   * @var string $api_url
   */
  private $api_url = 'https://[code].terminalsystems.com';

  private $config;
  private $httpClient;

  public function __construct(Config $config, ClientInterface $httpClient)
  {
    $this->config = $config;
    $this->httpClient = $httpClient;
  }

  public function getApiUrl()
  {
    return $this->api_url;
  }

  public function getConfig(): Config
  {
    return $this->config;
  }

  public function request(string $method, array $options = [])
  {
    try {
      $apiKey = $this->config->getApiKey();
      $airportCode = $this->config->getAirportCode();

      $api_url = str_replace('[code]', $airportCode, $this->api_url);

      $defaultOptions = [
        'headers' => [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json',
        ],
        'query' => ['apikey' => $apiKey]
      ];

      if(!empty($options)) {
        $options = array_merge($defaultOptions, $options);
      } else {
        $options = $defaultOptions;
      }

      $request = $this->httpClient->request($method, $api_url, $options);

      // Return as array
      $response = $this->prepareResponse($request);

    } catch (\JsonException $exeption) {
      $response = $exeption->getMessage();
    } catch (RequestException $exception) {
      $response = $exception->getMessage();
    }

    return $response;
  }

  public function arrivals() {
    return $this->request('GET', ['query' => ['arrdep' => 'a']]);
  }

  public function departures() {
    return $this->request('GET', ['query' => ['arrdep' => 'd']]);
  }

  /**
   * @return object
   *
   * @throws \InvalidArgumentException
   *  If $class does not exist.
   */
  public function api(string $class)
  {
    $api = null;

    switch ($class) {
      default:
      throw new \InvalidArgumentException("Undefined api instance called: '$class'.");
    }

    return $api;
  }

  public function __call(string $name, array $args): object
  {
    try {
      return $this->api($name);
    } catch (\InvalidArgumentException $e) {
      throw new \BadMethodCallException("Undefined method called: '$name'.");
    }
  }
}