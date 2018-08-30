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
		onshow : "请输入项目名称",
		onfocus : "项目名称至少2个字符,最多50个字符",
		oncorrect : "您输入的名称可用"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "项目名称两边不能有空符号"
		},
		onerror : "你输入的项目名称,请确认"
	});

	$("#score").formValidator({
		forcevalid : true,
		triggerevent : "change",
		onshow : "请输入分值（数字）",
		onfocus : "请输入数字",
		oncorrect : "你输入的内容正确,空则默认为0"
	}).inputValidator({
		min :0,
		type : "value",
		onerrormin : "你输入的值必须大于等于0,如不填则默认为0",
		onerror : "请输入分值(数字)"
	});// .defaultPassed();

})