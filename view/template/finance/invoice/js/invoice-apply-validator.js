$(document).ready(function(){

	$.formValidator.initConfig({
	   formid:"form1",
	   onsuccess:function(){
	      if(confirm("你输入成功,确定提交吗?")&&( $('#remainMoney').val()*1 - $('#invoiceMoney').val()*1 >= 0)){
	          return true;
	      }else if(( $('#remainMoney').val()*1 - $('#invoiceMoney').val()*1 < 0) ){
				alert( "开票金额已超上限！" );
	         return false;
	      }else{
	         return false;
	      }
	   }
	});
});