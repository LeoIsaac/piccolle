<?php

class Scraping {
  public $limit = 50;

  // スクレイピングして画像の配列を返す
  public function collect($url, $page) {
    $page = (int)$page;
    if($page <= 0) return false;
    $html = file_get_contents($url);
    $dom = @DOMDocument::loadHTML($html);
    $xml = simpleXML_import_dom($dom);
    $imgs = $xml->xpath('//img');
    $pic = 0;
    $count = 0;
    foreach($imgs as $img) {
      $array = (array)$img;
      $src = $array['@attributes']['src'];
      if(preg_match("/^https?:\/\/(www\.)?.*\.(jpg|png)$/", $src, $matches)) {
        $pic++;
        if( $pic <= ($page-1) * $this->limit || $count >= $this->limit ) continue;
        $ret[] = $matches[0];
        $count++;
      }
    }
    $ret[] = $pic;
    return $ret;
  }

  public function collect2ch($url, $page) {
    $page = (int)$page;
    if($page <= 0) return false;
    $html = file_get_contents($url);
    $dom = @DOMDocument::loadHTML($html);
    $xml = simpleXML_import_dom($dom);
    $imgs = $xml->xpath('//a');
    $pic = 0;
    $count = 0;
    foreach($imgs as $img) {
      $array = (array)$img;
      $src = $array['@attributes']['href'];
      if(preg_match("/^https?:\/\/(www\.)?(2ch.io\/|pinktower.com\/\?https?:\/\/)?(.*\.(jpg|png))$/", $src, $matches)) {
        $pic++;
        if( $pic <= ($page-1) * $this->limit || $count >= $this->limit ) continue;
        $ret[] = "http://" . $matches[3];
        $count++;
      }
    }
    $ret[] = $pic;
    return $ret;
  }

  public function paging($url, $page) {
    return "?url=" . urlencode($url) . "&page=${page}";
  }
}

?>
