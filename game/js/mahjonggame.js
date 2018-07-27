
$(document).ready(function() {
	
	$('input:text').each(function(){  
        var txt = $(this).val();

        $(this).focus(function(){
            if(txt === $(this).val()) $(this).val("");
        }).blur(function(){
            if($(this).val() == "") $(this).val(txt);
        });
	});
$('input:password').each(function(){  
        var txt = $(this).val();

        $(this).focus(function(){
            if(txt === $(this).val()) $(this).val("");
        }).blur(function(){
            if($(this).val() == "") $(this).val(txt);
        });
	});
    detectDomain();
	
});

/* Added by Max [Begin]
 *-------------------------------------------------------------------------------*/

/**
 * 选择游戏界面语言，并提交表单
 * @param string lang_code
 */
function chooseLanguage(lang_code) {
    $("input[name='user_lang']").val(lang_code);
    $('#choose-language').submit();
}

/**
 * 如果访问游戏栏目时的域名不是以“www”开头，则强制进行跳转
 */
function detectDomain() {
    var curr_domain = document.domain;

    if ( curr_domain == 'mahjong-ca.org' ) {
        window.location.href = 'http://www.mahjong-ca.org/game/';
    }
}

/**
 * 发送 User Support 内容
 */
function sendUserSupport() {
    var subject   = $("input[name='subject']").val();
    var email     = $("input[name='email']").val();
    var table     = $("input[name='table']").val();
    var hand      = $("input[name='hand']").val();
    var content   = $("textarea[name='content']").val();
    var btn_submit  = $("#button");

    subject = subject.replace(/(^\s*)|(\s*$)/g, "");
    content = content.replace(/(^\s*)|(\s*$)/g, "");

    if ( subject.length == 0 || content.length == 0 ) {
        alert('请填写“主题”和“内容”');
        return false;
    }


    btn_submit.attr('disabled', true);

    $('#user-support-form').submit();

    btn_submit.attr('disabled', false);

}

/* Added by Max [End]
 *-------------------------------------------------------------------------------*/


/* add by shawn [begin]
-----------------------------------------------------------------------------*/
function iframerealheight(){
	
	 var windowsheight = $(window).height();
	 var cheight = windowsheight - 0 + 'px';
	 $("#mainiframe").css({"height":cheight});
	
	}


$(document).ready(function() {
   iframerealheight();
});
$(window).resize(function(){
	iframerealheight();
	});

/* add by shawn [end]
-----------------------------------------------------------------------------*/