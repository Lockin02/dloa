//��ʼ��һЩ�ֶ�
var objName = 'require';
var initId = 'feeTbl_c';
var actionType = 'view';
var myUrl = '?model=flights_require_require&action=ajaxGet';

$(document).ready(function() {
	if($("#actType").val() == 'audit'){
		$("#buttonTable").hide();
	}

	//�������ʱ����ֵ�����������Ϣ
	if(strTrim($("#flyStartTimeShow").html()) == '' && strTrim($("#flyEndTimeShow").html()) == ''){
		$("#flyTimeKeyShow").hide();
	}

	//������ʾ
    changeType($("#ticketTypeHidden").val());
	
	//������Ա
	var itemTableObj = $("#itemTable");

	itemTableObj.yxeditgrid({
		url : "?model=flights_require_requiresuite&action=listJson",
		param : {
			"mainId" : $("#id").val(),
			"cardNoLimit" : $("#cardNoLimit").val()
		},
		tclass : 'form_in_table',
		type : 'view',
//		event : {
//			'reloadData' : function(e,g,data){
//				if(!data.length){
//					$("#itemTable tbody").append("<tr class='tr_odd'><td colspan='10'>--- û��������Ա��Ϣ ---</td>");
//				}
//			}
//		},
		colModel : [{
			name : 'employeeTypeName',
			display : 'Ա������',
			readonly : true
		},{
			name : 'airName',
			display : '����',
			readonly : true
		},{
			name : 'sex',
			display : '�˻����Ա�',
			readonly : true,
			width : 60
		}, {
			name : 'airPhone',
			display : '�ƶ�����',
			width : 80,
			readonly : true
		}, {
			name : 'cardTypeName',
			display : '֤������'
		}, {
			name : 'cardNo',
			display : '֤������'
		}, {
			name : 'validDate',
			display : '֤����Ч��'
		}, {
			name : 'birthDate',
			display : '��������'
		}, {
			name : 'nation',
			display : '����',
			width : 80
		}, {
			name : 'tourAgencyName',
			display : '���ÿͻ���'
		}, {
			name : 'tourCardNo',
			display : '���ÿͿ���'
		}]
	});
	//�˻�����Ϣ��һ��,Ĭ��Ϊ��¼����Ϣ
	$("#itemTable_cmp_airName0").append("W3School");
	$("#itemTable_cmp_airId0").val($("#requireId").val());
	$("#itemTable_cmp_airPhone0").val($("#requirePhone").val());
	$("#itemTable_cmp_cardNo0").val($("#cardNo").val());
});
