/**
 * yxcombogrid ����jQuery1.4.2+
 *
 * ����˵���� �������(֧�ֵ�ѡ����ѡ)���ɷ���HTML
 *
 */
(function ($) {
	$.woo.yxcombo.subclass('woo.yxcombogrid', {
		options: {// ����������
			width: 600,
			height: 200,
			scroll: 'auto',
			/**
			 * ��Ϊ�������ֵ������
			 */
			valueCol: 'id',
			/**
			 * ��Ϊ���������ʾ������
			 */
			nameCol: 'name',
			/**
			 * ģ��ƥ������key
			 */
			searchName: '',
			/**
			 * �Ƿ���ʾѡ��ֵ
			 */
			isShowName: true,
			/**
			 * �Ƿ���ʾ������չ�İ�ť
			 */
			isShowButton: false,
			/**
			 * ʧ���Ƿ���
			 */
			isFocusoutCheck: true,
			/**
			 * ʧ��У�鷽��
			 */
			focusoutCheckAction: 'getCountByName',
			/**
			 * ʧ��У���ύ�Ĳ���
			 */
			checkParam: {},
			/**
			 * �����ֶ���
			 */
			checkCol: '',
			/**
			 * �Զ��������������ƶ������ô�ֵ��combogrid���Զ����������򣬻�ȡ�����е�key���õ�val��
			 */
			autoHiddenName: {},
			/**
			 * �������
			 */
			gridOptions: {
				isRightMenu: false,
				isTitle: false,
				isToolBar: false,
				showcheckbox: true
			},
			/**
			 * ��������ѡ������
			 */
			openPageOptions: {
				url: '',// ��������url
				width: 900,
				height: 500
			},
			/**
			 * �Ƿ���������
			 */
			isDown: true,
			/**
			 * ����һ���������÷���ֵ
			 */
			returnValue: true,
			/**
			 * ֵ�ָ���
			 */
			valueSeparator: ",",
			isClear: true
		},
		_create: function () {
			var t = this, p = t.options, el = t.el;
			// p.isClear = true;
			// ������ѡ����ֻ��
			var showcheckbox = p.gridOptions.showcheckbox;
			if (showcheckbox) {
				$(el).attr("readonly", "readonly")
			}
			if (p.isClear) {
				// ׷��ȡ����ť
				t.clearButton = $("<span title='����������' class='clear-trigger'>&nbsp;</span>");
				t.clearButton.click(function () {
					$(el).trigger('beforeclear', [t]);// ��մ����¼�

					t.clearValue();
				});
				$(el).after(t.clearButton);
			}
			// �����ʼ�����
			if (!this.initWidth) {
				var w = $(el).css("width");
				if (w && w != 'auto') {
					this.initWidth = w.substr(0, w.length - 2);// ��ȥpx
				}
			}
			// ���Ŀ��
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
			// ��չ˫������ҳ��ѡ��
			if (p.openPageOptions) {
				if (p.openPageOptions.url != "") {
					$(el).attr('readonly', true);
				}
				// var op = p.openPageOptions;
				// var url = op.url;
				// if (url && url != '') {
				// 	$(el).attr("title", "��˫���ı��򵯳�ѡ��");
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
				// 		// �����˫���򿪵��򲻽�������
				// 		$(el).bind('click', function () {
				// 			t.kill();
				// 		});
				// 		$(el).attr('readonly', true);
				// 	}
				// }
			}
			// ���ñ���
			$(el).mouseover(function () {
				if ($(el).val() != "") {
					$(el).attr('title', $(el).val());
				}
			});
		},
		/**
		 * ����ѡ��� - �̳���yxcombo
		 */
		kill: function () {
			var t = this, el = this.el, p = t.options, container = this.container;

			container.parent().hide();
			t.comboOpen = false;

			// ʧ�����
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
						alert("û��ƥ����,������ѡ��.");
						t.clearValue();
					} else {
						if (id === '' && p.isDown) {
							// ȥ��id ==
							// ''���жϣ��޸���Щ�������û��id�������޷��Զ��ٷ���bug
							// alert("��˫��ѡ��������.");
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
		 * ��ȡ���������
		 */
		getGridOptions: function () {
			if (this.getGrid()) {
				return this.grid.options;
			}
			return this.options.gridOptions;
		},
		/**
		 * ��ȡ������
		 */
		getGrid: function () {
			if (!this.grid) {
				return "";
			}
			return this.grid;
		},

		/*--�����������--*/
		create: function () {
			/*---s:��ʼ��������---*/
			this._super();
			var t = this, p = t.options, el = t.el;
			var gridId = el.attr('id') + "_grid";
			var gridEL = $('<table id=' + gridId + ' ></table>');
			// t.remove();
			this.container.append(gridEL);
			// alert(this.container.height())
			if (p.gridOptions.isTitle) {// ���ڱ�����,��ȥ�������߶�,�����ֹ�����
				p.gridOptions.height = p.height - 90;// ��д����ȥ�ĸ߶�
			} else {
				p.gridOptions.height = p.height - 65;// ��д����ȥ�ĸ߶�
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
			/*---e:��ʼ��������---*/

			/*---s:��˫���¼������ݻ�������---*/
			var grid = $("#" + gridId).data(gridData);
			t.grid = grid;
			var rows = grid.getRows();
			// ����ǵ�ѡ
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
				// ��������ȡ���¼�
				//$(el).bind('keydown', function(event) {
				//	clearTimeout(t.returnFn);
				//	t.eventFlag = 0;
				//});
				// ����ģ��ƥ���¼���
				$(el).bind('keyup', function (event) {
					// 8Ϊbackspance ���˼� 32Ϊ�ո��
					if (event.keyCode > 47 || event.keyCode == 8
						|| event.keyCode == 32) {
						//t.eventFlag = 1;
						clearTimeout(t.returnFn);
						t.returnFn = setTimeout(keyEventFn, 1000);
					}
				});

				// ���̰��� ���»س���Ϊ
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
						if (event.keyCode == 38) {// ��
							var prevRow = selectedRow.prev();
							if (prevRow.size() > 0) {
								grid.clearCheckAll();
								prevRow.trigger('click', [true]);
								scrollTop -= h;
							}
							$(grid.bDiv).scrollTop(scrollTop);
						} else if (event.keyCode == 40) {// ��
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
			/*---e:��˫���¼������ݻ�������---*/

			/*---s:����Ƕ�ѡ�Ļ������ӵ�ѡ�����¼�---*/
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
							// ���ֵ���ڣ�ɾ��������
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
						// ѡ���ٷ��¼�
						row.trigger('after_row_check', [checkbox, row,
							rowData]);
					}
				});
				$("#" + gridId).live("row_success", function (e, rows) {
					// ���ֵ���ڽ��й�ѡ
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
		 * �����ı�ֵ
		 */
		setText: function (text) {
			var t = this, p = t.options, el = t.el;
			$(el).val(text);
			p.rowData[p.nameCol] = text;
		},
		/**
		 * ����rowData
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
		 * �����������ֵ
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
			// ���ûس�ʼ�����
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
		 * ���ֵ
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
				$(el).trigger('clear', []);// ��մ����¼�
				if (t.grid) {
					t.grid.options.param[p.searchName] = '';
					t.grid.reload();
				}
			}
		},
		/**
		 * ˢ�±������
		 */
		reload: function () {
			if (this.getGrid()) {
				this.grid.reload();
			}
		}
	});

})(jQuery);