<?php

$aryN = array();
$aryK = array();

function is_prime($i)
{
    if($i % 2 != 1) return false;
    $d = 3;
    $x = sqrt($i);
   while ($i % $d != 0 && $d < $x) $d += 2;
   return (($i % $d == 0 && $i != $d) * 1) == 0 ? true : false;
}

$upperN = 5;
$upperK = 3;

for ($i = 0; $i < $upperN; $i++)
  $aryN[$i] = mt_rand(0,mt_getrandmax());

for ($k = 20; $k < 100000; $k++)
{
  if (is_prime($k))
  {
    $aryK[$j] = $k;
    $j++;
    if ($j > $upperK)
      break;
  }
}

for ($i = 0; $i < $upperN; $i++)
{
  echo "num: ". $aryN[$i];
  for ($j = 0; $j < $upperK; $j++)
  {
    echo " ". bcmod(bcmul($aryK[$j],$aryN[$i]),32) . ",";
  }
  echo "\n";
}
