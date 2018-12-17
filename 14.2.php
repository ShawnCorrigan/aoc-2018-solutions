<?php
$input = '846021';

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

$input_length = strlen($input);

$last_six_recipes = '';

while (true) {
  $new_recipe = substr($scoreboard, $elves[0], 1) + substr($scoreboard, $elves[1], 1);
  
  $next_letter = substr($new_recipe, 0, 1);

  $last_six_recipes = substr($last_six_recipes . $next_letter, 0 - $input_length, $input_length);

  $scoreboard .= $next_letter;

  if ($input === $last_six_recipes) {
    break;
  }

  if ($new_recipe > 9) {
    $next_letter = substr($new_recipe, 1, 1);

    $last_six_recipes = substr($last_six_recipes . $next_letter, 0 - $input_length, $input_length);

    $scoreboard .= $next_letter;

    if ($input === $last_six_recipes) {
      break;
    }
  }


  foreach ($elves as &$elf) {
    move_elf($elf, $scoreboard);
  }
}

echo strlen($scoreboard) - $input_length;