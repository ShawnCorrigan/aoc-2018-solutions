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

$optimized_grid = [
  1 => array_merge([0],$grid[1])
];

foreach ($grid as $y => $row) {
  if ($y === 1) {
    continue;
  }

  foreach ($row as $x => $col) {
    $optimized_grid[$y][$x] = $col + $optimized_grid[$y - 1][$x];
  }
}

foreach ($optimized_grid as $y => $row) {
  $optimized_grid[$y][0] = 0;
}

unset($row);

foreach ($optimized_grid as $y => &$row) {
  foreach ($row as $x => &$col) {
    if ($x > 1) {
      $col += $optimized_grid[$y][$x - 1];
    }
  }
}

$strongest_power_level = [ 
  'x'     => 0,
  'y'     => 0,
  'size'  => 0,
  'total' => 0,
];

for ($s = 1; $s <= 300; $s++) {
  for ($y = 1; $y <= 300; $y++) {
    if ($y + $s - 1 > 300) {
      continue 2;
    }

    for ($x = 1; $x <= 300; $x++) {
      if ($x + $s - 1 > 300) {
        continue 2;
      }

      $corners = [
        'bottom_right' => $optimized_grid[$y + $s - 1][$x + $s - 1],
        'bottom_left'  => $x > 1 ? $optimized_grid[$y  + $s - 1][$x - 1] : 0,
        'top_left'     => $y > 1 ? $optimized_grid[$y - 1][$x - 1] : 0,
        'top_right'    => $y > 1 ? $optimized_grid[$y - 1][$x + $s - 1] : 0,
      ];

      $this_total = $corners['bottom_right'] - $corners['top_right'] - $corners['bottom_left'] + $corners['top_left'];

      if ($this_total > $strongest_power_level['total']) {
        $strongest_power_level = [
          'x'     => $x,
          'y'     => $y,
          'size'  => $s,
          'total' => $this_total,
        ];
      }
    }
  }
}

echo $strongest_power_level['x'] . "," . $strongest_power_level['y'] . "," . $strongest_power_level['size'];
