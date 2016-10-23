var storageFlag = false;
var fav = [];
var hate = [];
var kari = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC";
init();

$(function() {
  $('img.lazy').lazyload({
    threshold: 1000
  });

  //Modal
  $('img.lazy').click(function() {
    src = $(this).attr('data-original');
    $('#detail img').attr('src', src);
    // srotageが使用できない場合、ボタンを使用不可にする
    if(!storageFlag) {
      $('#favorite').prop('disabled', true);
      $('#hate').prop('disabled', true);
    }
    $('#detail').modal();
  });
  $('#favorite').click(function() {
    console.log("Fav: " + src);
    localStorage[src] = "fav";
  });
  $('#hate').click(function() {
    console.log("Hate: " + src);
    localStorage[src] = "hate";
    var selector = "img[src = '" + src + "']";
    $(selector).attr('src', kari);
    $('#detail').modal('hide');
  });
});


function init() {
  // Check
  if(('localStorage' in window) && (window.localStorage !== null)) {
    for(var key in localStorage) {
      var val = localStorage.getItem(key);
      if(val == "fav") fav.push(key);
      else hate.push(key);
    }
    storageFlag = true;
  } else {
    alert("このブラウザはHTML5 WebStorageApiが使用できないため、お気に入り保存機能が使えません。");
    storageFlag = false;
  }
}
