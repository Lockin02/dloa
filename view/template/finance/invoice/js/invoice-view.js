$(function(){
	//���ַ�Ʊ����
	if($("#isRed").val() == 1){
		$("#thisColor").attr("class","red").html("<font>[���ַ�Ʊ]</font>");
	}

    // ����
    if($("#currency").val() != '�����'){
        $("#currencyShow").show();
        $("#invoiceDetailCurShow").show();
    }

	//��Ⱦ��������ӱ�
	$("#checkTable").yxeditgrid({
		url : '?model=finance_income_incomecheck&action=listJson',
		param : { 'incomeId' : $("#id").val(),'incomeType' : '2'},
		title : '��Ʊ����',
		tableClass : 'form_in_table',
		type : 'view',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '��ͬid',
			name : 'contractId',
			type : 'hidden'
		}, {
			display : '��ͬ����',
			name : 'contractName',
			type : 'hidden'
		}, {
			display : '��ͬ���',
			name : 'contractCode'
		}, {
			display : '��������id',
			name : 'payConId',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'payConName'
		}, {
			display : '���κ������',
			name : 'checkMoney',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '��������',
			name : 'checkDate'
		}, {
			display : '��ע',
			name : 'remark'
		}],
		event : {
			'reloadData' : function(e, g, data){
				if(data){
                    $("#checkTableShow").show();
				}else{
					$("#checkTable tbody").append("<tr><td colspan='10'>-- ������ؼ�¼ --</td></tr>");
                }
			}
		}
	});
});

//Դ���鿴����
function clickFun(){
	var objTypeObj = $("#objType");
	if(objTypeObj.val() == ""){
		alert('δ�����Դ������');
		return false;
	}
	url = '?model=finance_invoice_invoice&action=toViewObj'
		+ '&objId=' + $("#objId").val()
		+ '&objType=' + objTypeObj.val()
	;
	showModalWin(url,1);
}