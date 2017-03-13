<?php

namespace Drupal\oembedly;

/**
 * Interface OembedlyManagerServiceInterface.
 *
 * @package Drupal\oembedly
 */
interface OembedlyManagerServiceInterface {
  public function createEmbed($url, array $config = []);
  public function build($url);
}
