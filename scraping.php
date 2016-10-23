<?php

class Scraping {
  public $limit = 50;
  public $url, $page, $pic, $count;

  function __construct($url, $page) {
    $this->url = $url;
    $this->page = $page;
    $this->pic = 0;
    $this->count = 0;
  }

  // スクレイピングして画像の配列を返す
  public function collect() {
    if($this->is2ch())
      return $this->type2ch();
    else
      return $this->typeNormal();
  }

  private function is2ch() {
    if(preg_match("/^https?:\/\/(www\.)?(.*\.)?(2ch.sc|bbspink.com)(.*)$/", $this->url))
      return true;
    else
      return false;
  }

  public function type2ch() {
    $xml = $this->xml($this->url);
    $imgs = $xml->xpath('//a');
    foreach($imgs as $img) {
      $array = (array)$img;
      $src = $array['@attributes']['href'];
      if(preg_match("/^https?:\/\/(www\.)?(2ch.io\/|pinktower.com\/\?https?:\/\/)?(.*\.(jpg|png))$/", $src, $matches)) {
        $this->pic++;
        if( $this->pic <= ($this->page-1) * $this->limit || $this->count >= $this->limit ) continue;
        $ret[] = "http://" . $matches[3];
        $this->count++;
      }
    }
    $ret[] = $this->pic;
    return $ret;
  }

  public function typeNormal() {
    $xml = $this->xml($this->url);
    $imgs = $xml->xpath('//img');
    foreach($imgs as $img) {
      $array = (array)$img;
      $src = $array['@attributes']['src'];
      if(preg_match("/^https?:\/\/(www\.)?.*\.(jpg|png)$/", $src)) {
        $this->pic++;
        if( $this->pic <= ($this->page-1) * $this->limit || $this->count >= $this->limit ) continue;
        $ret[] = $src;
        $this->count++;
      }
    }
    $ret[] = $this->pic;
    return $ret;
  }

  public function xml($url) {
    $html = file_get_contents($url);
    $dom = @DOMDocument::loadHTML($html);
    return simpleXML_import_dom($dom);
  }

  public function paging($url, $page, $is2ch) {
    return "?url=" . urlencode($url) . "&page=${page}&2ch=${is2ch}";
  }
}

?>
