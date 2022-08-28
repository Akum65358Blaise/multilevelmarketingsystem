$('#carousel').on('slid.bs.carousel', function (e) {
  var active =$(this).find('.active').index();
  var to = $(e.relatedTarget).index();
  
  
  
    
  $('#thumbcarousel').find('.showing').removeClass('showing');
  $('#thumbcarousel').find('[data-slide-to='+active+']').addClass('showing');
  
});

$('#carousel').on('slide.bs.carousel', function (e) {
  var active =$(this).find('.active').index();
  var to = $(e.relatedTarget).index();
  
  if(active == 4 && to == 5) {
    $('#thumbcarousel').carousel('next');
  }
  if(active == 9 && to == 0) {
    $('#thumbcarousel').carousel('next');
  }
});
