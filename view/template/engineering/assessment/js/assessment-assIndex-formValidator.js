$(document).ready(function() {
	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg) {
		},
		onsuccess : function() {
			if (confirm("输入成功,确定保存吗?")) {
				return true;
			} else {
				return false;
			}
		}
	});
	$("#name").formValidator({
		onshow : "请输入名称",
		onfocus : "名称至少2个字符,最多50个字符",
		oncorrect : "您输入的名称可用"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "名称两边不能有空符号"
		},
		onerror : "你输入的名称,请确认"
	});

	$("#sortNo").formValidator({
		forcevalid : true,
		triggerevent : "change",
		onshow : "请输入排序号（数字）",
		onfocus : "请输入数字",
		oncorrect : "你输入的内容正确"
	}).inputValidator({
		min : 0,
		type : "value",
		onerrormin : "你输入的值必须大于等于1",
		onerror : "请输入排序号(数字)"
	});// .defaultPassed();
})