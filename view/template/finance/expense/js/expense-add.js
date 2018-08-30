//������ȫ�ֱ���
var uploadfile;

$(function() {
	//���ù���
	$("#costbelong").costbelong({
		objName : 'expense'
	});

	//�ص�����
	$.scrolltotop({className: 'totop'});

	// ��Ʊ���ͻ���
	billTypeArr = getBillType();
	
	// ������黺��
	moduleArr = getData('HTBK');

	// ģ�� -- ��ʼ������
	var modelType = $("#modelType").val();
	if(modelType != "" && modelType != "0"){
		setTimeout(function(){
			initTemplate(modelType);
			initCostshareTemplate(modelType);
			$("#imgLoading").hide();
			$("#feeTbl").show();
			$("#costshareImgLoading").hide();
			$("#costshare").show();
		},500);
	}

	// ��ʼ�����ż�� - �ύ������ť
	initSubButton();
});

// ��ʼ��Ⱦ������Ϣģ�� - ����ʱ��
function initTemplate(modelType){
	// ��̨����ģ��ҳ��
	$.ajax({
	    type: "POST",
	    url: "?model=finance_expense_expense&action=initTempAdd",
	    data: {"modelType" : modelType},
	    async: false,
	    success: function(data){
			$("#invbody").html(data);
			// ��� ǧ��λ����
			formateMoney();
			resetCustomCostType();
		}
	});
}

//��ʼ��Ⱦ��̯��Ϣģ�� - ����ʱ��
function initCostshareTemplate(modelType){
	// ��̨����ģ��ҳ��
	$.ajax({
	    type: "POST",
	    url: "?model=finance_expense_expense&action=initCostshareTempAdd",
	    data: {"modelType" : modelType},
	    async: false,
	    success: function(data){
			$("#costsharebody").html(data);
			// ��� ǧ��λ����
			formateMoney();
		}
	});
}