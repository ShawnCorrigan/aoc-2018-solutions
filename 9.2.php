<?php
$input = <<<EOT
418 players; last marble is worth 70769 points
EOT;

preg_match('/(?<players>[0-9]+) players; last marble is worth (?<points>[0-9]+) points/', $input, $matches);

$players = $matches['players'];

$last_score = $matches['points'] * 100;

$circle = [
  0 => [
    'clockwise' => 0,
    'counterclockwise' => 0,
  ],
];

$current_index = 0;

$player = 1;

$scores = [];

for ($i = 1; $i <= $players; $i++) {
  $scores[$i] = 0;
}

$last_marble = 0;

$x = 1;

while ($x <= $last_score) {
  if ($x % 23 === 0) {
    $marble_to_remove = $circle[$last_marble]['counterclockwise'];

    for ($i = 0; $i < 6; $i++) {
      $marble_to_remove = $circle[$marble_to_remove]['counterclockwise'];
    }

    $before = $circle[$marble_to_remove]['counterclockwise'];

    $after = $circle[$marble_to_remove]['clockwise'];

    $circle[$before]['clockwise'] = $after;

    $circle[$after]['counterclockwise'] = $before;

    unset($circle[$marble_to_remove]);

    $scores[$player] += $marble_to_remove + $x;

    $last_marble = $after;
  } else {
    $before = $circle[$last_marble]['clockwise'];

    $after = $circle[$before]['clockwise'];

    $circle[$x] = [
      'counterclockwise' => $before,
      'clockwise' => $after,
    ];

    $circle[$after]['counterclockwise'] = $x;

    $circle[$before]['clockwise'] = $x;

    $last_marble = $x;
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
