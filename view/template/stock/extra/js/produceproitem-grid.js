var show_page = function(page) {
	$("#produceproitemGrid").yxgrid("reload");
};
$(function() {
	$("#produceproitemGrid").yxgrid({
		model : 'stock_extra_produceproitem',
		title : '生产物料需求',
		// 列信息
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'productId',
			display : '物料id',
			sortable : true,
			hide : true
		}, {
			name : 'productCode',
			display : '物料编号',
			sortable : true,
			width:80
		}, {
			name : 'productName',
			display : '物料名称',
			sortable : true,
			width:150
		}, {
			name : 'pattern',
			display : '规格型号',
			sortable : true
		}, {
			name : 'unitName',
			display : '单位',
			sortable : true,
			width:50
		}, {
			name : 'actNum',
			display : '库存数量',
			sortable : true,
			width:50
		}, {
			name : 'planInstockNum',
			display : '在途数量',
			sortable : true,
			width:50
		}, {
			name : 'relDocType',
			display : '需求类型',
			process:function(v,row){
				if(v==0){
					return "合同";
				}else if(v==1){
					return "借试用";
				}else{
					return "赠送";
				}
			},
			sortable : true
		}, {
			name : 'relDocCode',
			display : '需求编号',
			sortable : true
		}, {
			name : 'relDocId',
			display : '需求id',
			sortable : true,
			hide : true
		
		}, {
			name : 'configName',
			display : '配置',
			sortable : true
		}, {
			name : 'configNum',
			display : '数量',
			sortable : true,
			width:50
		}, {
			name : 'sendDate',
			display : '发货时间',
			sortable : true,
			width:80
		}, {
			name : 'remark',
			display : '备注',
			sortable : true,
			width:200
		}, {
			name : 'createName',
			display : '创建人',
			sortable : true,
			hide : true
		}, {
			name : 'createId',
			display : '创建人id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '创建日期',
			sortable : true,
			hide : true
		}, {
			name : 'updateName',
			display : '修改人',
			sortable : true,
			hide : true
		}, {
			name : 'updateId',
			display : '修改人id',
			sortable : true,
			hide : true
		}, {
			name : 'updateTime',
			display : '修改日期',
			sortable : true,
			hide : true
		} ],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [ {
			display : "物料编号",
			name : 'productCode'
		},{

			display : "物料名称",
			name : 'productCode'
		},{
			display : "配置",
			name : 'configName'
		},{
			display : "需求编号",
			name : 'relDocCode'
		} ]
	});
});