<?php
$input = 3214;

$grid = [];

$y = 1;

while ($y < 301){
  $x = 1;

  $rack_id = $y + 10;

  while ($x < 301) {
    $power_level = $rack_id * $x;

    $power_level += $input;

    $power_level *= $rack_id;

    if ($power_level < 100) {
      $power_level = 0;
    } else {
      $power_level = substr($power_level, -3, 1);
    }

    $power_level -= 5;

    $grid[$y][$x] = $power_level;

    $x++;
  }

  $y++;
}

$strongest_power_level = [
  'cell' => [
    'x' => null,
    'y' => null,
  ],
  'level' => null,
];

$square_power_levels = [];

foreach ($grid as $y => $row) {
  if (in_array($y, [1,300])) {
    continue;
  }

  foreach ($row as $x => $power_level) {
    if (in_array($x, [1,300])) {
      continue;
    }

    $this_power_level = 0;

    $y_pointer = $y - 1;
    
    while ($y_pointer <= $y + 1) {
      $x_pointer = $x - 1;
    
      while ($x_pointer <= $x + 1) {
        $this_power_level += $grid[$y_pointer][$x_pointer];
        $x_pointer++;
      }
    
      $y_pointer++;
    }

    if ($this_power_level > $strongest_power_level['level'] || $strongest_power_level['level'] === null) {
      $strongest_power_level = [
        'cell'  => [
          'x' => $y - 1,
          'y' => $x - 1,
        ],
        'level' => $this_power_level,
      ];
    }
  }
}

echo $strongest_power_level['cell']['x'] . "," . $strongest_power_level['cell']['y'];
