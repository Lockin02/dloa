$(document).ready(function(){

	$.formValidator.initConfig({
	   formid:"form1",
	   onsuccess:function(){
	      if(confirm("������ɹ�,ȷ���ύ��?")&&( $('#remainMoney').val()*1 - $('#invoiceMoney').val()*1 >= 0)){
	          return true;
	      }else if(( $('#remainMoney').val()*1 - $('#invoiceMoney').val()*1 < 0) ){
				alert( "��Ʊ����ѳ����ޣ�" );
	         return false;
	      }else{
	         return false;
	      }
	   }
	});
});