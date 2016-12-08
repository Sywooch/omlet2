$(document).ready(function(){
    $('#ingridient-add').on('click', function(){
        $(this).before(fetchAddBlock());

        setTimeout(function(){
            $('.ingridients-group').find('input').last().focus();
        }, 500);
    });

    $('body').on('focus', '.recipe-info', function(){
        $('.control-btn').attr('disabled','disabled');
        $(this).blur(function(){
            saveRecipeInfo()
        });
    });
});
function fetchAddBlock(dataId, value){
    if (typeof dataId == 'undefined')
        dataId = '';
    if (typeof value == 'undefined')
        value = '';

    var addBlock = '';
    addBlock += '<div class="row">';
    addBlock += '<div class="col-xs-10">';
    addBlock += '<input type="text" class="form-control recipe-info" data-id="' + dataId + '" placeholder="..." value="'+value+'">';
    addBlock += '</div>';
    addBlock += '<div class="col-xs-2">';
    addBlock += '<span onclick="removeIng($(this));" class="glyphicon glyphicon-remove ing-btn-remove" aria-hidden="true"></span>';
    addBlock += '</div>';
    addBlock += '</div>';

    return addBlock;

}

function removeIng(el){
    var id = el.parent().parent().find('input').data('id');
    $.ajax({
        type: 'POST',
        url: '/ajax/removeing',
        data: {'id' : id},
    });
    el.parent().parent().remove();
}


function showSave(){
    $('#showSave').show('fast', function(){
        $(this).animate({opacity:0}, 1000, function(){
            $(this).hide();
            $(this).css('opacity', '1');
        });
    });
}
function saveRecipeInfo(){
    if (window.updateProcessing === true) return false;

    window.updateProcessing = true;
    var recipeId = $('input[name="recipeId"]').val();
    var recipeName =  $('input[name="Recipe[name]"]').val();
    var recipeSection =  $('select[name="Recipe[section]"]').val();
    var recipeDesc =  $('textarea[name="Recipe[description]"]').val();
    var recipeCookTime = $('input[name="Recipe[cook_time]"]').val();

    var ingridients = [];
    $('.ingridients-group').find('input').each(function(){
        if ($(this).val() != '...') {
            var ing = {
                id : $(this).data('id') ? $(this).data('id').toString() : 'new',
                name : $(this).val()
            };
        }

        ingridients.push(ing);
    });

    var steps = [];
    var step = 1;
    $('textarea.step-description').each(function(){
        var id = $(this).data('id') ? $(this).data('id').toString() : 'new';
        steps.push({step: step, name: $(this).val(), id: id});
        step++;
    });

    var recipeInfo = {
        id: recipeId,
        name: recipeName,
        sectionId: recipeSection,
        recipeDesc: recipeDesc,
        cookTime: recipeCookTime,
        ing: ingridients,
        steps: steps
    }

    var success = false;
    $.ajax({
        type: 'POST',
        url: '/ajax/saverecipeinfo',
        data: {'recipeInfo' : JSON.stringify(recipeInfo)},
        success:function (response) {
            response = JSON.parse(response);
            if (response.error === false) {
                resetIngridients();
                updateIngridients(response.ingridients);
                updateSteps(response.steps);
                showSave();
            }
            $('.control-btn').removeAttr('disabled');
        }
    });
    setTimeout(function(){window.updateProcessing = false;}, 1000);

    return success;
}

function updateSteps(steps) {
    var newStep = $('textarea.step-description').last();
    var newItem = steps.pop();

    if (!newItem || typeof newStep.data('id') != 'undefined') return;

    var newBlock = '<textarea rows="11" data-id="'+newItem.id+'" class="step-description form-control recipe-info">'+newItem.instruction+'</textarea>';
    newStep.parent().html(newBlock);
}

function updateIngridients(ings){
    var newIngs = '';
    ings.forEach(function(item, i, arr){
        newIngs += fetchAddBlock(item.id, item.name);
    });
    newIngs += fetchAddBlock();

    $('.ingridients-group').find('div').remove();
    $('#ingridient-add').before(newIngs);
}

function resetIngridients(){
    $('.ingridients-group').find('input').each(function(){
        if ($(this).val() == '') {
            if (!$(this).parent().parent().next().is('button')) $(this).parent().parent().remove();
        }
    });
}
function goToUrl(el){
    window.location=window.ImageEditUrls[el.data('stepid')];
}

function removeStep(el){
    var id = el.next().data('id');

    $.ajax({
        type: 'POST',
        url: '/ajax/removestep',
        data: {'id' : id},
        success:function (response) {
            response = JSON.parse(response);
            resetSteps(response);
        }
    });
}

function resetSteps(steps){
    var actualStepsId = [];
    steps.forEach(function(item, i, arr){
        actualStepsId.push(parseInt(item.id));
    });
    $('textarea.step-description').each(function(){
        var id = $(this).data('id');
        if (id) {
            if (actualStepsId.indexOf(id) === -1) {
                $(this).parent().parent().remove();
                return;
            }

            window.ImageEditUrls[id] = steps[actualStepsId.indexOf(id)].url;
        } else {
            //$(this).data('id', )
        }

    });
}