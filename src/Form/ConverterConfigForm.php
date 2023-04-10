<?php

namespace Drupal\converter\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\taxonomy\Entity\Term;

class ConverterConfigForm extends ConfigFormBase {

  public function getFormId()
  {
    return 'converter_config_form';
  }

  protected function getEditableConfigNames()
  {
    return [
      'converter.settings'
    ];
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $config = $this->config('converter.settings');

    $vocabulary_name = 'currency';
    $query = \Drupal::entityQuery('taxonomy_term');
    $query->condition('vid', $vocabulary_name);
    $tids = $query->execute();
    $terms = Term::loadMultiple($tids);

    $terms_data = [];
    foreach($terms as $term) {
      $terms_data[$term->id()] = $term->getName();
    }

    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => 'API Key',
      '#default_value' => $config->get('api_key'),
    ];

    $form['currency_list'] = [
      '#type' => 'checkboxes',
      '#options' => $terms_data,
      '#title' => $this->t('Currency for import'),
      '#default_value' => $config->get('currency_list'),
    ];

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $config = $this->config('converter.settings');
    $config->set('currency_list', $form_state->getValue('currency_list'));
    $config->set('api_key', $form_state->getValue('api_key'));
    $config->save();

    parent::submitForm($form, $form_state);
  }

}
