<?php

namespace Drupal\amp\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\amp\AmpMetadataInterface;

/**
 * Defines the AMP Metadata entity.
 *
 * @ConfigEntityType(
 *   id = "amp_metadata",
 *   label = @Translation("AMP Metadata"),
 *   handlers = {
 *     "list_builder" = "Drupal\amp\AmpMetadataListBuilder",
 *     "form" = {
 *       "add" = "Drupal\amp\Form\AmpMetadataForm",
 *       "edit" = "Drupal\amp\Form\AmpMetadataForm",
 *       "delete" = "Drupal\amp\Form\AmpMetadataDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\amp\AmpMetadataHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "metadata",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   lookup_keys = {
 *     "globalToggle",
 *     "nodeTypeId"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/content/amp/metadata/{amp_metadata}",
 *     "add-form" = "/admin/config/content/amp/metadata/add",
 *     "edit-form" = "/admin/config/content/amp/metadata/{amp_metadata}/edit",
 *     "delete-form" = "/admin/config/content/amp/metadata/{amp_metadata}/delete",
 *     "collection" = "/admin/config/content/amp/metadata"
 *   }
 * )
 */
class AmpMetadata extends ConfigEntityBase implements AmpMetadataInterface {

  /**
   * The AMP Metadata ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The AMP Metadata label.
   *
   * @var string
   */
  protected $label;

  /**
   * The toggle for whether these settings apply globally or per content-type.
   *
   * @var boolean
   */
  protected $globalToggle;

  /**
   * The ID for a content type if these settings apply per content-type.
   *
   * @var string
   */
  protected $nodeTypeId;

  /**
   * The organization name.
   *
   * @var string
   */
  protected $organizationName;

  /**
   * The organization logo fid.
   *
   * @var string
   */
  protected $organizationLogoFid;

  /**
   * The organization logo image style ID.
   *
   * The image style should output a JPG, PNG or GIF, fitting within a 600x60
   * space. The height must be 60px, unless the width is 600px, and the aspect
   * ratio requires a height smaller than 60px.
   *
   * @var string
   */
  protected $organizationLogoImageStyleId;

  /**
   * The content schema type.
   *
   * Values can include:
   * - 'Article'
   * - 'NewsArticle'
   * - 'BlogPosting'
   *
   * @var string
   */
  protected $contentSchemaType;

  /**
   * The content headline token.
   *
   * Typically this will be a token for either the node title or a separate
   * field containing a short headline. Should be limited to 110 characters.
   *
   * @var string
   */
  protected $contentHeadlineToken;

  /**
   * The content author token.
   *
   * Typically this will be a token for the node author in order to correctly
   * show the author for each node.
   *
   * @var string
   */
  protected $contentAuthorToken;

  /**
   * The content description token.
   *
   * Typically this will be a token for either the node body summary or a
   * separate field containing the description. Should be limited to 150 chars.
   *
   * @var string
   */
  protected $contentDescriptionToken;

  /**
   * The content image token.
   *
   * @var string
   */
  protected $contentImageToken;

  /**
   * The content image style ID.
   *
   * The image style should output an image at least 696px wide: an image style
   * applied to the token image can help ensure that width.
   *
   * @var string
   */
  protected $contentImageStyleId;

  /**
   * {@inheritdoc}
   */
  public function isGlobal() {
    return $this->globalToggle;
  }

  /**
   * {@inheritdoc}
   */
  public function setGlobal() {
    $this->globalToggle = TRUE;
    $this->nodeTypeId = NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getNodeType() {
    if ($this->globalToggle || empty($this->nodeTypeId)) {
      return NULL;
    }
    else {
      return $this->nodeTypeId;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setNodeType($node_type_id) {
    $this->globalToggle = FALSE;
    $this->nodeTypeId = $node_type_id;
  }

  /**
   * {@inheritdoc}
   */
  public function getOrganizationName() {
    return $this->organizationName;
  }

  /**
   * {@inheritdoc}
   */
  public function setOrganizationName($organization_name) {
    $this->organizationName = $organization_name;
  }

  /**
   * {@inheritdoc}
   */
  public function getOrganizationLogoFid() {
    return $this->organizationLogoFid;
  }

  /**
   * {@inheritdoc}
   */
  public function setOrganizationLogoFid($organization_logo_fid) {
    $this->organizationLogoFid = $organization_logo_fid;
  }

  /**
   * {@inheritdoc}
   */
  public function getOrganizationLogoImageStyleId() {
    return $this->organizationLogoImageStyleId;
  }

  /**
   * {@inheritdoc}
   */
  public function setOrganizationLogoImageStyleId($organization_logo_image_style_id) {
    $this->organizationLogoImageStyleId = $organization_logo_image_style_id;
  }

  /**
   * {@inheritdoc}
   */
  public function getContentSchemaType() {
    return $this->contentSchemaType;
  }

  /**
   * {@inheritdoc}
   */
  public function setContentSchemaType($content_schema_type) {
    $valid_schema_types = ['Article', 'NewsArticle', 'BlogPosting'];
    if (in_array($content_schema_type, $valid_schema_types)) {
      $this->contentSchemaType = $content_schema_type;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getContentHeadlineToken() {
    return $this->contentHeadlineToken;
  }

  /**
   * {@inheritdoc}
   */
  public function setContentHeadlineToken($content_headline_token) {
    $this->contentHeadlineToken = $content_headline_token;
  }

  /**
   * {@inheritdoc}
   */
  public function getContentAuthorToken() {
    return $this->contentAuthorToken;
  }

  /**
   * {@inheritdoc}
   */
  public function setContentAuthorToken($content_author_token) {
    $this->contentAuthorToken = $content_author_token;
  }

  /**
   * {@inheritdoc}
   */
  public function getContentDescriptionToken() {
    return $this->contentDescriptionToken;
  }

  /**
   * {@inheritdoc}
   */
  public function setContentDescriptionToken($content_description_token) {
    $this->contentDescriptionToken = $content_description_token;
  }

  /**
   * {@inheritdoc}
   */
  public function getContentImageToken() {
    return $this->contentImageToken;
  }

  /**
   * {@inheritdoc}
   */
  public function setContentImageToken($content_image_token) {
    $this->contentImageToken = $content_image_token;
  }

  /**
   * {@inheritdoc}
   */
  public function getContentImageStyleId() {
    return $this->contentImageStyleId;
  }

  /**
   * {@inheritdoc}
   */
  public function setContentImageStyleId($content_image_style_id) {
    $this->contentImageStyleId = $content_image_style_id;
  }

}
