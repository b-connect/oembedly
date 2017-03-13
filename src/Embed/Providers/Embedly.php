<?php

namespace Drupal\oembedly\Embed\Providers;

use Drupal\oembedly\Embed\Providers\OEmbed\Embedly as OembedEmbedly;
use Embed\Providers\OEmbed;
use Embed\Adapters\Adapter;
use Embed\Http\Response;

/**
 * {@inheritdoc}
 */
class Embedly extends OEmbed {

  protected $adapter;

  /**
   * {@inheritdoc}
   */
  public function __construct(Adapter $adapter) {
    parent::__construct($adapter);
    $this->adapter = $adapter;
    $endPoint = $this->getEndPoint();
    if ($endPoint) {
      $this->extractOembed($adapter->getDispatcher()->dispatch($endPoint));
    }
  }


  /**
   * Save the oembed data in the bag.
   *
   * @param Response $response
   */
  private function extractOembed(Response $response) {
    if (($response->getUrl()->getExtension() === 'xml') || ($response->getUrl()->getQueryParameter('format') === 'xml')) {
      if ($xml = $response->getXmlContent()) {
        foreach ($xml as $element) {
          $content = trim((string) $element);
          if (stripos($content, '<![CDATA[') === 0) {
            $content = substr($content, 9, -3);
          }
          $this->bag->set($element->getName(), $content);
        }
      }

    }
    else {
      if (($json = $response->getJsonContent()) && empty($json['Error'])) {
        $this->bag->set($json);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  private function getEndPoint() {
    // Search using the domain.
    // Try with iframely.
    $extraParameters = (array) $this->adapter->getConfig('oembed[parameters]');
    $endPoint = OembedEmbedly::create($this->adapter);
    if ($endPoint && ($url = $endPoint->getEndPoint())) {
      return $url->withAddedQueryParameters($extraParameters);
    }

    return parent::getEndPoint();
  }

}
