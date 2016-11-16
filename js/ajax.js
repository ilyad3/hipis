function register() {
	var login = $("#reg_login").val();
	var email = $("#reg_mail").val();
	var pass = $("#reg_pass").val();
	var pass_repeat = $("#reg_repeat_pass").val();
	$("#reg_login, #reg_mail, #reg_pass, #reg_repeat_pass").removeAttr("class");
	$(".main_error").hide();
	if (login != "" && email != "" && pass != "" && pass_repeat != "" && pass == pass_repeat && pass.length > 5) {
		$.ajax({
	        type: "POST",
	        url:'../modules/register/reg.php',
	        data:{login:login,email:email,pass:pass,pass_repeat:pass_repeat},
	        success:function(data){
	        	var server_token = data;
	        	if (server_token == "bad_email") {
	        		$("#reg_mail").attr("class","main_error_input").focus();
					$(".main_error:eq(1)").show();
					$(".main_error:eq(1) p").text("Этот логин занят");
	        	}else if (server_token == "bad_login") {
	        		$("#reg_login").attr("class","main_error_input").focus();
					$(".main_error:eq(0)").show();
					$(".main_error:eq(0) p").text("Этот логин занят");
	        	}else if (server_token == "succes_register") {
	        		location.href= "/aa";
	        	}
	        }
	    });
	}else if (login == "") {
		$("#reg_login").attr("class","main_error_input").focus();
		$(".main_error:eq(0)").show();
		$(".main_error:eq(0) p").text("Заполните это поле");
	}else if (email == "") {
		$("#reg_mail").attr("class","main_error_input").focus();
		$(".main_error:eq(1)").show();
		$(".main_error:eq(1) p").text("Заполните это поле");
	}else if (pass == "") {
		$("#reg_pass").attr("class","main_error_input").focus();
		$(".main_error:eq(2)").show();
		$(".main_error:eq(2) p").text("Заполните это поле");
	}else if (pass_repeat == "") {
		$("#reg_repeat_pass").attr("class","main_error_input").focus();
		$(".main_error:eq(3)").show();
		$(".main_error:eq(3) p").text("Заполните это поле");
	}else if (pass_repeat != pass) {
		$("#reg_repeat_pass").attr("class","main_error_input").focus();
		$(".main_error:eq(3)").show();
		$(".main_error:eq(3) p").text("Пароли не совпадают");
	}else if (pass.length < 6) {
		$("#reg_pass").attr("class","main_error_input").focus();
		$(".main_error:eq(2)").show();
		$(".main_error:eq(2) p").text("Слишком короткий пароль");
	}
}

function check_login() {
	var login = $("#reg_login").val();
	$("#reg_login").removeAttr("class");
	$(".main_error").hide();
	 $.ajax({
        type: "POST",
        url:'../modules/register/check_login.php',
        data:{login:login},
        success:function(data){
        	var server_token = data;
        	if (server_token == "bad_login") {
        		$("#reg_login").attr("class","main_error_input").focus();
				$(".main_error:eq(0)").show();
				$(".main_error:eq(0) p").text("Этот логин занят");
        	}
        }
    });
}

function auth() {
	var login = $("#login").val();
	var pass = $("#password").val();
	if (pass != "" && login != "") {
		$.ajax({
	        type: "POST",
	        url:'../modules/auth/auth.php',
	        data:{login:login,pass:pass},
	        success:function(data){
	        	var server_token = data;
	        	if (server_token == "bad_login" || server_token == "bad_pass") {
	        		$("#auth_error").show().text("Неверный логин и/или пароль");
	        	}else if (server_token == 'success_auth') {
	        		var get = location.search;
					if (typeof(get) !== "undefined" && get != "") {
						var get = get.substr(1);
						location.href = "/"+get;
					}else{
						location.href = "/feed";
					}
	        	}
	        }
	    });
	}else if (login == "") {
		$("#login").focus();
	}else if (pass == "") {
		$("#password").focus();
	}
}

