<?php

namespace Drupal\oembedly\Embed\Providers\OEmbed;

use Embed\Adapters\Adapter;
use Embed\Http\Response;
use Embed\Http\Url;
use Embed\Providers\OEmbed\EndPointInterface;

/**
 * Embedly.
 */
class Embedly implements EndPointInterface {

  private $response;
  private $url;

  /**
   * {@inheritdoc}
   */
  public static function create(Adapter $adapter) {
    $url = $adapter->getConfig('oembed[iframely_url]');
    if (!empty($url)) {
      return new static($adapter->getResponse(), $url);
    }
  }

  /**
   * Constructor.
   *
   * @param Response $response
   *   Response.
   * @param string $url
   *   Url.
   */
  private function __construct(Response $response, $url) {
    $this->response = $response;
    $this->url = $url;
  }

  /**
   * {@inheritdoc}
   */
  public function getEndPoint() {
    return Url::create($this->url . '/oembed')
      ->withQueryParameters([
        'url' => (string) $this->response->getUrl(),
        'format' => 'json',
      ]);
  }

}
