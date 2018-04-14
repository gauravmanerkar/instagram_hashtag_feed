<?php

namespace Drupal\instagram_hashtag_feed\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

/**
 * {@inheritdoc}
 */
class InstagramHashtagFeedController extends ControllerBase {

  /**
   * Change post status.
   */
  public function changePostStatus($pid, $status) {
    if (!empty($pid) && is_numeric($pid) && is_numeric($status) && in_array($status, [0, 1])) {
      db_update('instagram_hashtag_feed')
        ->condition('id', $pid)->fields(['published' => $status])->execute();
      if ($status == '0') {
        drupal_set_message($this->t('Post Unpublished'));
      }
      elseif ($status == '1') {
        drupal_set_message($this->t('Post Published'));
      }
    }
    else {
      drupal_set_message($this->t('Operation failed'), 'error');
    }
    return $this->redirect('instagram_hashtag_feed.manage_posts');
  }

  /**
   * Clears all caches, then redirects to the previous page.
   */
  public function managePosts() {

    $build['fetch_and_flush_form'] = $this->formBuilder()->getForm('Drupal\instagram_hashtag_feed\Form\FetchAndFlushForm');

    $header = [
      ['data' => $this->t('POST ID'), 'field' => 'post_id'],
      ['data' => $this->t('Image'), 'field' => 'url'],
      ['data' => $this->t('Caption'), 'field' => 'caption'],
      ['data' => $this->t('Status'), 'field' => 'published'],
      ['data' => $this->t('Created'), 'field' => 'created'],
      ['data' => $this->t('Operations')],
    ];

    $query = db_select('instagram_hashtag_feed', 'ihf');
    $query->fields('ihf');

    $table_sort = $query->extend('Drupal\Core\Database\Query\TableSortExtender')
      ->orderByHeader($header)->orderBy('id', 'DESC');
    $pager = $table_sort->extend('Drupal\Core\Database\Query\PagerSelectExtender')
      ->limit(50);
    $result = $pager->execute();
    $rows = [];
    foreach ($result as $row) {
      $image = $this->t('<img src="@image_url" />', ['@image_url' => $row->url]);
      $operations = [];

      $operations['change_status'] = [
        'title' => ($row->published == 1 ? $this->t('Unpublish') : $this->t('Publish')),
        'url' => Url::fromRoute('instagram_hashtag_feed.change_status', ['pid' => $row->id, 'status' => ($row->published == 1 ? 0 : 1)]),
      ];
      $operations['delete'] = [
        'title' => $this->t('Delete'),
        'url' => Url::fromRoute('instagram_hashtag_feed.delete', ['pid' => $row->id]),
      ];
      $operations_data = ['#type' => 'operations', '#links' => $operations];
      $rows[] = [
        'data' => [
          'POST ID' => $row->post_id,
          'IMAGE' => $image,
          'Caption' => $row->caption,
          'Status' => ($row->published == 1 ? 'Published' : 'Unpublished'),
          'Created' => date('d/m/Y h:i:s a', $row->created),
          'Operations' => render($operations_data),
        ],
      ];
    }
    // Generate the table.
    $build['config_table'] = [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#prefix' => '<div class="instagram-hashtag-feed-manage-posts">',
      '#suffix' => '</div>',
      '#empty' => $this->t('No posts to display.'),
    ];
    // Finally add the pager.
    $build['pager'] = [
      '#type' => 'pager',
    ];
    $build['#attached']['library'][] = 'instagram_hashtag_feed/admin.styling';
    return $build;
  }

}