function vk_auth() {
	var login = $("#vk_login").val();
	var pass = $("#vk_password").val();
	if (pass != "" && login != "") {
		$.ajax({
	        type: "POST",
	        url:'../modules/add_accounts/vk.php',
	        data:{login:login,pass:pass},
	        success:function(data){
	        	var server_token = data;
	        	if (server_token != "success_vk") {
	        		location.href = server_token;
	        	}else{
	        		location.href = "/setting";
	        	}
	        }
	    });
	}else if (login == "") {
		$("#vk_login").focus();
	}else if (pass == "") {
		$("#vk_password").focus();
	}
}

function change_mail() {
	var email = $("#new_mail").val();
	$("#mail_error").hide();
	if (email != "") {
		$.ajax({
	        type: "POST",
	        url:'../modules/settings/change_mail.php',
	        data:{email:email},
	        success:function(data){
	        	var server_token = data;
	        	if (server_token == "bad_session") {
					$("#mail_error").show();
					$("#mail_error > font").text("Смена не возможна,обновите страницу");
	        	}else if (server_token == "bad_mail") {
	        		$("#mail_error").show();
					$("#mail_error > font").text("Этот адрес уже есть в базе данных");
	        	}else if (server_token == "success_mail") {
	        		$("#new_mail").val("");
	        		$("#setting_email > font").text(" "+email);
	        	}
	        }
	    });
	}else{
		$("#new_mail").focus();
		$("#mail_error").show();
		$("#mail_error > font").text("E-mail не может быть пустым");
	}
}

function change_pass() {
	var pass = $("#pass").val();
	var new_pass = $("#new_pass").val();
	var repeat_new_pass = $("#repeat_new_pass").val();
	var length_pass = new_pass.length;
	$("#pass_error, #new_pass_error, #repeat_new_pass_error").hide();
	if (pass != "" && new_pass != "" && repeat_new_pass != "" && new_pass == repeat_new_pass && length_pass > 5) {
		$.ajax({
	        type: "POST",
	        url:'../modules/settings/change_pass.php',
	        data:{pass:pass,new_pass:new_pass},
	        success:function(data){
	        	var server_token = data;
	        	if (server_token == "bad_session") {
					$("#pass_error").show();
					$("#pass_error > font").text("Смена не возможна,обновите страницу");
	        	}else if (server_token == "bad_pass") {
	        		$("#pass").focus();
					$("#pass_error").show();
					$("#pass_error > font").text("Неверный действующий пароль");
	        	}else if (server_token == "success_pass") {
	        		$("#pass, #new_pass, #repeat_new_pass").val("");
	        		alert("Пароль успешно изменён!");
	        	}
	        }
	    });
	}else if (pass == "") {
		$("#pass").focus();
		$("#pass_error").show();
		$("#pass_error > font").text("Введите действующий пароль");
	}else if (new_pass == "") {
		$("#new_pass").focus();
		$("#new_pass_error").show();
		$("#new_pass_error > font").text("Заполните это поле");
	}else if (repeat_new_pass == "") {
		$("#repeat_new_pass").focus();
		$("#repeat_new_pass_error").show();
		$("#repeat_new_pass_error > font").text("Заполните это поле");
	}else if (new_pass != repeat_new_pass) {
		$("#repeat_new_pass").focus();
		$("#repeat_new_pass_error").show();
		$("#repeat_new_pass_error > font").text("Пароли не совпадают");
	}else if (new_pass.length < 5) {
		$("#new_pass").focus();
		$("#new_pass_error").show();
		$("#new_pass_error > font").text("Пароль слишком короткий");
	}
}

function change_count() {
	var count = $("#new_count").val();
	$("#count_error").hide();
	if (count != "") {
		$.ajax({
	        type: "POST",
	        url:'../modules/settings/change_count.php',
	        data:{count:count},
	        success:function(data){
	        	var server_token = data;
	        	if (server_token == "need_refresh") {
					location.reload();
	        	}else if (server_token == "bad_count") {
	        		$("#count_error").show();
					$("#count_error").text("Максимальное количество 30.");
	        	}else if (server_token == "count_bad") {
	        		$("#count_error").show();
					$("#count_error").text("Минимальное количество 2.");
	        	}else if (server_token == "success_count") {
	        		$("#new_count").val("");
	        		if (count == "0") {
	        			$("#setting_count > font").text(" 4 (по умолчанию)");
	        		}else{
	        			$("#setting_count > font").text(" "+count);
	        		}
	        	}
	        }
	    });
	}else{
		$("#new_count").focus();
		$("#count_error").show();
		$("#count_error > font").text("Минимальное количество 2");
	}
}

