<?php
$input = <<<EOT
Step A must be finished before step R can begin.
Step J must be finished before step B can begin.
Step D must be finished before step B can begin.
Step X must be finished before step Z can begin.
Step H must be finished before step M can begin.
Step B must be finished before step F can begin.
Step Q must be finished before step I can begin.
Step U must be finished before step O can begin.
Step T must be finished before step W can begin.
Step V must be finished before step S can begin.
Step N must be finished before step P can begin.
Step P must be finished before step O can begin.
Step E must be finished before step C can begin.
Step F must be finished before step O can begin.
Step G must be finished before step I can begin.
Step Y must be finished before step Z can begin.
Step M must be finished before step K can begin.
Step C must be finished before step W can begin.
Step L must be finished before step W can begin.
Step W must be finished before step S can begin.
Step Z must be finished before step O can begin.
Step K must be finished before step S can begin.
Step S must be finished before step R can begin.
Step R must be finished before step I can begin.
Step O must be finished before step I can begin.
Step A must be finished before step Q can begin.
Step Z must be finished before step R can begin.
Step T must be finished before step R can begin.
Step M must be finished before step O can begin.
Step Q must be finished before step Z can begin.
Step V must be finished before step C can begin.
Step Y must be finished before step W can begin.
Step N must be finished before step F can begin.
Step J must be finished before step D can begin.
Step D must be finished before step N can begin.
Step B must be finished before step M can begin.
Step P must be finished before step I can begin.
Step W must be finished before step Z can begin.
Step Q must be finished before step V can begin.
Step V must be finished before step K can begin.
Step B must be finished before step Z can begin.
Step M must be finished before step I can begin.
Step G must be finished before step C can begin.
Step K must be finished before step O can begin.
Step E must be finished before step O can begin.
Step C must be finished before step I can begin.
Step X must be finished before step G can begin.
Step B must be finished before step T can begin.
Step B must be finished before step I can begin.
Step E must be finished before step F can begin.
Step N must be finished before step K can begin.
Step D must be finished before step W can begin.
Step R must be finished before step O can begin.
Step V must be finished before step I can begin.
Step T must be finished before step O can begin.
Step B must be finished before step Q can begin.
Step T must be finished before step L can begin.
Step M must be finished before step C can begin.
Step A must be finished before step M can begin.
Step F must be finished before step L can begin.
Step X must be finished before step T can begin.
Step G must be finished before step K can begin.
Step C must be finished before step L can begin.
Step D must be finished before step Z can begin.
Step H must be finished before step L can begin.
Step P must be finished before step Z can begin.
Step A must be finished before step V can begin.
Step G must be finished before step R can begin.
Step E must be finished before step G can begin.
Step D must be finished before step P can begin.
Step X must be finished before step L can begin.
Step U must be finished before step C can begin.
Step Z must be finished before step K can begin.
Step E must be finished before step W can begin.
Step B must be finished before step Y can begin.
Step J must be finished before step I can begin.
Step U must be finished before step P can begin.
Step Y must be finished before step L can begin.
Step N must be finished before step L can begin.
Step L must be finished before step S can begin.
Step H must be finished before step P can begin.
Step P must be finished before step S can begin.
Step J must be finished before step S can begin.
Step J must be finished before step U can begin.
Step H must be finished before step T can begin.
Step L must be finished before step I can begin.
Step N must be finished before step Z can begin.
Step A must be finished before step G can begin.
Step H must be finished before step S can begin.
Step S must be finished before step I can begin.
Step H must be finished before step E can begin.
Step W must be finished before step R can begin.
Step B must be finished before step G can begin.
Step U must be finished before step Y can begin.
Step J must be finished before step G can begin.
Step M must be finished before step L can begin.
Step G must be finished before step Z can begin.
Step N must be finished before step W can begin.
Step D must be finished before step E can begin.
Step A must be finished before step W can begin.
Step G must be finished before step Y can begin.
EOT;

function letter_can_go($str_array, $pairs, $letter) {
  $can_go = true;

  foreach ($pairs as $pair) {
    if ($pair['after'] === $letter && !in_array($pair['before'], $str_array)) {
      $can_go = false;
    }
  }

  return $can_go;
}

function parse_input($input) {
  $input_strings_array = explode(PHP_EOL, $input);

  $before_after_pairs = [];

  foreach ($input_strings_array as $input_string) {
    preg_match("/Step ([A-Z]) must be finished before step ([A-Z]) can begin\./", $input_string, $steps);

    $before_after_pairs[] = ['before'=>$steps[1], 'after' => $steps[2]];
  }

  return $before_after_pairs;
}

function add_letter_to_array(&$available_letter_options, &$letters_array) {
  $available_letter_options = array_unique($available_letter_options);

  sort($available_letter_options);

  $the_letter = array_shift($available_letter_options);

  $letters_array[] = $the_letter;

  return $the_letter;
}

function find_available_letters($the_letter,  $letters_array, $before_after_pairs) {
  $available_letter_options = [];

  foreach ($before_after_pairs as $pair) {
    if ($pair['before'] === $the_letter) {
      if (letter_can_go($letters_array, $before_after_pairs, $pair['after'])) {
        $available_letter_options[] = $pair['after'];
      }
    }
  }

  return $available_letter_options;
}

$before_after_pairs = parse_input($input);

$letters_array = [];

$available_letter_options = [];

foreach ($before_after_pairs as $pair) {
  $has_match = false;

  foreach ($before_after_pairs as $pair2) {
    if ($pair['before'] === $pair2['after']) {
      $has_match = true;
    }
  }

  if ($has_match === false) {
    $available_letter_options[] = $pair['before'];
  }
}

$the_letter = add_letter_to_array($available_letter_options, $letters_array);

while (count($letters_array) < 26) {
  $new_options = find_available_letters($the_letter, $letters_array, $before_after_pairs);

  $available_letter_options = array_merge($new_options, $available_letter_options);

  $the_letter = add_letter_to_array($available_letter_options, $letters_array);
}

echo implode($letters_array);