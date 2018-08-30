/**
 * 下拉发货单表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_shipapply', {
		options : {
			hiddenId : 'id',
			nameCol : 'contractNo',
			gridOptions : {
				model : 'stock_shipapply_shipapply',
				// 列信息
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'shipApplyNo',
					display : '发货申请单号',
					sortable : true
				}, {
					name : 'shipApplyDate',
					display : '申请日期',
					sortable : true
				}, {
					name : 'shipType',
					display : '发货类型',
					sortable : true
				}, {
					name : 'contractId',
					display : '合同id',
					sortable : true,
					hide : true
				}, {
					name : 'contractNo',
					display : '系统合同号',
					sortable : true
				}, {
					name : 'contractName',
					display : '合同名称',
					sortable : true
				}, {
					name : 'contractUnitId',
					display : '合同单位id',
					sortable : true,
					hide : true
				}, {
					name : 'contractUnitName',
					display : '合同单位',
					sortable : true,
					hide : true
				}, {
					name : 'customerName',
					display : '收货单位名称',
					sortable : true,
					hide : true
				}, {
					name : 'customerId',
					display : '收货单位Id',
					sortable : true,
					hide : true
				}, {
					name : 'address',
					display : '收货地址',
					sortable : true,
					hide : true
				}, {
					name : 'reachDate',
					display : '期望到达日期',
					sortable : true,
					hide : true
				}, {
					name : 'isMail',
					display : '是否邮寄',
					sortable : true,
					hide : true
				}, {
					name : 'stockId',
					display : '仓库Id',
					sortable : true,
					hide : true
				}, {
					name : 'stockName',
					display : '仓库名称',
					sortable : true
				}, {
					name : 'confimManId',
					display : '确认人id',
					sortable : true,
					hide : true
				}, {
					name : 'confirmMan',
					display : '确认人名称',
					sortable : true,
					hide : true
				}, {
					name : 'confirmTime',
					display : '确认时间',
					sortable : true,
					hide : true
				}, {
					name : 'confirmStatus',
					display : '确认状态',
					sortable : true,
					hide : true
				}, {
					name : 'remark',
					display : '备注',
					sortable : true,
					hide : true
				}, {
					name : 'createId',
					display : '创建人ID',
					sortable : true,
					hide : true
				}, {
					name : 'createName',
					display : '创建人名称',
					sortable : true
				}, {
					name : 'createTime',
					display : '创建时间',
					sortable : true,
					hide : true
				}, {
					name : 'updateId',
					display : '修改人ID',
					sortable : true,
					hide : true
				}, {
					name : 'updateName',
					display : '修改人名称',
					sortable : true,
					hide : true
				}, {
					name : 'updateTime',
					display : '修改时间',
					sortable : true,
					hide : true
				}, {
					name : 'ExaStatus',
					display : '审批状态',
					sortable : true,
					hide : true
				}, {
					name : 'ExaDT',
					display : '审批日期',
					sortable : true,
					hide : true
				}, {
					name : 'projectSendDate',
					display : '预计发货时间',
					sortable : true,
					hide : true
				}],
				// 快速搜索
				searchitems : [{
					display : '发货单号',
					name : 'shipApplyNo'
				}],
				// 默认搜索字段名
				sortname : "shipApplyNo",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);