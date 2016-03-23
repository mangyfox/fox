<?php

/**
 * @file
 * Contains \Drupal\fox\Plugin\Action\UnpublishFox.
 */

namespace Drupal\fox\Plugin\Action;

use Drupal\Core\Action\ActionBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Unpublishes a fox.
 *
 * @Action(
 *   id = "fox_unpublish_action",
 *   label = @Translation("Unpublish selected fox"),
 *   type = "fox"
 * )
 */
class UnpublishFox extends ActionBase {

  /**
   * {@inheritdoc}
   */
  public function execute($entity = NULL) {
    $entity->status = NODE_NOT_PUBLISHED;
    $entity->save();
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    /** @var \Drupal\fox\FoxInterface $object */
    $access = $object->access('update', $account, TRUE)
      ->andIf($object->status->access('edit', $account, TRUE));

    return $return_as_object ? $access : $access->isAllowed();
  }

}