function delete_key(key,access) {
	$.ajax({
	    type: "POST",
	    url:'../modules/settings/delete_key.php',
	    data:{key:key,access:access},
	    success:function(data){
		    var server_token = data;
		    if (server_token == "bad_session") {
				alert('Смена не возможна,обновите страницу');
		    }else if (server_token == "success_delete") {
		        alert('Аккаунт успешно отвязан');
		        $("#"+key+"_"+access).remove();
		        $("."+key+"_"+access).text("Отвязан");
		    }
	    }
	});
}

function get_code() {
	var email = $("#recovery_mail").val();
	$("#auth_error").hide();
	if (email != "") {
		$.ajax({
	        type: "POST",
	        url:'../modules/recovery_pass/get_code.php',
	        data:{email:email},
	        success:function(data){
	        	var server_token = data;
	        	if (server_token == "not_mail") {
	        		$("#recovery_mail").focus();
					$("#auth_error").show().text("Данный E-mail не зарегистрирован");
	        	}else if (server_token == "success_code") {
	        		$("#auth_error").show().text("Код выслан на E-mail");
	        		$(".auth_dialog > p:eq(1)").text("Код");
	        		$(".auth_dialog > input:eq(1)").focus();
	        		$('.main_auth > p:eq(1)').text("Проверить код").attr("onclick","check_code();");
	        	}
	        }
	    });
	}else{
		$("#recovery_mail").focus();
		$("#auth_error").show().text("E-mail не может быть пустым");
	}
}

function check_code() {
	var email = $("#recovery_mail").val();
	var code = $("#recovery_code").val();
	$("#auth_error").hide();
	if (code != "") {
		$.ajax({
	        type: "POST",
	        url:'../modules/recovery_pass/check_code.php',
	        data:{email:email,code:code},
	        success:function(data){
	        	var server_token = data;
	        	if (server_token == "bad_mail") {
	        		$("#recovery_mail").focus();
					$("#auth_error").show().text("Вы изменили E-mail");
	        	}else if (server_token == "bad_code") {
	        		$("#recovery_code").focus();
					$("#auth_error").show().text("Неверный код");
	        	}else if (server_token == "success_code") {
	        		$("#auth_error").show().text("Введите новый пароль");
	        		$(".auth_dialog > p:eq(0)").text("Новый пароль");
					$(".auth_dialog > input:eq(0)").attr("id","recovery_pass").focus().attr("type","password").val("");
	        		$(".auth_dialog > p:eq(1)").text("Повторите пароль");
	        		$(".auth_dialog > input:eq(1)").attr("id","repeat_recovery_pass").attr("type","password").val("");
	        		$('.main_auth > p:eq(1)').text("Сменить пароль").attr("onclick","recovery();");
	        	}
	        }
	    });
	}else{
		$("#recovery_code").focus();
		$("#auth_error").show().text("Код не может быть пустым");
	}
}

