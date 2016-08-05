(function($){
$(document).ready(function(){
  /*$('form').submit(function(e){
    e.preventDefault();
    var that = $(this);
    var messageBox = $('.alert');
    $('[type=submit]').attr('disabled', 'disabled');
    $.post(that.data('action'), that.serialize())
    .done(function(response){
      if(response.result){
        window.location.href = '<?= base_url('home')?>';
      }else{
        messageBox.removeClass('hidden').find('ul').html('<li>'+response.messages.join('</li><li>')+'</li>');
      }
    })
    .fail(function(){
      alert('An internal error has occured. Please try again in a few moments.');
    })
    .always(function(){
      $('[type=submit]').removeAttr('disabled');
      $('[type=password]').val('');
    });
  });*/
})
})(jQuery)