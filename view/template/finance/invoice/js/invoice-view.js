$(function(){
	//红字发票处理
	if($("#isRed").val() == 1){
		$("#thisColor").attr("class","red").html("<font>[红字发票]</font>");
	}

    // 货币
    if($("#currency").val() != '人民币'){
        $("#currencyShow").show();
        $("#invoiceDetailCurShow").show();
    }

	//渲染到款核销从表
	$("#checkTable").yxeditgrid({
		url : '?model=finance_income_incomecheck&action=listJson',
		param : { 'incomeId' : $("#id").val(),'incomeType' : '2'},
		title : '开票核销',
		tableClass : 'form_in_table',
		type : 'view',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '合同id',
			name : 'contractId',
			type : 'hidden'
		}, {
			display : '合同名称',
			name : 'contractName',
			type : 'hidden'
		}, {
			display : '合同编号',
			name : 'contractCode'
		}, {
			display : '付款条件id',
			name : 'payConId',
			type : 'hidden'
		}, {
			display : '付款条件',
			name : 'payConName'
		}, {
			display : '本次核销金额',
			name : 'checkMoney',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '核销日期',
			name : 'checkDate'
		}, {
			display : '备注',
			name : 'remark'
		}],
		event : {
			'reloadData' : function(e, g, data){
				if(data){
                    $("#checkTableShow").show();
				}else{
					$("#checkTable tbody").append("<tr><td colspan='10'>-- 暂无相关记录 --</td></tr>");
                }
			}
		}
	});
});

//源单查看功能
function clickFun(){
	var objTypeObj = $("#objType");
	if(objTypeObj.val() == ""){
		alert('未定义的源单类型');
		return false;
	}
	url = '?model=finance_invoice_invoice&action=toViewObj'
		+ '&objId=' + $("#objId").val()
		+ '&objType=' + objTypeObj.val()
	;
	showModalWin(url,1);
}