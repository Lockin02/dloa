$(document).ready(function() {
	$.formValidator.initConfig({
		formid: "form1",
		//autotip: true,
		onerror: function(msg) {
			//alert(msg);
		}
	});

	/** 调拨单号 * */
	$("#mainNo").formValidator({
		onshow: "请输入调拨单号",
		onfocus: "单号至少2个字符，最多50个字符",
		oncorrect: "OK"
	}).inputValidator({
		min: 2,
		max: 50,
		empty: {
			leftempty: false,
			rightempty: false,
			emptyerror: "单号两边不能有空符号"
		},
		onerror: "您输入的单号不合法，请重新输入"
	});

	/** 验证调出对象 * */
	$("#outStockName").formValidator({
		onshow: "请选择调出对象",
		onfocus: "请选择调出对象",
		oncorrect: "OK"
	}).inputValidator({
		min: 2,
		max: 50,
		empty: {
			leftempty: false,
			rightempty: false,
			emptyerror: "选择内容两边不能为空"
		},
		onerror: "未选择或选择不正确,请重新选择"
	});

	/** 验证发票号码 * */
	$("#inStockName").formValidator({
		onshow: "请选择调出对象",
		onfocus: "请选择调出对象",
		oncorrect: "OK"
	}).inputValidator({
		min: 2,
		max: 50,
		empty: {
			leftempty: false,
			rightempty: false,
			emptyerror: "选择内容两边不能为空"
		},
		onerror: "未选择或选择不正确,请重新选择"
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