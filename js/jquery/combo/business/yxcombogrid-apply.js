/**
 * 下拉固定资产采购申请
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_apply', {
		options : {
			hiddenId : 'id',
			nameCol : 'formCode',
			width : 550,
			gridOptions : {
				showcheckbox : false,
				model : 'asset_purchase_apply_apply',
				param : {
					"ExaStatus" : '完成'
				},
				// 列信息
				colModel : [{
					name : 'id',
					display : '采购申请id',
					sortable : true,
					hide : true
				}, {
					name : 'formCode',
					display : '单据编号',
					sortable : true
				}, {
					name : 'applyDetName',
					display : '申请部门',
					sortable : true
				}, {
					name : 'applyTime',
					display : '申请日期',
					sortable : true
				}, {
					name : 'applicantName',
					display : '申请人名称',
					sortable : true
				}, {
					name : 'useDetName',
					display : '使用部门',
					sortable : true
				}, {
					name : 'userName',
					display : '使用人名称',
					sortable : true
				}, {
					name : 'purchCategory',
					display : '采购种类',
					sortable : true,
					datacode : 'CGZL'
				}]
			}
		}
	});
})(jQuery);