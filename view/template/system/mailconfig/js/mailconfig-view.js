$(document).ready(function() {
	//�ı���߶�����Ӧ
	$("textarea").each(function(){
		$(this).height($(this)[0].scrollHeight);
	});

	//��Ⱦ�ӱ�
	$("#mainconfigitem").yxeditgrid({
		objName : 'mailconfig[mainconfigitem]',
		url : '?model=system_mailconfig_mainconfigitem&action=listJson',
		param : { "mainId" : $("#id").val() ,'dir' : 'ASC' ,'sort' : 'orderNum'},
		title : '�ӱ���Ⱦ',
		tableClass : 'form_in_table',
		isAddOneRow : false,
		type : 'view',
		colModel : [{
			display : '�ֶ�����',
			name : 'fieldName',
			tclass : 'txtmiddle',
			validation : {
				required : true
			}
		}, {
			display : '�ֶα���',
			name : 'fieldCode',
			tclass : 'txtmiddle',
			validation : {
				required : true
			}
		}, {
			display : '��ʾ����',
			name : 'showType',
			tclass : 'txtmiddle'
		}, {
			display : '�����',
			name : 'orderNum',
			tclass : 'txtmiddle'
		}]
	});
});