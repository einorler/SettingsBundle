$(document).ready(function(){function e(e,t){var a="";t&&(a='checked="checked"');var n='<label class="profile-choice"><input type="checkbox" '+a+' name="setting[profile][]" value="'+e+'">'+e+"</label>";$("#profiles-container .checkbox").append(n)}function t(t){$("#profiles-loader").show(),$("#profiles-container .checkbox").html(""),$.post(Routing.generate("ongr_settings_profiles_get_all"),function(a){$("#profiles-loader").hide(),a.forEach(function(a){$.inArray(a,t)>-1?e(a,!0):e(a,!1)})})}function a(e){var t=$("#experiment-form"),a="";t.find(".target-div").remove(),$.post(Routing.generate("ongr_settings_experiments_get_targets"),function(o){for(var r in o){a+='<div class="form-group target-div"><label class="col-sm-2 control-label" for="experiment_name">'+r+'</label><div class="col-sm-8"><div id="'+r+'-container" class="pb-5"><div class="checkbox"><table class="table"><tbody>';var i=0;a+="<tr>";for(var l in o[r])a+=n(o[r][l],r,e),2==i++&&(i=0,a+="</tr><tr>");a+="</tr>",a+="</tbody></table></div></div></div></div>"}t.append(a)})}function n(e,t,a){var n="";return a&&(n='checked="checked"'),'<td style="border: 0;"><label class="profile-choice"><input type="checkbox" '+n+' name="experiment['+t+'][]" value="'+e+'">'+e+"</label></td>"}var o=$("#settings").DataTable({ajax:{url:Routing.generate("ongr_settings_search_page"),dataSrc:"documents"},stateSave:!0,columns:[{data:"name"},{data:"value"},{data:"description"},{data:"profile"},{}],columnDefs:[{targets:1,render:function(e,t,a){if("bool"==a.type){var n=$("<label/>").addClass("boolean-property btn btn-default").addClass("boolean-property-"+a.id).attr("data-name",a.name),o=n.clone().html("ON").attr("data-element","boolean-property-"+a.id).attr("data-value",1),r=n.clone().html("OFF").attr("data-element","boolean-property-"+a.id).attr("data-value",0);1==a.value?o.addClass("btn-primary"):r.addClass("btn-primary");var i=$("<div/>").addClass("btn-group btn-group-sm").append(o,r);return i.prop("outerHTML")}return e}},{targets:3,orderable:!1},{targets:4,data:null,orderable:!1,render:function(e,t,a){return'<a class="edit btn btn-primary btn-xs" data-toggle="modal" data-target="#setting-edit">Edit</a>&nbsp;<a class="delete delete-setting btn btn-danger btn-xs" data-name="'+a.name+'">Delete</a>'}}]}),r=$("<button/>").html("Add new setting").addClass("btn btn-success btn-sm").attr({id:"new-setting-button"});$("#settings_filter").append(r.prop("outerHTML")),$("#new-setting-button").on("click",function(){$("#profiles-loader").show(),$(".profile-choice").remove(),$("#setting-action-title").text("New setting"),$("#setting-form-modal").modal(),t()}),$("#select-all-profiles").on("click",function(){$('#profiles-container .checkbox input[type="checkbox"]').prop("checked",!0)}),$("#add-new-profile").on("click",function(){e($("#add-new-profile-input").val()),$("#add-new-profile-input").val("")}),$("#add-new-profile-show-form").on("click",function(){$(this).hide(),$("#add-new-profile-container").show(),$("#add-new-profile-input").focus()}),$(".bool-value-input").on("click",function(){$(".bool-value-input").removeClass("btn-primary"),$(this).addClass("btn-primary"),$("#bool-value-input").val($(this).data("value"))}),$("#setting-value-tabs").on("shown.bs.tab",function(e){var t=$(e.target).data("value");$("#setting-type-input").val(t)}),$("#setting-form-modal").on("hide.bs.modal",function(){$("#setting-form-error").hide(),$("#setting-name-input").val(""),$("#setting-description-input").val(""),$("#setting-value-input").val(""),$("#force-update").val("0"),$(".bool-value-input").removeClass("btn-primary"),$(".bool-value-input-0").addClass("btn-primary"),$("#bool-value-input").val("0"),$("#string-value-input").val(""),$("#yaml-value-input").val(""),$("#setting-name-input").removeAttr("disable"),$("input:checkbox").removeAttr("checked")}),$("#setting-form-submit").on("click",function(e){e.preventDefault(),$("#setting-value-input").val($("#"+$("#setting-type-input").val()+"-value-input").val());var t=$("#setting-form").serializeArray();$.ajax({url:Routing.generate("ongr_settings_setting_submit"),data:t,success:function(e){0==e.error?(o.ajax.reload(),$("#setting-form-modal").modal("hide")):($("#setting-form-error-message").html(e.message),$("#setting-form-error").show())}})}),$("#settings tbody").on("click","a.edit",function(){var e=o.row($(this).parents("tr")).data();switch(t(e.profile),$("#setting-action-title").text("Setting edit"),$("#force-update").val("1"),$("#setting-name-input").val(e.name),$("#setting-name-input").attr("disable","disable"),$("#setting-name").val(e.name),$("#setting-description-input").val(e.description),$("#setting-value-input").val(e.value),$("#setting-type-input").val(e.type),$('#setting-value-tabs a[href="#'+e.type+'-value"]').tab("show"),$("#"+e.type+"-value-input").val(e.value),e.type){case"yaml":case"string":break;case"bool":$(".bool-value-input").removeClass("btn-primary"),$(".bool-value-input-"+e.value).addClass("btn-primary")}$("#setting-form-modal").modal()}),$("#settings tbody").on("click","label.boolean-property",function(){var e=$(this);$.post(Routing.generate("ongr_settings_settings_update_value"),{name:e.data("name"),value:e.data("value")},function(){var t=e.data("element");$("."+t).toggleClass("btn-primary")})}),$("#settings tbody").on("click","a.delete-setting",function(e){e.preventDefault();var t=$(this).data("name");$.confirm({text:"Are you sure you want to delete setting?",title:"Confirmation required",confirm:function(e){$.post(Routing.generate("ongr_settings_settings_delete"),{name:t},function(e){0==e.error&&o.ajax.reload()})},confirmButton:"Yes, delete it",cancelButton:"No",confirmButtonClass:"btn-danger",dialogClass:"modal-dialog modal-lg"})});var i=$("#profiles").DataTable({ajax:{url:Routing.generate("ongr_settings_profiles_get_all_detailed"),dataSrc:"documents"},stateSave:!0,order:[[1,"asc"]],columns:[{data:"name"},{data:"name"},{data:"settings"},{}],columnDefs:[{targets:0,orderable:!1,render:function(e,t,a){var n="toggle-profile",o=$("<label/>").addClass("btn btn-default").addClass(n).addClass(n+"-"+a.name).attr("data-name",a.name),r=o.clone().html("ON").attr("data-element",n+"-"+a.name),i=o.clone().html("OFF").attr("data-element",n+"-"+a.name);1==a.active?r.addClass("btn-primary"):i.addClass("btn-primary");var l=$("<div/>").addClass("btn-group btn-group-sm").append(r,i);return l.prop("outerHTML")}},{targets:2,orderable:!1},{targets:3,data:null,orderable:!1,defaultContent:'<a class="copy-link btn btn-primary btn-xs" data-toggle="modal">Copy link</a>&nbsp;'}]});$("#profiles tbody").on("click","label.toggle-profile",function(){var e=$(this);$.post(Routing.generate("ongr_settings_profiles_toggle"),{name:e.data("name")},function(){$(".toggle-profile-"+e.data("name")).toggleClass("btn-primary")})}),$("#profiles tbody").on("click","a.copy-link",function(e){e.preventDefault();var t=i.row($(this).parents("tr")).data(),a=Routing.generate("ongr_settings_enable_profile",{key:t.name},!0);$("#copy-placeholder").text(a);var n=document.createRange(),o=document.querySelector("#copy-placeholder");n.selectNode(o),window.getSelection().addRange(n);try{var r=document.execCommand("copy");if(!r)throw new Error("Cannot copy");noty({text:"Link successfully copied to the clipboard.",type:"success",layout:"topRight",theme:"bootstrapTheme",timeout:10,animation:{open:"animated fadeIn",close:"animated fadeOut"}})}catch(e){noty({text:"Something went wrong..",type:"error",layout:"topRight",theme:"bootstrapTheme",timeout:10,animation:{open:"animated fadeIn",close:"animated fadeOut"}}),$.alert({title:"Here's the link:",content:"<span>"+a+"</span>",confirmButton:"Close"})}});var l=($("#experiments").DataTable({ajax:{url:Routing.generate("ongr_settings_experiments_get_all_detailed"),dataSrc:"documents"},stateSave:!0,order:[[1,"asc"]],columns:[{data:"name"},{data:"name"},{data:"client"},{data:"profile"},{}],columnDefs:[{targets:0,orderable:!1,render:function(e,t,a){var n="toggle-profile",o=$("<label/>").addClass("btn btn-default").addClass(n).addClass(n+"-"+a.name).attr("data-name",a.name),r=o.clone().html("ON").attr("data-element",n+"-"+a.name),i=o.clone().html("OFF").attr("data-element",n+"-"+a.name);1==a.active?r.addClass("btn-primary"):i.addClass("btn-primary");var l=$("<div/>").addClass("btn-group btn-group-sm").append(r,i);return l.prop("outerHTML")}},{targets:2,orderable:!1,render:function(e,t,a){var n="";return"undefined"!=typeof a.os&&(n+="os = "+JSON.stringify(a.os)+"; "),"undefined"!=typeof a.device&&(n+="device = "+JSON.stringify(a.device)+"; "),"undefined"!=typeof a.client&&(n+="client = "+JSON.stringify(a.client)+"; "),n}},{targets:3,orderable:!1},{targets:4,data:null,orderable:!1,render:function(e,t,a){return'<a class="edit btn btn-primary btn-xs" data-toggle="modal" data-target="#experiment-edit">Edit</a>&nbsp;<a class="delete delete-setting btn btn-danger btn-xs" data-name="'+a.name+'">Delete</a>'}}]}),$("<button/>").html("Add new experiment").addClass("btn btn-success btn-sm").attr({id:"new-experiment-button"}));$("#experiments_filter").append(l.prop("outerHTML")),$("#new-experiment-button").on("click",function(){$("#experiment-action-title").text("New experiment"),$("#experiment-form-modal").modal(),t(),a()})});