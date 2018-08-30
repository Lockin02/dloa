var show_page = function(page) {
	$("#pickingGrid").yxgrid("reload");
};

$(function() {
	$("#pickingGrid").yxgrid({
		model : 'produce_plan_pickingitem',
		action : 'pageJsonProduct',
		param : {
			planId : $("#planId").val(),
			groupBy : 'productId'
		},
		title : '生产领料物料信息',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},{
			name: 'productCode',
			display: '物料编码',
			sortable: true,
			process : function (v ,row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_plan_pickingitem&action=toViewProduct&id="
					+ row.id + "&applyNum=" + row.applyNum + "\",1)'>" + v + "</a>";
			}
		},{
			name: 'productName',
			display: '物料名称',
			width : 300,
			sortable: true
		},{
			name: 'productId',
			display: '物料ID',
			sortable: true,
			hide: true
		},{
			name: 'pattern',
			display: '规格型号',
			sortable: true
		},{
			name: 'unitName',
			display: '单位',
			sortable: true
		},{
			name: 'applyNum',
			display: '申请数量',
			sortable: true
		}],

		toViewConfig: {
			toViewFn : function(p ,g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_plan_pickingitem&action=toViewProduct&id=" + get[p.keyField] + "&applyNum=" + get['applyNum'] ,'1');
				}
			}
		},
		searchitems: [{
			name: 'docCode',
			display: '单据编号'
		},{
			name: 'docDate',
			display: '单据日期'
		},{
			name: 'relDocCode',
			display: '源单编号'
		},{
			name: 'relDocName',
			display: '源单名称'
		},{
			name: 'relDocType',
			display: '源单类型'
		},{
			name: 'createName',
			display: '申请人'
		}]
	});
});