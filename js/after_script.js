 $(document).click( function(event){
      if( $(event.target).closest(".main_auth, #main_in, #auth_vk, #auth_inputs > a, .open_img").length ) 
        return;
      $(".main_auth, #main_bg").hide();
      $("body").removeAttr("style");
      event.stopPropagation();
});

window.onload = function () {
	var hash = location.hash;
	if (hash == "#auth") {
		show_auth();
		$("#auth_error").show().text("Пожалуйста авторизуйтесь");
	}
}

document.onkeyup = function (e) {
    e = e || window.event;
    if (e.keyCode === 13) {
        auth();
    }
    return false;
}
