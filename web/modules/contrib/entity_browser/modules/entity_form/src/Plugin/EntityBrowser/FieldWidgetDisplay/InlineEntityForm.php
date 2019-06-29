<?php

namespace Drupal\entity_browser_entity_form\Plugin\EntityBrowser\FieldWidgetDisplay;

use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\entity_browser\FieldWidgetDisplayBase;

/**
 * Displays the entity in a inline entity form.
 *
 * @EntityBrowserFieldWidgetDisplay(
 *   id = "inline_entity_form",
 *   label = @Translation("Inline entity form"),
 *   description = @Translation("Displays in an inline entity form.")
 * )
 */
class InlineEntityForm extends FieldWidgetDisplayBase implements ContainerFactoryPluginInterface {

  /**
   * Entity manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Entity display repository.
   *
   * @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface
   */
  protected $entityDisplayRepository;

  /**
   * Current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $user;

  /**
   * Constructs widget plugin.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Entity type manager service.
   * @param \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entity_display_repository
   *   Entity display repository service.
   * @param \Drupal\Core\Session\AccountProxyInterface $user
   *   Current user.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, EntityDisplayRepositoryInterface $entity_display_repository, AccountProxyInterface $user) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->entityDisplayRepository = $entity_display_repository;
    $this->user = $user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('entity_display.repository'),
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function view(EntityInterface $entity) {
    if ($entity->access('update', $this->user)) {
      return [
        '#type' => 'inline_entity_form',
        '#entity_type' => $entity->getEntityTypeId(),
        '#bundle' => $entity->bundle(),
        '#default_value' => $entity,
        '#form_mode' => $this->configuration['form_mode'],
      ];
    }
    else {
      return $entity->label();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $options = parent::settingsForm($form, $form_state);

    foreach ($this->entityDisplayRepository->getFormModeOptions($this->configuration['entity_type']) as $id => $form_mode_label) {
      $options[$id] = $form_mode_label;
    }

    return [
      'form_mode' => [
        '#type' => 'select',
        '#title' => $this->t('Form mode'),
        '#description' => $this->t('Select form mode to be used when rendering entities.'),
        '#default_value' => $this->configuration['form_mode'],
        '#options' => $options,
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'form_mode' => 'default',
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function calculateDependencies() {
    $dependencies = parent::calculateDependencies();

    if ($form_mode = $this->entityTypeManager->getStorage('entity_form_mode')->load($this->configuration['entity_type'] . '.' . $this->configuration['form_mode'])) {
      $dependencies[$form_mode->getConfigDependencyKey()][] = $form_mode->getConfigDependencyName();
    }

    return $dependencies;
  }

}
