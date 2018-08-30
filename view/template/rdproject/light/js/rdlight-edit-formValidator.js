$(document).ready(function(){
	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg){
		},
		onsuccess : function(){
			if(confirm("你输入成功,确定提交吗")){
				return true;
			}else{
				return false;
				}
			}
		});

		$("#Max").formValidator({
			onshow : "请输入范围上限",
			oncorrect : "您输入的值正确"

		}).inputValidator({
			min :1,
			onerror : "你输入的值,请确认"
		})
//		.compareValidator({
//			desid : "Min" ,
//			operateor : ">" ,
//			onerror : "范围上限不能小于范围下限"
//		})
//		.ajaxValidator({
//			type : "get",
//			url : "index1.php",
//			data : "model=rdproject_light_rdlight&action=ajaxRange&notId=" + $("#id").val() ,
//			datatype : "json",
//			success : function(data) {
//				if (data == "1") {
//					return true;
//				} else {
//					return false;
//				}
//			},
//			buttons : $("#submitSave"),
//			error : function() {
//				alert("服务器没有返回数据，可能服务器忙，请重试");
//			},
//			onerror : "输入范围有误,可能范围和其他标识灯冲突",
//			onwait : "正在对项目名称进行合法性校验，请稍候..."
//		})
		;

		$("#Min").formValidator({
			onshow : "请输入范围下限",
			oncorrect : "您输入的值正确"

		}).inputValidator({
			min :1,
			onerror : "你输入的值,请确认"
		})
//		.compareValidator({
//			desid : "Max" ,
//			operateor : "<" ,
//			onerror : "范围下限不能大于范围上限"
//		})
//		.ajaxValidator({
//			type : "get",
//			url : "index1.php",
//			data : "model=rdproject_light_rdlight&action=ajaxRange&notId=" + $("#id").val() ,
//			datatype : "json",
//			success : function(data) {
//				if (data == "1") {
//					return true;
//				} else {
//					return false;
//				}
//			},
//			buttons : $("#submitSave"),
//			error : function() {
//				alert("服务器没有返回数据，可能服务器忙，请重试");
//			},
//			onerror : "输入范围有误,可能范围和其他标识灯冲突",
//			onwait : "正在对项目名称进行合法性校验，请稍候..."
//		})
		;
})