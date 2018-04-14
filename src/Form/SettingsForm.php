<?php

namespace Drupal\instagram_hashtag_feed\Form;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure instagram_hashtag_feed settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Constructs a \Drupal\instagram_hashtag_feed\Form\SettingsForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ModuleHandlerInterface $module_handler) {
    parent::__construct($config_factory);

    $this->moduleHandler = $module_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('module_handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'instagram_hashtag_feed_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['instagram_hashtag_feed.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('instagram_hashtag_feed.settings');

    $form['content'] = [
      '#type' => 'details',
      '#title' => $this->t('Instagram Hashtag Feeds settings'),
      '#open' => TRUE,
    ];

    $form['content']['instagram_hashtag'] = [
      '#type' => 'textfield',
      '#title' => $this->t('#hashtag'),
      '#required' => TRUE,
      '#description' => $this->t('Example: drupal'),
      '#default_value' => $config->get('instagram_hashtag'),
    ];

    $form['content']['instagram_access_token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Instagram access token'),
      '#required' => TRUE,
      '#default_value' => $config->get('instagram_access_token'),
    ];

    $form['content']['instagram_post_auto_publish'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Auto publish posts'),
      '#default_value' => $config->get('instagram_post_auto_publish'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('instagram_hashtag_feed.settings')
      ->set('instagram_hashtag', $form_state->getValue('instagram_hashtag'))
      ->set('instagram_access_token', $form_state->getValue('instagram_access_token'))
      ->set('instagram_post_auto_publish', $form_state->getValue('instagram_post_auto_publish'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
