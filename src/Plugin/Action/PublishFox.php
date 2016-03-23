<?php

/**
 * @file
 * Contains \Drupal\fox\Plugin\Action\PublishFox.
 */

namespace Drupal\fox\Plugin\Action;

use Drupal\Core\Action\ActionBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Publishes a fox.
 *
 * @Action(
 *   id = "fox_publish_action",
 *   label = @Translation("Publish selected fox"),
 *   type = "fox"
 * )
 */
class PublishFox extends ActionBase {

  /**
   * {@inheritdoc}
   */
  public function execute($entity = NULL) {
    $entity->status = NODE_PUBLISHED;
    $entity->save();
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    /** @var \Drupal\fox\FoxInterface $object */
    $result = $object->access('update', $account, TRUE)
      ->andIf($object->status->access('edit', $account, TRUE));

    return $return_as_object ? $result : $result->isAllowed();
  }

}
