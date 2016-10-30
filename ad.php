<?php

class Ad {
  private $adfile = "ad.txt";

  public function addAd() {
    if(!file_exists($this->adfile)) return;
    $line = file($this->adfile);
    if(count($line) == 0) return;
    $random = rand(0, count($line)-1);
    echo $line[$random];
  }
}

?>
