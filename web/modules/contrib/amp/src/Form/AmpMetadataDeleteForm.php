<?php

namespace Drupal\amp\Form;

use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Drupal\Core\Entity\EntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Builds the form to delete AMP Metadata entities.
 */
class AmpMetadataDeleteForm extends EntityConfirmFormBase implements ContainerAwareInterface {

  /**
   * The container.
   *
   * @var \Symfony\Component\DependencyInjection\ContainerInterface
   */
  protected $container;

  /**
   * The cache tags invalidator.
   *
   * @var \Drupal\Core\Cache\CacheTagsInvalidatorInterface
   */
  protected $tagInvalidate;

  /**
   * Constructs a AmpMetadataDeleteForm object.
   *
   * @param \Drupal\Core\Cache\CacheTagsInvalidatorInterface $tag_invalidate
   *   The cache tags invalidator.
   */
  public function __construct(CacheTagsInvalidatorInterface $tag_invalidate) {
    $this->tagInvalidate = $tag_invalidate;
  }

  /**
   * Sets the Container.
   *
   * @param ContainerInterface|null $container A ContainerInterface instance or null
   */
  public function setContainer(ContainerInterface $container = null) {
    $this->container = $container;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('cache_tags.invalidator')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete %name?', array('%name' => $this->entity->label()));
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.amp_metadata.collection');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->entity->delete();
    $this->tagInvalidate->invalidateTags(['amp_available_metadata']);
    $this->tagInvalidate->invalidateTags(['amp_metadata']);

    drupal_set_message(
      $this->t('content @type: deleted @label.',
        [
          '@type' => $this->entity->bundle(),
          '@label' => $this->entity->label(),
        ]
        )
    );

    $form_state->setRedirectUrl($this->getCancelUrl());
  }

}
