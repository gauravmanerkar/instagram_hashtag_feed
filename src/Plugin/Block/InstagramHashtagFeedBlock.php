<?php

namespace Drupal\instagram_hashtag_feed\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a Instagram Hashtag Feed Block.
 *
 * @Block(
 *   id = "instagram_hashtag_feed_block",
 *   admin_label = @Translation("Instagram Hashtag Feed Block"),
 * )
 */
class InstagramHashtagFeedBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $output = '';
    $build['#markup'] = $output;
    $build['#cache']['max-age'] = 0;
    $build['#attached']['library'][] = 'instagram_hashtag_feed/instagram_hashtag_feed.listing';
    $build['#allowed_tags'] = [
      'div', 'script', 'style', 'link', 'form',
      'h2', 'h1', 'h3', 'h4', 'h5',
      'table', 'thead', 'tr', 'td', 'tbody', 'tfoot',
      'img', 'a', 'span', 'option', 'select', 'input',
      'ul', 'li', 'br', 'p', 'link', 'hr', 'style', 'img',
      'class',
    ];
    return $build;
  }

}
