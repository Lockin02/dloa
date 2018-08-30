
function myload(){
	//对采购合同的申请设备数量进行验证，最大不能超过采购任务的设备的最大可申请数量
	$(".amount").bind("change",function(){
		var thisVal = parseInt( $(this).val() );
		var nextVal = parseInt( $(this).next().val() );
		if(isNaN(this.value.replace(/,|\s/g,''))){
			alert('请输入数字');
				$(this).attr("value",nextVal);
		}
		if(thisVal>nextVal){
			alert("请不要超过最大可申请数量 "+nextVal);
			$(this).attr("value",nextVal);
		}else if(thisVal<1){
			alert("请不要输入0或负数");
				$(this).attr("value",thisVal);
			$(this).attr("value",nextVal);
		}
	});

}


/**********************删除动态表单*************************/
function mydel(obj, mytable) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;
		for (i = 2; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i-1;
		}
		sumAllMoney();
	}
}

//表单提交时，进行信息的校验
function checkAllData(){
	var booble=true;
	$("input.amount").each(function(){
		if ($(this).val()==""||$(this).val()==0) {
			alert("请输入数量,不能为空或者小于1");
			$(this).focus();
			booble=false;
			return false;
		}
	});

	return booble;

}

