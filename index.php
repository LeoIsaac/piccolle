<?php
require_once "scraping.php";
$url = isset($_GET['url']) ? $_GET['url'] : null;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>PicColle</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
  <!-- CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/3.3.7/paper/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
  <link rel="stylesheet" href="src/style.css">
  <!-- JS -->
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="src/jquery.lazyload.js"></script>
  <script src="src/script.js"></script>
</head>

<body>
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="true" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="./">ぴくこれ</a>
    </div><!-- /.navbar-header -->

    <div id="navbar" class="navbar-collapse collapse">
      <form class="navbar-form navbar-right" action="./">
        <div class="input-group">
          <input type="url" class="form-control" name="url" placeholder="URL" value="<?php echo $url; ?>" autocomplete="off">
          <span class="input-group-btn">
            <button type="submit" class="btn btn-default">収集</button>
          </span>
          <span id="count"></span>
        </div>
      </form>
    </div><!--/.nav-collapse -->
  </nav>

  <div class="container">
    <?php if( $url ):
    $scraping = new Scraping;
    $imgs = $scraping->collect($url, $page);
    if($imgs != null)
      foreach(array_slice($imgs, 0, count($imgs)-1) as $img) {
        echo "<img data-original='${img}' class='lazy thumbnail col-xs-12 col-sm-6 col-md-4'>";
      }
    else echo "NULLだよ〜";
    ?>
    <div class="paging">
      <nav aria-label="Page navigation">
        <ul class="pagination">
          <li class="<?php if($page == 1) echo 'disabled' ?>">
            <a href="<?php if($page != 1) echo $scraping->paging($url, $page-1); ?>" aria-label="Previous">
              <span aria-hidden="true">&laquo;</span>
            </a>
          </li>
          <?php $pages = ceil((int)$imgs[count($imgs)-1] / $scraping->limit);
          for($i = 1; $i <= $pages; $i++): ?>
          <li class="<?php if($i == $page) echo 'active'; ?>">
            <a href="<?php echo $scraping->paging($url, $i); ?>"><?php echo $i; ?></a>
          </li>
          <?php endfor; ?>
          <li class="<?php if($page == $pages) echo 'disabled'; ?>">
            <a href="<?php if($page != $pages) echo $scraping->paging($url, $page+1); ?>" aria-label="Next">
              <span aria-hidden="true">&raquo;</span>
            </a>
          </li>
        </ul>
      </nav>
    </div>

    <?php else: ?>
    <div class="jumbotron" id="about">
      <h3>About ぴくこれ</h3>
      <p>任意のウェブページ上の画像だけを取ってきて表示するウェブアプリです。使い方は簡単で、画像を取ってきたいURLを入力欄に入れて「Collection」をクリックするだけ！それだけで画像だけ取ってきて表示します。</p>
      <p>製作者: <a href="https://twitter.com/_leo_isaac" target="_blank">Isaac</a></p>
    </div><!-- /.jumbotron -->
    <?php endif; ?>

    <div id="detail" class="modal fade" tabindex="-1">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-body">
            <img class="img-responsive">
            <button type="button" id="favorite" class="btn btn-primary">お気に入り</button>
            <button type="button" id="hate" class="btn btn-warning">嫌い</button>
            <button type="button" id="nothing" class="btn btn-default" data-dismiss="modal">何もしない</button>
          </div><!-- /.mordal-body -->
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /#detail -->
  </div><!-- /.container -->
</body>
</html>
