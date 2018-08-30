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
// 直接提交审批
function toApp() {
	document.getElementById('form1').action = "index1.php?model=contract_deduct_deduct&action=add&act=app";
}
//判断扣款金额
 function confimMoney(){
     var contractMoney = $("#contractMoney").val();
     var disdeductMoney = $("#disdeductMoney").val();
     var incomeMoney = $("#incomeMoney").val();
     var deductMoney = $("#deductMoney").val();
     var applyMoney = $("#deductMoney_apply").val();
     var tempMoney = contractMoney-incomeMoney-applyMoney

     if(parseInt(deductMoney) > parseInt(tempMoney).toFixed(2)){
          alert("申请扣款金额不得大于'合同金额-已收金额-已申请扣款金额'");
          $("#deductMoney_v").val("");
          $("#deductMoney").val("");
     }
 }