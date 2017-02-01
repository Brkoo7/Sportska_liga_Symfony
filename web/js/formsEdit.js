$( document ).ready(function() {
    $('#addCategory').hide();
    $('#addOneItem').click(function() {
        $('#message').text('Prvo završite sa ažuriranjem');
        $('.aside').css('border', '2px solid red');
        $('.aside').css('background-color', 'gray');
        $('.aside').css('padding', '20px');
        $('#addCategory').hide();
    });

    $('.js-datepicker').datepicker({  
        format: 'yyyy-mm-dd'
    });
});