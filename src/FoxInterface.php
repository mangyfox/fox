<?php

/**
 * @file
 * Contains \Drupal\fox\FoxInterface.
 */

namespace Drupal\fox;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Fox entities.
 *
 * @ingroup fox
 */
interface FoxInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.
  /**
   * Gets the Fox type.
   *
   * @return string
   *   The Fox type.
   */
  public function getType();

  /**
   * Gets the Fox name.
   *
   * @return string
   *   Name of the Fox.
   */
  public function getName();

  /**
   * Sets the Fox name.
   *
   * @param string $name
   *   The Fox name.
   *
   * @return \Drupal\fox\FoxInterface
   *   The called Fox entity.
   */
  public function setName($name);

  /**
   * Gets the Fox creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Fox.
   */
  public function getCreatedTime();

  /**
   * Sets the Fox creation timestamp.
   *
   * @param int $timestamp
   *   The Fox creation timestamp.
   *
   * @return \Drupal\fox\FoxInterface
   *   The called Fox entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Fox published status indicator.
   *
   * Unpublished Fox are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Fox is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Fox.
   *
   * @param bool $published
   *   TRUE to set this Fox to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\fox\FoxInterface
   *   The called Fox entity.
   */
  public function setPublished($published);

}
