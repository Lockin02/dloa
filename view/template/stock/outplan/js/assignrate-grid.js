var show_page = function(page) {
	$("#assignrateGrid").yxgrid("reload");
};
$(function() {
	$("#assignrateGrid").yxgrid({
		model : 'stock_outplan_assignrate',
		param : { relDocId : $('#docId').val(),relDocType : $('#docType').val() },
		title : '物料确认进度备注',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'relDocType',
			display : '源单类型',
			sortable : true
		}, {
			name : 'relDocId',
			display : '源单id',
			sortable : true
		}, {
			name : 'rObjCode',
			display : '源单业务编码',
			sortable : true
		}, {
			name : 'keyword',
			display : '关键字',
			sortable : true
		}, {
			name : 'remark',
			display : '进度描述',
			sortable : true
		}, {
			name : 'createTime',
			display : '创建时间',
			sortable : true
		}, {
			name : 'createName',
			display : '创建人名称',
			sortable : true
		}, {
			name : 'createId',
			display : '创建人ID',
			sortable : true
		}, {
			name : 'updateId',
			display : '修改人Id',
			sortable : true
		}, {
			name : 'updateName',
			display : '修改人名称',
			sortable : true
		}, {
			name : 'updateTime',
			display : '修改时间',
			sortable : true
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : {
			display : "搜索字段",
			name : 'XXX'
		}
	});
});