function recovery() {
	var pass = $("#recovery_pass").val();
	var repeat_pass = $("#repeat_recovery_pass").val();
	var length_pass = pass.length;
	$("#auth_error").hide();
	if (pass != "" && repeat_pass != "" && repeat_pass == pass && length_pass > 5) {
		$.ajax({
	        type: "POST",
	        url:'../modules/recovery_pass/recovery.php',
	        data:{pass:pass},
	        success:function(data){
	        	var server_token = data;
	        	if (server_token == "start_recovery") {
	        		alert("Пожалуйста,начните процесс сначала");
	        	}else if (server_token == "success_pass") {
	        		$("#auth_error").show().text("Пароль успешно восстановлен!");
	        		$('.main_auth > p:eq(0)').text("Авторизация");
					$('.main_auth > p:eq(1)').text("Войти").attr("onclick","auth();");
					$(".auth_dialog > p:eq(0)").text("Логин");
					$(".auth_dialog > input:eq(0)").attr("id","login").focus().attr("type","text").val("");
					$(".auth_dialog > p:eq(1)").text("Пароль");
					$(".auth_dialog > input:eq(1)").attr("id","password").val("");
					$(".auth_dialog > a").text("Забыли пароль?").attr("onclick","recovery_pass();");
	        	}
	        }
	    });
	}else if (pass == "") {
		$("#recovery_pass").focus();
		$("#auth_error").show().text("Пароль не может быть пустым");
	}else if (repeat_pass == "") {
		$("#repeat_recovery_pass").focus();
		$("#auth_error").show().text("Повторите пароль");
	}else if (pass != repeat_pass) {
		$("#repeat_recovery_pass").focus();
		$("#auth_error").show().text("Пароли не совпадают");
	}else if (length_pass < 6) {
		$("#recovery_pass").focus();
		$("#auth_error").show().text("Слишком короткий пароль");
	}
}

function vk_like(source_id,post_id) {
	$.ajax({
	        type: "POST",
	        url:'../modules/actions/vk/like.php',
	        data:{source_id:source_id,post_id:post_id},
	        success:function(data){
	        	var server_token = data;
	        	if (server_token == "need_refresh") {
	        		location.reload();
	        	}else{
	        		$("#vk_like_post_"+post_id).html("<img src='img/post_icons/like.png'><p>"+server_token+"</p>").attr("onclick","vk_unlike('"+source_id+"','"+post_id+"');");
	        		$(".vk_body_"+post_id).attr("onDblClick","vk_unlike('"+source_id+"','"+post_id+"');")
	        	}
	        }
	    });
}

function vk_unlike(source_id,post_id) {
	$.ajax({
	        type: "POST",
	        url:'../modules/actions/vk/unlike.php',
	        data:{source_id:source_id,post_id:post_id},
	        success:function(data){
	        	var server_token = data;
	        	if (server_token == "need_refresh") {
	        		location.reload();
	        	}else{
	        		$("#vk_like_post_"+post_id).html("<img src='img/post_icons/no_like.png'><p>"+server_token+"</p>").attr("onclick","vk_like('"+source_id+"','"+post_id+"');");
	        		$(".vk_body_"+post_id).attr("onDblClick","vk_like('"+source_id+"','"+post_id+"');")
	        	}
	        }
	    });
}

function vk_repost(source_id,post_id) {
	$.ajax({
	        type: "POST",
	        url:'../modules/actions/vk/repost.php',
	        data:{source_id:source_id,post_id:post_id},
	        success:function(data){
	        	var server_token = data;
	        	if (server_token == "need_refresh") {
	        		location.reload();
	        	}else{
	        		$(".vk_repost_"+post_id).html("<img src='img/post_icons/repost.png'><p>"+server_token+"</p>");
	        	}
	        }
	    });
}

function twit_like(twit_id) {
	$.ajax({
	        type: "POST",
	        url:'../modules/actions/twit/like.php',
	        data:{twit_id:twit_id},
	        success:function(data){
	        	var server_token = data;
	        	if (server_token == "need_refresh") {
	        		location.reload();
	        	}else{
	        		$("#twit_like_"+twit_id).html("<img src='img/post_icons/twit_like.png'><p>"+server_token+"</p>").attr("onclick","twit_unlike('"+twit_id+"');");
	        		$(".twit_body_"+twit_id).attr("onDblClick","twit_unlike('"+twit_id+"');")
	        	}
	        }
	    });
}

function twit_unlike(twit_id) {
	$.ajax({
	        type: "POST",
	        url:'../modules/actions/twit/unlike.php',
	        data:{twit_id:twit_id},
	        success:function(data){
	        	var server_token = data;
	        	if (server_token == "need_refresh") {
	        		location.reload();
	        	}else{
	        		$("#twit_like_"+twit_id).html("<img src='img/post_icons/no_twit_like.png'><p>"+server_token+"</p>").attr("onclick","twit_like('"+twit_id+"');");
	        		$(".twit_body_"+twit_id).attr("onDblClick","twit_like('"+twit_id+"');")
	        	}
	        }
	    });
}

