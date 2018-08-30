
/**
 * 可编辑表格
 */
(function($) {
	$.woo.yxgrid.subclass('woo.yxegrid', {
		options : {
			isRightMenu : false,
			usepager : false,
			pageSize : 999,
			isViewAction : false,
			isEditAction : false,
			toAddConfig : {
				text : '新增',
				/**
				 * 默认点击新增按钮触发事件
				 */
				toAddFn : function(p, g) {
					g.addEditRow();
				}
			},
			isSaveAction : true,
			toSaveConfig : {
				text : '保存',
				action : 'saveBatch',
				plusUrl : '',
				/**
				 * 默认点击保存按钮触发事件
				 */
				toSaveFn : function(p, g) {
					g.saveData();
				}
			},
			isReloadAction : true,
			toReloadConfig : {
				text : '刷新',
				/**
				 * 默认点击刷新按钮触发事件
				 */
				toSaveFn : function(p, g) {
					g.reload();
				}
			}
		},

		/**
		 * 初始化组件
		 */
		_create : function() {
			var g = this, el = this.el, p = this.options, cm = p.colModel;
			$(document).click(function(e) {
				var nodeName = $(e.target).context.nodeName;
				if (nodeName != "INPUT" && nodeName != "SELECT") {
					// 处理上一个编辑的列
					if (g.lastEditCell) {
						var lastEditor = g.lastEditCell.data('editor');
						var lastCm = g.lastEditCell.data('cm');
						if (lastEditor) {
							var div = g.lastEditCell.children().first();// 拿到第一个孩子，这里返回的是两个在ie7下append会报错
							var pv = lastEditor.val();
							g.lastEditCell.data('value', pv);// 拿到编辑控件的真实值
							if (lastCm.process) {
								pv = lastCm.process(lastEditor.val(),
										lastCm.datadictData);
							}
							g.lastEditCell.data('hvalue', pv);// 拿到编辑控件的隐藏/冗余值
							div.append(pv);
							lastEditor.remove();// 从DOM中删除所有匹配的元素
						}
						g.lastEditCell = null;
					}
				} else {
					e.stopPropagation();
				}
			});

		},
		/**
		 * 重写获取按钮数组方法，加上保存及刷新按钮
		 */
		_getButtons : function() {
			var g = this, el = this.el, p = this.options;
			// 调用父类函数
			var buttons = this._super();
			if (p.isSaveAction) {
				buttons.push({
							name : 'Save',
							text : p.toSaveConfig.text,
							index : 35,
							icon : 'add',
							action : function() {
								p.toSaveConfig.toSaveFn(p, g);
							}
						});
			}
			if (p.isReloadAction) {
				buttons.push({
							name : 'Reload',
							text : p.toReloadConfig.text,
							index : 50,
							icon : 'reload',
							action : function() {
								g.reload();
							}
						});
			}
			return buttons;
		},
		/**
		 * 动态添加一行数据
		 */
		addEditRow : function(rowNum) {
			var g = this, el = this.el, p = this.options, cm = p.colModel;
			var count = parseInt(g.getCount());
			rowNum = rowNum ? rowNum : count;
			var defRow = {
				rowNum : rowNum
			};
			for (var v in cm) {
				if (cm[v].editor) {
					if (cm[v].editor.defVal) {// 设置默认值
						defRow[cm[v].name] = cm[v].editor.defVal;
					} else if (cm[v].editor.defValIndex) {// 设置默认选中项
						var data = cm[v].editor.data
								? cm[v].editor.data
								: cm[v].datadictData;
						defRow[cm[v].name] = data[cm[v].editor.defValIndex].dataCode;
					}
				}
			}
			// 在指定的行加入数据
			var row = $(g.addOneRow(rowNum, defRow));
			p.total = count + 1;
			row.data("data", defRow);
//			row.children("td").each(function() {
//						g.addCellProp(this);
//					});
			var gridTbody = $('tbody', g.bDiv);
			if (gridTbody[0]) {
				gridTbody.append(row);
			} else {// 数据为空情况
				var grid = $('table', g.bDiv);
				grid.empty();
				grid.append("<tbody>");
				$('tbody', g.bDiv).append(row);
			}
		},
		/**
		 * 重写添加数据方法，在添加完数据后添加列可编辑属性
		 */
		addData : function(data, isFirst) {
			this._super(data, isFirst);
			var g = this, el = this.el, p = this.options;
			$('tbody tr td', g.bDiv).each(function() {
						g.cellProp(this);
					});
		},
		/**
		 * 设置每一列属性
		 * 
		 * @param cell
		 *            编辑的列对象
		 */
		cellProp : function(cell) {
			var g = this, el = this.el, p = this.options;
			cell = $(cell);
			var cellNum = $('td', cell.parent()).index(cell);// 编辑的列位置
			var tr = cell.parent();// 编辑的行
			var th = $('th:eq(' + cellNum + ')', g.hDiv).get(0);// 编辑的列头
			var row = tr.data('data');
			// 使动态加入的列也能有此功能
			cell.bind('dblclick', function() {
						var c = $(this);
						var cm = $(th).data('cm');
						if (cm) {
							var v = c.data('value');
							if (v == undefined) {
								v = row[cm.name];
							}
							if (v == undefined) {
								v = c.text().trim();
							}
							cm.editor = cm.editor ? cm.editor : {
								type : 'textfield'
							};
							cm.editor.type = cm.editor.type
									? cm.editor.type
									: 'textfield';
							switch (cm.editor.type) {
								/**
								 * 普通文本
								 */
								case 'textfield' :
									c.children().empty();// 删除匹配的元素集合中所有的子节点

									var editor = $("<input type='text'/>")
											.width(c.width() - 2);
									editor.val(v);
									c.append(editor);
									editor.focus();// append后focus才能生效
									break;
								/**
								 * 日期控件
								 */
								case 'datefield' :
									break;
								/**
								 * 静态文本
								 */
								case 'text' :
									break;
								/**
								 * 下拉选择
								 */
								case 'combo' :
									c.children().empty();
									var editor = $("<select/>").width(c.width()
											- 2);
									editor.val(v);
									var data = cm.editor.data
											? cm.editor.data
											: cm.datadictData;
									if (data) {
										for (var i = 0; i < data.length; i++) {
											var item = data[i];
											var option = $("<option>")
													.val(item.dataCode)
													.text(item.dataName);
											editor.append(option);
										}
									}
									if (v) {
										editor.val(v);// 设置默认值
									}
									c.append(editor);
									// 如果没有设置列处理函数，自动创建一个
									if (!cm.process) {
										cm.process = function(v, row) {
											return $.woo.getDataName(v, data);
										};
									}
									break;
								/**
								 * 下拉单选树
								 */
								break;
							default :
								break;

						}

						c.data('editor', editor);
						c.data('cm', cm);
						g.lastEditCell = c;
					}
					});
		},
		/**
		 * 保存表格数据
		 */
		saveData : function() {
			var param = {};
			var g = this, p = this.options, c = p.toSaveConfig;
			// 动态构建提交参数
			$('tbody tr td', g.bDiv).each(function() {
				var cell = $(this);
				var cellNum = $('td', cell.parent()).index(cell);// 编辑的列位置
				var tr = cell.parent();// 编辑的行
				var th = $('th:eq(' + cellNum + ')', g.hDiv).get(0);// 编辑的列头
				var row = tr.data('data');
				var cm = $(th).data('cm');
				var v = cell.data('value');
				if (!v && cm) {
					v = row[cm.name];
				}
				if (cm) {
					param[p.objName + "[" + row.rowNum + "][" + cm.name + "]"] = v;
					if (cm.hiddenName) {
						var hv = cell.data('hvalue');
						param[p.objName + "[" + row.rowNum + "]["
								+ cm.hiddenName + "]"] = hv;
					}
				}
			});
			if (c.action) {
				var saveUrl = "?model=" + p.model + "&action=" + c.action
						+ c.plusUrl;
				$.ajax({
							type : 'post',
							url : saveUrl,
							data : param,
							success : function(data) {
								if (data != null && data.error != null) {
									if (p.onError) {
										p.onError(data);
										g.hideLoading();
									}
								} else {
									g.reload();
								}
							},
							error : function(data) {
								try {
									if (p.onError) {
										p.onError(data);
									} else {
										alert("提交数据发生异常;")
									}
									g.hideLoading();
								} catch (e) {
								}
							}
						});
			}
		}
	});

})(jQuery);