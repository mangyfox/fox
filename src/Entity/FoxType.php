<?php

/**
 * @file
 * Contains \Drupal\fox\Entity\FoxType.
 */

namespace Drupal\fox\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\fox\FoxTypeInterface;

/**
 * Defines the Fox type entity.
 *
 * @ConfigEntityType(
 *   id = "fox_type",
 *   label = @Translation("Fox type"),
 *   handlers = {
 *     "list_builder" = "Drupal\fox\FoxTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\fox\Form\FoxTypeForm",
 *       "edit" = "Drupal\fox\Form\FoxTypeForm",
 *       "delete" = "Drupal\fox\Form\FoxTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\fox\FoxTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "fox_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "fox",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/fox_type/{fox_type}",
 *     "add-form" = "/admin/structure/fox_type/add",
 *     "edit-form" = "/admin/structure/fox_type/{fox_type}/edit",
 *     "delete-form" = "/admin/structure/fox_type/{fox_type}/delete",
 *     "collection" = "/admin/structure/fox_type"
 *   }
 * )
 */
class FoxType extends ConfigEntityBundleBase implements FoxTypeInterface {
  /**
   * The Fox type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Fox type label.
   *
   * @var string
   */
  protected $label;

}
