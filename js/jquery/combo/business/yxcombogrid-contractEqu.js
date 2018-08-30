/**
 * 合同物料信息下拉表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_contractEqu', {
		isDown : true,
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
			hiddenId : 'id',
			openPageOptions : {
				url : '?model=contract_contract_equ&action=selectEqu',
				width : '750'
			},
			gridOptions : {
				isTitle: true,
				showcheckbox : false,
				model : 'contract_contract_equ',
				action : 'pageJson',
				param : {
					'isDel' : '0',
					'maxNum' : 0
				},
				pageSize : 10,
				// 列信息
				colModel : [{
							display : 'id',
							name : 'id',
							hide : true
						},{
							display : '物料id',
							name : 'productId',
							hide : true
						},{
							display : '物料编码',
							name : 'productCode',
							width : 100
						}, {
							display : '物料名称',
							name : 'productName',
							width : 200
						}, {
							display : '合同id',
							name : 'contractId',
							hide : true
						}, {
							display : '型号/版本',
							name : 'productModel',
							width : 300
						}, {
							display : '已执行数量',
							name : 'executedNum',
							hide : true
						}, {
							display : '已退货数量',
							name : 'backNum',
							hide : true
						}, {
							display : '已确认换货数量',
							name : 'applyExchangeNum',
							hide : true
						}],
				// 快速搜索
				searchitems : [{
							display : '物料编码',
							name : 'productCodeSearch'
						}, {
							display : '物料名称',
							name : 'productNameSearch'
						}]
			}
		}
	});
})(jQuery);