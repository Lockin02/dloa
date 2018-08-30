var show_page = function(page) {
	$("#outplanGrid").yxgrid("reload");
};
$(function() {
	$("#outplanGrid").yxgrid({
		model : 'stock_outplan_outplan',
		title : '发货计划',
		param : { "docId" : $("#docId").val() , "docType" : $("#docType").val() },
		showcheckbox :false,
		isAddAction : true,
		isEditAction : false,
		isViewAction : false,
		isAddAction : false,
		isDelAction : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'planCode',
			display : '计划编号',
			sortable : true
		}, {
			name : 'docId',
			display : '合同Id',
			hide : true,
			sortable : true
		}, {
			name : 'docCode',
			display : '合同号',
			sortable : true
		}, {
			name : 'docName',
			display : '合同名称',
			sortable : true
		}, {
			name : 'week',
			display : '周次',
			sortable : true
		}, {
			name : 'planIssuedDate',
			display : '下达日期',
			sortable : true
//		}, {
//			name : 'docType',
//			display : '发货类型',
//			datacode : 'FHJHLX',
//			sortable : true
		}, {
			name : 'stockName',
			display : '发货仓库',
			sortable : true
		}, {
			name : 'type',
			display : '性质',
			datacode : 'FHXZ',
			sortable : true
		}, {
			name : 'purConcern',
			display : '采购人员关注重点',
			hide : true,
			sortable : true
		}, {
			name : 'shipConcern',
			display : '发货人员关注',
			hide : true,
			sortable : true
		}, {
			name : 'issuedStatus',
			display : '下达状态',
			sortable : true
		}, {
			name : 'docStatus',
			display : '状态',
			sortable : true
		}, {
			name : 'shipPlanDate',
			display : '计划发货日期',
			sortable : true
		}, {
			name : 'isOnTime',
			display : '是否按时发货',
			sortable : true
		}, {
			name : 'delayType',
			display : '延期原因归类',
			hide : true,
			sortable : true
		}, {
			name : 'delayReason',
			display : '未发具体原因',
			hide : true,
			sortable : true
		}],

		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=stock_outplan_outplan&action=toView&id='
						+ row.id
						+ '&docType='
						+ row.docType
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=700');
			}
		}]
	});
});