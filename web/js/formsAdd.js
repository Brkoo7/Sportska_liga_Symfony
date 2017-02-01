$( document ).ready(function() {
    $('#addOneItem').click(function() {
        $('#addCategory').toggle();
    });
    $('.close').click(function() {
        $('#addCategory').hide();
    });
    /**
     * Ukoliko validacija ne prolazi ostani na prikazu forme
     */
    if ($('form>div>ul').children().length > 0) {
        $('#addCategory').show();   
    }
    $('.js-datepicker').datepicker({  
        format: 'yyyy-mm-dd'
    });
});
