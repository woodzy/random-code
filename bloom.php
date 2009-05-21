<?php

class BloomFilter
{
  private $M;
  private $K;
  private $MLen;
  private $aryK;
  private $filter;
  private $fps;
  private $inserted;

  function __construct($bitLength, $hashFunctions)
  {
    $this->M = $bitLength;
    $this->K = $hashFunctions;
    $this->MLen = ceil($this->M/(PHP_INT_SIZE*8.0));

    $this->inserted = 0;

    $this->filter = array();
    $this->aryK = array();

    for ($k = 0; $k < $this->K; $k++)
    {
      $this->aryK[$k] = mt_rand(0,mt_getrandmax());;
    }
  }

  function add($item)
  {
    $type = gettype($item);

    if ($type == "integer")
    {
    }
    else if ($type == "string")
    {
      $item = abs(crc32($item));
    }
    else 
    {
      throw new Exception();
    }

    $collision = array();

    for ($j = 0; $j < $this->K; $j++)
    {
      $index = intval(bcmod(bcmul($this->aryK[$j],$item),$this->M));
      $fIndex = floor($index/(PHP_INT_SIZE*8));
      $val = ($this->filter[$fIndex] >> ($index % $this->M)) & 1;
      $collision[$j] = $val;
      echo " ". $index . ",";

      $this->filter[$fIndex] = 
        $this->filter[$fIndex] | (1 << ($index % $this->M));
    }

    $this->inserted++;

    for ($j = 0; $j < $this->K; $j++)
    {
      if (!$collision[$j])
        break;
    }

    if ($j >= $this->K)
    {
      $this->fps++;
      echo " -- collision found";
    }
  }

  function check($item)
  {
    $type = gettype($item);
    if ($type == "string")
    {
      $item = abs(crc32($item));
    }
    else if ($type != "integer")
    {
      throw new Exception();
    }

    $collision = array();

    for ($j = 0; $j < $this->K; $j++)
    {
      $index = intval(bcmod(bcmul($this->aryK[$j],$item),$this->M));
      $fIndex = floor($index/(PHP_INT_SIZE*8));
      $val = ($this->filter[$fIndex] >> ($index % $this->M)) & 1;
      $collision[$j] = $val;
    }

    for ($j = 0; $j < $this->K; $j++)
    {
      if (!$collision[$j])
        break;
    }

    if ($j >= $this->K)
    {
      return true;
    }
    else 
      return false;
  }

  function fpPercent()
  {
    $exp = (-1 * $this->K * $this->inserted) / ($this->M * 1.0);
    return pow(1 - exp($exp), $this->K);
  }

  function printFilter()
  {
    $str = '';
    for ($k = 0; $k < $this->MLen; $k++)
    {
      for ($i = 0; $i < PHP_INT_SIZE*8; $i++)
        $str .= ($this->filter[$k] >> $i) & 1;
    }

    return $str;
  }

  function getCollisionRate()
  {
    return $this->fps/($this->M * 1.0);
  }
}

# vim: set ai cin sw=2 expandtab:
