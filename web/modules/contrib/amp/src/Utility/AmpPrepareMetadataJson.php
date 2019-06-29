<?php

namespace Drupal\amp\Utility;

use Drupal\amp\AmpPrepareMetadataJsonInterface;
use Drupal\Component\Render\PlainTextOutput;
use Drupal\Core\Image\ImageInterface;
use Drupal\Core\StreamWrapper\PublicStream;
use Drupal\Core\Utility\Token;
use Drupal\file\Entity\File;
use Drupal\file\FileInterface;
use Drupal\image\Entity\ImageStyle;
use Drupal\image\ImageStyleInterface;
use Drupal\node\NodeInterface;

/**
 * Class AmpPrepareMetadataJson
 *
 * Processes AMP Metadata settings and prepares them to be encoded as JSON.
 *
 * @package Drupal\amp\Utility
 */
class AmpPrepareMetadataJson implements AmpPrepareMetadataJsonInterface {

  /**
   * The array containing AMP metadata settings
   *
   * @var array
   */
  protected $ampMetadataSettings;

  /**
   * The canonical url for this node.
   *
   * @var string
   */
  protected $canonicalUrl;

  /**
   * The node object that will be processed.
   *
   * @var \Drupal\node\NodeInterface
   */
  protected $node;

  /**
   * Tracks whether AMP metadata JSON will provide complete, valid output. In
   * the future this will be used to provide additional debugging assistance.
   *
   * @var boolean
   */
  protected $ampMetadataComplete;

  /**
   * The array containing metadata that can be encoded as JSON.
   *
   * @var array
   */
  protected $ampPreparedMetadataJson;

  /**
   * The list of AMP metadata items in the JSON.
   */
  protected $ampMetadataList;

  /**
   * The token service.
   *
   * @var \Drupal\Core\Utility\Token;
   */
  protected $token;


  /**
   * Constructs a new AmpPrepareMetadataJson service.
   *
   * @param Token $token
   *   The token service.
   */
  public function __construct(Token $token) {
    $this->ampMetadataSettings = [];
    $this->canonicalUrl = '';
    $this->node = NULL;
    $this->token = $token;
  }

