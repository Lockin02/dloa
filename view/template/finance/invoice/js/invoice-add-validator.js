$(document).ready(function(){

	$.formValidator.initConfig({
		formid:"form1",
	    onsuccess:function(){
	       if(confirm("������ɹ�,ȷ���ύ��?")){
	           return true;
	       }else{
				return false;
	       }
	    }
	});

	/**��λ������֤**/
	$("#customerName").formValidator({
	   onshow:"�����뵥λ����",
	   oncorrect:"�������˵�λ����"
	}).inputValidator({
	   min:1,
	   max:50,
	   onerror:"����������Ʋ��Ϸ�������������"
	});

});