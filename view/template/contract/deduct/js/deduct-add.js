$(document).ready(function() {

	$.formValidator.initConfig({
				theme : "Default",
				submitOnce : true,
				formID : "form1",
				onError : function(msg, obj, errorlist) {
					alert(msg);
				}
			});
  })
// ֱ���ύ����
function toApp() {
	document.getElementById('form1').action = "index1.php?model=contract_deduct_deduct&action=add&act=app";
}
//�жϿۿ���
 function confimMoney(){
     var contractMoney = $("#contractMoney").val();
     var disdeductMoney = $("#disdeductMoney").val();
     var incomeMoney = $("#incomeMoney").val();
     var deductMoney = $("#deductMoney").val();
     var applyMoney = $("#deductMoney_apply").val();
     var tempMoney = contractMoney-incomeMoney-applyMoney

     if(parseInt(deductMoney) > parseInt(tempMoney).toFixed(2)){
          alert("����ۿ���ô���'��ͬ���-���ս��-������ۿ���'");
          $("#deductMoney_v").val("");
          $("#deductMoney").val("");
     }
 }