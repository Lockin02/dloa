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

	// ģ�� -- ��ʼ������
	var modelType = $("#modelType").val();
	if(modelType != "" && modelType != "0"){
		initTemplate(modelType);
		$("#imgLoading").hide();
		$("#feeTbl").show();
	}

	// ��ʼ�����ż�� - �ύ������ť
	initSubButton();
});

// ��ʼ��Ⱦģ�� - ����ʱ��
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