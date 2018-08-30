$(document).ready(function(){
	$.formValidator.initConfig({
		formid:"form1",
		onerror:function(msg){
			//alert(msg);
		},
		onsuccess:function(){
			if(confirm("您输入成功，确定提交吗？")){
				return true;
			}else{
				return flase;
			}
		}
	});

	//下拉框的判断
	$("#projectType").formValidator({
		onshow:"请选择项目类型",
		onfocus:"项目类型是必须项",
		oncorrect:"谢谢"
	}).inputValidator({
		min:"",
		onerror:"你是不是忘记选择项目类型了"
	}).defaultPassed();



	//普通输入框的判断
	$("#projectName").formValidator({
		onshow:"请输入项目名称",
		onfocus:"项目名称请不要输入多于25个汉字",
		oncorrect:"您输入的项目名称可用"
	}).inputValidator({
		min:1,
		max:50,
		empty:{
			leftempty:false,
			rightempty:false,
			emptyerror:"项目名称两边不能有空符号"
		},
		onerror:"您输入的项目名称不符合要求，请检查"
	});


	$("#milestoneName").formValidator({
		onshow:"请输入里程碑名称",
		onfocus:"里程碑名称请不要输入多于25个汉字",
		oncorrect:"您输入的里程碑名称可用"
	}).inputValidator({
		min:1,
		max:50,
		empty:{
			leftempty:false,
			rightempty:false,
			emptyerror:"里程碑名称两边不能有空符号"
		},
		onerror:"你输入的里程碑名称不符合要求，请检查"
	});

	$("#editMilestoneName").formValidator({
		onshow:"请输入要更改的里程碑名称",
		onfocus:"里程碑名称请不要输入多于25个汉字",
		oncorrect:"您输入的里程碑名称可用"
	}).inputValidator({
		min:1,
		max:50,
		empty:{
			leftempty:false,
			rightempty:false,
			emptyerror:"里程碑名称两边不能有空符号"
		},
		onerror:"你输入的里程碑名称不符合要求，请检查"
	});












});