$(document).ready(function() {
	$("#defaultUserName").yxselect_user({
		mode: 'check',
		hiddenId: 'defaultUserId',
		formCode: 'defaultUserName'
	});

	$("#ccUserName").yxselect_user({
		mode: 'check',
		hiddenId: 'ccUserId',
		formCode: 'ccUserName'
	});

	$("#bccUserName").yxselect_user({
		mode: 'check',
		hiddenId: 'bccUserId',
		formCode: 'bccUserName'
	});

	$('#mailContent').ckeditor();

	//����֤
	validate({
		objCode: {
			required: true
		},
		objName: {
			required: true
		},
		mailTitle: {
			required: true
		},
		mailContent: {
			required: true
		}
	});

	//��Ⱦ�ӱ�
	$("#mainconfigitem").yxeditgrid({
		objName: 'mailconfig[mainconfigitem]',
		title: '�ӱ���Ⱦ',
		tableClass: 'form_in_table',
		isAddOneRow: false,
		colModel: [{
			display: '�ֶ�����',
			name: 'fieldName',
			tclass: 'txtmiddle',
			validation: {
				required: true
			}
		}, {
			display: '�ֶα���',
			name: 'fieldCode',
			tclass: 'txtmiddle',
			validation: {
				required: true
			}
		}, {
			display: '��ʾ����',
			name: 'showType',
			tclass: 'txtmiddle'
		}, {
			display: '�����',
			name: 'orderNum',
			tclass: 'txtmiddle'
		}]
	});
});