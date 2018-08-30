/**
 * yxcombogrid 基于jQuery1.4.2+
 *
 * 功能说明： 下拉表格(支持单选，多选)，可放入HTML
 *
 */
(function ($) {
	$.woo.yxcombo.subclass('woo.yxcombogrid', {
		options: {// 下拉框属性
			width: 600,
			height: 200,
			scroll: 'auto',
			/**
			 * 作为下拉表格值的列名
			 */
			valueCol: 'id',
			/**
			 * 作为下拉表格显示的列名
			 */
			nameCol: 'name',
			/**
			 * 模糊匹配搜索key
			 */
			searchName: '',
			/**
			 * 是否显示选中值
			 */
			isShowName: true,
			/**
			 * 是否显示附加扩展的按钮
			 */
			isShowButton: false,
			/**
			 * 失焦是否检测
			 */
			isFocusoutCheck: true,
			/**
			 * 失焦校验方法
			 */
			focusoutCheckAction: 'getCountByName',
			/**
			 * 失焦校验提交的参数
			 */
			checkParam: {},
			/**
			 * 检测的字段名
			 */
			checkCol: '',
			/**
			 * 自动创建隐藏域名称对象，设置此值后combogrid会自动创建隐藏域，获取数据中的key设置到val中
			 */
			autoHiddenName: {},
			/**
			 * 表格属性
			 */
			gridOptions: {
				isRightMenu: false,
				isTitle: false,
				isToolBar: false,
				showcheckbox: true
			},
			/**
			 * 弹出窗口选项设置
			 */
			openPageOptions: {
				url: '',// 弹出窗口url
				width: 900,
				height: 500
			},
			/**
			 * 是否允许下拉
			 */
			isDown: true,
			/**
			 * 设置一个参数设置返回值
			 */
			returnValue: true,
			/**
			 * 值分隔符
			 */
			valueSeparator: ",",
			isClear: true
		},
		_create: function () {
			var t = this, p = t.options, el = t.el;
			// p.isClear = true;
			// 下拉多选设置只读
			var showcheckbox = p.gridOptions.showcheckbox;
			if (showcheckbox) {
				$(el).attr("readonly", "readonly")
			}
			if (p.isClear) {
				// 追加取消按钮
				t.clearButton = $("<span title='点击清空数据' class='clear-trigger'>&nbsp;</span>");
				t.clearButton.click(function () {
					$(el).trigger('beforeclear', [t]);// 清空触发事件

					t.clearValue();
				});
				$(el).after(t.clearButton);
			}
			// 保存初始化宽度
			if (!this.initWidth) {
				var w = $(el).css("width");
				if (w && w != 'auto') {
					this.initWidth = w.substr(0, w.length - 2);// 减去px
				}
			}
			// 更改宽度
			if (this.initWidth && p.isClear) {
				$(el).width(this.initWidth - t.clearButton.width());
			}

			var name = $(el).attr('name');
			if (name) {
				var index = name.lastIndexOf("[");
				if (index > 0) {
					name = name.substr(0, index);
					t.cmpName = name;
					if (p.autoHiddenName) {
						for (var key in p.autoHiddenName) {
							var hiddenName = name + "[" + p.autoHiddenName[key]
								+ "]";
							$hiddenCmp = $("<input type='hidden' id='"
								+ hiddenName + "'>");
							$hiddenCmp.attr('name', hiddenName);
							$(el).after($hiddenCmp);
						}
					}
				}
			}
			// 扩展双击弹出页面选择
			if (p.openPageOptions) {
				if (p.openPageOptions.url != "") {
					$(el).attr('readonly', true);
				}
				// var op = p.openPageOptions;
				// var url = op.url;
				// if (url && url != '') {
				// 	$(el).attr("title", "请双击文本框弹出选择");
				// 	var w = op.width;
				// 	var h = op.height;
				// 	if (showcheckbox) {
				// 		url += "&showcheckbox=" + showcheckbox;
				// 	}
				// 	if (p.isShowButton) {
				// 		url += "&showButton=" + p.isShowButton;
				// 	}
				// 	$(el).bind('dblclick', function () {
				// 		t.kill();
				// 		var checkIds = $("#" + p.hiddenId).val();
				// 		// if (checkIds && checkIds != '') {
				// 		url += "&checkIds=" + checkIds;
				// 		// }
				// 		var returnRowData = showModalDialog(url, [t, window],
				// 			"dialogWidth:" + w + "px;dialogHeight:" + h
				// 			+ "px;");
				// 		t.setValue(returnRowData);

				// 	});
				// 	if (!p.isDown) {
				// 		// 如果有双击打开的则不进行下拉
				// 		$(el).bind('click', function () {
				// 			t.kill();
				// 		});
				// 		$(el).attr('readonly', true);
				// 	}
				// }
			}
			// 设置标题
			$(el).mouseover(function () {
				if ($(el).val() != "") {
					$(el).attr('title', $(el).val());
				}
			});
		},
		/**
		 * 隐藏选择框 - 继承自yxcombo
		 */
		kill: function () {
			var t = this, el = this.el, p = t.options, container = this.container;

			container.parent().hide();
			t.comboOpen = false;

			// 失焦清空
			if (p.isFocusoutCheck) {
				if ($(el).val() != "") {
					var id = $("#" + p.hiddenId).val();

					var param = {
						nameVal: $.trim($(el).val()),
						nameCol: p.checkCol != '' ? p.checkCol : p.nameCol,
						idVal: id,
						idCol: p.valueCol,
						checkParam: p.checkParam
					};
					var data = $.ajax({
						url: '?model=' + p.gridOptions.model + '&action=' + p.focusoutCheckAction,
						data: param,
						async: false
					}).responseText;
					if (data == 0) {
						alert("没有匹配项,请重新选择.");
						t.clearValue();
					} else {
						if (id === '' && p.isDown) {
							// 去掉id ==
							// ''的判断，修复有些下拉表格没有id隐藏域无法自动促发的bug
							// alert("请双击选择下拉项.");
							// t.clearValue();
							t.grid.selectFirstRow();
							var selectedRow = t.grid.getFirstRow();
							if (selectedRow) {
								selectedRow.trigger('dblclick');
							}
						}
					}
				}
			}
		},
		/**
		 * 获取表格配置项
		 */
		getGridOptions: function () {
			if (this.getGrid()) {
				return this.grid.options;
			}
			return this.options.gridOptions;
		},
		/**
		 * 获取表格对象
		 */
		getGrid: function () {
			if (!this.grid) {
				return "";
			}
			return this.grid;
		},

		/*--创建下拉表格--*/
		create: function () {
			/*---s:初始化下拉框---*/
			this._super();
			var t = this, p = t.options, el = t.el;
			var gridId = el.attr('id') + "_grid";
			var gridEL = $('<table id=' + gridId + ' ></table>');
			// t.remove();
			this.container.append(gridEL);
			// alert(this.container.height())
			if (p.gridOptions.isTitle) {// 存在标题栏,减去标题栏高度,不出现滚动条
				p.gridOptions.height = p.height - 90;// 先写死减去的高度
			} else {
				p.gridOptions.height = p.height - 65;// 先写死减去的高度
			}

			$("#" + gridId).empty();
			var gridData = "yxsgrid";
			if (!p.gridOptions.event) {
				p.gridOptions.event = {};
			}
			if (p.gridOptions.showcheckbox == true) {
				p.isFocusoutCheck = false;
			}
			p.gridOptions.event.afterload = function (e, data) {

			};
			if (p.gridOptions.subGridOptions) {
				gridData = "yxsubgrid";
				$("#" + gridId).yxsubgrid(p.gridOptions);
			} else {
				$("#" + gridId).yxsgrid(p.gridOptions);
			}
			/*---e:初始化下拉框---*/

			/*---s:绑定双击事件，并摧毁下拉框---*/
			var grid = $("#" + gridId).data(gridData);
			t.grid = grid;
			var rows = grid.getRows();
			// 如果是单选
			if (!p.gridOptions.showcheckbox) {
				rows.live('dblclick', function (e, isTrigger) {
					var rowData = grid.getSelecteRowData();
					if (!rowData) {
						var firstRow = grid.getFirstRow();
						rowData = firstRow.data('data');
					}
					p.rowData = rowData;
					t.setValue(rowData);
					t.kill();
					// $(el).focus();
				});
				t.eventFlag = 0;
				var keyEventFn = function () {
					//if (t.eventFlag == 1) {
					var searchName = p.searchName;
					if ($.woo.isEmpty(searchName)) {
						searchName = p.nameCol;
						p.searchName = searchName;
					}
					grid.options.param[searchName] = $.trim($(el).val());
					grid.reload();
					//}
				};
				// 按键按下取消事件
				//$(el).bind('keydown', function(event) {
				//	clearTimeout(t.returnFn);
				//	t.eventFlag = 0;
				//});
				// 进行模糊匹配事件绑定
				$(el).bind('keyup', function (event) {
					// 8为backspance 回退键 32为空格键
					if (event.keyCode > 47 || event.keyCode == 8
						|| event.keyCode == 32) {
						//t.eventFlag = 1;
						clearTimeout(t.returnFn);
						t.returnFn = setTimeout(keyEventFn, 1000);
					}
				});

				// 键盘按下 上下回车行为
				var keydownFn = function (event, type) {
					var selectedRow = grid.getSelectedRow();
					if (selectedRow) {
						var scrollTop = $(grid.bDiv).scrollTop();
						var h = selectedRow.height();

						if (event.keyCode == 13) {
							selectedRow.trigger('dblclick');
						}
						// $(el).trigger('blur');
						// $("#" + gridId).trigger('focus');
						if (type == 2) {
							h -= 17;
						}
						if (event.keyCode == 38) {// 上
							var prevRow = selectedRow.prev();
							if (prevRow.size() > 0) {
								grid.clearCheckAll();
								prevRow.trigger('click', [true]);
								scrollTop -= h;
							}
							$(grid.bDiv).scrollTop(scrollTop);
						} else if (event.keyCode == 40) {// 下
							var nextRow = selectedRow.next();
							if (nextRow.size() > 0) {

								grid.clearCheckAll();
								nextRow.trigger('click', [true]);
								event.stopPropagation();
								scrollTop += h;
							}
							$(grid.bDiv).scrollTop(scrollTop);
						}
					}
				};
				$(el).bind('keydown', function (event) {
					keydownFn(event, 1);
				});
				$("#" + gridId).bind('keydown', function (event) {
					keydownFn(event, 2);
				});

			}
			/*---e:绑定双击事件，并摧毁下拉框---*/

			/*---s:如果是多选的话，增加单选框点击事件---*/
			if (p.gridOptions.showcheckbox) {
				rows.live('row_check', function (e, checkbox, row, rowData) {
					if (p.hiddenId) {
						if (checkbox.attr('checked')) {
							if (p.idArr.indexOf(rowData[p.valueCol]) == -1) {
								p.idArr.push(rowData[p.valueCol]);
								var nameVal = t
									.processNameColRowData(rowData);
								p.nameArr.push(nameVal);
								// p.nameArr.push(rowData[p.nameCol]);
							}
						} else {
							// 如果值存在，删除数组项
							var index = p.idArr
								.indexOf(rowData[p.valueCol]);
							if (index != -1) {
								p.idArr.splice(index, 1);
								p.nameArr.splice(index, 1);
							}
						}
						p.nameStr = p.nameArr.join(p.valueSeparator);
						p.idStr = p.idArr.join(p.valueSeparator);
						if (p.isShowName == true) {
							$("#" + el.attr('id')).val(p.nameStr);
						}
						$("#" + p.hiddenId).val(p.idStr);
						// 选择后促发事件
						row.trigger('after_row_check', [checkbox, row,
							rowData]);
					}
				});
				$("#" + gridId).live("row_success", function (e, rows) {
					// 如果值存在进行勾选
					for (var i = 0, l = rows.size(); i < l; i++) {
						var rowData = $(rows[i]).data('data');
						var v = rowData[p.valueCol];
						if (v) {
							if ((p.valueSeparator + p.idArr.toString() + p.valueSeparator)
								.indexOf(p.valueSeparator + v
								+ p.valueSeparator) != -1) {
								var checkbox = grid.getCheckboxByRow(rows[i]);
								checkbox.trigger('click', [true]);
							}
						}
					}
				})
			}
		},
		/**
		 * 设置文本值
		 */
		setText: function (text) {
			var t = this, p = t.options, el = t.el;
			$(el).val(text);
			p.rowData[p.nameCol] = text;
		},
		/**
		 * 处理rowData
		 */
		processNameColRowData: function (rowData) {
			var t = this, p = t.options, el = t.el;
			var index = p.nameCol.indexOf('.');
			if (index > 0) {
				var f1 = p.nameCol.substring(0, index);
				var f2 = p.nameCol.substring(index + 1);
				var v = "";
				if (rowData[f1]) {
					v = rowData[f1][f2] || "";
				}
				return v;
			}
			return rowData[p.nameCol];
		},
		/**
		 * 设置下拉表格值
		 */
		setValue: function (rowData) {
			var t = this, p = t.options, el = t.el;
			if (rowData) {
				var t = this, p = t.options, el = t.el;
				p.rowData = rowData;
				if (!p.gridOptions.showcheckbox) {
					if (p.hiddenId) {
						p.idStr = rowData[p.valueCol];
						$("#" + p.hiddenId).val(p.idStr);
						var nameVal = t.processNameColRowData(rowData);
						p.nameArr.push(nameVal);
						// p.nameStr = rowData[p.nameCol];
						$(el).val(nameVal);
						$(el).attr('title', p.nameStr);
					}
					if (t.cmpName && p.autoHiddenName) {
						for (var key in p.autoHiddenName) {
							$("input[name='" + t.cmpName + "["
								+ p.autoHiddenName[key] + "]']")
								.val(rowData[key]);
						}
					}
				}
			}
			// t.trigger('row_dblclick', [row, row.data('data')]);
		},
		remove: function () {
			var t = this, p = t.options, el = t.el;
			this._super();
			if (p.isClear == true) {
				this.clearButton.remove();
			}
			// 设置回初始化宽度
			if (this.initWidth) {
				$(el).width(this.initWidth + "px");
			}
			$(el).attr("title", "");
			var gridId = el.attr('id') + "_grid";
			if (p.gridOptions.subGridOptions) {
				gridData = "yxsubgrid";
				$("#" + gridId).yxsubgrid("remove");
			} else {
				$("#" + gridId).yxsgrid("remove");
			}
		},
		/**
		 * 清空值
		 */
		clearValue: function () {
			var t = this, p = t.options, el = t.el;
			if ($(el).val() != '' || $("#" + p.hiddenId).val() != '') {
				if ($(el).val() != '') {
					$(el).val('');
					p.nameArr = [];
					p.nameStr = "";
				}
				if ($("#" + p.hiddenId).val() != '') {
					$("#" + p.hiddenId).val('');
					p.idArr = [];
					p.idStr = "";
				}
				$(el).trigger('clear', []);// 清空触发事件
				if (t.grid) {
					t.grid.options.param[p.searchName] = '';
					t.grid.reload();
				}
			}
		},
		/**
		 * 刷新表格数据
		 */
		reload: function () {
			if (this.getGrid()) {
				this.grid.reload();
			}
		}
	});

})(jQuery);