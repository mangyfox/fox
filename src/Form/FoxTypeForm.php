<?php

/**
 * @file
 * Contains \Drupal\fox\Form\FoxTypeForm.
 */

namespace Drupal\fox\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class FoxTypeForm.
 *
 * @package Drupal\fox\Form
 */
class FoxTypeForm extends EntityForm {
  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $fox_type = $this->entity;
    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $fox_type->label(),
      '#description' => $this->t("Label for the Fox type."),
      '#required' => TRUE,
    );

    $form['id'] = array(
      '#type' => 'machine_name',
      '#default_value' => $fox_type->id(),
      '#machine_name' => array(
        'exists' => '\Drupal\fox\Entity\FoxType::load',
      ),
      '#disabled' => !$fox_type->isNew(),
    );

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $fox_type = $this->entity;
    $status = $fox_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Fox type.', [
          '%label' => $fox_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Fox type.', [
          '%label' => $fox_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($fox_type->urlInfo('collection'));
  }

}
