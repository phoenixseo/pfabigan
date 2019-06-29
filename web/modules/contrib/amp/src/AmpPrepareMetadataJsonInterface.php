<?php

namespace Drupal\amp;

use Drupal\node\NodeInterface;

/**
 * Provides an interface for preparing AMP Metadata JSON.
 */
interface AmpPrepareMetadataJsonInterface {

  /**
   * Gets the encoded AMP metadata JSON.
   *
   * @param array $amp_metadata_settings
   *   The array containing AMP metadata settings.
   * @param string $canonical_url
   *   The canonical url for this node.
   * @param NodeInterface $node
   *   The node object.
   *
   * @return string
   *   A JSON encoded string on success or FALSE on failure.
   */
  public function getJson(array $amp_metadata_settings, $canonical_url, NodeInterface $node);

  /**
   * Gets the array containing metadata ready to be converted to JSON.
   */
  public function getPreparedMetadataJson();

  /**
   * Allows additions or changes to metadata ready to be converted to JSON.
   *
   * @param array $prepared_metadata
   */
  public function setPreparedMetadataJson($prepared_metadata);

  /**
   * Get the list of metadata items.
   */
  public function getAmpMetadataList();

  /**
   * Sets the list of metadata items.
   */
  public function setAmpMetadataList();
}
