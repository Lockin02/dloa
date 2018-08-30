$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        }

    });

/** 验证供应商名称 * */
$("#pickingCode").formValidator({
	onshow : "请输入领料申请单号",
	onfocus : "领料申请单号至少2个字符，最多50个字符",
	oncorrect : "您输入的号码有效"
}).inputValidator({
	min : 2,
	max : 50,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : "号码两边不能有空符号"
	},
	onerror : "您输入的号码不合法，请重新输入"
});

/** 验证发票号码 * */
$("#pickingType").formValidator({
	onshow : "请输入领料类型",
	onfocus : "领料类型至少2个字符，最多50个字符",
	oncorrect : "您输入的类型有效"
}).inputValidator({
	min : 2,
	max : 50,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : "类型两边不能为空"
	},
	onerror : "您输入的类型不合法，请重新输入"
});


///** 验证付款日期 * */
// $("#payDate").formValidator({
//	    onshow: "请选择付款日期",
//	    onfocus: "请选择日期",
//	    oncorrect: "你输入的日期合法"
//	}).inputValidator({
//	    min: "1900-01-01",
//	    max: "2100-01-01",
//	    type: "date",
//	    onerror: "日期必须在\"1900-01-01\"和\"2100-01-01\"之间"
//	});

});
