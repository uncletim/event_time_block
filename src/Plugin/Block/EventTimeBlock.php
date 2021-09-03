<?php

namespace Drupal\event_time_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;
use Drupal\Core\Datetime\DrupalDateTime;
/**
 *
 * @Block(
 *   id = "event_time__block",
 *   admin_label = @Translation("Event time block"),
 *   category = @Translation("Event time block"),
 * )
 */
class EventTimeBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */

  public function build() {
    return [
      '#markup' => $this->returnEventTime(),
      '#cache' => [
        'max-age' => 0
      ],
    ];
  }

private function returnEventTime() {



  $node = \Drupal::routeMatch()->getParameter('node');
  if ($node instanceof \Drupal\node\NodeInterface) {
    $nid = $node->id();
  }
   
  $eventTimeString = Node::load($nid)->field_date->value;

  $eventDate = substr($eventTimeString, 0, 10);

  $date1 = new DrupalDateTime($eventDate);
  $date = $date1->format('Y-m-d');

  $now1 = new DrupalDateTime();
  $now = $now1->format('Y-m-d');
  
  if($date < $now) {
      $message = 'This event already passed.';
  } else if ($date == $now) {
      $message = 'This event is happening today.';
  } else if ($date > $now) {
   

    function dateDifference($now, $date) {
      $diff = strtotime($now) - strtotime($date);
      return ceil(abs($diff / 86400));
    }
 
     $dateDiff = dateDifference($now, $date);
     if ($dateDiff > 1) {
      $message = $dateDiff.' days left until event starts.';
     } else {
      $message = $dateDiff.' day left until event starts';
     }

  }

  return $message;
}


}
