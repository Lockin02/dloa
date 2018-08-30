var show_page = function(page) {
	$("#contractrateGrid").yxgrid("reload");
};
$(function() {
	$("#contractrateGrid").yxgrid({
		model : 'stock_outplan_contractrate',
		title : '发货需求进度备注',
		param : { relDocId : $('#docId').val(),relDocType : $('#docType').val() },
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		height : 400,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'relDocType',
			display : '合同类型',
			sortable : true,
			hide : true
		}, {
			name : 'relDocId',
			display : '合同id',
			sortable : true,
			hide : true
		}, {
			name : 'rObjCode',
			display : '合同业务编码',
			sortable : true,
			hide : true
		}, {
			name : 'keyword',
			display : '关键字',
			width : 150,
			sortable : true
		}, {
			name : 'createName',
			display : '创建人',
			width : 80,
			sortable : true
		}, {
			name : 'createTime',
			display : '创建时间',
			width : 120,
			sortable : true
		}, {
			name : 'createId',
			display : '创建人ID',
			sortable : true,
			hide : true
		}, {
			name : 'remark',
			display : '进度描述',
			width : 400,
			sortable : true
		}],
		buttonsEx : [{
			name : 'Add',
			// hide : true,
			text : "新增",
			icon : 'add',

			action : function(row) {
				showThickboxWin('?model=stock_outplan_contractrate&action=toAdd&relDocId='
				+ $('#docId').val()
				+ '&relDocType='
				+ $('#docType').val()
				+ '&rObjCode='
				+ $('#objCode').val()
				+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
			}
		}],
		menusEx : [{
			name : 'view',
			// hide : true,
			text : "查看",
			icon : 'view',

			action : function(row) {
				showThickboxWin('?model=stock_outplan_contractrate&action=init&id='
				+ row.id
				+ '&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
			}
		}]
	});
});