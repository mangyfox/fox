<?php

/**
 * @file
 * Contains \Drupal\fox\Form\FoxForm.
 */

namespace Drupal\fox\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Fox edit forms.
 *
 * @ingroup fox
 */
class FoxForm extends ContentEntityForm {
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\fox\Entity\Fox */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Fox.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Fox.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.fox.canonical', ['fox' => $entity->id()]);
  }

}
