<?php

/**
 * @file
 * Contains \Drupal\fox\Form\FoxDeleteMultipleForm.
 */

namespace Drupal\fox\Form;

use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\user\PrivateTempStoreFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Provides a fox deletion confirmation form.
 */
class FoxDeleteMultipleForm extends ConfirmFormBase {

  /**
   * The array of foxes to delete.
   *
   * @var string[][]
   */
  protected $foxInfo = array();

  /**
   * The tempstore factory.
   *
   * @var \Drupal\user\PrivateTempStoreFactory
   */
  protected $tempStoreFactory;

  /**
   * The fox storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $manager;

  /**
   * Constructs a FoxDeleteMultipleForm form object.
   *
   * @param \Drupal\user\PrivateTempStoreFactory $temp_store_factory
   *   The tempstore factory.
   * @param \Drupal\Core\Entity\EntityManagerInterface $manager
   *   The entity manager.
   */
  public function __construct(PrivateTempStoreFactory $temp_store_factory, EntityManagerInterface $manager) {
    $this->tempStoreFactory = $temp_store_factory;
    $this->storage = $manager->getStorage('fox');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('user.private_tempstore'),
      $container->get('entity.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'fox_multiple_delete_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->formatPlural(count($this->foxInfo), 'Are you sure you want to delete this fox?', 'Are you sure you want to delete these foxes?');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('system.admin_content');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $this->foxInfo = $this->tempStoreFactory->get('fox_multiple_delete_confirm')->get(\Drupal::currentUser()->id());
    if (empty($this->foxInfo)) {
      return new RedirectResponse($this->getCancelUrl()->setAbsolute()->toString());
    }
    /** @var \Drupal\fox\FoxInterface[] $foxes */
    $foxes = $this->storage->loadMultiple(array_keys($this->foxInfo));

    $items = [];
    foreach ($this->foxInfo as $id => $langcodes) {
      foreach ($langcodes as $langcode) {
        $fox = $foxes[$id]->getTranslation($langcode);
        $key = $id . ':' . $langcode;
        $default_key = $id . ':' . $fox->getUntranslated()->language()->getId();

        // If we have a translated entity we build a nested list of translations
        // that will be deleted.
        $languages = $fox->getTranslationLanguages();
        if (count($languages) > 1 && $fox->isDefaultTranslation()) {
          $names = [];
          foreach ($languages as $translation_langcode => $language) {
            $names[] = $language->getName();
            unset($items[$id . ':' . $translation_langcode]);
          }
          $items[$default_key] = [
            'label' => [
              '#markup' => $this->t('@label (Original translation) - <em>The following content translations will be deleted:</em>', ['@label' => $fox->label()]),
            ],
            'deleted_translations' => [
              '#theme' => 'item_list',
              '#items' => $names,
            ],
          ];
        }
        elseif (!isset($items[$default_key])) {
          $items[$key] = $fox->label();
        }
      }
    }

    $form['foxes'] = array(
      '#theme' => 'item_list',
      '#items' => $items,
    );
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    if ($form_state->getValue('confirm') && !empty($this->foxInfo)) {
      $total_count = 0;
      $delete_foxes = [];
      /** @var \Drupal\Core\Entity\ContentEntityInterface[][] $delete_translations */
      $delete_translations = [];
      /** @var \Drupal\fox\FoxInterface[] $foxes */
      $foxes = $this->storage->loadMultiple(array_keys($this->foxInfo));

      foreach ($this->foxInfo as $id => $langcodes) {
        foreach ($langcodes as $langcode) {
          $fox = $foxes[$id]->getTranslation($langcode);
          if ($fox->isDefaultTranslation()) {
            $delete_foxes[$id] = $fox;
            unset($delete_translations[$id]);
            $total_count += count($fox->getTranslationLanguages());
          }
          elseif (!isset($delete_foxes[$id])) {
            $delete_translations[$id][] = $fox;
          }
        }
      }

      if ($delete_foxes) {
        $this->storage->delete($delete_foxes);
        $this->logger('content')->notice('Deleted @count foxes.', array('@count' => count($delete_foxes)));
      }

      if ($delete_translations) {
        $count = 0;
        foreach ($delete_translations as $id => $translations) {
          $fox = $foxes[$id]->getUntranslated();
          foreach ($translations as $translation) {
            $fox->removeTranslation($translation->language()->getId());
          }
          $fox->save();
          $count += count($translations);
        }
        if ($count) {
          $total_count += $count;
          $this->logger('content')->notice('Deleted @count content translations.', array('@count' => $count));
        }
      }

      if ($total_count) {
        drupal_set_message($this->formatPlural($total_count, 'Deleted 1 fox.', 'Deleted @count foxes.'));
      }

      $this->tempStoreFactory->get('fox_multiple_delete_confirm')->delete(\Drupal::currentUser()->id());
    }

    $form_state->setRedirect('system.admin_content');
  }

}
