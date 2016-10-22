<?php

class Ad {
  private $adfile = "ad.txt";

  public function addAd() {
    $line = file($this->adfile);
    $random = rand(0, count($line));
    echo $line[$random];
  }
}

?>
