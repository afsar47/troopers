function goBack() {
    window.history.back();
}
(function ($) {
    $(window).on("load", function () {

        $(".content-section").mCustomScrollbar({
            theme: "minimal"
        });

    });

})(jQuery);
$(function () {
    $('.prize_modal').click(function () {
        modal_header = $(this).prev().prev().val();
        modal_body = $(this).prev().val();
        $('#prizemodal p').html(modal_header);
        $('#prizemodal .modal-body').html(modal_body);
        $('#prizemodal').modal();

        //appending modal background inside the bigform-content
        $('.modal-backdrop').appendTo($('#upcoming'));
        //removing body classes to enable click events
        $('body').removeClass();
    });
});