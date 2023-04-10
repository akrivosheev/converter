<?php

namespace Drupal\converter\Controller;

class CheckController {

  public function showContent() {

    $res = \Drupal::service('converter.service_converter')->convert(2000, 'USD', 'EUR');

    return [
      '#type' => 'markup',
      '#markup' => '<h2>CHECK: ' . $res  . ' </h2>',
    ];
  }
}
