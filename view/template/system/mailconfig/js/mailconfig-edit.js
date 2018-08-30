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

	//�Ƿ������������Դ
	if ($("#isMainHidden").val() == 1) {
		$("#isMainY").attr('checked', true);
	} else {
		$("#isMainN").attr('checked', true);
	}

	//�Ƿ������������Դ
	if ($("#isItemHidden").val() == 1) {
		$("#isItemY").attr('checked', true);
	} else {
		$("#isItemN").attr('checked', true);
	}
	//�ı���߶�����Ӧ
	$("textarea").each(function() {
		$(this).height($(this)[0].scrollHeight);
	});

	//��Ⱦ�ӱ�
	$("#mainconfigitem").yxeditgrid({
		objName: 'mailconfig[mainconfigitem]',
		url: '?model=system_mailconfig_mainconfigitem&action=listJson',
		param: {mainId: $("#id").val(), dir: 'ASC', sort: 'orderNum'},
		title: '�ӱ���Ⱦ',
		tableClass: 'form_in_table',
		isAddOneRow: false,
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
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
			tclass: 'txtmiddle',
			type: 'select',
			options: [{
				value: "",
				name: ""
			}, {
				value: "�����ֵ�",
				name: "�����ֵ�"
			}]
		}, {
			display: '�����',
			name: 'orderNum',
			tclass: 'txtmiddle'
		}]
	});
});