  /**
   * {@inheritdoc}
   */
  public function getJson(array $amp_metadata_settings, $canonical_url, NodeInterface $node) {
    if (!empty($this->ampMetadataSettings = $amp_metadata_settings) && !empty($this->canonicalUrl = $canonical_url) && !empty($this->node = $node)) {
      $this->prepareMetadataJson();
      $this->setAmpMetadataList();
      $this->checkMetadataComplete();
    }

    if (!empty($this->ampPreparedMetadataJson)) {
      // Improve appearance of JSON output by using json_encode() options
      // JSON_PRETTY_PRINT and JSON_UNESCAPED_SLASHES, which are not supported
      // by Drupal's JSON serialization service ('serialization.json').
      return json_encode($this->ampPreparedMetadataJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
    }
    else {
      return FALSE;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getPreparedMetadataJson() {
    return $this->ampPreparedMetadataJson;
  }

  /**
   * {@inheritdoc}
   */
  public function setPreparedMetadataJson($prepared_metadata) {
    $this->ampPreparedMetadataJson = $prepared_metadata;
  }

  /**
   * Prepares a metadata array to be ready for JSON conversion.
   */
  protected function prepareMetadataJson() {
    if (empty($this->ampPreparedMetadataJson)) {
      $this->ampPreparedMetadataJson = [];
    }
    $this->prepareSchemaContext();
    $this->prepareSchemaType();
    $this->prepareMainEntity();
    $this->prepareHeadline();
    $this->prepareAuthor();
    $this->prepareDatePublished();
    $this->prepareDateModified();
    $this->prepareContentImage();
    $this->prepareDescription();
    $this->preparePublisher();
  }

  /**
   * Prepare the schema context.
   */
  protected function prepareSchemaContext() {
    $this->ampPreparedMetadataJson['@context'] = 'http://schema.org';
  }

  /**
   * Prepare the schema type.
   */
  protected function prepareSchemaType() {
    if (isset($this->ampMetadataSettings['schema_type']) && !empty($this->ampMetadataSettings['schema_type'])) {
      $this->ampPreparedMetadataJson['@type'] = $this->ampMetadataSettings['schema_type'];
    }
  }

  /**
   * Prepare the main entity of the page.
   */
  protected function prepareMainEntity() {
    if (!empty($this->canonicalUrl)) {
      $this->ampPreparedMetadataJson['mainEntityOfPage'] = [
        '@type' => 'WebPage',
        '@id' => $this->canonicalUrl
      ];
    }
  }

  /**
   * Prepare headline of content, if available: maximum 110 characters.
   */
  protected function prepareHeadline() {
    if (isset($this->ampMetadataSettings['content_headline_token']) && !empty($this->ampMetadataSettings['content_headline_token'])) {
      $headline = PlainTextOutput::renderFromHtml($this->token->replace($this->ampMetadataSettings['content_headline_token'], ['node' => $this->node]));
      $this->ampPreparedMetadataJson['headline'] = (strlen($headline) > 110) ? mb_strimwidth($headline, 0, 107, "...") : $headline;
    }
  }

  /**
   * Prepare author information.
   */
  protected function prepareAuthor() {
    if (isset($this->ampMetadataSettings['content_author_token']) && !empty($this->ampMetadataSettings['content_author_token'])) {
      $this->ampPreparedMetadataJson['author'] = [
        '@type' => 'Person',
        'name' => PlainTextOutput::renderFromHtml($this->token->replace($this->ampMetadataSettings['content_author_token'], ['node' => $this->node]))
      ];
    }
  }

  /**
   * Prepare the published date.
   */
  protected function prepareDatePublished() {
    $date_published = date(DATE_ATOM, $this->node->getCreatedTime());
    if (!empty($date_published)) {
      $this->ampPreparedMetadataJson['datePublished'] = $date_published;
    }
  }

  /**
   * Prepare the modified date.
   */
  protected function prepareDateModified() {
    $date_modified = date(DATE_ATOM, $this->node->getChangedTime());
    if (!empty($date_modified)) {
      $this->ampPreparedMetadataJson['dateModified'] = $date_modified;
    }
  }

  /**
   * Prepare content image information.
   */
  protected function prepareContentImage() {
    if (isset($this->ampMetadataSettings['content_image_token']) && !empty($this->ampMetadataSettings['content_image_token'])) {
      $content_image_uri = $this->getUriFromImageTokenString($this->ampMetadataSettings['content_image_token']);
      if (!empty($content_image_uri)) {

        $content_image_style_id = '';
        if (isset($this->ampMetadataSettings['content_image_style_id']) && !empty($this->ampMetadataSettings['content_image_style_id'])) {
          $content_image_style_id = $this->ampMetadataSettings['content_image_style_id'];
        }
        $content_image_info = $this->getImageInformation($content_image_uri, $content_image_style_id);

        if (!empty($content_image_info)) {

          $this->ampPreparedMetadataJson['image'] = [
            '@type' => 'ImageObject',
            'url' => $content_image_info['url'],
            'width' => $content_image_info['width'],
            'height' => $content_image_info['height']
          ];
        }
      }
    }
  }

  /**
   * Prepare description of content, if available: maximum 150 characters.
   */
  protected function prepareDescription() {
    if (isset($this->ampMetadataSettings['content_description_token']) && !empty($this->ampMetadataSettings['content_description_token'])) {
      $description = PlainTextOutput::renderFromHtml($this->token->replace($this->ampMetadataSettings['content_description_token'], ['node' => $this->node]));
      $this->ampPreparedMetadataJson['description'] = (strlen($description) > 150) ? mb_strimwidth($description, 0, 147, "...") : $description;
    }
  }

  /**
   * Prepare publisher information.
   */
  protected function preparePublisher() {
    if (isset($this->ampMetadataSettings['org_name']) && !empty($this->ampMetadataSettings['org_name']) && isset($this->ampMetadataSettings['org_logo_fid']) && !empty($this->ampMetadataSettings['org_logo_fid'])) {
      /** @var FileInterface $org_logo_file */
      if (!empty($org_logo_file = File::load($this->ampMetadataSettings['org_logo_fid'])) && !empty($org_logo_uri = $org_logo_file->getFileUri())) {
        $org_logo_style_id = '';
        if (isset($this->ampMetadataSettings['org_logo_style_id']) && !empty($this->ampMetadataSettings['org_logo_style_id'])) {
          $org_logo_style_id = $this->ampMetadataSettings['org_logo_style_id'];
        }
        $org_logo_info = $this->getImageInformation($org_logo_uri, $org_logo_style_id);

        if (!empty($org_logo_info)) {

          $this->ampPreparedMetadataJson['publisher'] = [
            '@type' => 'Organization',
            'name' => PlainTextOutput::renderFromHtml(\Drupal::service('token')->replace($this->ampMetadataSettings['org_name'], ['node' => $this->node])),
            'logo' => [
              '@type' => 'ImageObject',
              'url' => $org_logo_info['url'],
              'width' => $org_logo_info['width'],
              'height' => $org_logo_info['height']
            ]
          ];
        }
      }
    }
  }

  /**
   * Get an image URI from a string containing an image token.
   *
   * @param string $image_token_string
   *   The string containing an image token.
   *
   * @return string $image_uri
   *   The URI of the image.
   */
  protected function getUriFromImageTokenString($image_token_string) {
    $image_url = $this->token->replace($image_token_string, ['node' => $this->node]);

    // Provide backup parsing of image element if token does not output a URL.
    if (strip_tags($image_url) != $image_url) {
      // Force path to be absolute.
      if (strpos($image_url, 'img src="/') !== FALSE) {
        global $base_root;
        $image_url = str_replace('img src="/', 'img src="' . $base_root . '/', $image_url);
      }

      $matches = [];
      preg_match('/src="([^"]*)"/', $image_url, $matches);
      if (!empty($matches[1])) {
        $image_url = $matches[1];
      }
    }
    $image_url = PlainTextOutput::renderFromHtml($image_url);

    $public_stream_base_url = PublicStream::baseUrl();

    $image_uri = '';
    if (substr($image_url, 0, strlen($public_stream_base_url)) == $public_stream_base_url) {
      $image_uri = file_build_uri(substr($image_url, strlen($public_stream_base_url)));
    }
    return $image_uri;
  }

  /**
   * Gets image information.
   *
   * @param string $image_uri
   *   The URI of the image. URI should begin with 'public://'.
   * @param string $image_style_id
   *   The optional ID of the image style.
   *
   * @return array
   *   The array containing information about the image. Return an empty array
   *   if there is a problem getting information about the image. Otherwise the
   *   image inforation array includes the following keys:
   *   - url
   *   - width
   *   - px
   */
  protected function getImageInformation($image_uri, $image_style_id = '') {
    $image_url = '';
    $image_width = '';
    $image_height = '';

    /** @var ImageInterface $image */
    $image = \Drupal::service('image.factory')->get($image_uri);
    if ($image->isValid()) {
      $image_url = file_create_url($image_uri);
      $image_width = $image->getWidth();
      $image_height = $image->getHeight();

      if (!empty($image_style_id)) {
        /** @var ImageStyleInterface $image_style */
        $image_style = ImageStyle::load($image_style_id);
        $image_dimensions = [
          'width' => $image_width,
          'height' => $image_height
        ];
        $image_style->transformDimensions($image_dimensions, $image_uri);
        $image_url = $image_style->buildUrl($image_uri);
        $image_width = $image_dimensions['width'];
        $image_height = $image_dimensions['height'];
      }
    }

    if (!empty($image_url) && !empty($image_width) & !empty($image_height)) {
      return [
        'url' => $image_url,
        'width'=> $image_width,
        'height' => $image_height
      ];
    }
    else {
      return [];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getAmpMetadataList() {
    return $this->ampMetadataList;
  }

  /**
   * {@inheritdoc}
   */
  public function setAmpMetadataList() {
    $this->ampMetadataList = [
      '@context',
      'schema_type',
      'mainEntityOfPage',
      'headline',
      'author',
      'datePublished',
      'dateModified',
      'image',
      'description',
      'publisher'
    ];
  }

  /**
   * Check if metadata JSON is complete.
   */
  protected function checkMetadataComplete() {
    $completed = [];

    foreach($this->ampMetadataList as $metadatum) {
      $completed[$metadatum] = isset($this->ampPreparedMetadataJson[$metadatum]) ? !empty($this->ampPreparedMetadataJson[$metadatum]) : FALSE;
    }

    $this->ampMetadataComplete = TRUE;
    foreach($completed as $item_complete) {
      if ($item_complete === FALSE) {
        $this->ampMetadataComplete = FALSE;
      }
    }
  }
}
