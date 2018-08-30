$(document).ready(function() {
	$("#trialplantemdetail").yxeditgrid({
		url : '?model=hr_baseinfo_trialplantemdetail&action=listJson',
		tableClass : 'form_in_table',
		type : 'view',
		param : {
			'planId' : $("#id").val()
		},
		title : '��ϸ����',
		colModel : [{
			display : '��������',
			name : 'taskTypeName',
			tclass : 'txtmiddle',
			width : '10%'
		}, {
			display : '��������',
			name : 'taskName',
			width : '15%'
		}, {
			display : '��������',
			name : 'description',
			width : '20%'
		}, {
			display : '��������',
			name : 'managerName',
			width : '10%'
		}, {
			display : '�������',
			name : 'taskScore',
			width : '10%'
		}, {
			display : '�Ƿ��л��ֹ���',
			name : 'isRule',
			type : 'hidden'
		}, {
			display : '���ֹ���',
			name : 'setRule',
			type : 'statictext',
			event : {
				'click' : function(e) {
					var rowNum = $(this).data("rowNum");
					// ��ȡisRule
					var isRuleObj = $("#trialplantemdetail_cmp_isRule" + rowNum);
					// ��ȡ��������
					var taskName = $("#trialplantemdetail_cmp_taskName" + rowNum).val();

					if(isRuleObj.val() != '' && isRuleObj.val() != '0'){
						// ����
						url = "?model=hr_baseinfo_trialplantemdetailex&action=toViewRule&id=" + isRuleObj.val()
							+ "&trialplantemdetail_cmp_isRule"
							+ rowNum
							+ "&taskName="
							+ taskName
						;
						var returnValue = showModalDialog(url, '',"dialogWidth:600px;dialogHeight:300px;");
					}
				}
			},
			process : function(html,rowDate){
				if(rowDate.isRule == "0" || rowDate.isRule == ""){
					return 'û�����ù���';
				}else{
					return html;
				}
			},
			html : "<a href='javascript:void(0)'>�鿴����</a>",
			width : '10%'
		}, {
			display : '��������',
			name : 'isNeed',
			process : function(v){
				if(v == '1'){
					return '����';
				}else{
					return '��ѡ';
				}
			},
			width : '10%'
		}, {
			display : 'ǰ������',
			name : 'beforeName',
			tclass : 'txt',
			readonly : true
		}, {
			display : 'ǰ������id',
			name : 'beforeId',
			type : 'hidden'
		}]
	})
})