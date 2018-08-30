

$(window).load(function(){
	//获取当前可是窗口的高度，并设置iframe窗口
	var windowHeight = $(window).height() - 30;
	$("#frame1").css('height',windowHeight);
	$("#frame2").css('height',windowHeight);

	var f1= $($('#frame1').contents());
	var f2= $($('#frame2').contents());

    var frm1 = document.getElementById("frame1").contentWindow;
    var frm2 = document.getElementById("frame2").contentWindow;

    frm1.onscroll = function(){
		f2.scrollLeft(f1.scrollLeft());
		f2.scrollTop(f1.scrollTop());
    }
    frm2.onscroll = function(){
		f1.scrollLeft(f2.scrollLeft());
		f1.scrollTop(f2.scrollTop());
    }
});
