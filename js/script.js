function show_auth() {
	$(".main_auth, #main_bg").show();
	$("#login, #password").val("");
	$("#login").focus();
	$("#auth_error").hide();
}

function show_vk_auth() {
	$("#vk_auth, #main_bg").show();
	$("#main_bg").css("z-index","0");
	$("#login").focus();
}

function recovery_pass() {
	$('.main_auth > p:eq(0)').text("Восстановление пароля");
	$('.main_auth > p:eq(1)').text("Получить код").attr("onclick","get_code();");
	$(".auth_dialog > p:eq(0)").text("E-mail");
	$(".auth_dialog > input:eq(0)").attr("id","recovery_mail").focus();
	$(".auth_dialog > p:eq(1)").text("Код (Оставьте это поле пока пустым)");
	$(".auth_dialog > input:eq(1)").attr("id","recovery_code").attr("type","text");
	$(".auth_dialog > a").text("Я вспомнил пароль!").attr("onclick","unrecovery_pass();");
}

function unrecovery_pass() {
	$("#auth_error").hide();
	$('.main_auth > p:eq(0)').text("Авторизация");
	$('.main_auth > p:eq(1)').text("Войти").attr("onclick","auth();");
	$(".auth_dialog > p:eq(0)").text("Логин");
	$(".auth_dialog > input:eq(0)").attr("id","login").focus();
	$(".auth_dialog > p:eq(1)").text("Пароль");
	$(".auth_dialog > input:eq(1)").attr("id","password").attr("type","password");
	$(".auth_dialog > a").text("Забыли пароль?").attr("onclick","recovery_pass();");
}

function show_feed_img(src) {
	var scrolled = window.pageYOffset || document.documentElement.scrollTop;
	$("#main_bg").show().css("margin",scrolled+" 0 0 0");
	$("#main_bg").css("z-index","10");
	$("#main_bg").html("<div id='open_img'><img src='img/menu_logo.png'></div>");
	$("#open_img > img").attr("src",src);
	var h = $("#main_bg").height();
	$("body").height(h).css("overflow-y","hidden");
}

function show_comments(post_id) {
	$("#commewnt_to_"+post_id).show("slow");
	$(".vk_comment_btn_"+post_id).attr("onclick","hide_comments('"+post_id+"')");
	$("#comment_area_to_"+post_id).focus();
	var div = $("#vk_c_to_"+post_id);
	var scrolled = div.height();
	div.scrollTop(scrolled + 1000000);
}

function hide_comments(post_id) {
	$("#commewnt_to_"+post_id).hide("slow");
	$(".vk_comment_btn_"+post_id).attr("onclick","show_comments('"+post_id+"')");
}

function vk_answer_to_comment(post_id,source_id,comment_owner) {
	$("#vk_answer_btn_to_"+post_id).attr("onclick","vk_comment('"+post_id+"','"+source_id+"','"+comment_owner+"');");
	$("#comment_area_to_"+post_id).focus();
}

function inst_show_comments(post_id) {
	$("#inst_comment_to_"+post_id).show("slow");
	$(".inst_comment_btn_"+post_id).attr("onclick","inst_hide_comments('"+post_id+"')");
	var div = $("#inst_c_to_"+post_id);
	var scrolled = div.height();
	div.scrollTop(scrolled + 1000000);
}

function inst_hide_comments(post_id) {
	$("#inst_comment_to_"+post_id).hide("slow");
	$(".inst_comment_btn_"+post_id).attr("onclick","inst_show_comments('"+post_id+"')");
}

function show_post_menu() {
	$("#post_menu").show();
	$("#change_post img, #change_text").attr("onclick","hide_post_menu();");
}

function hide_post_menu() {
	$("#post_menu").hide();
	$("#change_post img, #change_text").attr("onclick","show_post_menu();");
}

function change_post(name,key) {
	if (name == "inst") {
		alert("Временно недоступно");
	}else{
		var check = $('#'+name+'_check_'+key).attr('checked');
		if (check == 'checked') {
			$('#'+name+'_check_'+key).removeAttr('checked');
			$('#'+name+'_post_change_'+key).attr('class','change_btn');
		}else{
			$('#'+name+'_check_'+key).attr('checked', true);
			$('#'+name+'_post_change_'+key).attr('class','change_btn change_btn_active');
		}
	}
}