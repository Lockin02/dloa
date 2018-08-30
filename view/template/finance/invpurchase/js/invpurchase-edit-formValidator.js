$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        },
        onsuccess : function(msg){
			for(var i = 1 ;i <= $("#invnumber").val()*1 ; i++ ){
				if( $("#productId" + i).length == 0 ) continue;
				if($("#productId" + i).val() == ""){
					alert('不能存在物料为空的项');
					return false;
				}
				if($("#amount" + i).val() == "" || $("#amount" + i).val()*1 == 0){
					alert('物料必须填写对应的金额且金额不能为0');
					return false;
				}
			}
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
		onfocus : "发票号码至少2个字符",
		oncorrect : "您输入的号码有效"
	}).inputValidator({
		min : 2,
		max : 300,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "号码两边不能为空"
		},
		onerror : "您输入的号码不合法，请重新输入"
	});

	/** 验证供归属公司 * */
	$("#businessBelongName").formValidator({
		onshow: "请输入归属公司",
		onfocus: "归属公司名称至少2个字符，最多50个字符",
		oncorrect: "您输入的名称有效"
	}).inputValidator({
		min: 2,
		max: 50,
		empty: {
			leftempty: false,
			rightempty: false,
			emptyerror: "名称两边不能有空符号"
		},
		onerror: "您输入的名称不合法，请重新输入"
	});
	
	/** 验证采购方式 * */
	$("#pruType").formValidator({
		onshow : "请输入采购方式",
		onfocus : "发票号码至少2个字符，最多50个字符",
		oncorrect : "您输入的采购方式有效"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "输入内容两边不能为空"
		},
		onerror : "您输入的内容不合法，请重新输入"
	});

	/** 验证付款日期 * */
	$("#payDate").formValidator({
	    onshow: "请选择付款日期",
	    onfocus: "请选择日期",
	    oncorrect: "你输入的日期合法"
	}).inputValidator({
	    min: "1900-01-01",
	    max: "2100-01-01",
	    type: "date",
	    onerror: "日期必须在\"1900-01-01\"和\"2100-01-01\"之间"
	});

	/** 验证单据日期 * */
	$("#formDate").formValidator({
	    onshow: "请选择单据日期",
	    onfocus: "请选择日期",
	    oncorrect: "你输入的日期合法"
	}).inputValidator({
	    min: "1900-01-01",
	    max: "2100-01-01",
	    type: "date",
	    onerror: "日期必须在\"1900-01-01\"和\"2100-01-01\"之间"
	});
});