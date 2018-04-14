<?php

namespace Drupal\instagram_hashtag_feed\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides post fetch and flush form.
 *
 * @internal
 */
class FetchAndFlushForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'instagram_hashtag_feed_fetch_and_flush_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['manage'] = [
      '#type' => 'details',
      '#title' => $this->t('Import/Flush Instagram Posts'),
      '#open' => TRUE,
    ];

    $form['manage']['fetch'] = [
      '#type' => 'submit',
      '#value' => $this->t('Fetch Posts'),
      '#submit' => ['::fetchInstagramPosts'],
    ];

    $form['manage']['flush'] = [
      '#type' => 'submit',
      '#value' => $this->t('Flush Posts'),
      '#submit' => ['::flushInstagramPosts'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function fetchInstagramPosts(array &$form, FormStateInterface $form_state) {
    $posts_imported = instagram_hashtag_feed_fetch();
    if ($posts_imported === FALSE) {
      drupal_set_message($this->t('Failed importing instagram posts'), 'error');
    }
    else {
      $message = 'Imported ' . $posts_imported . ' instagram posts';
      drupal_set_message($message);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function flushInstagramPosts(array &$form, FormStateInterface $form_state) {
    if (instagram_hashtag_feed_flush()) {
      drupal_set_message($this->t('Deleted all instagram posts'));
    }
  }

}
