//�ж��´�ɹ��ƻ��Ĳɹ������Ƿ�Ϸ�
function addPlan(obj){
		var thisVal = parseInt( $(obj).val() );
		var nextVal = parseInt( $(obj).next().val() );
		if(isNaN(obj.value.replace(/,|\s/g,''))){
			alert("����������");
			$(obj).attr("value",nextVal);
		}
		else if(thisVal>nextVal){
			if(!confirm("ȷ������ԭ�ƻ�����"+nextVal+"?")){
				$(obj).attr("value",nextVal);
			}
		}else if(thisVal<1){
			alert("��������ȷ������,����Ϊ�ջ���С��1");
			$(obj).attr("value"," ");
			$(obj).focus();
		}
}

//�ɹ��ƻ������ύʱ���ж������Ƿ�Ϸ�
function checkForm(){
   $("input.amount").each(function(){
   		if($(this).val()==0){
   			alert(123)
   		}
   });
}