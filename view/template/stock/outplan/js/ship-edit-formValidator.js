$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        }
    });

/** 客户名称 * */
if(!($('#docType').val()=='oa_borrow_borrow' && $('#customerName').val()=='')){
	$("#customerName").formValidator({
		onshow : "请输入客户名称",
		onfocus : "名称至少2个字符，最多50个字符",
		oncorrect : "您输入的名称有效"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "名称两边不能有空符号"
		},
		onerror : "您输入的名称不合法，请重新输入"
	});
}

/** 联系人 * */
$("#linkman").formValidator({
	onshow : "请输入联系人",
	onfocus : "联系人至少2个字符，最多50个字符",
	oncorrect : "您输入的联系人有效"
}).inputValidator({
	min : 2,
	max : 50,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : "联系人两边不能有空符号"
	},
	onerror : "您输入的联系人不合法，请重新输入"
});

///** 物流公司 * */
//$("#companyName").formValidator({
//	onshow : "请输入物流公司",
//	onfocus : "物流公司至少2个字符，最多50个字符",
//	oncorrect : "您输入的物流公司有效"
//}).inputValidator({
//	min : 2,
//	max : 50,
//	empty : {
//		leftempty : false,
//		rightempty : false,
//		emptyerror : "物流公司两边不能有空符号"
//	},
//	onerror : "您输入的物流公司不合法，请重新输入"
//});


/** 发货人 * */
$("#shipman").formValidator({
	onshow : "请选择发货人",
	onfocus : "请选择发货人",
	oncorrect : "您选择的发货人有效"
}).inputValidator({
	min : 2,
	max : 50,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : ""
	},
	onerror : "您选择的发货人不合法，请重新输入"
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
/** 验证付款日期 * */
 $("#shipDate").formValidator({
	    onshow: "请选择发货日期",
	    onfocus: "请选择日期",
	    oncorrect: "你输入的日期合法"
	}).inputValidator({
	    min: "1900-01-01",
	    max: "2100-01-01",
	    type: "date",
	    onerror: "日期必须在\"1900-01-01\"和\"2100-01-01\"之间"
	});

});
