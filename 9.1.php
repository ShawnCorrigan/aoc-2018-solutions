<?php
$input = <<<EOT
418 players; last marble is worth 70769 points
EOT;

preg_match('/(?<players>[0-9]+) players; last marble is worth (?<points>[0-9]+) points/', $input, $matches);

$players = $matches['players'];

$last_score = $matches['points'];

$circle = [0];

$current_index = 0;

$player = 1;

$scores = [];

for ($i = 1; $i <= $players; $i++) {
  $scores[$i] = 0;
}

$x = 1;

while ($x <= $last_score) {
  if ($x % 23 === 0) {
    $scores[$player] += $x;
    
    $current_index -= 7;

    if ($current_index < 0) {
      $current_index = count($circle) - abs($current_index);
    }

    $scores[$player] += $circle[$current_index];

    array_splice($circle, $current_index, 1);

    if ($current_index === count($circle)) {
      $current_index = 0;
    }
  } else {
    if ($current_index + 2 <= count($circle))  {
      $current_index += 2;
    } elseif ($current_index + 1 === count($circle) - 1) {
      $current_index = 0;
    } elseif ($current_index === count($circle) - 1) {
      $current_index = 1;
    }

    array_splice($circle, $current_index, 0, $x);
  }

  $player++;

  $player = $player > $players ? 1 : $player;

  $x++;
}

$highest_score = null;

foreach ($scores as $score) {
  if ($score > $highest_score || $highest_score === null) {
    $highest_score = $score;
  }
}

echo $highest_score;
