<?php

namespace Drupal\amp;

use Drupal\amp\AmpMetadataInfo;
use Drupal\amp\AmpMetadataInterface;
use Drupal\amp\EntityTypeInfo;
use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\file\FileInterface;
use Drupal\image\Entity\ImageStyle;
use Drupal\image\ImageStyleInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a listing of AMP Metadata entities.
 */
class AmpMetadataListBuilder extends ConfigEntityListBuilder {

  /**
   * The AMP metadata info service.
   *
   * @var \Drupal\amp\AmpMetadataInfo
   */
  protected $ampMetadataInfo;

  /**
   * Information about AMP-enabled node types.
   *
   * @var \Drupal\amp\EntityTypeInfo
   */
  protected $entityTypeInfo;

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity.manager')->getStorage($entity_type->id()),
      $container->get('amp.metadata'),
      $container->get('amp.entity_type'),
      $container->get('module_handler')
    );
  }

  /**
   * Constructs a new EntityListBuilder object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition.
   * @param \Drupal\Core\Entity\EntityStorageInterface $storage
   *   The entity storage class.
   * @param \Drupal\amp\AmpMetadataInfo $amp_metadata_info
   *   The AMP metadata info service.
   * @param \Drupal\amp\EntityTypeInfo $entity_type_info
   *   Information about AMP-enabled node types.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, AmpMetadataInfo $amp_metadata_info, EntityTypeInfo $entity_type_info, ModuleHandlerInterface $module_handler) {
    parent::__construct($entity_type, $storage);
    $this->ampMetadataInfo = $amp_metadata_info;
    $this->entityTypeInfo = $entity_type_info;
    $this->moduleHandler = $module_handler;
  }

  /**
   * {@inheritdoc}
   */
  public function load() {
    $entities = parent::load();
    // Move the Global defaults to the top if they exist.
    if (isset($entities['global'])) {
      return array('global' => $entities['global']) + $entities;
    }
    else {
      return $entities;
    }

  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('AMP Metadata type');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $this->getLabelAndConfig($entity);

    return $row + parent::buildRow($entity);
  }

  /**
   * Renders the metada settings label plus its configuration.
   *
   * @param AmpMetadataInterface $entity
   *   The metadata settings entity.
   *
   * @return
   *   Render array for a table cell.
   */
  public function getLabelAndConfig(EntityInterface $entity) {
    $prefix = '';
    $label = '';
    $has_settings = FALSE;

    if ($entity->id() != 'global') {
      $prefix = '<div class="indentation"></div>';
      $label = $this->t('Settings for @type @overrides', ['@type' => $entity->label(), '@overrides' => $this->ampMetadataInfo->ampMetadataHasGlobal() ? 'overrides' : '']);
    }
    else {
      $label = $this->t('Settings for global metadata');
    }

    $details= [
      '#type' => 'table',
      '#title' => $label,
      '#header' => ['Setting', 'Data']
    ];

    if (!empty($org_name = $entity->getOrganizationName())) {
      $details['organization_name']['label'] = [
        '#markup' => '<strong>Organization name</strong>:'
      ];
      $details['organization_name']['value'] = [
        '#markup' => $org_name
      ];
      $has_settings = TRUE;
    }

    /** @var FileInterface $org_logo_file */
    if (!empty($org_logo_fid = $entity->getOrganizationLogoFid()) && !empty($org_logo_file = File::load($org_logo_fid))) {
      $details['organization_logo']['label'] = [
        '#markup' => '<strong>Organization logo file</strong>:'
      ];
      $details['organization_logo']['value'] = [
        '#markup' => $org_logo_file->getFilename()
      ];
      $has_settings = TRUE;
    }

    /** @var ImageStyleInterface $org_logo_image_style */
    if (!empty($org_logo_image_style_id = $entity->getOrganizationLogoImageStyleId()) && !empty($org_logo_image_style = ImageStyle::load($org_logo_image_style_id))) {
      $details['organization_logo_image_style']['label'] = [
        '#markup' => '<strong>Organization logo image style</strong>:'
      ];
      $details['organization_logo_image_style']['value'] = [
        '#markup' => $org_logo_image_style->getName()
      ];
      $has_settings = TRUE;
    }

    if (!empty($content_headline = $entity->getContentHeadlineToken())) {
      $details['content_headline']['label'] = [
        '#markup' => '<strong>Content headline token</strong>:'
      ];
      $details['content_headline']['value'] = [
        '#markup' => $content_headline
      ];
      $has_settings = TRUE;
    }

    if (!empty($content_schema = $entity->getContentSchemaType())) {
      $details['content_schema_type']['label'] = [
        '#markup' => '<strong>Content schema type</strong>:'
      ];
      $details['content_schema_type']['value'] = [
        '#markup' => $content_schema
      ];
      $has_settings = TRUE;
    }

    if (!empty($content_author = $entity->getContentAuthorToken())) {
      $details['content_author']['label'] = [
        '#markup' => '<strong>Content author token</strong>:'
      ];
      $details['content_author']['value'] = [
        '#markup' => $content_author
      ];
      $has_settings = TRUE;
    }

    if (!empty($content_description = $entity->getContentDescriptionToken())) {
      $details['content_description']['label'] = [
        '#markup' => '<strong>Content author token</strong>:'
      ];
      $details['content_description']['value'] = [
        '#markup' => $content_description
      ];
      $has_settings = TRUE;
    }

    if (!empty($content_image = $entity->getContentImageToken())) {
      $details['content_image']['label'] = [
        '#markup' => '<strong>Content image token</strong>:'
      ];
      $details['content_image']['value'] = [
        '#markup' => $content_image
      ];
      $has_settings = TRUE;
    }

    /** @var ImageStyleInterface $content_image_style */
    if (!empty($content_image_style_id = $entity->getContentImageStyleId()) && !empty($content_image_style = ImageStyle::load($content_image_style_id))) {
      $details['content_image_style']['label'] = [
        '#markup' => '<strong>Content image style</strong>:'
      ];
      $details['content_image_style']['value'] = [
        '#markup' => $content_image_style->getName()
      ];
      $has_settings = TRUE;
    }

    if (!$has_settings) {
      $details = [
        '#markup' => $this->t('No settings for this metadata type.')
      ];
    }

    return [
      'data' => [
        '#type' => 'details',
        '#prefix' => $prefix,
        '#title' => $entity->label(),
        'details' =>  $details,
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    $view = '';
    $add = '';
    $has_global = $this->ampMetadataInfo->ampMetadataHasGlobal();
    $has_types_without_settings = !empty($this->ampMetadataInfo->getAmpNodeTypesWithoutMetadataSettings());
    $has_no_types_with_settings = FALSE;
    if (count($this->entityTypeInfo->getAmpEnabledTypes()) === count($this->ampMetadataInfo->getAmpNodeTypesWithoutMetadataSettings())) {
      $has_no_types_with_settings = TRUE;
    }
    $has_no_amp_types = empty($this->entityTypeInfo->getAmpEnabledTypes());

    // Add appropriate lanague about viewing settings.
    if ($has_no_amp_types) {
      $view = $this->t('Learn how to enable AMP for content types on the <a href=":settings">AMP settings page</a>.', [':settings' => Url::fromRoute('amp.settings')->toString()]);
    }
    else {
      if (!$has_global && $has_no_types_with_settings) {
        $view = $this->t('No metadata settings created yet.');
      }
      elseif ($has_global && $has_no_types_with_settings) {
        $view = $this->t('To view a summary of the global metadata settings, click on the title.');
      }
      elseif (!$has_types_without_settings || ($has_types_without_settings && !$has_no_types_with_settings)) {
        if ($has_global) {
          $view = $this->t('To view a summary of the settings for global metadata or for a content type override, click on the title of the settings you would like to view.');
        }
        else {
          $view = $this->t('To view a summary of the settings for a content type, click on the title of the settings you would like to view.');
        }
      }

      // Add appropriate lanague about adding settings.
      if ($has_global) {
        if ($has_no_types_with_settings) {
          $add = $this->t('You can also add settings overrides for AMP-enabled content types.');
        }
        elseif ($has_types_without_settings) {
          $add = $this->t('You can also add settings for AMP-enabled content types that do not yet have overrides.');
        }
      }
      else {
        if ($has_no_types_with_settings) {
          $add = $this->t('The first metadata setttings you create will apply globally.');
        }
        elseif ($has_types_without_settings) {
          $add = $this->t('You can also add global AMP metadata settings or settings for AMP-enabled content types that do not yet have settings.');
        }
        else {
          $add = $this->t('You can also add global AMP metadata settings.');
        }
      }
    }

    $build['header'] = array(
      '#cache' => [
        'max-age' => 0,
        'tags' => [
          'amp_types',
          'amp_available_metadata'
        ]
      ]
    );
    if (!empty($view) && !empty($add)) {
      $build['header']['#markup'] = '<p>' . $view . '</p><p>' . $add . '</p>';
    }
    elseif (empty($view) && !empty($add)) {
      $build['header']['#markup'] = '<p>' . $add . '</p>';
    }
    elseif (!empty($view) && empty($add)) {
      $build['header']['#markup'] = '<p>' . $view . '</p>';
    }
    else {
      $build['header']['#markup'] = '<p>There should be a message here, but apparently something unforseen has happened.</p>';
    }

    if (!$this->moduleHandler->moduleExists(('token'))) {
      // Provide message in case somebody has upgraded AMP module but has not
      // installed Token.
      drupal_set_message($this->t('The AMP module now requires the <a href="@module">Token</a> module as a dependency. Please download and install Token in order for AMP metadata to appear properly.', ['@module' => 'https://www.drupal.org/project/token']), 'warning');
    }

    return $build + parent::render();
  }
}
