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

	//表单验证
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

	//渲染从表
	$("#mainconfigitem").yxeditgrid({
		objName: 'mailconfig[mainconfigitem]',
		title: '从表渲染',
		tableClass: 'form_in_table',
		isAddOneRow: false,
		colModel: [{
			display: '字段名称',
			name: 'fieldName',
			tclass: 'txtmiddle',
			validation: {
				required: true
			}
		}, {
			display: '字段编码',
			name: 'fieldCode',
			tclass: 'txtmiddle',
			validation: {
				required: true
			}
		}, {
			display: '显示类型',
			name: 'showType',
			tclass: 'txtmiddle'
		}, {
			display: '排序号',
			name: 'orderNum',
			tclass: 'txtmiddle'
		}]
	});
});