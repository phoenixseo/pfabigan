<?php

namespace Drupal\amp;

use Drupal\Core\Cache\Cache;
use Drupal\amp\EntityTypeInfo;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Cache\CacheBackendInterface;

/**
 * Service class for retrieving information about AMP Metadata.
 */
class AmpMetadataInfo {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The cache backend to use for the complete theme registry data.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cache;

  /**
   * The information on AMP entity types.
   *
   * @var \Drupal\amp\EntityTypeInfo
   */
  protected $entityTypeInfo;

  /**
   * Constructs an AmpMetadataInfo service.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   *   The cache backend interface to use for the complete theme registry data.
   * @param \Drupal\amp\EntityTypeInfo $entity_type_info
   *   The information on AMP entity types.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, CacheBackendInterface $cache, EntityTypeInfo $entity_type_info) {
    $this->entityTypeManager = $entity_type_manager;
    $this->cache = $cache;
    $this->entityTypeInfo = $entity_type_info;
  }

  /**
   * Check if global settings exist for AMP metadata.
   *
   * @return boolean
   *   Returns TRUE if global settings exist for AMP metadata.
   */
  public function ampMetadataHasGlobal() {
    return !empty($this->entityTypeManager->getStorage('amp_metadata')->load('global'));
  }

  /**
   * Returns an array of available node types to override.
   *
   * @return array
   *   A list of available node types as $id => $label.
   */
  public function getAmpNodeTypesWithoutMetadataSettings() {
    $options = array();

    $amp_metadata_settings = $this->entityTypeManager->getStorage('amp_metadata')->loadMultiple();
    $amp_types_with_settings = [];
    /** @var AmpMetadataInterface $amp_metadata  */
    foreach ($amp_metadata_settings as $amp_metadata_id => $amp_metadata) {
      if (!$amp_metadata->isGlobal()) {
        $amp_types_with_settings[] = $amp_metadata->getNodeType();
      }
    }

    $amp_enabled_node_types = $this->entityTypeInfo->getAmpEnabledTypes();
    foreach ($amp_enabled_node_types as $node_type) {
      if (!in_array($node_type, $amp_types_with_settings)) {
        $options[$node_type] = $this->entityTypeManager->getStorage('node_type')->load($node_type)->label();
      }
    }

    return $options;
  }

}
