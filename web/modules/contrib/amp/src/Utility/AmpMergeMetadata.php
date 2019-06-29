<?php

namespace Drupal\amp\Utility;

use Drupal\amp\AmpMetadataInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class AmpMergeMetadata
 *
 * Merges AMP Metadata global and node type settings.
 *
 * @package Drupal\amp\Utility
 */
class AmpMergeMetadata {

  /**
   * Array of \Drupal\amp\Entity\AmpMetadata entities.
   *
   * @var array
   */
  protected $metadataEntities;

  /**
   * Global metadata settings.
   *
   * @var array
   */
  protected $ampGlobalMetadata;

  /**
   * Node type metadata settings..
   *
   * @var array
   */
  protected $ampNodeTypeMetadata;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new AmpMergeMetadata service.
   *
   * @param EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
    $this->metadataEntities = $this->entityTypeManager->getStorage('amp_metadata')->loadMultiple();
    $this->ampGlobalMetadata = [];
    $this->ampNodeTypeMetadata = [];
  }

  /**
   * Helper function to get merged global and per node type metadata.
   *
   * @param string $node_type
   *   The node type.
   *
   * @return array
   *   An array containing merged metadata for this node type.
   */
  public function getMergedMetadata($node_type) {
    /** @var AmpMetadataInterface $metadata_entity */
    foreach ($this->metadataEntities as $metadata_entity) {
      // Check if these settings are for global or type override matching the node
      // type. Because the if selector uses OR, store this outside if statement.
      $is_global = $is_type = FALSE;
      $is_global = $metadata_entity->isGlobal();
      $is_type = ($metadata_entity->getNodeType() === $node_type);
      if ($is_global || $is_type) {
        // Get metadata settings from config entity.
        $entity_settings = [];

        // Get organization metadata.
        if (!empty($org_name = $metadata_entity->getOrganizationName())) {
          $entity_settings['org_name'] = $org_name;
        }
        if (!empty($org_logo_fid = $metadata_entity->getOrganizationLogoFid())) {
          $entity_settings['org_logo_fid'] = $org_logo_fid;
        }
        if (!empty($org_logo_style_id = $metadata_entity->getOrganizationLogoImageStyleId())) {
          $entity_settings['org_logo_style_id'] = $org_logo_style_id;
        }

        // Get content metadata.
        if (!empty($schema_type = $metadata_entity->getContentSchemaType())) {
          $entity_settings['schema_type'] = $schema_type;
        }
        if (!empty($content_headline_token = $metadata_entity->getContentHeadlineToken())) {
          $entity_settings['content_headline_token'] = $content_headline_token;
        }
        if (!empty($content_author_token = $metadata_entity->getContentAuthorToken())) {
          $entity_settings['content_author_token'] = $content_author_token;
        }
        if (!empty($content_description_token = $metadata_entity->getContentDescriptionToken())) {
          $entity_settings['content_description_token'] = $content_description_token;
        }
        if (!empty($content_image_token = $metadata_entity->getContentImageToken())) {
          $entity_settings['content_image_token'] = $content_image_token;
        }
        if (!empty($content_image_style_id = $metadata_entity->getContentImageStyleId())) {
          $entity_settings['content_image_style_id'] = $content_image_style_id;
        }

        // Store entity settings as global or per type so overrides can be merged.
        if ($is_global) {
          $this->ampGlobalMetadata = $entity_settings;
        }
        elseif ($is_type) {
          $this->ampNodeTypeMetadata = $entity_settings;
        }
      }
    }

    return array_merge($this->ampGlobalMetadata, $this->ampNodeTypeMetadata);
  }

}
