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
			onerror : "你输入的值,请确认"
		});

		$("#Min").formValidator({
			onshow : "请输入范围上限",
			oncorrect : "您输入的值正确"

		}).inputValidator({
			onerror : "你输入的值,请确认"
		}).compareValidator({
			desid : "Max" ,
			operateor : "<=" ,
			onerror : "范围下限不能大于范围上限"
		});
})