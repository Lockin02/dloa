$(document).ready(function() {

});

//ˢ���б�
function show_page(){
	window.opener.show_page();
	window.close();
}

//ˢ���б�
function show_page2(){
	window.opener.show_page();
	location.reload();
}

//�ύ����
function toAudit(){
	var isLate = $("#isLate").val();
	var id = $("#expenseId").val();
	var Amount = $("#Amount").val();
	var CheckAmount = $("#CheckAmount").val();
	var CostBelongDeptId = $("#CostBelongDeptId").val();

	//������ݵı������ͼ���һ�£���Ҫ����ȷ��
	if(CheckAmount*1 != Amount*1){
		if(confirm('�����Ѿ����޸ģ���Ҫ�ύ��������ȷ�ϣ����ھͽ������ύȷ����')){
			$.ajax({
				type : "POST",
				url : "?model=finance_expense_expense&action=handConfirm",
				data : {
					id : id
				},
				success : function(msg) {
					if (msg == '1') {
						alert('�ύ�ɹ���');
						window.opener.show_page();
						window.close();
					}else{
						alert("�ύʧ��! ");
					}
				}
			});
		}
	}else{
		if(isLate == "1"){
			showThickboxWin('controller/finance/expense/ewf_indexlate.php?actTo=ewfSelect&billId='
				+ id + '&flowMoney=' + Amount
				+ '&billDept=' + CostBelongDeptId
				+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600");
		}else{
			showThickboxWin('controller/finance/expense/ewf_index.php?actTo=ewfSelect&billId='
				+ id + '&flowMoney=' + Amount
				+ '&billDept=' + CostBelongDeptId
				+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600");
		}
	}
}

//�ύȷ��
function toHandConfirm(){
	var id = $("#expenseId").val();
	var Amount = $("#Amount").val();
	var CheckAmount = $("#CheckAmount").val();

	//������ݵı������ͼ���һ�£���Ҫ����ȷ��
	if(CheckAmount*1 != Amount*1){
		if(confirm('ȷ�Ͻ������ύȷ����')){
			$.ajax({
				type : "POST",
				url : "?model=finance_expense_expense&action=handConfirm",
				data : {
					id : id
				},
				success : function(msg) {
					if (msg == '1') {
						alert('�ύ�ɹ���');
						window.opener.show_page();
						window.close();
					}else{
						alert("�ύʧ��! ");
					}
				}
			});
		}
	}else{
		alert('���ݽ��û�з������������Ҫ�ύȷ��');
	}
}

//���
function toBack(){
	if(!confirm('ȷ��Ҫ��ص�����')) return false;

	var id = $("#expenseId").val();
	$.ajax({
		type : "POST",
		url : "?model=finance_expense_expense&action=ajaxBack",
		data : {
			id : id
		},
		success : function(msg) {
			if (msg == '1') {
				alert('��سɹ���');
				show_page();
			}else{
				alert("���ʧ��! ");
			}
		}
	});
}

//�鿴��־
function showWorklog(worklogId){
	var costdetailId = $("#costdetailId" + worklogId).val();
	var skey = "";
    $.ajax({
	    type: "POST",
	    url: "?model=engineering_worklog_esmworklog&action=md5RowAjax",
	    data: { "id" : worklogId },
	    async: false,
	    success: function(data){
	   	   skey = data;
		}
	});
	showOpenWin("?model=engineering_worklog_esmworklog&action=toView&id=" + worklogId +"&skey=" + skey + "&costdetailId="+ costdetailId ,1,750,1150);
}

//�޸���ϸ
function editWorklog(worklogId){
	var costdetailId = $("#costdetailId" + worklogId).val();
	var expenseId = $("#expenseId").val();
	var allcostdetailId = $("#allcostdetailId").val();
	var skey = "";
    $.ajax({
	    type: "POST",
	    url: "?model=engineering_worklog_esmworklog&action=md5RowAjax",
	    data: { "id" : worklogId },
	    async: false,
	    success: function(data){
	   	   skey = data;
		}
	});
	showOpenWin("?model=engineering_worklog_esmworklog&action=toCheckEdit&id=" + worklogId +"&skey=" + skey + "&costdetailId="+ costdetailId + "&expenseId=" + expenseId + "&allcostdetailId=" + allcostdetailId,1,750,1150);
}