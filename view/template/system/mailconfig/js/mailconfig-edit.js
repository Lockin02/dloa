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

	//是否加载主表数据源
	if ($("#isMainHidden").val() == 1) {
		$("#isMainY").attr('checked', true);
	} else {
		$("#isMainN").attr('checked', true);
	}

	//是否加载主表数据源
	if ($("#isItemHidden").val() == 1) {
		$("#isItemY").attr('checked', true);
	} else {
		$("#isItemN").attr('checked', true);
	}
	//文本域高度自适应
	$("textarea").each(function() {
		$(this).height($(this)[0].scrollHeight);
	});

	//渲染从表
	$("#mainconfigitem").yxeditgrid({
		objName: 'mailconfig[mainconfigitem]',
		url: '?model=system_mailconfig_mainconfigitem&action=listJson',
		param: {mainId: $("#id").val(), dir: 'ASC', sort: 'orderNum'},
		title: '从表渲染',
		tableClass: 'form_in_table',
		isAddOneRow: false,
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
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
			tclass: 'txtmiddle',
			type: 'select',
			options: [{
				value: "",
				name: ""
			}, {
				value: "数据字典",
				name: "数据字典"
			}]
		}, {
			display: '排序号',
			name: 'orderNum',
			tclass: 'txtmiddle'
		}]
	});
});