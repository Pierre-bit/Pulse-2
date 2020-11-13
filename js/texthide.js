var question = $('.Music'), reponse = $('.texthide');
$(question).each(function () {
    $(this).on('click', function () {
        $(this).next().slideToggle();
        $(reponse).not($(this).next()).hide();
    });
});