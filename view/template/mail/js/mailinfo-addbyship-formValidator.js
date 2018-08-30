$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        }
    });

/** 邮寄单号 * */
$("#mailNo").formValidator({
	onshow : "请输入邮寄单号",
	onfocus : "邮寄单号至少2个字符，最多200个字符",
	oncorrect : "您输入的邮寄单号有效"
}).inputValidator({
	min : 2,
	max : 200,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : "邮寄单号两边不能有空符号"
	},
	onerror : "您输入的邮寄单号不合法，请重新输入"
});

/** 物流公司 * */
$("#logisticsName").formValidator({
	onshow : "请输入物流公司",
	onfocus : "物流公司至少2个字符，最多50个字符",
	oncorrect : "您输入的物流公司有效"
}).inputValidator({
	min : 2,
	max : 50,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : "物流公司两边不能有空符号"
	},
	onerror : "您输入的物流公司不合法，请重新输入"
});

/** 物流公司 * */
$("#customerName").formValidator({
	onshow : "请选择客户",
	onfocus : "客户名称至少2个字符，最多50个字符",
	oncorrect : "您输入的客户名称有效"
}).inputValidator({
	min : 2,
	max : 50,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : "客户名称两边不能有空符号"
	},
	onerror : "您输入的客户名称不合法，请重新输入"
});


/** 收件人 * */
$("#receiver").formValidator({
	onshow : "请选择收件人",
	onfocus : "请选择收件人",
	oncorrect : "您选择的收件人有效"
}).inputValidator({
	min : 2,
	max : 50,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : ""
	},
	onerror : "您选择的收件人不合法，请重新输入"
});
/** 审核人 * */
$("#auditman").formValidator({
	onshow : "请选择审核人",
	onfocus : "请选择审核人",
	oncorrect : "您选择的审核人有效"
}).inputValidator({
	min : 2,
	max : 50,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : ""
	},
	onerror : "您选择的审核人不合法，请重新输入"
});
/** 邮寄日期 * */
 $("#mailTime").formValidator({
	    onshow: "请选择邮寄日期",
	    onfocus: "请选择日期",
	    oncorrect: "你输入的日期合法"
	}).inputValidator({
	    min: "1900-01-01",
	    max: "2100-01-01",
	    type: "date",
	    onerror: "日期必须在\"1900-01-01\"和\"2100-01-01\"之间"
	});

});
