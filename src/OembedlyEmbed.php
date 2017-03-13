<?php

namespace Drupal\oembedly;

use Embed\Embed;
use Embed\Http\Url;
use Embed\Adapters\Webpage;
use Embed\Adapters\File;
use Embed\Http\DispatcherInterface;
use Embed\Http\CurlDispatcher;
use Embed\Exceptions\InvalidUrlException;

/**
 * {@inheritdoc}
 */
class OembedlyEmbed extends Embed {

  /**
   * Gets the info from an url.
   */
  public static function create($url, array $config = [], DispatcherInterface $dispatcher = NULL) {
    if (!($url instanceof Url)) {
      $url = Url::create($url);
    }
    if ($dispatcher === NULL) {
      $dispatcher = new CurlDispatcher();
    }
    $info = self::process($url, $config, $dispatcher);
    // Repeat the process if.
    // - The canonical url is different.
    // - No embed code has found.
    $from = preg_replace('|^(\w+://)|', '', rtrim((string) $info->getResponse()->getUrl(), '/'));
    $to = preg_replace('|^(\w+://)|', '', rtrim($info->url, '/'));
    if ($from !== $to && empty($info->code)) {
      return self::process(Url::create($info->url), $config, $dispatcher);
    }
    return $info;
  }

  /**
   * Process the url.
   */
  private static function process(Url $url, array $config, DispatcherInterface $dispatcher) {
    $response = $dispatcher->dispatch($url);
    // If is a file use File Adapter.
    if (File::check($response)) {
      return new File($response, $config, $dispatcher);
    }
    // Search the adapter using the domain.
    $adapter = 'Drupal\\oembedly\\Embed\\Adapters\\Embedly';
    if (class_exists($adapter) && $adapter::check($response)) {
      return new $adapter($response, $config, $dispatcher);
    }
    // Use the default webpage adapter.
    if (Webpage::check($response)) {
      return new Webpage($response, $config, $dispatcher);
    }
    $exception = new InvalidUrlException(sprintf("Invalid url '%s' (Status code %s)", (string) $url, $response->getStatusCode()));
    $exception->setResponse($response);
    throw $exception;
  }

}
