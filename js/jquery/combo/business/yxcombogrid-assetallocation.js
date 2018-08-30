/**
 * 资产借用格组件
 */

(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_allocation', {
		options : {
			hiddenId : 'id',

			nameCol : 'billNo',

			gridOptions : {
				model : 'asset_daily_allocation',

					// 表单
				colModel : [ {
					                display : 'id',
					                name : 'id',
					                sortable : true,
					                hide : true
					            },
					            {
					                display : '表单编号',
					                name : 'billNo',
					                sortable : true
					            },{
								   display:'调拨日期',
								   name : 'moveDate',
					               sortable : true


								},{
								   display:'调出部门Id',
								   name : 'outDeptId',
					               sortable : true,
									hide : true

								},
					            {
					                display : '调出部门',
					                name : 'outDeptName',
					                sortable : true
					            },
					            {
					                display : '调入部门id',
					                name : 'inDeptId',
					                sortable : true,
									hide : true
					            },{
								   display:'调入部门',
								   name : 'inDeptName',
					               sortable : true

								},
					            {
					                display : '调拨申请人',
					                name : 'proposer',
					                sortable : true
					            },
					            {
					                display : '调入确认人',
					                name : 'recipient',
					                sortable : true
					            },
					            {
					                display : '备注',
					                name : 'remark',
					                sortable : true,
									hide : true
					            }, {
									name : 'ExaStatus',
									display : '审批状态',
									sortable : true
								}, {
									name : 'ExaDT',
									display : '审批时间',
									sortable : true,
									hide : true
								}, {
									name : 'isSign',
									display : '是否签收',
									sortable : true
								},{
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
									display : '录入人',
									sortable : true,
					                hide:true
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

				sortorder : "ASC",
				title : '资产调拨信息'
			}
		}
	});
})(jQuery);