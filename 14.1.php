<?php
$input = 846021;

function move_elf(&$elf, $scoreboard) {
  $move_to = $elf + substr($scoreboard, $elf, 1) + 1;

  if ($move_to >= strlen($scoreboard)) {
    $elf = ($move_to % strlen($scoreboard));
  } else {
    $elf = $move_to;
  }
}

$scoreboard = 37;

$elves = [
  0 => 0,
  1 => 1,
];

while (strlen($scoreboard) < $input + 10) {
  $new_recipe = substr($scoreboard, $elves[0], 1) + substr($scoreboard, $elves[1], 1);

  $scoreboard .= $new_recipe;

  foreach ($elves as &$elf) {
    move_elf($elf, $scoreboard);
  }
}

$last_10_scores = substr($scoreboard, -10, 10);

echo $last_10_scores;
