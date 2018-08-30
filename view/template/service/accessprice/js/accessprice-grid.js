var show_page = function(page) {
	$("#accesspriceGrid").yxgrid("reload");
};
$(function() {
			$("#accesspriceGrid").yxgrid({
						model : 'service_accessprice_accessprice',
						title : '零配件价格表',
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'productCode',
									display : '物料编号',
									sortable : true
								}, {
									name : 'productName',
									display : '物料名称',
									sortable : true,
									width : 200
								}, {
									name : 'warranty',
									display : '保修期',
									sortable : true
								}, {
									name : 'pattern',
									display : '规格型号',
									sortable : true
								}, {
									name : 'unitName',
									display : '单位',
									sortable : true
								}, {
									name : 'lowPrice',
									display : '最低价',
									sortable : true,
									process : function(v, row) {
										return moneyFormat2(v);
									}
								}, {
									name : 'highPrice',
									display : '最高价',
									sortable : true,
									process : function(v, row) {
										return moneyFormat2(v);
									}
								}, {
									name : 'strartDate',
									display : '适用开始日期',
									sortable : true
								}, {
									name : 'endDate',
									display : '适用结束日期',
									sortable : true
								}, {
									name : 'remark',
									display : '备注',
									sortable : true,
									hide : true
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
								}],
						// 主从表格设置
						// subGridOptions : {
						// url :
						// '?model=service_accessprice_NULL&action=pageJson',
						// param : {
						// paramId : 'mainId',
						// colId : 'id'
						// },
						// colModel : {
						// name : 'XXX',
						// display : '从表字段'
						// }
						// },

						toEditConfig : {
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
						searchitems : [{
									display : '物料编号',
									name : 'productCode'
								}, {
									display : '物料名称',
									name : 'productName'
								}]
					});
		});