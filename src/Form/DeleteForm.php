<?php

namespace Drupal\instagram_hashtag_feed\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;

/**
 * Class DeleteForm.
 *
 * @package Drupal\instagram_hashtag_feed\Form
 */
class DeleteForm extends ConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'instagram_hashtag_feed_delete_form';
  }

  public $pid;

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Are you sure you want to delete %pid?', [
      '%pid' => $this->pid,
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('instagram_hashtag_feed.manage_posts');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelText() {
    return t('Cancel');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $pid = NULL) {
    $this->id = $pid;
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $query = \Drupal::database();
    $query->delete('instagram_hashtag_feed')->condition('id', $this->id)->execute();
    drupal_set_message(t("Post deleted successfully"));
    $form_state->setRedirect('instagram_hashtag_feed.manage_posts');
  }

}
