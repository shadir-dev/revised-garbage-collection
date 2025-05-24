<?php
// Queue Simulation in PHP

$run = 10;
$mean = 10.0;
$sd = 1.5;
$mue = 9.5;
$sigma = 1.0;

$wt = 0.0;
$cit = 0.0;
$cat = 0.0;
$se = 0.0;
$cwt = 0.0;

echo str_pad("i", 5) .
     str_pad("IAT", 10) .
     str_pad("CAT", 10) .
     str_pad("SB", 10) .
     str_pad("ST", 10) .
     str_pad("SE", 10) .
     str_pad("WT", 10) .
     str_pad("IT", 10) . PHP_EOL;

for ($j = 1; $j <= $run; $j++) {
    // Generate inter-arrival time
    $sum = 0.0;
    for ($i = 0; $i < 12; $i++) {
        $sum += mt_rand() / mt_getrandmax();
    }
    $x1 = $mean + $sd * ($sum - 6.0);
    $iat = $x1;
    $cat += $iat;

    // Determine service begin time and waiting time
    if ($cat <= $se) {
        $sb = $se;
        $wt = $se - $cat;
        $cwt += $wt;
        $it = 0;
    } else {
        $sb = $cat;
        $it = $sb - $se;
        $cit += $it;
        $wt = 0;
    }

    // Generate service time
    $sum = 0.0;
    for ($i = 0; $i < 12; $i++) {
        $sum += mt_rand() / mt_getrandmax();
    }
    $x2 = $mue + $sigma * ($sum - 6.0);
    $st = $x2;
    $se = $sb + $st;

    // Output row
    echo str_pad($j, 5) .
         str_pad(number_format($iat, 2), 10) .
         str_pad(number_format($cat, 2), 10) .
         str_pad(number_format($sb, 2), 10) .
         str_pad(number_format($st, 2), 10) .
         str_pad(number_format($se, 2), 10) .
         str_pad(number_format($wt, 2), 10) .
         str_pad(number_format($it, 2), 10) . PHP_EOL;
}

$awt = $cwt / $run;
$pcu = (($cat - $cit) * 100.0) / $cat;

echo "\nAverage Waiting Time: " . number_format($awt, 2) . " minutes\n";
echo "Percentage Capacity Utilization: " . number_format($pcu, 2) . "%\n";
?>
