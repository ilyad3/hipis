function auth() {
    var login = $("#login").val();
    var pass = $("#pass").val();
    $.ajax({
        type: "POST",
        url:'http://hipis.ru/api/auth.php',
        data:{login:login,pass:pass},
        success:function(data){
            var answer = jQuery.parseJSON(data);
            $("body").append("<br>Ответ сервера: "+answer.msg);
            $("body").append("<br>Код ошибки: "+answer.code);
        }
    });
}

function feed() {
	var token = "afb8936967e1417937ea5cddb27db11b";
	$.ajax({
        type: "POST",
        url:'http://hipis.ru/api/feed.php',
        data:{token:token},
        success:function(data){
        	var answer = jQuery.parseJSON(data);
        	var vk_answer = jQuery.parseJSON(answer.vk_news);
        	//alert(vk_answer[0].code);
        	$("body").html(data);
        }
    });
}