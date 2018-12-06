<?php
$input = <<<EOT
181, 47
337, 53
331, 40
137, 57
200, 96
351, 180
157, 332
113, 101
285, 55
189, 188
174, 254
339, 81
143, 61
131, 155
239, 334
357, 291
290, 89
164, 149
248, 73
311, 190
54, 217
285, 268
354, 113
318, 191
182, 230
156, 252
114, 232
159, 299
324, 280
152, 155
295, 293
194, 214
252, 345
233, 172
272, 311
230, 82
62, 160
275, 96
335, 215
185, 347
134, 272
58, 113
112, 155
220, 83
153, 244
279, 149
302, 167
185, 158
72, 91
264, 67
EOT;

$input_strings_array = explode(PHP_EOL, $input);

$coordinates = [];

$end_x = 0;
$end_y = 0;
$i = 0;

foreach($input_strings_array as $input_string) {
  $this_coordinates = explode(", ", $input_string);
  $end_x = $this_coordinates[0] > $end_x ? $this_coordinates[0] : $end_x;
  $end_y = $this_coordinates[1] > $end_y ? $this_coordinates[1] : $end_y;
  $coordinates[$this_coordinates[0]][$this_coordinates[1]] = 0;
}

$region_size = 0;
$x1 = 0;

while ($x1 <= $end_x) {
  $y1 = 0;
  while ($y1 <= $end_y) {
    $total_distance = 0;
    foreach ($coordinates as $x2 => $point_array) {
      foreach ($point_array as $y2 => $point) {
        $distance = abs($x2 - $x1) + abs($y2 - $y1);
        $total_distance += $distance;
      }
    }
    if ($total_distance < 10000) {
      $region_size++;
    }
    $y1++;
  }
  $x1++;
}

$largest_area = null;
foreach ($coordinates as $x) {
  foreach ($x as $y) {
    $largest_area = ($y > $largest_area || $largest_area === null) ? $y : $largest_area;
  }
}

echo $region_size;