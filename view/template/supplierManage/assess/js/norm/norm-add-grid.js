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
		//isViewAction : false,
		//isEditAction : false,
		 action : 'pageJson',
		 showcheckbox : true,
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
		boName : '方案指标',
		sortname : "id",
		sortorder : "ASC",
		toAddConfig : {
			text : '添加指标',
			action : 'sanToAdd',
			plusUrl : '&assId='+assId

		},
		toViewConfig : {
				text : '查看',
				action : 'sasRead'
		},
		toEditConfig : {
				text : '编辑',
				action : 'sasToEdit'
		},
		toDelConfig : {
				text : '删除',
				toDelFn : function(p, g) {
					var rowObj = g.getSelectedRow();
					if (rowObj) {
						if (window.confirm("确认要删除?")) {
							$.ajax({
										type : "POST",
										url : "?model=" + p.model + "&action="
												+ p.toDelConfig.action
												+ p.toDelConfig.plusUrl,
										data : {
											id : rowObj.data('data').id
													.toString()
											// 转换成以,隔开方式
										},
										success : function(msg) {
											if (msg == 1) {
												g.reload();
												alert('删除成功！');
											}
										}
									});
						}
					} else {
						alert('请选择一行记录！');
					}
				}
			}
	});
});