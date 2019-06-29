<?php

namespace Drupal\media_entity_pinterest\Plugin\MediaEntity\Type;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\media_entity\MediaInterface;
use Drupal\media_entity\MediaTypeBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides media type plugin for Pinterest.
 *
 * @MediaType(
 *   id = "pinterest",
 *   label = @Translation("Pinterest"),
 *   description = @Translation("Provides business logic and metadata for Pinterest.")
 * )
 */
class Pinterest extends MediaTypeBase {

  /**
   * Config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a new class instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Entity type manager service.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   *   Entity field manager service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, EntityFieldManagerInterface $entity_field_manager, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_type_manager, $entity_field_manager, $config_factory->get('media_entity.settings'));
    $this->configFactory = $config_factory;
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
      $container->get('entity_field.manager'),
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'use_pinterest_api' => FALSE,
    ];
  }

  /**
   * List of validation regular expressions.
   *
   * @var array
   *
   * possible hostnames:
   *   www.pinterest.com,
   *   pinterest.com,
   *   jp.pinterest.com,
   *   pinterest.jp,
   *   pinterest.co.uk
   */
  public static $validationRegexp = [
    // Match PIN_URL_RE.
    '@^\s*(https?://)?(\w+\.)?pinterest\.([a-zA-Z]+\.)?([a-zA-Z]+)/pin/(?P<id>\d+)/?\s*$$@i' => 'id',
    // Match BOARD_URL_RE.
    '@^\s*(https?://)?(\w+\.)?pinterest\.([a-zA-Z]+\.)?([a-zA-Z]+)/(?P<username>\w+)/(?P<slug>[\w\-_\~]+)/?\s*$@iu' => 'board',
    // Match BOARD_SECTION_URL_RE.
    '@^\s*(https?://)?(\w+\.)?pinterest\.([a-zA-Z]+\.)?([a-zA-Z]+)/(?P<username>\w+)/(?P<slug>[\w\-_\~]+)/(?P<section>[\w\-_\~%]+)/?\s*$@iu' => 'section',
    // Match USER_URL_RE.
    '@^\s*(https?://)?(\w+\.)?pinterest\.([a-zA-Z]+\.)?([a-zA-Z]+)/(?P<username>\w+)/?\s*$@iu' => 'user',
  ];

  /**
   * {@inheritdoc}
   */
  public function providedFields() {
    // TODO: Implement providedFields() method.
    $fields = [
      'id' => $this->t('Pin ID'),
      'board' => $this->t('Board name'),
      'section' => $this->t('Section name'),
      'user' => $this->t('Pinterest user'),
    ];

    // TODO: Implement providedFields() method when using the API.
    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getField(MediaInterface $media, $name) {
    $matches = $this->matchRegexp($media);

    if (empty($matches)) {
      return FALSE;
    }

    // First we return the fields that are available from regex.
    switch ($name) {
      case 'id':
        if (!empty($matches['id'])) {
          return $matches['id'];
        }
        return FALSE;

      case 'section':
        if (!empty($matches['section'])) {
          return $matches['section'];
        }
        return NULL;

      case 'board':
        if (!empty($matches['slug'])) {
          return $matches['slug'];
        }
        return FALSE;

      case 'user':
        if (!empty($matches['username'])) {
          return $matches['username'];
        }
        return FALSE;
    }

    // TODO: Implement getField() method for API fields.
    // If we have auth settings return the other fields.
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    // TODO: Implement buildConfigurationForm() method.
    /** @var \Drupal\media_entity\MediaBundleInterface $bundle */
    $bundle = $form_state->getFormObject()->getEntity();
    $options = [];
    $allowed_field_types = ['string', 'string_long', 'link'];
    foreach ($this->entityFieldManager->getFieldDefinitions('media', $bundle->id()) as $field_name => $field) {
      if (in_array($field->getType(), $allowed_field_types) && !$field->getFieldStorageDefinition()->isBaseField()) {
        $options[$field_name] = $field->getLabel();
      }
    }

    $form['source_field'] = [
      '#type' => 'select',
      '#title' => $this->t('Field with source information'),
      '#description' => $this->t('Field on media entity that stores Pinterest embed code or URL. You can create a bundle without selecting a value for this dropdown initially. This dropdown can be populated after adding fields to the bundle.'),
      '#default_value' => empty($this->configuration['source_field']) ? NULL : $this->configuration['source_field'],
      '#options' => $options,
    ];

    $form['use_pinterest_api'] = [
      '#type' => 'select',
      '#title' => $this->t('Whether to use Pinterest api to fetch pin or not.'),
      '#description' => $this->t("In order to use Pinterest's api you have to create a developer account and an application. For more information consult the readme file."),
      '#default_value' => empty($this->configuration['use_pinterest_api']) ? 0 : $this->configuration['use_pinterest_api'],
      '#options' => [
        0 => $this->t('No'),
      ],
    ];

    // TODO: Implement buildConfigurationForm() method for API fields.
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function attachConstraints(MediaInterface $media) {
    parent::attachConstraints($media);

    if (isset($this->configuration['source_field'])) {
      $source_field_name = $this->configuration['source_field'];
      if ($media->hasField($source_field_name)) {
        foreach ($media->get($source_field_name) as &$embed_code) {
          /** @var \Drupal\Core\TypedData\DataDefinitionInterface $typed_data */
          $typed_data = $embed_code->getDataDefinition();
          $typed_data->addConstraint('PinEmbedCode');
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultThumbnail() {
    return $this->config->get('icon_base') . '/pinterest.png';
  }

  /**
   * {@inheritdoc}
   */
  public function thumbnail(MediaInterface $media) {
    if ($local_image = $this->getField($media, 'thumbnail_local')) {
      return $local_image;
    }

    return $this->getDefaultThumbnail();
  }

  /**
   * Runs preg_match on embed code/URL.
   *
   * @param \Drupal\media_entity\MediaInterface $media
   *   Media object.
   *
   * @return array|bool
   *   Array of preg matches or FALSE if no match.
   *
   * @see preg_match()
   */
  protected function matchRegexp(MediaInterface $media) {
    $matches = [];

    if (isset($this->configuration['source_field'])) {
      $source_field = $this->configuration['source_field'];
      if ($media->hasField($source_field)) {
        $property_name = $media->{$source_field}->first()->mainPropertyName();
        foreach (static::$validationRegexp as $pattern => $key) {
          // URLs will sometimes have urlencoding, so we decode for matching.
          if (preg_match($pattern, urldecode($media->{$source_field}->{$property_name}), $matches)) {
            return $matches;
          }
        }
      }
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultName(MediaInterface $media) {
    $id = $this->getField($media, 'id');
    $board = $this->getField($media, 'board');
    $user = $this->getField($media, 'user');
    // The default name will be the Pin ID for Pins.
    if (!empty($id)) {
      return $id;
    }
    // The default name will be the username and board slug for Boards.
    if (!empty($user) && !empty($board)) {
      return $user . ' - ' . $board;
    }
    // The default name will be the username for Profiles.
    if (!empty($user) && empty($board)) {
      return $user;
    }
    return parent::getDefaultName($media);
  }

}
