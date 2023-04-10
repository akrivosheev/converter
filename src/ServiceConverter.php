<?php

namespace Drupal\converter;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\taxonomy\Entity\Term;

class ServiceConverter {

  /**
   * The config name.
   *
   * @var string
   */
  protected $configName = 'converter.settings';

  /**
   * The config factory object.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a ServiceExample object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   A configuration factory instance.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * @param $number
   * @param $from
   * @param $to
   *
   * @return float|int
   */
  public function convert($number, $from, $to) {

    $from_tid = converter_getTermIdByCurrencyCode($from);
    $to_tid = converter_getTermIdByCurrencyCode($to);

    $query = \Drupal::entityQuery('node')
      ->condition('status', 1)
      ->condition('field_base', $from_tid);
    $nids = $query->execute();

    if (!empty($nids)) {
      $nid = current($nids);
      $node = Node::load($nid);
      $currency_rates = $node->get('field_currency_rates')->getValue();
    }

    $paragraph_ids = [];
    foreach ($currency_rates as $currency_rate) {
      $paragraph_ids[] = $currency_rate['target_id'];
    }

    $paragraphs = Paragraph::loadMultiple($paragraph_ids);

    foreach ($paragraphs as $paragraph) {

      $field_currency = $paragraph->get('field_currency')->getValue();
      $term_id = current($field_currency)['target_id'];

      if ($to_tid == $term_id) {
        $field_rate = $paragraph->get('field_rate')->getValue();
        $rate = current($field_rate)['value'];
        break;
      }
      else {
        continue;
      }
    }

    $res = $number * $rate;
    return $res;
  }
}
