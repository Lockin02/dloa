
/**
 * �ɱ༭���
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
				text : '����',
				/**
				 * Ĭ�ϵ��������ť�����¼�
				 */
				toAddFn : function(p, g) {
					g.addEditRow();
				}
			},
			isSaveAction : true,
			toSaveConfig : {
				text : '����',
				action : 'saveBatch',
				plusUrl : '',
				/**
				 * Ĭ�ϵ�����水ť�����¼�
				 */
				toSaveFn : function(p, g) {
					g.saveData();
				}
			},
			isReloadAction : true,
			toReloadConfig : {
				text : 'ˢ��',
				/**
				 * Ĭ�ϵ��ˢ�°�ť�����¼�
				 */
				toSaveFn : function(p, g) {
					g.reload();
				}
			}
		},

		/**
		 * ��ʼ�����
		 */
		_create : function() {
			var g = this, el = this.el, p = this.options, cm = p.colModel;
			$(document).click(function(e) {
				var nodeName = $(e.target).context.nodeName;
				if (nodeName != "INPUT" && nodeName != "SELECT") {
					// ������һ���༭����
					if (g.lastEditCell) {
						var lastEditor = g.lastEditCell.data('editor');
						var lastCm = g.lastEditCell.data('cm');
						if (lastEditor) {
							var div = g.lastEditCell.children().first();// �õ���һ�����ӣ����ﷵ�ص���������ie7��append�ᱨ��
							var pv = lastEditor.val();
							g.lastEditCell.data('value', pv);// �õ��༭�ؼ�����ʵֵ
							if (lastCm.process) {
								pv = lastCm.process(lastEditor.val(),
										lastCm.datadictData);
							}
							g.lastEditCell.data('hvalue', pv);// �õ��༭�ؼ�������/����ֵ
							div.append(pv);
							lastEditor.remove();// ��DOM��ɾ������ƥ���Ԫ��
						}
						g.lastEditCell = null;
					}
				} else {
					e.stopPropagation();
				}
			});

		},
		/**
		 * ��д��ȡ��ť���鷽�������ϱ��漰ˢ�°�ť
		 */
		_getButtons : function() {
			var g = this, el = this.el, p = this.options;
			// ���ø��ຯ��
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
		 * ��̬���һ������
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
					if (cm[v].editor.defVal) {// ����Ĭ��ֵ
						defRow[cm[v].name] = cm[v].editor.defVal;
					} else if (cm[v].editor.defValIndex) {// ����Ĭ��ѡ����
						var data = cm[v].editor.data
								? cm[v].editor.data
								: cm[v].datadictData;
						defRow[cm[v].name] = data[cm[v].editor.defValIndex].dataCode;
					}
				}
			}
			// ��ָ�����м�������
			var row = $(g.addOneRow(rowNum, defRow));
			p.total = count + 1;
			row.data("data", defRow);
//			row.children("td").each(function() {
//						g.addCellProp(this);
//					});
			var gridTbody = $('tbody', g.bDiv);
			if (gridTbody[0]) {
				gridTbody.append(row);
			} else {// ����Ϊ�����
				var grid = $('table', g.bDiv);
				grid.empty();
				grid.append("<tbody>");
				$('tbody', g.bDiv).append(row);
			}
		},
		/**
		 * ��д������ݷ���������������ݺ�����пɱ༭����
		 */
		addData : function(data, isFirst) {
			this._super(data, isFirst);
			var g = this, el = this.el, p = this.options;
			$('tbody tr td', g.bDiv).each(function() {
						g.cellProp(this);
					});
		},
		/**
		 * ����ÿһ������
		 * 
		 * @param cell
		 *            �༭���ж���
		 */
		cellProp : function(cell) {
			var g = this, el = this.el, p = this.options;
			cell = $(cell);
			var cellNum = $('td', cell.parent()).index(cell);// �༭����λ��
			var tr = cell.parent();// �༭����
			var th = $('th:eq(' + cellNum + ')', g.hDiv).get(0);// �༭����ͷ
			var row = tr.data('data');
			// ʹ��̬�������Ҳ���д˹���
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
								 * ��ͨ�ı�
								 */
								case 'textfield' :
									c.children().empty();// ɾ��ƥ���Ԫ�ؼ��������е��ӽڵ�

									var editor = $("<input type='text'/>")
											.width(c.width() - 2);
									editor.val(v);
									c.append(editor);
									editor.focus();// append��focus������Ч
									break;
								/**
								 * ���ڿؼ�
								 */
								case 'datefield' :
									break;
								/**
								 * ��̬�ı�
								 */
								case 'text' :
									break;
								/**
								 * ����ѡ��
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
										editor.val(v);// ����Ĭ��ֵ
									}
									c.append(editor);
									// ���û�������д��������Զ�����һ��
									if (!cm.process) {
										cm.process = function(v, row) {
											return $.woo.getDataName(v, data);
										};
									}
									break;
								/**
								 * ������ѡ��
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
		 * ����������
		 */
		saveData : function() {
			var param = {};
			var g = this, p = this.options, c = p.toSaveConfig;
			// ��̬�����ύ����
			$('tbody tr td', g.bDiv).each(function() {
				var cell = $(this);
				var cellNum = $('td', cell.parent()).index(cell);// �༭����λ��
				var tr = cell.parent();// �༭����
				var th = $('th:eq(' + cellNum + ')', g.hDiv).get(0);// �༭����ͷ
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
										alert("�ύ���ݷ����쳣;")
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