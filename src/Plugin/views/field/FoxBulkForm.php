<?php

/**
 * @file
 * Contains \Drupal\fox\Plugin\views\field\FoxBulkForm.
 */

namespace Drupal\fox\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\system\Plugin\views\field\BulkForm;
use Drupal\fox\FoxInterface;

/**
 * Defines a fox operations bulk form element.
 *
 * @ViewsField("fox_bulk_form")
 */
class FoxBulkForm extends BulkForm {

  /**
   * {@inheritdoc}
   */
  protected function emptySelectedMessage() {
    return $this->t('No foxes selected.');
  }

}
