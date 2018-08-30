var show_page = function(page) {
	$("#linkmanGrid").yxgrid_linkman("reload");
};
$(function() {
			var showcheckbox = $("#showcheckbox").val();
			$("#linkmanGrid").yxgrid_linkman({
				height : 350,
				isViewAction : false,
				/**
				 * 是否显示修改按钮/菜单
				 *
				 * @type Boolean
				 */
				isEditAction : false,
				/**
				 * 是否显示删除按钮/菜单
				 *
				 * @type Boolean
				 */
				isDelAction : false,
				showcheckbox : showcheckbox,
				toAddConfig : {
					toAddFn : function(p) {
						var c = p.toAddConfig;
						var url = "?model=" + p.model + "&action=" + c.action
								+ "&showDialog=1";
						var returnValue = window.showModalDialog(url,
								window.dialogArguments,
								"dialogWidth:900px;dialogHeight:500px;");
						if (returnValue) {
							var objRv = $.json2obj(returnValue);
							var t = $("#linkmanGrid").data('cmp');
							//这样都给我想出来，太厉害了，要纪念下！~
							var $row = $(t.addOneRow(1, objRv));
							t.el.trigger('row_dblclick', [$row,
											t.transRow(objRv)]);
							window.returnValue = objRv;
							window.close();
						}
					}
				},
				event : window.dialogArguments.options.gridOptions.event
					// 把事件复制过来
			});
			$("#saveButton").bind('click', function() {
						var row = $("#linkmanGrid")
								.yxgrid_linkman('getSelectedRow');
						row.trigger('row_dblclick', [row, row.data('data')]);
						window.returnValue = row.data('data');
						window.close();
					});
		});