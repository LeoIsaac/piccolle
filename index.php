<?php
require_once "scraping.php";
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>jsTest</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
  <!-- CSS -->
  <link rel="stylesheet" href="src/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="src/bootstrap/font-awesome.min.css">
  <link rel="stylesheet" href="src/style.css">
  <!-- JS -->
  <script src="src/bootstrap/jquery-3.1.0.slim.min.js"></script>
  <script src="src/bootstrap/bootstrap.min.js"></script>
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
					<input type="url" class="form-control" name="url" placeholder="URL" value="<?php echo $_GET['url']; ?>" autocomplete="off">
					<span class="input-group-btn">
						<button type="submit" class="btn btn-default">収集</button>
					</span>
					<span id="count"></span>
				</div>
			</form>
		</div><!--/.nav-collapse -->
	</nav>

  <div class="container">
    <?php $url = $_GET['url'] ?: null;
    if( $url ): ?>
    <?php
    foreach(Scraping::collect($url) as $img) {
      echo "<img data-original='${img}' class='lazy thumbnail col-md-6 col-xs-12'>";
    }
    ?>

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
