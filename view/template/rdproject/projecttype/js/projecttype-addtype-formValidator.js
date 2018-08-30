$(document).ready(function(){
	$.formValidator.initConfig({
		formid:"form1",
		onerror:function(msg){
		},
		onsuccess:function(){
			return true;
		}
	});


	//添加项目类型时的判断
	$("#projectType").formValidator({
		onshow:"请输入项目类型名称",
		onfocus:"项目类型名称请不要输入多于25个汉字",
		oncorrect:"您输入的项目类型名称可用"
	}).inputValidator({
		min:1,
		max:50,
		empty:{
			leftempty:false,
			rightempty:false,
			emptyerror:"项目类型名称两边不能有空符号"
		},
		onerror:"您输入的项目类型名称不符合要求，请检查"
	});

	//对项目类型编码的唯一性判断
	$("#typeCode").formValidator({
		onshow:"请用输入项目类型的唯一编码",
		onfocus:"请用大写字母输入，不要多于25个字母",
		oncorrect:"您输入的项目类型编码可用"
	}).inputValidator({
		min:1,
		max:50,
		empty:{
			leftempty:false,
			rightempty:false,
			emptyerror:"项目类型编码两边不能有空符号"
		},
		onerror:"您输入的项目类型编码不符合要求，请检查"
	});

});



//强制转换类型，把项目类型编码转换成大写字母
function upperCase(){
//	var x = document.getElementById("typeCode").value
//	document.getElementById("typeCode").value = x.toUpperCase()
}