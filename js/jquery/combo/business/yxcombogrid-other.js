/**
 * 零配件订单下拉表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_other', {
		options : {
			hiddenId : 'id',
			nameCol : 'orderCode',
			gridOptions : {
				showcheckbox : false,
				model : 'contract_other_other',
				action : 'pageJson',
				pageSize : 10,
				// 列信息
				colModel : [{
						display : 'id',
						name : 'id',
						sortable : true,
						hide : true
					}, {
						name : 'fundType',
						display : '款项性质',
						sortable : true,
						datacode : 'KXXZ'
					}, {
						name : 'orderCode',
						display : '鼎利合同号',
						sortable : true,
						width : 120
					}, {
						name : 'orderTempCode',
						display : '临时合同号',
						sortable : true,
						hide : true,
						width : 110
					}, {
						name : 'objCode',
						display : '业务编号',
						sortable : true,
						hide : true
					}, {
						name : 'orderName',
						display : '合同名称',
						sortable : true,
						width : 120
					}, {
						name : 'signCompanyName',
						display : '签约公司',
						sortable : true
					}, {
						name : 'proName',
						display : '公司省份',
						sortable : true
					}, {
						name : 'address',
						display : '联系地址',
						sortable : true,
						hide : true
					}, {
						name : 'phone',
						display : '联系电话',
						sortable : true
					}, {
						name : 'linkman',
						display : '联系人',
						sortable : true
					}, {
						name : 'signDate',
						display : '签约日期',
						sortable : true
					}, {
						name : 'orderMoney',
						display : '合同总金额',
						sortable : true,
						process : function(v) {
							return moneyFormat2(v);
						}
					}, {
						name : 'principalName',
						display : '合同负责人',
						sortable : true
					}, {
						name : 'deptName',
						display : '部门名称',
						sortable : true,
						hide : true
					}, {
						name : 'fundCondition',
						display : '款项条件描述',
						sortable : true,
						hide : true
					}, {
						name : 'description',
						display : '合同内容描述',
						sortable : true,
						hide : true
					}, {
						name : 'ExaStatus',
						display : '审批状态',
						sortable : true
					}
				],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);