<?php

/**
 * @file
 * Contains \Drupal\fox\FoxAccessControlHandler.
 */

namespace Drupal\fox;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Fox entity.
 *
 * @see \Drupal\fox\Entity\Fox.
 */
class FoxAccessControlHandler extends EntityAccessControlHandler {
  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\fox\FoxInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished fox entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published fox entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit fox entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete fox entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add fox entities');
  }

}
