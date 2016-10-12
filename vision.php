<?php

require_once "config.php";

$key = Config::$key;

header("Content-type: application/json; charset=utf-8");
$img = $_POST['img'] ?: $argv[1];
$res = isSafe($img, $key);
$data = json_encode(array(
  "flag" => $res,
  "img" => $img
));
echo $data;

//ヤバければfalse, ヤバくなければtrue
function isSafe($img, $key) {
  $json = json_encode( array(
    "requests" => array(
      array(
        "image" => array(
          "content" => base64_encode( file_get_contents($img) )
        ),
        "features" => array(
          /*
          array(
            "type" => "LABEL_DETECTION",
            "maxResults" => 3
          ),
          */
          array(
            "type" => "SAFE_SEARCH_DETECTION",
            "maxResults" => 3
          )
        )
      )
    )
  ));
  $endpoint = "https://vision.googleapis.com/v1/images:annotate?key=" . $key;
  $resJson = sender($endpoint, $json);
  $response = json_decode($resJson, true);
  $safeSearchAnnotation = $response["responses"][0]["safeSearchAnnotation"];
  $medical = $safeSearchAnnotation["medical"];
  $violence = $safeSearchAnnotation["violence"];
  $bad = ["VERY_LIKELY", "LIKELY"];
  return !(in_array($medical, $bad) || in_array($violence, $bad));
}


function sender($endpoint, $json) {
  $curl = curl_init();
  curl_setopt( $curl, CURLOPT_URL, $endpoint );
  curl_setopt( $curl, CURLOPT_HEADER, true );
  curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, "POST" );
  curl_setopt( $curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json") );
  curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
  curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
  curl_setopt( $curl, CURLOPT_TIMEOUT, 15 );
  curl_setopt( $curl, CURLOPT_POSTFIELDS, $json );
  $res1 = curl_exec($curl);
  $res2 = curl_getinfo($curl);
  curl_close($curl);
  return substr($res1, $res2["header_size"]);
}

?>
