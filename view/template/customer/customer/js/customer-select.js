var show_page = function(page) {
	$("#customerGrid").yxgrid_customer("reload");
};
$(function() {
			var showcheckbox = $("#showcheckbox").val();
			var showButton = $("#showButton").val();

			var textArr = [];
			var valArr = [];
			var indexArr = [];
			var combogrid = window.dialogArguments[0];
			var opener = window.dialogArguments[1];
			var p = combogrid.options;
			var eventStr = jQuery.extend(true, {}, p.gridOptions.event);
			if (!showcheckbox) { // 如果是单选，则隐藏文本域
				if (eventStr.row_dblclick) {
					var dbclickFunLast = eventStr.row_dblclick;
					eventStr.row_dblclick = function(e, row, data) {
						dbclickFunLast(e, row, data);
						window.returnValue = row.data('data');
						window.close();
					};
				} else {
					eventStr.row_dblclick = function(e, row, data) {
						window.returnValue = row.data('data');
						window.close();
					};
				}

			} else {// 多选
				var rowCheckFunLast = function() {
				};
				if (eventStr.row_check) {
					rowCheckFunLast = eventStr.row_check;
				}
				eventStr.row_check = function(e, checkbox, row, rowData) {
					var el = combogrid.el;
					if (p.hiddenId) {
						if (checkbox.attr('checked')) {
							if (p.idArr.indexOf(rowData[p.valueCol]) == -1) {
								p.idArr.push(rowData[p.valueCol]);
								p.nameArr.push(rowData[p.nameCol]);
							}
						} else {
							// 如果值存在，删除数组项
							var index = p.idArr.indexOf(rowData[p.valueCol]);
							if (index != -1) {
								p.idArr.splice(index, 1);
								p.nameArr.splice(index, 1);
							}
						}
						p.nameStr = p.nameArr.toString();
						p.idStr = p.idArr.toString();
						if (p.isShowName == true) {
							opener.$("#" + el.attr('id')).val(p.nameStr);
						}
						opener.$("#" + p.hiddenId).val(p.idStr);
						// 选择后促发事件
						row
								.trigger('after_row_check', [checkbox, row,
												rowData]);
					}
					rowCheckFunLast(e, checkbox, row, rowData);
				}
				var checkIds = $("#checkIds").val();
				eventStr.row_success = function(e, rows, g) {
					for (var i = 0, l = rows.size(); i < l; i++) {
						var rowData = $(rows[i]).data('data');
						var v = rowData[combogrid.options.valueCol];
						if (v) {
							if (("," + checkIds + ",").indexOf("," + v + ",") != -1) {
								var checkbox = g.getCheckboxByRow(rows[i]);
								checkbox.trigger('click', [true]);
							}
						}
					}
				}
			}
			var gridOptions = combogrid.options.gridOptions;
			$("#customerGrid").yxgrid_customer({
				//height : 350,
				isAddAction : showButton,
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
				param : gridOptions.param,
				buttonsEx : null,
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
							var t = $("#customerGrid").data('cmp');
							// 这样都给我想出来，太厉害了，要纪念下！~
							var $row = $(t.addOneRow(1, objRv));
							t.el.trigger('row_dblclick', [$row,
											t.transRow(objRv)]);
							window.returnValue = objRv;
							window.close();
						}
					}
				},
				event : eventStr
					// 把事件复制过来
			});
//			$("#saveButton").bind('click', function() {
//						var row = $("#customerGrid")
//								.yxgrid_customer('getSelectedRow');
//						row.trigger('row_dblclick', [row, row.data('data')]);
//						window.returnValue = row.data('data');
//						window.close();
//					});
		});