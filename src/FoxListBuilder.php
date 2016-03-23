<?php

/**
 * @file
 * Contains \Drupal\fox\FoxListBuilder.
 */

namespace Drupal\fox;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Fox entities.
 *
 * @ingroup fox
 */
class FoxListBuilder extends EntityListBuilder {
  use LinkGeneratorTrait;
  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Fox ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\fox\Entity\Fox */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.fox.edit_form', array(
          'fox' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
