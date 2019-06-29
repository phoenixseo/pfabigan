<?php

namespace Drupal\Tests\entity_browser_entity_form\FunctionalJavascript;

use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\FunctionalJavascriptTests\JavascriptTestBase;

/**
 * Test for inline entity form field widget display.
 *
 * @covers \Drupal\entity_browser_entity_form\Plugin\EntityBrowser\FieldWidgetDisplay\InlineEntityForm
 *
 * @group entity_browser_entity_form
 *
 * @package Drupal\Tests\entity_browser_entity_form\FunctionalJavascript
 */
class InlineEntityFormFieldWidgetDisplayTest extends JavascriptTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'entity_browser_entity_form_test',
    'ctools',
    'views',
    'block',
    'node',
    'file',
    'image',
    'field_ui',
    'views_ui',
    'system',
  ];

  /**
   * User permissions for logged user.
   *
   * @var array
   */
  protected static $userPermissions = [
    'access content',
    'create foo content',
    'bypass node access',
    'access entity_browser_test_entity_form entity browser pages',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->drupalCreateContentType([
      'type' => 'foo',
      'name' => 'Foo',
    ]);

    FieldStorageConfig::create([
      'field_name' => 'field_reference',
      'type' => 'entity_reference',
      'entity_type' => 'node',
      'cardinality' => FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED,
      'settings' => [
        'target_type' => 'node',
      ],
    ])->save();

    FieldConfig::create([
      'field_name' => 'field_reference',
      'entity_type' => 'node',
      'bundle' => 'foo',
      'label' => 'Reference',
      'settings' => [],
    ])->save();

    /** @var \Drupal\Core\Entity\Display\EntityFormDisplayInterface $form_display */
    $form_display = $this->container->get('entity_type.manager')
      ->getStorage('entity_form_display')
      ->load('node.foo.default');

    $form_display->setComponent('field_reference', [
      'type' => 'entity_browser_entity_reference',
      'settings' => [
        'entity_browser' => 'entity_browser_test_entity_form',
        'field_widget_display' => 'inline_entity_form',
        'field_widget_display_settings' => [
          'form_mode' => 'default',
        ],
        'open' => TRUE,
      ],
    ])->save();

    /** @var \Drupal\Core\Entity\Display\EntityDisplayInterface $display */
    $display = $this->container->get('entity_type.manager')
      ->getStorage('entity_view_display')
      ->load('node.foo.default');

    $display->setComponent('field_reference', [
      'settings' => [
        'link' => TRUE,
      ],
      'type' => 'entity_reference_label',
      'region' => 'content',
    ])->save();

    $account = $this->drupalCreateUser(static::$userPermissions);
    $this->drupalLogin($account);
  }

  /**
   * Testing of inline entity form field widget.
   */
  public function testInlineEntityFormWidget() {
    $this->drupalGet('node/add/foo');
    $page = $this->getSession()->getPage();

    // Fill form and create new entity inside entity browser.
    $page->fillField('title[0][value]', 'Cartoon quotes');
    $page->clickLink('Select entities');
    $this->getSession()
      ->switchToIFrame('entity_browser_iframe_entity_browser_test_entity_form');
    $page->fillField('inline_entity_form[title][0][value]', 'Trees and people used to be good friends');
    $page->pressButton('Save entity');

    // Switch back to the main form.
    $this->getSession()->switchToIFrame();
    $this->assertSession()->assertWaitOnAjaxRequest();

    // Check that form for inner entity exists and that field is filled.
    $this->assertSession()
      ->fieldValueEquals('field_reference[current][items][0][display][title][0][value]', 'Trees and people used to be good friends');
    $page->pressButton('Save');

    // Check view display for entity.
    $this->drupalGet('/node/2');
    $this->assertSession()
      ->pageTextContains('Trees and people used to be good friends');

    // Edit entity with changing value in inline form and check that value is
    // correct after entity is saved.
    $this->drupalGet('/node/2/edit');
    $page->fillField('field_reference[current][items][0][display][title][0][value]', 'Trees and people used to be good friends [Tatsuo Kusakabe]');
    $page->pressButton('Save');

    $this->assertSession()
      ->pageTextContains('Trees and people used to be good friends [Tatsuo Kusakabe]');
  }

}
