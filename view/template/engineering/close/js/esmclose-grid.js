var show_page = function() {
	$("#esmcloseGrid").yxgrid("reload");
};

$(function() {
	$("#esmcloseGrid").yxgrid({
		model: 'engineering_close_esmclose',
		title: '项目关闭申请',
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'projectCode',
			display: '项目编号',
			sortable: true
		}, {
			name: 'projectName',
			display: '项目名称',
			sortable: true
		}, {
			name: 'applyId',
			display: '申请人id',
			sortable: true
		}, {
			name: 'applyName',
			display: '申请人名',
			sortable: true
		}, {
			name: 'content',
			display: '描述',
			sortable: true
		}, {
			name: 'applyDate',
			display: '申请日期',
			sortable: true
		}, {
			name: 'ExaStatus',
			display: '审批状态',
			sortable: true
		}, {
			name: 'ExaDT',
			display: '审批日期',
			sortable: true
		}, {
			name: 'status',
			display: '状态',
			sortable: true
		}, {
			name: 'createId',
			display: '创建人Id',
			sortable: true
		}, {
			name: 'createName',
			display: '创建人名称',
			sortable: true
		}, {
			name: 'createTime',
			display: '创建时间',
			sortable: true
		}, {
			name: 'updateId',
			display: '修改人Id',
			sortable: true
		}, {
			name: 'updateName',
			display: '修改人名称',
			sortable: true
		}, {
			name: 'updateTime',
			display: '修改时间',
			sortable: true
		}],
		toEditConfig: {
			action: 'toEdit'
		},
		toViewConfig: {
			action: 'toView'
		},
		searchitems: [{
			display: "搜索字段",
			name: 'XXX'
		}]
	});
});