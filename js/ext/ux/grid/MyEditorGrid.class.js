Ext.namespace('Ext.ux.grid');
Ext.ux.grid.MyEditorGrid = Ext.extend(Ext.grid.EditorGridPanel, {
	/**
	 * 默认排序字段
	 * 
	 * @type
	 */
	defaultSortField : 'id',

	/**
	 * 默认排序方式 ASC：升序 DESC：降序
	 * 
	 * @type String
	 */
	defaultSortdirection : 'ASC',
	/**
	 * 是否使用排序数据源
	 * 
	 * @type
	 */
	fnSort : false,
	/**
	 * 分组字段
	 * 
	 * @type
	 */
	groupField : null,
	/**
	 * 是否有进度条
	 * 
	 * @type Boolean
	 */
	// loadMask : true,
	/**
	 * 储存表格结构
	 * 
	 * @type String
	 */
	structure : '',
	/**
	 * 传值参数(数组)
	 * 
	 * @type String
	 */
	params : '',
	/**
	 * 执行删除操作url
	 * 
	 * @type
	 */
	deleteUrl : null,
	/**
	 * 初始化url 可以不使用通过动作加载数据
	 * 
	 * @type String
	 */
	url : '',
	/**
	 * 表格默认高度
	 * 
	 * @type Number
	 */
	height : 180,
	/**
	 * bodyStyle
	 * 
	 * @type String
	 */
	bodyStyle : "width:100%;height:100%;",
	/**
	 * 是否可折叠
	 * 
	 * @type Boolean
	 */
	collapsible : true,
	/**
	 * 点击标题折叠
	 * 
	 * @type Boolean
	 */
	titleCollapse : true,
	/**
	 * 单击进行编辑
	 * 
	 * @type Number
	 */
	clicksToEdit : 1,
	/**
	 * 表单背景透明
	 * 
	 * @type Boolean
	 */
	frame : true,
	/**
	 * 是否边框
	 * 
	 * @type Boolean
	 */
	border : true,
	/**
	 * 每列默认宽度
	 * 
	 * @type Number
	 */
	fieldwidth : 150,
	/**
	 * 是否显示新增按钮
	 * 
	 * @type Boolean
	 */
	isAddButton : true,
	/**
	 * 是否显示删除按钮
	 * 
	 * @type Boolean
	 */
	isDelButton : true,
	/**
	 * 是否显示刷新按钮
	 * 
	 * @type Boolean
	 */
	isRefresh : true,
	/**
	 * 删除按钮名称
	 * 
	 * @type String
	 */
	delButtonText : '删除',
	/**
	 * 删除后执行事件
	 * 
	 * @type
	 */
	afterDel : null,
	/**
	 * 设置动态替换表格元素列：actionIndex[x] = true
	 * 
	 * @type
	 */
	actionIndex : [],
	/**
	 * 记录表格最后选择的行号
	 * 
	 * @type Number
	 */
	lastSelectedRow : 0,
	/**
	 * 表格store事件集
	 * 
	 * @type
	 */
	storeListeners : {},
	listeners : {
		beforeedit : function(e) {
			e.grid.getSelectionModel().selectRow(e.row);
		}
	},
	// 初始化组件
	initComponent : function() {
		if (this.structure != '') {
			this.initStructure();
		}
		Ext.ux.grid.MyEditorGrid.superclass.initComponent.call(this);
	},
	/**
	 * 设置表格添加默认值
	 * 
	 * @param {}
	 *            recordMap
	 */
	setRecordMap : function(recordMap) {
		var myEditorGrid = this;
		for (var i = 0; i < this.structure.length; i++) {
			var c = this.structure[i];
			// 判断是否传了表单上组件名称，有则设置默认值为其值
			if (c.defName && myEditorGrid.myForm) {
				recordMap[c.name] = myEditorGrid.myForm.form
						.findField(c.defName).getValue();
			} else
				recordMap[c.name] = c.defValue ? c.defValue : '';
			if (c.defHiddenName && myEditorGrid.myForm) {
				recordMap[c.hiddenName] = myEditorGrid.myForm.form
						.findField(c.defHiddenName).getValue();
			} else
				recordMap[c.hiddenName] = c.defHidenValue
						? c.defHidenValue
						: '';
		}
	},
	/**
	 * 根据组件数据结构获取editor实例
	 */
	getEditorVar : function(c) {
		var myEditorGrid = this;
		var editorVar = "";
		if (c.hiddenName) {
			var h = new Ext.form.Hidden({
						name : c.hiddenName,
						isHidden : true
					});
		}
		switch (c.editorType) {
			case 'textfield' :
				editorVar = new Ext.form.TextField({
							allowBlank : c.required ? false : true,
							maxLength : c.maxLength ? c.maxLength : 100
						});
				break;
			case 'hidden' :
				editorVar = new Ext.form.Hidden({
							name : c.name,
							type : c.type,
							value : c.value

						});
				c.hidden = true;
				break;
			case 'numberfield' :
				editorVar = new Ext.form.NumberField({
							name : c.name,
							allowNegative : false,
							allowBlank : c.required ? false : true,
							maxValue : c.maxValue ? c.maxValue : 100000000
						});
				break;
			case 'datefield' :
				if (!c.type) {
					c.type = "date";
					c.renderer = c.renderer ? c.renderer : Ext.util.Format
							.dateRenderer('Y-m-d');
				}
				editorVar = new Ext.form.DateField({
							name : c.name,
							format : "Y-m-d"
						});
				// 如果是只读表格则特殊处理datefield
				if (myEditorGrid.isView == true) {
					editorVar.setReadOnly(true);
				}
				break;
			case 'combo' :
				c.valueField = c.valueField || 'dataCode';
				c.displayField = c.displayField || 'dataName';
				editorVar = new Ext.form.ComboBox({
							allowBlank : c.required ? false : true,
							displayField : c.displayField,
							valueField : c.valueField,
							hiddenName : c.name,
							listeners : c.listeners,
							emptyText : '请选择...',
							typeAhead : true,
							triggerAction : 'all',
							selectOnFocus : true,
							mode : 'local',
							tpl : '<tpl for="."><div class="x-combo-list-item">{'
									+ c.displayField + '}</div></tpl>',
							store : new Ext.data.Store({
										autoLoad : true,
										proxy : new Ext.data.MemoryProxy(c.fobj),
										reader : new Ext.data.JsonReader({}, [{
															name : c.valueField
														}, {
															name : c.displayField
														}])
									})
						});
				break;
			case 'comboEx' :
				c.valueField = c.valueField || 'dataCode';
				c.displayField = c.displayField || 'dataName';
				editorVar = new Ext.ux.combox.MyComboBox({
							allowBlank : c.required ? false : true,
							displayField : c.displayField,
							valueField : c.valueField,
							hideFistTrigger : c.hideFistTrigger
									? c.hideFistTrigger
									: false,
							name : c.name,
							hiddenName : c.hiddenName ? c.hiddenName : null,
							listeners : c.listeners,
							emptyText : '请选择...',
							typeAhead : true,
							triggerAction : 'all',
							selectOnFocus : true,
							mode : 'local',
							tpl : '<tpl for="."><div class="x-combo-list-item">{'
									+ c.displayField + '}</div></tpl>',
							store : new Ext.data.Store({
										autoLoad : true,
										proxy : Class
												.forName("Ext.data.MemoryProxy")
												.newInstance(c.fobj),
										reader : new Ext.data.JsonReader({}, [{
															name : c.valueField
														}, {
															name : c.displayField
														}, {
															name : c.tips
																	|| 'tips'
														}])
									})
						});
				if (c.hiddenName) {
					var c2 = c;
					editorVar.on('select', function(t) {
								var editRecord = myEditorGrid
										.getSelectionModel().getSelected();
								editRecord.set(t.hiddenName, t.getRawValue());
							})

				}
				break;
			case 'checkTree' :
				var tree = new Ext.ux.tree.MyTree({
							url : c.url,
							checkModel : c.checkModel
									? c.checkModel
									: 'cascade',
							onlyLeafCheckable : false
						});

				editorVar = new Ext.ux.combox.ComboBoxCheckTree({
							allowBlank : c.required ? false : true,
							emptyText : '请选择...',
							hiddenField : c.hiddenName ? h.id : null,
							tree : tree
						});
				break;
			case 'radioTree' :
				var tree = new Ext.ux.tree.MyTree({
							url : c.url,
							listeners : c.listeners,
							hiddenName : c.hiddenName
						});

				if (c.hiddenName) {
					// 设置选择变量隐藏域值
					tree.on('click', function(node, e) {
						var editRecord = myEditorGrid.getSelectionModel()
								.getSelected();
						editRecord.set(node.getOwnerTree().hiddenName, node.id);
					});
				}
				editorVar = new Ext.ux.combox.ComboBoxTree({
							allowBlank : c.required ? false : true,
							emptyText : '请选择...',
							keyUrl : c.keyUrl,
							hiddenField : c.hiddenName ? h.id : null,
							tree : tree
						});
				break;
			case 'combogrid' :// 下拉表格
				c.myGrid.hiddenName = c.hiddenName;
				c.myGrid.gridValue = c.gridValue;
				c.myGrid.selectType = c.myGrid.selectType
						? c.myGrid.selectType
						: '';
				c.myGrid.isToExcel = false;
				c.myGrid.isToPDF = false;
				c.myGrid.isReturn = false;
				c.myGrid.viewConfig = {
					forceFit : true
				};
				c.myGrid.height = 200;
				// c.myGrid = Ext.ComponentMgr.create(c.myGrid,
				// c.myGrid.xtype);

				editorVar = new Ext.ux.combox.MyGridComboBox({
							allowBlank : c.required ? false : true,
							myGrid : c.myGrid,
							gridName : c.gridName,
							gridValue : c.gridValue,
							listeners : c.listeners,
							afterInitMyGrid : function(combo) {
								combo.myGrid.on('rowdblclick', function(t) {
											var record = t.getSelectionModel()
													.getSelected();
											var editRecord = myEditorGrid
													.getSelectionModel()
													.getSelected();
											editRecord.set(t.hiddenName,
													record.data[t.gridValue]);
										});
							}

						});
				break;
			case 'checkbox' :// 未完成
				editorVar = new Ext.form.Checkbox({
							name : c.name,
							inputValue : 1,
							checked : c.checked == true ? true : false
						});
				break;
			case 'button' :// 下拉表格
				c.editorType = "";
				c.renderer = function() {
					return "<input type='button' class='btn' value='添加' onclick="
							+ c.onclick + " >";
				}
				break;
		}
		return editorVar;
	},
	initStructure : function() {
		var myEditorGrid = this;
		if (myEditorGrid.myForm)
			// 如果是表格有设定只读属性则不覆盖
			if (!this.isView) {
				this.isView = myEditorGrid.myForm.isViewForm;
			}

		this.sm = new Ext.grid.RowSelectionModel({
					singleSelect : true
				});
		var oCM = []; // 列模式数组
		oCM.push(new Ext.grid.RowNumberer()); // 行号生成器
		var oRecord = []; // 用于匹配表格数据数组，将获取数据转换成Record
		function doGridItems(structure) {
			for (var i = 0; i < structure.length; i++) {
				var c = structure[i];
				var editorVar = "";
				if (c.header) {
					// 特殊处理date只读操作
					if (c.editorType == 'unEdit'
							|| (myEditorGrid.isView == true && c.editorType != 'datefield'))
						c.editorType = "";
					else
						c.editorType = c.editorType
								? c.editorType
								: 'textfield';

					if (c.hiddenName) {

						oRecord.push({
									name : c.hiddenName,
									mapping : c.hiddenName
								});

					}
					editorVar = myEditorGrid.getEditorVar(c);
				}
				if (c.header) {
					oCM.push({
								header : c.header,
								dataIndex : c.name,
								hidden : c.hidden || false,
								width : c.width
										? c.width
										: myEditorGrid.fieldwidth,
								align : c.align ? c.align : 'left',
								editor : editorVar,
								summaryType : c.summaryType,
								renderer : c.renderer
										? c.renderer
										: extUtil.toolTip
							});
				}
				oRecord.push({
							name : c.name,
							mapping : c.mapping,
							type : c.type,
							dateFormat : c.type == 'date'
									|| c.type == 'datetime'
									? 'Y-m-d H:i:s'
									: ''
						});
			}
		}
		doGridItems(this.structure);
		this.colModel = new Ext.grid.ColumnModel({
					columns : oCM,
					comboE : [],
					getCellEditor : function(colIndex, rowIndex, comboE) { // 重写getCellEditor方法扩展：每个表格可以返回任意控件
						// var field = this.getDataIndex(colIndex);
						if (myEditorGrid.actionIndex[colIndex]) {
							if (comboE) {
								this.comboE[rowIndex] = comboE;
								return comboE;
							} else if (this.comboE[rowIndex]) {
								return this.comboE[rowIndex];
							}
						}
						return Ext.grid.ColumnModel.prototype.getCellEditor
								.call(this, colIndex, rowIndex);
					}
				});
		this.record = Ext.data.Record.create(oRecord);

		var reader = new Ext.data.JsonReader({
					totalProperty : "totalSize",
					root : "collection",
					id : 'id'
				}, myEditorGrid.record);
		if (this.fnSort) {
			this.store = new Ext.data.GroupingStore({
				reader : reader,
				groupField : this.groupField,
				sortInfo : {
					field : this.defaultSortField,
					direction : this.defaultSortdirection
				}
					// listeners :
					// this.storeListeners//为了使默认load事件最先执行，不能在这里进行storeListeners赋值
				});
		} else {
			this.store = new Ext.data.Store({
						reader : reader
					});
		}
		this.store.on('load', function(store) {
					myEditorGrid.getSelectionModel()
							.selectRow(myEditorGrid.lastSelectedRow);
				});
		// 循环添加store事件
		for (var f in this.storeListeners) {
			myEditorGrid.store.addListener(f, this.storeListeners[f]);
		}
		this.store.grid = this;// 方便store事件里调用grid
		// url不为空则初始化加载数据
		if (myEditorGrid.url != '') {
			this.store.proxy = new Ext.data.HttpProxy({
						url : myEditorGrid.url
					});
			if (myEditorGrid.params != '') {
				this.store.load({
							params : myEditorGrid.params
						});
			} else {
				this.store.reload();
			}
		}

		if (!this.tbar)
			this.tbar = [];
		if (this.isView != true) {

			if (this.isAddButton) {
				// 添加按钮
				var addButton = {
					text : '添加',
					iconCls : 'add',
					scope : this,
					handler : this.addFn
				};
				this.tbar.push(addButton);
			}
			if (this.isDelButton) {
				// 删除按钮
				var delButton = {
					id : this.delButtonId,
					text : this.delButtonText,
					scope : this,
					iconCls : 'remove',
					handler : this.delFn
				};
				this.tbar.push(delButton);
			}
			if (this.isRefresh) {
				// 刷新按钮
				var refreshButton = {
					text : '刷新',
					iconCls : 'icon-reload',
					handler : function() {
						if (myEditorGrid.store.proxy) {
							myEditorGrid.store.reload();
						}
					}
				};
				this.tbar.push(refreshButton);
			}
		}
		// 添加自定义按钮
		if (!Ext.isEmpty(this.pluginsButtons)) {
			this.tbar.push(this.pluginsButtons);
		}
	},
	reset : function() {

	},
	/**
	 * 新增函数
	 */
	addFn : function() {
		var recordMap = {};
		var myEditorGrid = this;
		myEditorGrid.setRecordMap(recordMap);
		var e = new myEditorGrid.record(recordMap);
		var rowNum = myEditorGrid.store.getCount();
		myEditorGrid.store.insert(rowNum, e);
		myEditorGrid.startEditing(rowNum, 0);
	},
	/**
	 * 删除函数
	 */
	delFn : function() {
		var myEditorGrid = this;
		var e = myEditorGrid.getSelectionModel().getSelected();
		if (!e) {
			myEditorGrid.getSelectionModel().selectFirstRow();
			e = myEditorGrid.getSelectionModel().getSelected();
		} else {
			if (e.data.id && myEditorGrid.deleteUrl != null) {
				var deleteBo = myEditorGrid.title ? myEditorGrid.title : '数据';
				Ext.MessageBox.confirm("删除", "您确定要删除该" + deleteBo + "吗?",
						function(x) {
							if (x == "yes") {

								Ext.Ajax.request({
											url : myEditorGrid.deleteUrl,
											method : 'POST',
											success : function(result, request) {
												var json = result.responseText;
												var o = eval("(" + json + ")");
												// 如果后台删除成功则删除页面记录
												if (o.success == true) {
													myEditorGrid.stopEditing();
													myEditorGrid.store
															.remove(e);
													// 删除之后事件
													if (myEditorGrid.afterDel) {
														myEditorGrid
																.afterDel(e);
													}

												}
												Ext.Msg.info({
															message : o.message
																	? o.message
																	: '删除数据成功！'
														});

											},
											failure : doFailure,
											params : {
												ids : e.data.id
											}
										});

							}
						});
			} else {
				myEditorGrid.stopEditing();
				myEditorGrid.store.remove(e);
			}
		}

	}
});
Ext.reg('myeditorgrid', Ext.ux.grid.MyEditorGrid);