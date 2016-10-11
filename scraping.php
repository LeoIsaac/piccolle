<?php

class Scraping {
  // スクレイピングして画像の配列を返す
  public function collect($url) {
    $html = file_get_contents($url);
    $dom = @DOMDocument::loadHTML($html);
    $xml = simpleXML_import_dom($dom);
    $imgs = $xml->xpath('//a');
    foreach($imgs as $img) {
      $array = (array)$img;
      if(preg_match("/^http.*\.(jpg|png)$/", $array[0]))
        $ret[] = $array[0];
    }
    return $ret;
  }
}

?>
