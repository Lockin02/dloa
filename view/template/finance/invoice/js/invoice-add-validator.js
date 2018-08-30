$(document).ready(function(){

	$.formValidator.initConfig({
		formid:"form1",
	    onsuccess:function(){
	       if(confirm("你输入成功,确定提交吗?")){
	           return true;
	       }else{
				return false;
	       }
	    }
	});

	/**单位名称验证**/
	$("#customerName").formValidator({
	   onshow:"请输入单位名称",
	   oncorrect:"您输入了单位名称"
	}).inputValidator({
	   min:1,
	   max:50,
	   onerror:"您输入的名称不合法，请重新输入"
	});

});