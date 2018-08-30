/**
 * 零配件订单下拉表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_accessorder', {
				options : {
					hiddenId : 'id',
					nameCol : 'docCode',
					gridOptions : {
						showcheckbox : false,
						model : 'service_accessorder_accessorder',
						action : 'pageJson',
						pageSize : 10,
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'docCode',
									display : '单据编号',
									sortable : true
								}, {
									name : 'docDate',
									display : '签订日期',
									sortable : true
								}, {
									name : 'customerName',
									display : '客户名称',
									sortable : true
								}, {
									name : 'contactUserName',
									display : '客户联系人',
									sortable : true,
									hide : true
								}, {
									name : 'telephone',
									display : '联系电话',
									sortable : true,
									hide : true
								}, {
									name : 'adress',
									display : '客户地址',
									sortable : true,
									hide : true
								}, {
									name : 'chargeUserName',
									display : '负责人名称',
									sortable : true
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
									name : 'auditerUserName',
									display : '审批人名称',
									sortable : true,
									hide : true
								}, {
									name : 'docStatus',
									display : '单据状态',
									sortable : true,
									process : function(v, row) {
										if (row.docStatus == 'WZX') {
											return "未执行";
										} else {
											return "执行中";
										}
									}
								}, {
									name : 'saleAmount',
									display : '订单金额',
									sortable : true,
									process : function(v, row) {
										return moneyFormat2(v);
									}
								}, {
									name : 'areaLeaderName',
									display : '区域负责人名称',
									sortable : true,
									hide : true
								}, {
									name : 'areaName',
									display : '归属区域',
									sortable : true,
									hide : true
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
									name : 'updateTime',
									display : '修改日期',
									sortable : true,
									hide : true
								}],
						// 快速搜索
						searchitems : [{
									display : '区域名称',
									name : 'areaName'
								}],
						// 默认搜索字段名
						sortname : "id",
						// 默认搜索顺序
						sortorder : "ASC"
					}
				}
			});
})(jQuery);