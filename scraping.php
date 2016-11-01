<?php

class Scraping {
  public $limit = 50;
  private $url, $page, $pic, $count;

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

  // URLから2chかそうでないかを判別し、2chならtrue、それ以外ならfalseを返す
  private function is2ch() {
    if(preg_match("/^https?:\/\/(www\.)?(.*\.)?(2ch\.sc|bbspink\.com)(.*)$/", $this->url))
      return true;
    else
      return false;
  }

  // 2chだった場合、aタグの.(jpg|png)で終わっているもの全てを配列に入れて返す
  private function type2ch() {
    $xpath = $this->xpath($this->url);
    $imgs = $xpath->query('//a');
    foreach($imgs as $img) {
      $src = $img->textContent;
      if(preg_match("/\.(jpg|png)$/", $src)) {
        $this->pic++;
        if( $this->pic <= ($this->page-1) * $this->limit || $this->count >= $this->limit ) continue;
        $ret[] = $src;
        $this->count++;
      }
    }
    // ttpプロトコル対応
    $msgs = $xpath->query('//div[@class="message"]');
    foreach($msgs as $msg) {
      $lines = $msg->textContent;
      $line = explode(" ", $lines);
      foreach($line as $src) {
        if(preg_match("/^ttp.*\.(jpg|png)/", $src)) {
          $this->pic++;
          if( $this->pic <= ($this->page-1) * $this->limit || $this->count >= $this->limit ) continue;
          $ret[] = 'h' . $src;
          $this->count++;
        }
      }
    }
    $ret[] = $this->pic;
    return $ret;
  }

  // 2chでない場合、imgタグのsrcを配列に入れて返す
  private function typeNormal() {
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

  private function xpath($url) {
    $dom = new DOMDocument;
    @$dom->loadHTMLFile($url);
    return new DOMXPath($dom);
  }

  private function xml($url) {
    $html = file_get_contents($url);
    $dom = @DOMDocument::loadHTML($html);
    return simpleXML_import_dom($dom);
  }

  public function paging($url, $page) {
    return "?url=" . urlencode($url) . "&page=${page}";
  }
}

?>
