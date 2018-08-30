$(document).ready(function(){
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        }
    });
	$("#templateName").formValidator({
		onshow:"请输入模板名称",
		onfocus:"模板名称请不要输入多于25个汉字",
		oncorrect:"您输入的模板名称不可用"
	}).inputValidator({
		min:1,
		max:50,
		empty:{
			leftempty:false,
			rightempty:false,
			emptyerror:"模板名称两边不能有空符号"
		},
		onerror:"您输入的模板名称不符合要求，请检查"
	});
});