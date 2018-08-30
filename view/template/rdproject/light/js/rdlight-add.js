function judgeMax(){
	var thisValue=$("#Max").val();
	var minValue=$("#Min").val();
	var maxValue=$("#maxValue").val();
	if(parseFloat(thisValue)>=parseFloat(maxValue)){
		$("#Max").val("");
		alert("不能超过最大值："+maxValue);
	}
	if(parseFloat(thisValue)<=parseFloat(minValue)){
		$("#Max").val("");
		alert("不能小于下限值："+minValue);
	}
}

function judgeMin(){
	var thisValue=$("#Min").val();
	var maxValue=$("#Max").val();
	var mindValue=$("#minValue").val();
	if(parseFloat(thisValue)<0){
		alert("输入的值不能小于0");
		$("#Min").val("");
	}
	if(parseFloat(thisValue)<=parseFloat(mindValue)){
		alert("不能小于最小值："+mindValue);
		$("#Min").val("");
	}
	if(parseFloat(thisValue)>=parseFloat(maxValue)){
		alert("不能大于上限值："+maxValue);
		$("#Min").val("");
	}
}