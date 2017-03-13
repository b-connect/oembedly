<?php

namespace Drupal\oembedly\Embed\Adapters;

use Drupal\oembedly\Embed\Providers\Embedly as EmbedlyProvider;
use Embed\Adapters\Adapter;
use Embed\Providers\TwitterCards;

/**
 * Adapter to provide all information from any webpage.
 */
class Embedly extends Adapter {

  /**
   * {@inheritdoc}
   */
  protected function init() {
    $this->providers = [
      'oembed' => new EmbedlyProvider($this),
    ];
  }

}
