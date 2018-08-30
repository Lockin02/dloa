$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        }

    });
/**
 * 跟踪人姓名
 */
	$("#trackName").formValidator({
		onshow : "请输入跟踪人名称",
		onfocus : "姓名至少2个字符，最多50个字符",
		oncorrect : "您输入的姓名有效"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "姓名两边不能为空"
		},
		onerror : "您输入的姓名不合法，请重新输入"
	});

/**
 *跟踪日期
 */
$("#trackDate").formValidator({
         onshow:"请输入跟踪日期",
     	 onfocus:"跟踪日期不能为空",
     	 oncorrect:"你输入的日期合法"
     }).inputValidator({
     	 min:"1900-01-01",
     	 max:"3000-01-01",
     	 type:"date",
     	 onerror:"日期必须在\"1900-01-01\"和\"3000-01-01\"之间"});//.defaultPassed();

});