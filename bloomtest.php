<?php

include("bloom.php");

$upperN = 300;
$upperK = 5;
$upperM = 6000;

$filter = new BloomFilter($upperM, $upperK);

for ($i = 0; $i < $upperN; $i++)
  $aryN[$i] = mt_rand(0,mt_getrandmax());

for ($i = 0; $i < $upperN; $i++)
{
  echo "num: ". $aryN[$i];

  $filter->add($aryN[$i]);

  echo "\n";
}

echo $filter->printFilter();

echo "\n";

echo "theoretical error rate: ". $filter->fpPercent() ."\n";
echo "error rate: ". $filter->getCollisionRate() ."\n";

# vim: set ai cin sw=2 expandtab:
