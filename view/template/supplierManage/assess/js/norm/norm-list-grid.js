// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".normGrid").yxgrid("reload");
};
$(function() {
	var assId = $("#assId").val();
	$(".normGrid").yxgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装
		url : '?model=supplierManage_assess_norm&action=sanPageJson&assId='+assId,
		model : 'supplierManage_assess_norm',
		isToolBar : false,
		isRightMenu : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox:false,
		//action : 'pageJson',
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '评估指标名称',
					name : 'normName',
					sortable : true,
					width : 200
				}, {
					display : '指标编码',
					name : 'normCode',
					sortable : true,
					width : 200
				}, {
					display : '权重',
					name : 'weight',
					sortable : true,
					width : 200
				}, {
					display : '指标总分',
					name : 'normTotal',
					sortable : true,
					width : 200
				}],
		// title : '客户信息',
		// 业务对象名称
		boName : '方案指标',
		// 默认搜索字段名
		sortname : "id",
		// 默认搜索顺序
		sortorder : "ASC"
	});
});