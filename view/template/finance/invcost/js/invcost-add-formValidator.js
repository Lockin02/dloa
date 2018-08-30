$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        }

    });

/** 验证供应商名称 * */
$("#supplierName").formValidator({
	onshow : "请输入供应商名称",
	onfocus : "供应商名称至少2个字符，最多50个字符",
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

/** 验证发票号码 * */
$("#objNo").formValidator({
	onshow : "请输入发票号码",
	onfocus : "发票号码至少2个字符，最多50个字符",
	oncorrect : "您输入的号码有效"
}).inputValidator({
	min : 2,
	max : 50,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : "号码两边不能为空"
	},
	onerror : "您输入的号码不合法，请重新输入"
});

});
