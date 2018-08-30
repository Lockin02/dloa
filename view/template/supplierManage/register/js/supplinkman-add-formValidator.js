$(document).ready(function() {

	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg) {
		},
		onsuccess : function() {
			if (confirm("你输入成功,确定提交吗?")) {
				return true;
			} else {
				return false;
			}

		}
	});

	$("#name").formValidator({
		onshow : "请输入供应商名称",
		onfocus : "供应商名称至少2个字符,最多50个字符",
		oncorrect : "您输入的供应商名称可用"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "供应商名称两边不能有空符号"
		},
		onerror : "你输入的名称不合法,请确认"
	});
	$("#plane").formValidator({
		empty : true,
		onshow : "请输入你的联系电话，可以为空",
		onfocus : "格式例如：0577-88888888",
		oncorrect : "谢谢你的合作",
		onempty : "你不想留联系电话了吗？"
	}).regexValidator({
		regexp : "^[[0-9]{3}-|\[0-9]{4}-]?([0-9]{8}|[0-9]{7})?$",
		onerror : "你输入的联系电话格式不正确"
	});
	$("#email").formValidator({
		onshow : "请输入邮箱",
		onfocus : "邮箱6-100个字符,输入正确了才能离开焦点",
		oncorrect : "恭喜你,你输对了",
		forcevalid : true
	}).inputValidator({
		min : 6,
		max : 100,
		onerror : "你输入的邮箱长度非法,请确认"
	}).regexValidator({
		regexp : "^([\\w-.]+)@(([[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.)|(([\\w-]+.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(]?)$",
		onerror : "你输入的邮箱格式不正确"
	});
	$("#fax").formValidator({
		empty : true,
		onshow : "请输入你的联系电话，可以为空哦",
		onfocus : "格式例如：0577-88888888",
		oncorrect : "谢谢你的合作",
		onempty : "你真的不想留联系电话了吗？"
	}).regexValidator({
		regexp : "^[[0-9]{3}-|\[0-9]{4}-]?([0-9]{8}|[0-9]{7})?$",
		onerror : "你输入的联系电话格式不正确"
	});
})