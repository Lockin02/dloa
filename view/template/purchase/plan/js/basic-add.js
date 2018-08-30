//判断下达采购计划的采购数量是否合法
function addPlan(obj){
		var thisVal = parseInt( $(obj).val() );
		var nextVal = parseInt( $(obj).next().val() );
		if(isNaN(obj.value.replace(/,|\s/g,''))){
			alert("请输入数字");
			$(obj).attr("value",nextVal);
		}
		else if(thisVal>nextVal){
			if(!confirm("确定超过原计划数量"+nextVal+"?")){
				$(obj).attr("value",nextVal);
			}
		}else if(thisVal<1){
			alert("请输入正确的数量,不能为空或者小于1");
			$(obj).attr("value"," ");
			$(obj).focus();
		}
}

//采购计划新增提交时，判断输入是否合法
function checkForm(){
   $("input.amount").each(function(){
   		if($(this).val()==0){
   			alert(123)
   		}
   });
}