/**
 * 资产借用格组件
 */

(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_borrow', {
		options : {
			hiddenId : 'id',

			nameCol : 'billNo',

			gridOptions : {
				model : 'asset_daily_borrow',

					// 表单
				colModel : [{
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
								   display:'借用客户id',
								   name : 'borrowCustomeId',
					               sortable : true,
					                hide:true

								},{
								   display:'借用客户',
								   name : 'borrowCustome',
					               sortable : true

								},
					            {
					                display : '借用部门id',
					                name : 'deptId',
					                sortable : true,
					                hide:true
					            },
					            {
					                display : '借用部门',
					                name : 'deptName',
					                sortable : true
					            },
					            {
					                display : '借用申请人id',
					                name : 'chargeManId',
					                sortable : true,
					                hide:true
					            },{
								   display:'借用申请人',
								   name : 'chargeMan',
					               sortable : true

								},
					            {
					                display : '借用日期',
					                name : 'borrowDate',
					                sortable : true
					            },
					            {
					                display : '预计归还时间',
					                name : 'predictDate',
					                sortable : true
					            }, {
					                display : '责任人id',
					                name : 'reposeManId',
					                sortable : true,
					                hide:true
					            },{
								    display:'责任人',
									name : 'reposeMan',
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