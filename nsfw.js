jQuery(document).ready(function($){
  $('#nsfw-toggle').on('click', function () {
    $('.entry-content img').toggleClass('nsfw-toggle-class');
  });
});