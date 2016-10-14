$(document).ready(function(){function e(e,t,a){var n="";a&&(n='checked="checked"');var r='<label class="profile-choice"><input type="checkbox" '+n+' name="'+t+'[profile][]" value="'+e+'">'+e+"</label>";$("#profiles-container .checkbox").append(r)}function t(t,a){"experiment"!==a&&(a="setting"),$("#profiles-loader").show(),$("#profiles-container .checkbox").html(""),$.post(Routing.generate("ongr_settings_profiles_get_all"),function(n){$("#profiles-loader").hide(),n.forEach(function(n){$.inArray(n,t)>-1?e(n,a,!0):e(n,a,!1)})})}function a(e){var t=$("#experiment-form");$.post(Routing.generate("ongr_settings_experiments_get_targets"),function(a){for(var o in a){var i=t.find("#"+o+"-container").find(".checkbox");i.html("");var l=$('<select id="multiselect-'+o+'" multiple="multiple" class="hidden" name="setting[value]['+o+'][names][]"></select>');for(var s in a[o])l=r(l,a[o][s],o,e);i.append(l),$("#multiselect-"+o).multiselect({enableFiltering:!0});var d=$('<span class="btn btn-success target-attribute-toggle" id="target-attribute-toggle-'+o+'" style="margin-left: 10px;">More details</span>');i.append(d),$("#target-attribute-toggle-"+o).on("click",$.proxy(n,null,o))}})}function n(e){var t=$("#target-attribute-toggle-"+e);if("More details"==t.text()){t.text("Less details");var a=[];$("#multiselect-"+e+" :selected").each(function(e,t){a.push($(t).text())})}else t.text("More details")}function r(e,t,a,n){var r="";return null!=n&&n.indexOf(t)!==-1&&(r='selected="true"'),e.append("<option "+r+' value="'+t+'">'+t+"</option>"),e}var o=$("#settings").DataTable({ajax:{url:Routing.generate("ongr_settings_search_page"),dataSrc:"documents"},stateSave:!0,columns:[{data:"name"},{data:"value"},{data:"description"},{data:"profile"},{}],columnDefs:[{targets:1,render:function(e,t,a){if("bool"==a.type){var n=$("<label/>").addClass("boolean-property btn btn-default").addClass("boolean-property-"+a.id).attr("data-name",a.name),r=n.clone().html("ON").attr("data-element","boolean-property-"+a.id).attr("data-value",1),o=n.clone().html("OFF").attr("data-element","boolean-property-"+a.id).attr("data-value",0);1==a.value?r.addClass("btn-primary"):o.addClass("btn-primary");var i=$("<div/>").addClass("btn-group btn-group-sm").append(r,o);return i.prop("outerHTML")}return e}},{targets:3,orderable:!1},{targets:4,data:null,orderable:!1,render:function(e,t,a){return'<a class="edit btn btn-primary btn-xs" data-toggle="modal" data-target="#setting-edit">Edit</a>&nbsp;<a class="delete delete-setting btn btn-danger btn-xs" data-name="'+a.name+'">Delete</a>'}}]}),i=$("<button/>").html("Add new setting").addClass("btn btn-success btn-sm").attr({id:"new-setting-button"});$("#settings_filter").append(i.prop("outerHTML")),$("#new-setting-button").on("click",function(){$("#profiles-loader").show(),$(".profile-choice").remove(),$("#setting-action-title").text("New setting"),$("#setting-form-modal").modal(),t()}),$("#select-all-profiles").on("click",function(){$('#profiles-container .checkbox input[type="checkbox"]').prop("checked",!0)}),$("#add-new-profile").on("click",function(){e($("#add-new-profile-input").val()),$("#add-new-profile-input").val("")}),$("#add-new-profile-show-form").on("click",function(){$(this).hide(),$("#add-new-profile-container").show(),$("#add-new-profile-input").focus()}),$(".bool-value-input").on("click",function(){$(".bool-value-input").removeClass("btn-primary"),$(this).addClass("btn-primary"),$("#bool-value-input").val($(this).data("value"))}),$("#setting-value-tabs").on("shown.bs.tab",function(e){var t=$(e.target).data("value");$("#setting-type-input").val(t)}),$("#setting-form-modal").on("hide.bs.modal",function(){$("#setting-form-error").hide(),$("#setting-name-input").val(""),$("#setting-description-input").val(""),$("#setting-value-input").val(""),$("#force-update").val("0"),$(".bool-value-input").removeClass("btn-primary"),$(".bool-value-input-0").addClass("btn-primary"),$("#bool-value-input").val("0"),$("#string-value-input").val(""),$("#yaml-value-input").val(""),$("#setting-name-input").removeAttr("disable"),$("input:checkbox").removeAttr("checked")}),$("#setting-form-submit").on("click",function(e){e.preventDefault(),$("#setting-value-input").val($("#"+$("#setting-type-input").val()+"-value-input").val());var t=$("#setting-form").serializeArray();$.ajax({url:Routing.generate("ongr_settings_setting_submit"),data:t,success:function(e){0==e.error?(o.ajax.reload(),$("#setting-form-modal").modal("hide")):($("#setting-form-error-message").html(e.message),$("#setting-form-error").show())}})}),$("#settings tbody").on("click","a.edit",function(){var e=o.row($(this).parents("tr")).data();switch(t(e.profile),$("#setting-action-title").text("Setting edit"),$("#force-update").val("1"),$("#setting-name-input").val(e.name),$("#setting-name-input").attr("disable","disable"),$("#setting-name").val(e.name),$("#setting-description-input").val(e.description),$("#setting-value-input").val(e.value),$("#setting-type-input").val(e.type),$('#setting-value-tabs a[href="#'+e.type+'-value"]').tab("show"),$("#"+e.type+"-value-input").val(e.value),e.type){case"yaml":case"string":break;case"bool":$(".bool-value-input").removeClass("btn-primary"),$(".bool-value-input-"+e.value).addClass("btn-primary")}$("#setting-form-modal").modal()}),$("#settings tbody").on("click","label.boolean-property",function(){var e=$(this);$.post(Routing.generate("ongr_settings_settings_update_value"),{name:e.data("name"),value:e.data("value")},function(){var t=e.data("element");$("."+t).toggleClass("btn-primary")})}),$("#settings tbody").on("click","a.delete-setting",function(e){e.preventDefault();var t=$(this).data("name");$.confirm({text:"Are you sure you want to delete setting?",title:"Confirmation required",confirm:function(e){$.post(Routing.generate("ongr_settings_settings_delete"),{name:t},function(e){0==e.error&&o.ajax.reload()})},confirmButton:"Yes, delete it",cancelButton:"No",confirmButtonClass:"btn-danger",dialogClass:"modal-dialog modal-lg"})});var l=$("#profiles").DataTable({ajax:{url:Routing.generate("ongr_settings_profiles_get_all_detailed"),dataSrc:"documents"},stateSave:!0,order:[[1,"asc"]],columns:[{data:"name"},{data:"name"},{data:"settings"},{}],columnDefs:[{targets:0,orderable:!1,render:function(e,t,a){var n="toggle-profile",r=$("<label/>").addClass("btn btn-default").addClass(n).addClass(n+"-"+a.name).attr("data-name",a.name),o=r.clone().html("ON").attr("data-element",n+"-"+a.name),i=r.clone().html("OFF").attr("data-element",n+"-"+a.name);1==a.active?o.addClass("btn-primary"):i.addClass("btn-primary");var l=$("<div/>").addClass("btn-group btn-group-sm").append(o,i);return l.prop("outerHTML")}},{targets:2,orderable:!1},{targets:3,data:null,orderable:!1,defaultContent:'<a class="copy-link btn btn-primary btn-xs" data-toggle="modal">Copy link</a>&nbsp;'}]});$("#profiles tbody").on("click","label.toggle-profile",function(){var e=$(this);$.post(Routing.generate("ongr_settings_profiles_toggle"),{name:e.data("name")},function(){$(".toggle-profile-"+e.data("name")).toggleClass("btn-primary")})}),$("#profiles tbody").on("click","a.copy-link",function(e){e.preventDefault();var t=l.row($(this).parents("tr")).data(),a=Routing.generate("ongr_settings_enable_profile",{key:t.name},!0);$("#copy-placeholder").text(a);var n=document.createRange(),r=document.querySelector("#copy-placeholder");n.selectNode(r),window.getSelection().addRange(n);try{var o=document.execCommand("copy");if(!o)throw new Error("Cannot copy");noty({text:"Link successfully copied to the clipboard.",type:"success",layout:"topRight",theme:"bootstrapTheme",timeout:10,animation:{open:"animated fadeIn",close:"animated fadeOut"}})}catch(e){noty({text:"Something went wrong..",type:"error",layout:"topRight",theme:"bootstrapTheme",timeout:10,animation:{open:"animated fadeIn",close:"animated fadeOut"}}),$.alert({title:"Here's the link:",content:"<span>"+a+"</span>",confirmButton:"Close"})}});var s=$("#experiments").DataTable({ajax:{url:Routing.generate("ongr_settings_experiments_get_all_detailed"),dataSrc:"documents"},stateSave:!0,order:[[1,"asc"]],columns:[{data:"name"},{data:"name"},{data:"client"},{data:"profile"},{}],columnDefs:[{targets:0,orderable:!1,render:function(e,t,a){var n="toggle-experiment",r=$("<label/>").addClass("btn btn-default").addClass(n).addClass(n+"-"+a.name).attr("data-name",a.name),o=r.clone().html("ON").attr("data-element",n+"-"+a.name),i=r.clone().html("OFF").attr("data-element",n+"-"+a.name);a.active.indexOf(a.name)!=-1?o.addClass("btn-primary"):i.addClass("btn-primary");var l=$("<div/>").addClass("btn-group btn-group-sm").append(o,i);return l.prop("outerHTML")}},{targets:2,orderable:!1,render:function(e,t,a){var n=JSON.parse(a.value),r="";for(var o in n)r+=o+" = "+JSON.stringify(n[o])+"; ";return r}},{targets:3,orderable:!1},{targets:4,data:null,orderable:!1,render:function(e,t,a){return'<a class="edit btn btn-primary btn-xs" data-toggle="modal" data-target="#experiment-edit">Edit</a>&nbsp;<a class="delete delete-setting btn btn-danger btn-xs" data-name="'+a.name+'">Delete</a>'}}]}),d=$("<button/>").html("Add new experiment").addClass("btn btn-success btn-sm").attr({id:"new-experiment-button"});$("#experiments_filter").append(d.prop("outerHTML")),$("#new-experiment-button").on("click",function(){$("#experiment-name-input").val(""),$("#experiment-name-input").attr("disabled",!1),$("#experiment-action-title").text("New experiment"),$("#experiment-form-modal").modal(),$("#force-update").val("0"),t(),a()}),$("#experiment-form-submit").on("click",function(e){e.preventDefault();var t=$("#experiment-form").serializeArray();$.ajax({url:Routing.generate("ongr_settings_setting_submit"),data:t,success:function(e){0==e.error?(s.ajax.reload(),$("#experiment-form-modal").modal("hide")):($("#experiment-form-error-message").html(e.message),$("#experiment-form-error").show())}})}),$("#experiments tbody").on("click","label.toggle-experiment",function(){var e=$(this);$.post(Routing.generate("ongr_settings_experiments_toggle"),{name:e.data("name")},function(){$(".toggle-experiment-"+e.data("name")).toggleClass("btn-primary")})}),$("#experiments tbody").on("click","a.edit",function(){var e=s.row($(this).parents("tr")).data();t(e.profile),a(e.value),$("#experiment-action-title").text("Edit experiment"),$("#force-update").val("1"),$("#experiment-name-input").val(e.name),$("#experiment-name-input").attr("disabled",!0),$("#experiment-name").val(e.name),$("#experiment-form-modal").modal()}),$("#experiments tbody").on("click","a.delete-setting",function(e){e.preventDefault();var t=$(this).data("name");$.confirm({text:"Are you sure you want to delete setting?",title:"Confirmation required",confirm:function(e){$.post(Routing.generate("ongr_settings_settings_delete"),{name:t},function(e){0==e.error&&s.ajax.reload()})},confirmButton:"Yes, delete it",cancelButton:"No",confirmButtonClass:"btn-danger",dialogClass:"modal-dialog modal-lg"})})});