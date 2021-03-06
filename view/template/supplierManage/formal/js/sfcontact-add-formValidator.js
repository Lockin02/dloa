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
//	$("#mobile1").formValidator({
//		onshow : "请输入你的手机号码",
//		onfocus : "必须是13或15打头哦",
//		oncorrect : "谢谢你的合作，你的手机号码正确"
//	}).regexValidator({
//		regexp : "mobile",
//		datatype : "enum",
//		onerror : "手机号码格式不正确"
//	});
    $("#busiCode").formValidator({
        onshow: "请输入业务编号",
        onfocus: "业务编号至少5个字符,最多50个字符",
        oncorrect: "您输入的业务编号可用"
    }).inputValidator({
        min: 5,
        max: 50,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "业务编号两边不能有空符号"
        },
        onerror: "你输入的业务编号非法,请确认"
    });
	$("#name").formValidator({
		onshow : "请输入联系人名称",
		onfocus : "联系人称至少2个字符,最多50个字符",
		oncorrect : "OK"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "联系人名称两边不能有空符号"
		},
		onerror : "联系人为空,请输入"
	});
//	$("#plane").formValidator({
//		empty : false,
//		onshow : "请输入你的联系电话，不可以为空",
//		onfocus : "格式例如：0577-88888888"
////		oncorrect : "谢谢你的合作"
////		onempty : "你不想留联系电话了吗？"
//	}).regexValidator({
//		regexp : "^[[0-9]{3}-|\[0-9]{4}-]?([0-9]{8}|[0-9]{7})?$",
//		onerror : "你输入的联系电话格式不正确或为空"
//	});
//	$("#email").formValidator({
//		onshow : "请输入邮箱",
//		onfocus : "邮箱6-100个字符,输入正确了才能离开焦点",
//		oncorrect : "恭喜你,你输对了"
////		forcevalid : true
//	}).inputValidator({
//		min : 6,
//		max : 100,
//		onerror : "你输入的邮箱长度非法,请确认"
//	}).regexValidator({
//		regexp : "^([\\w-.]+)@(([[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.)|(([\\w-]+.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(]?)$",
//		onerror : "你输入的邮箱格式不正确"
//	});
//	$("#fax").formValidator({
//		empty : false,
//		onshow : "请输入你的联系电话，不可以为空哦",
//		onfocus : "格式例如：0577-88888888"
////		oncorrect : "谢谢你的合作",
////		onempty : "你真的不想留联系电话了吗？"
//	}).regexValidator({
//		regexp : "^[[0-9]{3}-|\[0-9]{4}-]?([0-9]{8}|[0-9]{7})?$",
//		onerror : "你输入的联系电话格式不正确"
//	});

	    $("#manageUserName").formValidator({
        onshow: "请选择负责人",
        onfocus: "不可输入，请选择",
        oncorrect: "您选择的负责人有效"
    }).inputValidator({
        min: 1,
        onerror: "请选择负责人"
    });
})