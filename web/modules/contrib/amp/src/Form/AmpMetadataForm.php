<?php

namespace Drupal\amp\Form;

use Drupal\amp\AmpMetadataInfo;
use Drupal\amp\Entity\AmpMetadata;
use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\file\FileInterface;
use Drupal\file\FileUsage\DatabaseFileUsageBackend;
use Drupal\image\ImageStyleInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AmpMetadataForm.
 *
 * @package Drupal\amp\Form
 */
class AmpMetadataForm extends EntityForm implements ContainerAwareInterface {

  /**
   * The container.
   *
   * @var \Symfony\Component\DependencyInjection\ContainerInterface
   */
  protected $container;

  /**
   * The AMP metadata info service.
   *
   * @var \Drupal\amp\AmpMetadataInfo
   */
  protected $ampMetadataInfo;

  /**
   * The file usage service.
   *
   * @var \Drupal\file\FileUsage\DatabaseFileUsageBackend
   */
  protected $fileUsage;

  /**
   * The cache tags invalidator.
   *
   * @var \Drupal\Core\Cache\CacheTagsInvalidatorInterface
   */
  protected $tagInvalidate;

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Constructs a AmpMetadataForm object.
   *
   * @param \Drupal\amp\AmpMetadataInfo $amp_metadata_info
   *   The AMP metadata info service.
   * @param \Drupal\file\FileUsage\DatabaseFileUsageBackend
   *   The file usage service.
   * @param \Drupal\Core\Cache\CacheTagsInvalidatorInterface $tag_invalidate
   *   The cache tags invalidator.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct(AmpMetadataInfo $amp_metadata_info, DatabaseFileUsageBackend $file_usage, CacheTagsInvalidatorInterface $tag_invalidate, ModuleHandlerInterface $module_handler) {
    $this->ampMetadataInfo = $amp_metadata_info;
    $this->fileUsage = $file_usage;
    $this->tagInvalidate = $tag_invalidate;
    $this->moduleHandler = $module_handler;
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
      $container->get('amp.metadata'),
      $container->get('file.usage'),
      $container->get('cache_tags.invalidator'),
      $container->get('module_handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    /** @var AmpMetadata $amp_metadata */
    $amp_metadata = $this->entity;

    // Check if global metadata settings already exist.
    $amp_metadata_global_exists = $this->ampMetadataInfo->ampMetadataHasGlobal();

    // Get AMP-enabled node types without existing settings.
    $node_type_options = $this->ampMetadataInfo->getAmpNodeTypesWithoutMetadataSettings();

    if (!$this->moduleHandler->moduleExists(('token'))) {
      // Provide message in case somebody has upgraded AMP module but has not
      // installed Token.
      drupal_set_message($this->t('The AMP module now requires the <a href="@module">Token</a> module as a dependency. Please download and install Token in order for AMP metadata to appear properly.', ['@module' => 'https://www.drupal.org/project/token']), 'warning');
    }

    $introduction_title = $amp_metadata_global_exists && !$amp_metadata->isGlobal() ? $this->t('Content type override for metadata settings') : $this->t('Global metadata settings');
    $top_stories_guidelines_url = Url::fromUri('https://developers.google.com/search/docs/data-types/articles#article_types');
    $form['introduction'] = [
      '#type' => 'item',
      '#title' => $introduction_title,
      '#description' => t('These metadata settings provide information used in the Top Stories carousel in Google Search. Find complete details in the <a href=":top_stories_guidelines">Top Stories guidelines</a>.', [':top_stories_guidelines' => $top_stories_guidelines_url->toString()]),
    ];

    // Show a node type selector if this is new metadata, and global metadata
    // settings already exist.
    if ($amp_metadata->isNew() && $amp_metadata_global_exists) {
      $form['node_type'] = array(
        '#type' => 'select',
        '#title' => t('Content type'),
        '#description' => t('Select a content type for which you would like to override the global AMP metadata setting. Settings are overridden on a field by field basis.'),
        '#options' => $node_type_options,
        '#required' => TRUE,
        '#default_value' => NULL,
      );
    }

    // Add a collapsible section for organization information. Open by default
    // for global settings.
    $show_organization_fields = $amp_metadata_global_exists && !$amp_metadata->isGlobal() ? FALSE : TRUE;
    $form['organization_group'] = [
      '#type' => 'details',
      '#title' => 'Organization information',
      '#open' => $show_organization_fields
    ];

    $form['organization_group']['description'] = [
      '#type' => 'item',
      '#description' => t('Provide information about your organization for use in search metadata.'),
    ];

    // Add the token browser for ease in selecting token values.
    $form['organization_group']['token_link'] = [
      '#theme' => 'token_tree_link',
      '#token_types' => 'all',
      '#global_types' => TRUE,
      '#click_insert' => TRUE,
      '#show_restricted' => FALSE,
      '#recursion_limit' => 3,
      '#text' => t('Browse available tokens'),
    ];

    // Add an option to set the organization name that appears in the carousel.
    $org_name = $amp_metadata->getOrganizationName();
    $form['organization_group']['amp_organization_name'] = [
      '#type' => 'textfield',
      '#title' => t('Organization name'),
      '#description' => t('The name of the organization that will appear in the Top Stories carousel. Tokens are allowed.'),
      '#default_value' => $org_name? $org_name : '',
      '#attributes' => ['placeholder' => '[site:name]']
    ];

    // Add an option to upload an organizational logo for the carousel.
    $top_stories_logo_guidelines_url = Url::fromUri('https://developers.google.com/search/docs/data-types/articles#amp-logo-guidelines');
    $logo_config_fid = $amp_metadata->getOrganizationLogoFid();
    $default_logo_fid = $logo_config_fid ? [$logo_config_fid] : NULL;
    $form['organization_group']['amp_organization_logo_fid_new'] = [
      '#type' => 'managed_file',
      '#title' => t('Organization logo'),
      '#description' => t('Upload a logo for your organization that will appear in the Top Stories carousel. <span class="warning">This logo must have a height of 60px and a width less than 600px. SVG logos are not allowed: please provide a JPG, JPEG, GIF or PNG. See the AMP <a href=":logo_guidelines">logo guidelines</a>.</span>', [':logo_guidelines' => $top_stories_logo_guidelines_url->toString()]),
      '#default_value' => $default_logo_fid,
      '#upload_location' => 'public://amp',
      '#upload_validators' => [
        'file_validate_is_image' => [],
        'file_validate_extensions' => ['png gif jpg jpeg'],
        'file_validate_image_resolution' => ['600x60']
      ]
    ];

    // Store the initial logo file ID. This will help us determine if the logo
    // file is removed, in which case we should delete the file.
    $form['organization_group']['amp_organization_logo_fid_previous'] = [
      '#type' => 'hidden',
      '#value' => $default_logo_fid
    ];

    // Add an option to select an image style for the organization logo.
    $org_logo_style = $amp_metadata->getOrganizationLogoImageStyleId();
    $form['organization_group']['amp_organization_logo_image_style_id'] = [
      '#type' => 'select',
      '#title' => t('Organization logo image style'),
      '#options' => image_style_options(TRUE),
      '#description' => t('The image style to use for the organization logo.'),
      '#default_value' => $org_logo_style? $org_logo_style : '',
    ];

    // Add a section for content information.
    $form['content_group'] = [
      '#type' => 'fieldset',
      '#title' => 'Content information',
    ];

    $form['content_group']['description'] = [
      '#type' => 'item',
      '#description' => t('Provide information about your content for use in the Top Stories carousel in Google Search.'),
    ];

    // Add an option to select the schema type for AMP pages.
    $schema_type_options = [
      'Article' => 'Article',
      'NewsArticle' => 'NewsArticle',
      'BlogPosting' => 'BlogPosting',
    ];
    $schema_type = $amp_metadata->getContentSchemaType();
    $form['content_group']['amp_content_schema_type'] = [
      '#type' => 'select',
      '#title' => t('AMP schema type'),
      '#options' => $schema_type_options,
      '#description' => t('The type of schema to use on AMP pages'),
      '#default_value' => $schema_type? $schema_type : 'NewsArticle',
    ];

    // Add an option to set the headline on AMP pages.
    $content_headline = $amp_metadata->getContentHeadlineToken();
    $form['content_group']['amp_content_headline'] = [
      '#type' => 'textfield',
      '#title' => t('Article headline'),
      '#description' => t('A short headline for an AMP article, using fewer than 110 characters and no HTML markup. Use tokens to provide the correct headline for each article page.'),
      '#default_value' => $content_headline? $content_headline : '',
      '#attributes' => ['placeholder' => '[node:title]']
    ];

    // Add an option to set the author on AMP pages.
    $content_author = $amp_metadata->getContentAuthorToken();
    $form['content_group']['amp_content_author'] = [
      '#type' => 'textfield',
      '#title' => t('Author name'),
      '#description' => t('The name of the author to use on AMP pages. Use tokens to provide the correct author for each article page. Token output should be text only, no HTML markup.'),
      '#default_value' => $content_author? $content_author : '',
      '#attributes' => ['placeholder' => '[node:author:display-name]']
    ];

    // Add an option to set the description on AMP pages.
    $content_description = $amp_metadata->getContentDescriptionToken();
    $form['content_group']['amp_content_description'] = [
      '#type' => 'textfield',
      '#title' => t('Article description'),
      '#description' => t('A short description of an AMP article, using fewer than 150 characters and no HTML markup. Use tokens to provide the correct description for each article page.'),
      '#default_value' => $content_description? $content_description : '',
      '#attributes' => ['placeholder' => '[node:summary]']
    ];

    // Add an option to set the image on AMP pages.
    $top_stories_image_guidelines_url = Url::fromUri('https://developers.google.com/search/docs/data-types/articles#article_types');
    $content_image = $amp_metadata->getContentImageToken();
    $form['content_group']['amp_content_image'] = [
      '#type' => 'textfield',
      '#title' => t('Article image for carousel'),
      '#description' => t('An article image to appear in the Top Stories carousel. Images must be at least 696px wide: refer to <a href=":image_guidelines">article image guidelines</a> for further details. Use tokens to provide the correct image for each article page.', [':image_guidelines' => $top_stories_image_guidelines_url->toString()]),
      '#default_value' => $content_image? $content_image : '',
      '#attributes' => ['placeholder' => '[node:field_image]']
    ];

    // Add an option to select an image style for the organization logo.
    $content_image_style = $amp_metadata->getContentImageStyleId();
    $form['content_group']['amp_content_image_style_id'] = [
      '#type' => 'select',
      '#title' => t('Article image style'),
      '#options' => image_style_options(TRUE),
      '#description' => t('The image style to use for the article image'),
      '#default_value' => $content_image_style? $content_image_style : '',
    ];

    // Add the token browser for ease in selecting token values.
    $form['content_group']['token_link'] = [
      '#theme' => 'token_tree_link',
      '#token_types' => 'all',
      '#global_types' => TRUE,
      '#click_insert' => TRUE,
      '#show_restricted' => FALSE,
      '#recursion_limit' => 3,
      '#text' => t('Browse available tokens'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    /** @var AmpMetadata $amp_metadata */
    $amp_metadata = $this->entity;
    $this->tagInvalidate->invalidateTags(['amp_metadata']);

    if ($amp_metadata->isNew()) {
      if (!$this->ampMetadataInfo->ampMetadataHasGlobal()) {
        $amp_metadata->setGlobal();
        $amp_metadata->set('id', 'global');
        $amp_metadata->set('label', $this->t('Global settings for AMP metadata'));
      }
      else {
        $node_type = $form_state->getValue('node_type');
        $amp_metadata->setNodeType($node_type);
        $amp_metadata->set('id', $node_type);
        $amp_metadata->set('label', $this->t('@type settings for AMP metadata', [
          '@type' => $this->entityTypeManager->getStorage('node_type')->load($node_type)->label()
        ]));
      }
      $this->tagInvalidate->invalidateTags(['amp_available_metadata']);
    }

    // Save organization name.
    $amp_metadata->setOrganizationName($form_state->getValue('amp_organization_name'));

    // Get file IDs of the currently uploaded logo as well as the previously
    // uploaded logo.
    $logo_fid_new = '';
    $logo_value_new = $form_state->getValue('amp_organization_logo_fid_new');
    if (!empty($logo_value_new) && isset($logo_value_new[0])) {
      $logo_fid_new = $logo_value_new[0];
    }
    $logo_fid_previous = '';
    $logo_value_previous = $form_state->getValue('amp_organization_logo_fid_previous');
    if (!empty($logo_value_previous) && isset($logo_value_previous[0])) {
      $logo_fid_previous = $logo_value_previous[0];
    }

    // Save new organization logo.
    /** @var \Drupal\file\FileInterface $logo_file_new */
    if (!empty($logo_fid_new) && !empty($logo_file_new = File::load($logo_fid_new))) {
      $logo_file_new->setPermanent();
      $logo_file_new->save();
      // File usage requires an entity type and entity ID. Those don't exist
      // for simple configuration: we use organization and the fid for those
      // items. This causes an error if you go to the file usage page, as the
      // organization entity type does not exist. Life is full of hard choices
      // like this. Alternative is using config entity, which seems unwieldy.
      $this->fileUsage->add($logo_file_new, 'amp', 'amp_metadata', $amp_metadata->get('id'));
      $amp_metadata->setOrganizationLogoFid($logo_fid_new);
    }

    // If no file ID is set for the logo but a value previously existed, or if
    // the new file ID does not match the previous file ID, then the previous
    // file needs to be removed. Delete the previous logo file.
    /** @var \Drupal\file\FileInterface $logo_file_previous */
    if (!empty($logo_fid_previous) && ($logo_fid_previous !== $logo_fid_new) && !empty($logo_file_previous= File::load($logo_fid_previous))) {
      $this->fileUsage->delete($logo_file_previous, 'amp', 'amp_metadata', $amp_metadata->get('id'));
      // Only delete the file if this is the only place it was in use.
      if (empty($this->fileUsage->listUsage($logo_file_previous))) {
        $logo_file_previous->setTemporary();
        $logo_file_previous->delete();
      }
      $amp_metadata->setOrganizationLogoFid(NULL);
    }
    $amp_metadata->setOrganizationLogoImageStyleId($form_state->getValue('amp_organization_logo_image_style_id'));

    // Save content settings.
    $amp_metadata->setContentSchemaType($form_state->getValue('amp_content_schema_type'));
    $amp_metadata->setContentHeadlineToken($form_state->getValue('amp_content_headline'));
    $amp_metadata->setContentAuthorToken($form_state->getValue('amp_content_author'));
    $amp_metadata->setContentDescriptionToken($form_state->getValue('amp_content_description'));
    $amp_metadata->setContentImageToken($form_state->getValue('amp_content_image'));
    $amp_metadata->setContentImageStyleId($form_state->getValue('amp_content_image_style_id'));

    // Save the metadata and set a message about saving the data.
    $status = $amp_metadata->save();
    $action = $status == SAVED_UPDATED ? 'Updated' : 'Created';

    if ($amp_metadata->isGlobal()) {
      drupal_set_message($this->t('@action the global settings for  AMP Metadata.', [
          '@action' => $action
        ]));
    }
    else {
      drupal_set_message($this->t('@action the @type settings for AMP Metadata.', [
        '@action' => $action,
        '@type' => $this->entityTypeManager->getStorage('node_type')->load($amp_metadata->getNodeType())->label()
      ]));
    }

    $form_state->setRedirectUrl($amp_metadata->urlInfo('collection'));
  }

}
