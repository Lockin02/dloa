function judgeMax(){
	var thisValue=$("#Max").val();
	var minValue=$("#Min").val();
	var maxValue=$("#maxValue").val();
	if(parseFloat(thisValue)>=parseFloat(maxValue)){
		$("#Max").val("");
		alert("���ܳ������ֵ��"+maxValue);
	}
	if(parseFloat(thisValue)<=parseFloat(minValue)){
		$("#Max").val("");
		alert("����С������ֵ��"+minValue);
	}
}

function judgeMin(){
	var thisValue=$("#Min").val();
	var maxValue=$("#Max").val();
	var mindValue=$("#minValue").val();
	if(parseFloat(thisValue)<0){
		alert("�����ֵ����С��0");
		$("#Min").val("");
	}
	if(parseFloat(thisValue)<=parseFloat(mindValue)){
		alert("����С����Сֵ��"+mindValue);
		$("#Min").val("");
	}
	if(parseFloat(thisValue)>=parseFloat(maxValue)){
		alert("���ܴ�������ֵ��"+maxValue);
		$("#Min").val("");
	}
}