<?php

/**
 * @file
 * Contains \Drupal\fox\FoxViewsData.
 */

namespace Drupal\fox;

use Drupal\views\EntityViewsData;

/**
 * Provides the views data for the fox entity type.
 */
class FoxViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['fox']['fox_bulk_form'] = array(
      'title' => t('Bulk update'),
      'help' => t('Add a form element that lets you run operations on multiple foxes.'),
      'field' => array(
        'id' => 'fox_bulk_form',
      ),
    );

    return $data;
  }

}
