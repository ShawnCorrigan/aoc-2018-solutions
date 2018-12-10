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

function parse_input($input)
{
  $input_strings_array = explode(PHP_EOL, $input);

  $before_after_pairs = [];

  foreach ($input_strings_array as $input_string) {
    preg_match("/Step ([A-Z]) must be finished before step ([A-Z]) can begin\./", $input_string, $steps);

    $before_after_pairs[] = ['before'=>$steps[1], 'after' => $steps[2]];
  }

  return $before_after_pairs;
}

function time_to_place($letter) {
  return alphabet_position($letter) + 60;
}

function alphabet_position($letter) {
  return ord(strtoupper($letter)) - ord('A') + 1;
}

function find_first_letters($before_after_pairs) {
  $first_letter_options = [];

  foreach ($before_after_pairs as $pair) {
    $has_match = false;

    foreach ($before_after_pairs as $pair2) {
      if ($pair['before'] === $pair2['after']) {
        $has_match = true;
      }
    }

    if ($has_match === false) {
      $first_letter_options[] = $pair['before'];
    }
  }

  return $first_letter_options;
}

function find_available_letters($letters_array, $before_after_pairs, $used_letters) {
  $available_letter_options = [];

  foreach ($letters_array as $the_letter) {
    foreach ($before_after_pairs as $pair) {
      if ($pair['before'] === $the_letter) {
        if (!in_array($pair['after'], $used_letters) && letter_can_go($letters_array, $before_after_pairs, $pair['after'])) {
          $available_letter_options[] = $pair['after'];
        }
      }
    }
  }

  return $available_letter_options;
}

function find_available_workers($workers) {
  $available_workers = [];

  foreach ($workers as $key => $worker) {
    if (is_null($worker['letter'])) {
      $available_workers[] = $key;
    }
  }

  return $available_workers;
}
  
function increment_worker_time(&$workers) {
  $letters_ready = [];

  foreach ($workers as &$worker) {
    if ($worker['letter'] !== null) {
      $worker['seconds_left']--;
    }

    if ($worker['seconds_left'] === 0) {
      $letters_ready[] = $worker['letter'];
      
      $worker = [
        'letter'       => null,
        'seconds_left' => null,
      ];
    }
  }

  return $letters_ready;
}

$before_after_pairs = parse_input($input);

$letters_array = [];

$workers = [
  0 => [
    'letter'       => null,
    'seconds_left' => null,
  ],
  1 => [
    'letter'       => null,
    'seconds_left' => null,
  ],
  2 => [
    'letter'       => null,
    'seconds_left' => null,
  ],
  3 => [
    'letter'       => null,
    'seconds_left' => null,
  ],
  4 => [
    'letter'       => null,
    'seconds_left' => null,
  ],
];

$used_letters = [];

$available_letter_options = [];

$seconds = 0;

while (count($letters_array) < 26) {
  $letters_ready = increment_worker_time($workers);

  $available_workers = find_available_workers($workers);

  $log[$seconds]['letters_ready'] = $letters_ready;

  foreach ($letters_ready as $letter_ready) {
    $letters_array[] = $letter_ready;
  }

  if (count($available_workers) > 0) {

    if ($seconds === 0) {
      $new_options = find_first_letters($before_after_pairs);
    } else {
      $new_options = find_available_letters($letters_array, $before_after_pairs, $used_letters);
    }

    $available_letter_options = array_merge($available_letter_options, $new_options);

    $available_letter_options = array_unique($available_letter_options);

    sort($available_letter_options);

    foreach ($available_workers as $available_worker) {
      if (count($available_letter_options) < 1) {
        break;
      }

      $next_letter = array_shift($available_letter_options);
      
      $workers[$available_worker] = [
        'letter'       => $next_letter,
        'seconds_left' => time_to_place($next_letter)
      ];

      $used_letters[] = $next_letter;
    }
  }

  if (count($letters_array) < 26) {
    $seconds++;
  }
}

echo $seconds;
