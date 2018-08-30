var show_page = function(page) {
	$("#mailconfigGrid").yxgrid("reload");
};
$(function() {
	$("#mailconfigGrid").yxgrid({
		model: 'system_mailconfig_mailconfig',
		title: '通用邮件配置',
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},
		{
			name: 'objCode',
			display: '业务编码',
			sortable: true,
			width : 120
		},
		{
			name: 'objName',
			display: '业务名称',
			sortable: true,
			width : 120
		},
		{
			name: 'description',
			display: '描述信息',
			sortable: true,
			hide: true
		},
		{
			name: 'mailTitle',
			display: '邮件标题',
			sortable: true,
			width : 120
		},
		{
			name: 'mailContent',
			display: '邮件内容',
			sortable: true,
			hide: true
		},
		{
			name: 'defaultUserName',
			display: '默认发送人',
			sortable: true,
			width : 120
		},
		{
			name: 'defaultUserId',
			display: '默认发送人id',
			sortable: true,
			hide: true
		},
		{
			name: 'ccUserName',
			display: '默认抄送人',
			sortable: true,
			width : 120
		},
		{
			name: 'ccUserId',
			display: '默认抄送人id',
			sortable: true,
			hide: true
		},
		{
			name: 'bccUserName',
			display: '默认密送人',
			sortable: true,
			width : 120
		},
		{
			name: 'bccUserId',
			display: '默认密送人id',
			sortable: true,
			hide: true
		},
		{
			name: 'isMain',
			display: '加载主表',
			sortable: true,
			width : 70,
			process : function(v){
				if(v == "1"){
					return '是';
				}else{
					return '否';
				}
			}
		},
		{
			name: 'isItem',
			display: '加载从表',
			sortable: true,
			width : 70,
			process : function(v){
				if(v == "1"){
					return '是';
				}else{
					return '否';
				}
			}
		}],
		toAddConfig: {
			action: 'toAdd',
			formHeight : 500,
			formWidth : 900
		},
		toEditConfig: {
			action: 'toEdit',
			formHeight : 500,
			formWidth : 900
		},
		toViewConfig: {
			action: 'toView',
			formHeight : 500,
			formWidth : 900
		},
		searchitems: [{
			display: "业务编码",
			name: 'objCodeSearch'
		},{
			display: "业务名称",
			name: 'objNameSearch'
		},{
			display: "邮件标题",
			name: 'mailTitleSearch'
		},{
			display: "邮件内容",
			name: 'mailContentSearch'
		}]
	});
});