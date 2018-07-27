/*
 * Script for mahjong-org.azurewebsites.net
 * http://mahjong-org.azurewebsites.net/
 *
 */

$(document).ready(function() {

  //主导航效果脚本
  $("#menu_mind li").hover(function(){
	  	  $(this).find(".submenu").stop().slideDown();
		  $(this).find(".a1").stop().animate({top: "-80px" }, "fast");
		  $(this).find(".a2").stop().animate({top: "-80px" }, "fast");
	  }, function(){
		  $(this).find(".submenu").stop().slideUp();
		  $(this).find(".a1").stop().animate({top: "0px" }, "fast");
		  $(this).find(".a2").stop().animate({top: "0px" }, "fast");
		  });
		  
   //banner切换
   setInterval(function(){
	   if($('.bannerbox .p1').is(":visible")){
		   $(".bannerbox .p1").fadeOut(1000);
	       $(".bannerbox .p2").fadeIn(1000);
		   } else {
			   $(".bannerbox .p2").fadeOut(1000);
	           $(".bannerbox .p1").fadeIn(1000);
			   
			   };
	   
	   },8000);
	   
	   
	//搜索焦点脚本
    $('input:text').each(function(){  
		var txt = $(this).val();
		$(this).focus(function(){  
		   if(txt === $(this).val()) $(this).val("");  
		}).blur(function(){  
		if($(this).val() == "") $(this).val(txt);  
	    });  
	 });
	 
	 //顶部搜索框脚本
	 $(".formbox input.t").focus(function(){
		 $(this).animate({width:"180px"}); 
		 }).blur(function(){
			 $(this).animate({width:"60px"});		 
			 });
			 
		
	 //运行fancybox
	 $(".fancybox").fancybox();
	 jQuery('.gallery-icon a').addClass('fancybox').fancybox();	 
	
	
	//报名按钮
    setInterval(function(){
	    $(".hand_p").animate({"top":"97px","left":"36px"},200).animate({"top":"100px","left":"38px"},200);
	   },600);
	   
	
	//二维码微信
	$(".wcode_layout").hover(function(){
		
		$(this).find(".w_code_b").stop().delay(300).fadeIn();
		
		},function(){
			
			$(this).find(".w_code_b").fadeOut();
			
			});
	
    
});

//设为首页 加入收藏 start
function AddFavorite(sURL, sTitle) 
{ 
    try 
    { 
        window.external.addFavorite(sURL, sTitle); 
    } 
    catch (e) 
    { 
        try 
        { 
            window.sidebar.addPanel(sTitle, sURL, ""); 
        } 
        catch (e) 
        { 
            alert("加入收藏失败，请使用Ctrl+D进行添加"); 
        } 
    } 
} 
function SetHome(obj,vrl){ 
        try{ 
                obj.style.behavior='url(#default#homepage)';obj.setHomePage(vrl); 
        } 
        catch(e){ 
                if(window.netscape) { 
                        try { 
                                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect"); 
                        } 
                        catch (e) { 
                                alert("此操作被浏览器拒绝！\n请在浏览器地址栏输入“about:config”并回车\n然后将 [signed.applets.codebase_principal_support]的值设置为'true',双击即可。"); 
                        } 
                        var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch); 
                        prefs.setCharPref('browser.startup.homepage',vrl); 
                 } 
        } 
};
//设为首页 加入收藏 end

