$(document).ready(function() {
	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg) {
		},
		onsuccess : function() {
			if (confirm("你输入成功,确定保存吗?")) {
				return true;
			} else {
				return false;
			}
		}
	});

	$("#stockCode").formValidator({
		onshow : "请输入仓库编号",
		onfocus : "仓库编号至少2个字符,最多50个字符",
		oncorrect : "输入正确"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "仓库编号两边不能有空符号"
		},
		onerror : "你输入的仓库编号,请确认"
	});
	$("#dealUserName").formValidator({
		onshow : "请选择经办人",
		onfocus : "不可输入，请选择",
		oncorrect : "您选择的经办人有效"
	}).inputValidator({
		min : 1,
		onerror : "请选择经办人"
	});
	$("#auditUserName").formValidator({
		onshow : "请选择审核人",
		onfocus : "不可输入，请选择",
		oncorrect : "您选择的审核人有效"
	}).inputValidator({
		min : 1,
		onerror : "请选择审核人"
	});
	$("#stockName").formValidator({
		onshow : "请选择仓库",
		onfocus : "请选择,可以输入",
		oncorrect : "您选择的仓库有效"
	}).inputValidator({
		min : 1,
		onerror : "请选择仓库"
	});
})