<?php

namespace Drupal\amp;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining AMP Metadata entities.
 */
interface AmpMetadataInterface extends ConfigEntityInterface {

  /**
   * Check if the settings apply globally.
   *
   * @return boolean
   *   TRUE if the settings apply globally.
   */
  public function isGlobal();

  /**
   * Apply these settings globally.
   */
  public function setGlobal();

  /**
   * Get the node type for these settings.
   *
   * @return string
   *   The identifier of the node type.
   */
  public function getNodeType();

  /**
   * Apply these settings per node type.
   *
   * @param string $node_type_id
   *   The identifier of the node type.
   */
  public function setNodeType($node_type_id);

  /**
   * Get the organization name.
   *
   * @return string
   *   The name of the organization.
   */
  public function getOrganizationName();

  /**
   * Set the organization name.
   *
   * @param string $organization_name
   *   The name of the organization.
   */
  public function setOrganizationName($organization_name);

  /**
   * Get the organization logo FID.
   *
   * @return string
   *   The fid of the organization logo.
   */
  public function getOrganizationLogoFid();

  /**
   * Set the organization logo FID.
   *
   * @param string $organization_logo_fid
   *   The fid of the organization logo.
   */
  public function setOrganizationLogoFid($organization_logo_fid);

  /**
   * Get the organization logo image style ID.
   *
   * @return string
   *   The ID of the organization logo image style.
   */
  public function getOrganizationLogoImageStyleId();

  /**
   * Set the organization logo image style ID.
   *
   * The image style should output a JPG, PNG or GIF, fitting within a 600x60
   * space. The height must be 60px, unless the width is 600px, and the aspect
   * ratio requires a height smaller than 60px.
   *
   * @param string $organization_logo_image_style_id
   *   The ID of the organization logo image style.
   */
  public function setOrganizationLogoImageStyleId($organization_logo_image_style_id);

  /**
   * Get the content schema type.
   *
   * @return string
   *   The schema type of this content.
   */
  public function getContentSchemaType();

  /**
   * Set the content schema type.
   *
   * Values can include:
   * - 'Article'
   * - 'NewsArticle'
   * - 'BlogPosting'
   *
   * @param string $content_schema_type
   *   The schema type of this content.
   */
  public function setContentSchemaType($content_schema_type);

  /**
   * Get the content headline token.
   *
   * @return string
   *   The token for the content headline.
   */
  public function getContentHeadlineToken();

  /**
   * Set the content headline token.
   *
   * Typically this will be a token for either the node title or a separate
   * field containing a short headline. Should be limited to 110 characters.
   *
   * @param string $content_headline_token
   *   The token for the content headline.
   */
  public function setContentHeadlineToken($content_headline_token);

  /**
   * Get the content author token.
   *
   * @return string
   *   The token for the content author.
   */
  public function getContentAuthorToken();

  /**
   * Set the content author token.
   *
   * Typically this will be a token for the node author in order to correctly
   * show the author for each node.
   *
   * @param string $content_author_token
   *   The token for the content author.
   */
  public function setContentAuthorToken($content_author_token);

  /**
   * Get the content description token.
   *
   * @return string
   *   The token for the content description.
   */
  public function getContentDescriptionToken();

  /**
   * Set the content description token.
   *
   * Typically this will be a token for either the node body summary or a
   * separate field containing the description. Should be limited to 150 chars.
   *
   * @param string $content_description_token
   *   The token for the content description.
   */
  public function setContentDescriptionToken($content_description_token);

  /**
   * Get the content image token.
   *
   * @return string
   *   The token for the content image.
   */
  public function getContentImageToken();

  /**
   * Set the content image token.
   *
   * @param string $content_image_token
   *   The token for the content image.
   */
  public function setContentImageToken($content_image_token);

  /**
   * Get the content image style ID.
   *
   * @return string
   *   The ID for the content image style.
   */
  public function getContentImageStyleId();

  /**
   * Set the content image style ID.
   *
   * The image style should output an image at least 696px wide.
   *
   * @param string $content_image_style_id
   *   The ID for the content image style.
   */
  public function setContentImageStyleId($content_image_style_id);
}
