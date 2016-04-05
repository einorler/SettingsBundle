$(document).ready(function(){
    $('#addSettingProfiles').multiselect();
    $('.boolean').on('click', function(){
        // ----- implement ajax request to change the setting
        if(!$(this).hasClass('btn-primary')) {
            $(this).toggleClass('btn-primary');
            $(this).siblings().toggleClass('btn-primary');
        }
    });
    $('.string').on('click', function(event){
        // ----- implement ajax request to change the setting
        event.preventDefault();
        var id = $(this).attr('rel');
        var old = $(this).text();
        var newContent = '<div class="input-group input-group-sm string-update">'
            +'<span class="input-group-btn">'
            +'<button class="btn btn-default string-update-save" >Save</button>'
            +'<button class="btn btn-default string-update-close" onclick="stringUpdateClose(this, \''+id+'\')">Close</button>'
            +'</span>'
            +'<input class="form-control" type="text" value="'+old+'">'
            +'</div>'
            +'</div>';
        $(this).parent().html(newContent);
    });
    $('.setting-type').on('click', function(){
        if(!$(this).hasClass('btn-primary')) {
            $(this).toggleClass('btn-primary');
            $(this).siblings().removeClass('btn-primary');
            $relElement = $('.'+$(this).attr('rel'));
            $relElement.toggleClass('hidden');
            $relElement.siblings().addClass('hidden');
        }
    });
    $('.setting-type-boolean').on('click', function(){
        if(!$(this).hasClass('btn-primary')) {
            $(this).toggleClass('btn-primary');
            $(this).siblings('label').removeClass('btn-primary');
            if($(this).attr('rel') == 'true') {
                $(this).siblings('input').prop('checked', true);
            } else {
                $(this).siblings('input').prop('checked', false);
            }
        }
    });
    $('.settings-array-add').on('click', function(event){
        event.preventDefault();
        var key = parseInt($('#addSettingArray').attr('rel'))+1;
        $('#addSettingArray').attr('rel', key);
        var render = '<li>'
            +'<div class="input-group">'
            +'<input type="text" class="form-control" name="setting-array_'+key+'">'
            +'<span class="input-group-btn">'
            +'<button class="btn btn-danger" type="button" onclick="addArrayRemoveInput(this)"><i class="glyphicon glyphicon-remove"></i></button>'
            +'</span>'
            +'</div>'
            +'</li>';
        $('#addSettingArray').append(render);
    });
});
function stringUpdateClose(el, id){
    $container = $('.settings-container');
    var text = jQuery(el).parent().next().val()
    var newContent = '<a href="#" class="string" rel="'+id+'" onclick="stringUpdateChange(this, event)">'+text+'</a>';
    jQuery(el).parent().parent().html(newContent);
};
function stringUpdateChange(el, event){
    event.preventDefault();
    var id = jQuery(el).attr('rel');
    var old = jQuery(el).text();
    var newContent = '<div class="input-group input-group-sm string-update">'
        +'<span class="input-group-btn">'
        +'<button class="btn btn-default string-update-save" >Save</button>'
        +'<button class="btn btn-default string-update-close" onclick="stringUpdateClose(this, \''+id+'\')">Close</button>'
        +'</span>'
        +'<input class="form-control" type="text" value="'+old+'">'
        +'</div>'
        +'</div>';
    jQuery(el).parent().html(newContent);
}
function arrayHandleList(el, event, data){
    event.preventDefault();
    var $el = jQuery(el);
    var render = '';
    var settings = jQuery.parseJSON(data);
    if($el.html() == 'more'){
        settings.forEach(function(val){
            render = render + '<li>' + val + '</li>';
        });
        data = data.replace(/(["])/g, '&#34');
        render = render + '<li><a href="#" onclick="arrayHandleList(this, event, \''+data+'\')">less</a></li>';
        $el.parent().parent().html(render);
    }else if($el.html() == 'less'){
        render = render + '<li>' + settings[0] + '</li>';
        render = render + '<li>' + settings[1] + '</li>';
        render = render + '<li>' + settings[2] + '</li>';
        data = data.replace(/(["])/g, '&#34');
        render = render + '<li><a href="#" onclick="arrayHandleList(this, event, \''+data+'\')">more</a></li>';
        $el.parent().parent().html(render);
    }
}
function addArrayRemoveInput(el){
    jQuery(el).parent().parent().parent().empty();
}
function addSetting(e){
    e.preventDefault();
    $('#addSettingMessages').find('.alert').addClass('hidden');
    var url = $('#addSettingForm').attr('url');
    var name = $('#addSettingName').val();
    var profiles = $('#addSettingProfiles').val();
    var description = $('#addSettingDescription').val();
    var type = $('#addSettingType').find('.btn-primary').text();
    var value;
    var errorMessage = '';

    switch(type) {
        case 'Boolean':
            $('#addSettingBoolean').is(':checked') ? value = 'true' : value = 'false';
            break;
        case 'Default':
            value = $('#addSettingDefault').val();
            break;
        case 'Array':
            value = [];
            $('#addSettingArray').find('input').each(function () {
                value.push(this.value);
            });
            break;
        case 'Object':
            value = $('#addSettingObject').val();
            break;
    }
    if(name == '') {
        errorMessage = 'Setting name value must be set. ';
    }
    if(profiles == null) {
        errorMessage = errorMessage + 'At least one profile has to be set. ';
    }
    if(value == '' ) {
        errorMessage = errorMessage + 'You have to set the value of the setting';
    }
    renderError('#addSettingMessages', errorMessage);
    var data = {
        setting_name: name,
        setting_profiles: profiles,
        setting_type: type,
        setting_description: description,
        setting_value: value
    };
    var requestData = {data: JSON.stringify(data)};
    //data = JSON.stringify(data);
    //alert(data);
    $.ajax({
        url: '/settings/setting/set?0=hjkfb&1=kjbf',
        type: "POST",
        data: requestData,
        success: function (data) {
            data = JSON.parse(data);
            renderError('#addSettingMessages', data.error);
            if(data.code == 201) {
                $('#addSettingMessages').find('.alert-success').toggleClass('hidden');
            }
            $('#addSettingForm').find('input').val('');
            $('#addSettingForm').find('textarea').val('');
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert('Užlausa nepavyko : ' + errorThrown);
        }
    });
}
function renderError(blockId, errorMessage) {
    if(errorMessage != '') {
        $(blockId).find('.error-message').text(errorMessage);
        $(blockId).find('.alert-danger').toggleClass('hidden');
        return
    }
}