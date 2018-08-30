/**
 * 物料基本信息下拉表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_contequ', {
		setValue : function(rowData) {
			if (rowData) {
				var t = this, p = t.options, el = t.el;
				p.rowData = rowData;
				if (p.gridOptions.showcheckbox) {
					if (p.hiddenId) {
						p.idStr = rowData.idArr;
						$("#" + p.hiddenId).val(p.idStr);
						p.nameStr = rowData.text;
						$(el).val(p.nameStr);
						$(el).attr('title', p.nameStr);
					}
				} else if (!p.gridOptions.showcheckbox) {
					if (p.hiddenId) {
						p.idStr = rowData[p.valueCol];
						$("#" + p.hiddenId).val(p.idStr);
						p.nameStr = rowData[p.nameCol];
						$(el).val(p.nameStr);
						$(el).attr('title', p.nameStr);
					}
				}
			}
		},
		options : {
			hiddenId : 'productId',
			nameCol : 'productNo',
			gridOptions : {
				showcheckbox : false,
				model : 'stock_outplan_outplan',
				action : 'shipEquJson',
				param : {
	// "ext1" : "WLSTATUSKF"
				},
				pageSize : 10,
				// 列信息
				colModel : [{
							display : '产品线',
							name : 'productLine',
							hide : true,
							width : 130
						}, {
							display : '执行部门',
							name : 'productLineName',
							hide : true,
							width : 130
						}, {
							display : '物料Id',
							name : 'productId',
							hide : true,
							width : 130
						}, {
							display : '物料编码',
							name : 'productNo',
							width : 130
						}, {
							display : '物料名称',
							name : 'productName',
							width : 180
						}, {
							display : '是否赠送',
							name : 'isSell',
							process : function (v){
								if( v=='on' ){
									return '否';
								}else{
									return '是';
								}
							}
						}, {
							display : '单位',
							name : 'unitName'
						}, {
							display : '源单设备Id',
							name : 'id',
							hide : true
						}, {
							display : '源单Id',
							name : 'orderOrgid',
							hide : true
						}, {
							display : '类型+源单Id',
							name : 'orderId',
							hide : true
						}, {
							display : '源单编码',
							name : 'orderCode',
							hide : true
						}, {
							display : '源单名称',
							name : 'orderName',
							hide : true
						}, {
							display : '数量',
							name : 'number',
							hide : true
						}, {
							display : '已下达发货数量',
							name : 'issuedShipNum',
							hide : true
						}, {
							display : '已下达采购数量',
							name : 'issuedPurNum',
							hide : true
						}, {
							display : '已下达生产数量',
							name : 'issuedProNum',
							hide : true
						}, {
							display : '已执行数量',
							name : 'executedNum',
							hide : true
						}],
				// 快速搜索
				searchitems : [{
							display : '物料编码',
							name : 'productNo'
						}, {
							display : '物料名称',
							name : 'productName'
						}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);