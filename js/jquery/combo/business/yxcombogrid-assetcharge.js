/**
 * 资产借用格组件
 */

(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_charge', {
		options : {
			hiddenId : 'id',

			nameCol : 'billNo',

			gridOptions : {
				model : 'asset_daily_charge',

					// 表单
				colModel : [{
                display : 'id',
                name : 'id',
                sortable : true,
                hide : true
            },
            {
                display : '领用单编号',
                name : 'billNo',
                sortable : true
            },
            {
                display : '领用日期',
                name : 'chargeDate',
                sortable : true
            },
            {
                display : '领用部门id',
                name : 'deptId',
                sortable : true,
                hide : true
            },
            {
                display : '领用部门名称',
                name : 'deptName',
                sortable : true
            },
            {
                display : '领用人Id',
                name : 'chargeManId',
                sortable : true,
                hide : true
            },
            {
                display : '领用人',
                name : 'chargeMan',
                sortable : true
            },{
                display : '审批状态',
                name : 'ExaStatus',
                sortable : true
            },{
                display : '签收状态',
                name : 'isSign',
                sortable : true
            },
//            {
//                display : '审批时间',
//                name : 'ExaDT',
//                sortable : true
//            },
            {
                display : '备注',
                name : 'remark',
                sortable : true
            }],

				/**
				 * 快速搜索
				 */
				searchitems : [{
					display : '表单编号',
					name : 'billNo'
				}],
				sortorder : "ASC",
				title : '资产借用信息'
			}
		}
	});
})(jQuery);