<?php

class Scraping {
  public $limit = 20;

  // スクレイピングして画像の配列を返す
  public function collect($url, $page) {
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
      if(preg_match("/^http.*\.(jpg|png)$/", $array[0])) {
        $pic++;
        if( $pic <= ($page-1) * $this->limit || $count >= $this->limit ) continue;
        $ret[] = $array[0];
        //$ret[] = $pic;
        $count++;
      }
    }
    $ret[] = $pic;
    return $ret;
  }

  public function paging($url, $page) {
    return "/?url=" . urlencode($url) . "&page=${page}";
  }
}

?>