function twit_repost(twit_id) {
	$.ajax({
	        type: "POST",
	        url:'../modules/actions/twit/repost.php',
	        data:{twit_id:twit_id},
	        success:function(data){
	        	var server_token = data;
	        	if (server_token == "need_refresh") {
	        		location.reload();
	        	}else{
	        		$(".repost_twit_"+twit_id).html("<img src='img/post_icons/repost.png'><p>"+server_token+"</p>");
	        	}
	        }
	    });
}

function vk_comment(source_id,post_id,reply) {
	var comment_text = $("#comment_area_to_"+post_id+"_"+source_id).val();
	$.ajax({
	        type: "POST",
	        url:'../modules/actions/vk/comment.php',
	        data:{source_id:source_id,post_id:post_id,comment_text:comment_text,reply:reply},
	        success:function(data){
	        	var server_token = data;
	        	if (server_token == "need_refresh") {
	        		location.reload();
	        	}else if (server_token != "need_refresh" && server_token != "") {
	        		$("#commewnt_to_"+post_id).html(server_token);
	        	}else if (server_token == "bad_comment") {
	        		alert("Некоректный формат комментария!");
	        	}
	       	}
	});
}

function posting() {
	$("#post_btn").removeAttr("onclick");
	var post_text = $("#post_text_area").val();
	if (post_text != "") {
		var vk_count = $(".vk_button").length;
		var count_vk = 0;
		var vk_post = "";
		var fb_count = $(".fb_button").length;
		var count_fb = 0;
		var fb_post = "";
		var twit_count = $(".twit_button").length;
		var count_twit = 0;
		var twit_post = "";
		while (vk_count > count_vk) {
			if ($(".vk_button:eq("+count_vk+")").attr('checked')) {
				var vk_class = $(".vk_button:eq("+count_vk+")").attr("class");
				if (vk_post == "") {
					var vk_post = vk_class.replace(/vk_button/g,"");
				}else{
					var vk_post = vk_post+"+"+vk_class.replace(/vk_button/g,"");
				}
			}
			count_vk++;
		}
		while (fb_count > count_fb) {
			if ($(".fb_button:eq("+count_fb+")").attr('checked')) {
				var fb_class = $(".fb_button:eq("+count_fb+")").attr("class");
				if (fb_post == "") {
					var fb_post = fb_class.replace(/fb_button/g,"");
				}else{
					var fb_post = fb_post+"+"+vk_class.replace(/fb_button/g,"");
				}
			}
			count_fb++;
		}
		while (twit_count > count_twit) {
			if ($(".twit_button:eq("+count_twit+")").attr('checked')) {
				var twit_class = $(".twit_button:eq("+count_twit+")").attr("class");
				if (twit_post == "") {
					var twit_post = twit_class.replace(/twit_button/g,"");
				}else{
					var twit_post = twit_post+"+"+vk_class.replace(/twit_button/g,"");
				}
			}
			count_twit++;
		}
		$.ajax({
	        type: "POST",
	        url:'../modules/posts/posting.php',
	        data:{post_text:post_text,vk_post:vk_post,fb_post:fb_post,twit_post:twit_post},
	        success:function(data){
	        	var server_token = data;
	        	// alert(server_token);
	        	// $("#post_text_area").val(server_token);
	        	if (server_token == "need_refresh") {
	        		alert("Необходимо обновить страницу");
	        		location.reload();
	        	}else if(server_token == "success_posting") {
	        		alert("Пост успешно размещён в социальных сетях");
	        		$("#post_text_area").val("");
	        		$("#post_btn").attr("onclick","posting();");
	        	}
	       	}
		});
	}else{
		alert("Введите текст поста");
		$("#post_text_area").focus();
		$("#post_btn").attr("onclick","posting();");
	}

}