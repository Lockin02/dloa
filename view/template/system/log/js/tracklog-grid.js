(function($) {

	$.woo.yxgrid.subclass('woo.yxgrid_tracklog', {
		options : {
			model : 'system_log_tracklog',
			// 表单
			colModel : [{
						display : 'id',
						name : 'id',
						sortable : true,
						hide : true
					}, {
						display : '操作人',
						name : 'createName'
					}, {
						display : '操作时间',
						name : 'createTime',
						width : 150
					}, {
						display : '操作类型',
						name : 'op'
					}, {
						display : '关联业务编号',
						name : 'reObjCode',
						process : function(v, data) {
							if (data.reObjType == "outplan") {
								return "<a href='javascript:window.open(\"?model=stock_outplan_outplan&action=outplandetailTab&planId="
										+ data.reObjId
										+ "&docType="
										+ data.objType
										+ "&skey="
										+ data.skey_ + "\")'>" + v + "</a>";
							}
						}

					}, {
						display : '备注',
						name : 'remark',
						width:200
					}],
			/**
			 * 快速搜索
			 */
			searchitems : [{
						display : '操作类型',
						name : 'op'
					}],
			sortorder : "DESC",
			sortname : "id",
			isAddAction : false,
			isDelAction : false,
			isEditAction : false,
			title : '业务对象操作轨迹信息'
		}
	});
})(jQuery);