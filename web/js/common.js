function onRecipeHover(){
    $('.recipe-wrapper').mouseleave(function(){
        $(this).off(onRecipeHover);
    });

    $(this).find('.main-rec-content').animate({
        opacity: 0.2,
    },150);

    //ingridients
    var ings = $(this).find('.rec-ing');
    ings.css('display', 'block');
    ings.animate({
        bottom: '390px',
    },150);
}

$(document).ready(function(){
    $('.recipe-wrapper').hover(onRecipeHover);

    $('.recipe-wrapper').mouseleave(function(){
        $(this).find('.main-rec-content').animate({
            opacity: 1,
        },150);

        //ingridients
        var ings = $(this).find('.rec-ing');
        ings.animate({
            bottom: '0px',
        },150, function(){
            ings.css('display', 'none');
        });
    });
});
