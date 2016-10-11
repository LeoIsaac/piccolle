$(function() {
  $('img.lazy').lazyload({
    threshold: 1000
  });

  //Modal
  $('img.lazy').click(function() {
    $('#detail img').attr('src', $(this).attr('data-original'));
    $('#detail').modal();
  });
  $('#favorite').click(function() {
    console.log("Fav: " + $('#detail img').attr('src'));
  });
  $('#hate').click(function() {
    console.log("Hate: " + $('#detail img').attr('src'));
  });
});
