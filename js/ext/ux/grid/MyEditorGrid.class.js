Ext.namespace('Ext.ux.grid');
Ext.ux.grid.MyEditorGrid = Ext.extend(Ext.grid.EditorGridPanel, {
	/**
	 * Ĭ�������ֶ�
	 * 
	 * @type
	 */
	defaultSortField : 'id',

	/**
	 * Ĭ������ʽ ASC������ DESC������
	 * 
	 * @type String
	 */
	defaultSortdirection : 'ASC',
	/**
	 * �Ƿ�ʹ����������Դ
	 * 
	 * @type
	 */
	fnSort : false,
	/**
	 * �����ֶ�
	 * 
	 * @type
	 */
	groupField : null,
	/**
	 * �Ƿ��н�����
	 * 
	 * @type Boolean
	 */
	// loadMask : true,
	/**
	 * ������ṹ
	 * 
	 * @type String
	 */
	structure : '',
	/**
	 * ��ֵ����(����)
	 * 
	 * @type String
	 */
	params : '',
	/**
	 * ִ��ɾ������url
	 * 
	 * @type
	 */
	deleteUrl : null,
	/**
	 * ��ʼ��url ���Բ�ʹ��ͨ��������������
	 * 
	 * @type String
	 */
	url : '',
	/**
	 * ���Ĭ�ϸ߶�
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
	 * �Ƿ���۵�
	 * 
	 * @type Boolean
	 */
	collapsible : true,
	/**
	 * ��������۵�
	 * 
	 * @type Boolean
	 */
	titleCollapse : true,
	/**
	 * �������б༭
	 * 
	 * @type Number
	 */
	clicksToEdit : 1,
	/**
	 * ������͸��
	 * 
	 * @type Boolean
	 */
	frame : true,
	/**
	 * �Ƿ�߿�
	 * 
	 * @type Boolean
	 */
	border : true,
	/**
	 * ÿ��Ĭ�Ͽ��
	 * 
	 * @type Number
	 */
	fieldwidth : 150,
	/**
	 * �Ƿ���ʾ������ť
	 * 
	 * @type Boolean
	 */
	isAddButton : true,
	/**
	 * �Ƿ���ʾɾ����ť
	 * 
	 * @type Boolean
	 */
	isDelButton : true,
	/**
	 * �Ƿ���ʾˢ�°�ť
	 * 
	 * @type Boolean
	 */
	isRefresh : true,
	/**
	 * ɾ����ť����
	 * 
	 * @type String
	 */
	delButtonText : 'ɾ��',
	/**
	 * ɾ����ִ���¼�
	 * 
	 * @type
	 */
	afterDel : null,
	/**
	 * ���ö�̬�滻���Ԫ���У�actionIndex[x] = true
	 * 
	 * @type
	 */
	actionIndex : [],
	/**
	 * ��¼������ѡ����к�
	 * 
	 * @type Number
	 */
	lastSelectedRow : 0,
	/**
	 * ���store�¼���
	 * 
	 * @type
	 */
	storeListeners : {},
	listeners : {
		beforeedit : function(e) {
			e.grid.getSelectionModel().selectRow(e.row);
		}
	},
	// ��ʼ�����
	initComponent : function() {
		if (this.structure != '') {
			this.initStructure();
		}
		Ext.ux.grid.MyEditorGrid.superclass.initComponent.call(this);
	},
	/**
	 * ���ñ�����Ĭ��ֵ
	 * 
	 * @param {}
	 *            recordMap
	 */
	setRecordMap : function(recordMap) {
		var myEditorGrid = this;
		for (var i = 0; i < this.structure.length; i++) {
			var c = this.structure[i];
			// �ж��Ƿ��˱���������ƣ���������Ĭ��ֵΪ��ֵ
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
	 * ����������ݽṹ��ȡeditorʵ��
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
				// �����ֻ����������⴦��datefield
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
							emptyText : '��ѡ��...',
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
							emptyText : '��ѡ��...',
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
							emptyText : '��ѡ��...',
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
					// ����ѡ�����������ֵ
					tree.on('click', function(node, e) {
						var editRecord = myEditorGrid.getSelectionModel()
								.getSelected();
						editRecord.set(node.getOwnerTree().hiddenName, node.id);
					});
				}
				editorVar = new Ext.ux.combox.ComboBoxTree({
							allowBlank : c.required ? false : true,
							emptyText : '��ѡ��...',
							keyUrl : c.keyUrl,
							hiddenField : c.hiddenName ? h.id : null,
							tree : tree
						});
				break;
			case 'combogrid' :// �������
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
			case 'checkbox' :// δ���
				editorVar = new Ext.form.Checkbox({
							name : c.name,
							inputValue : 1,
							checked : c.checked == true ? true : false
						});
				break;
			case 'button' :// �������
				c.editorType = "";
				c.renderer = function() {
					return "<input type='button' class='btn' value='���' onclick="
							+ c.onclick + " >";
				}
				break;
		}
		return editorVar;
	},
	initStructure : function() {
		var myEditorGrid = this;
		if (myEditorGrid.myForm)
			// ����Ǳ�����趨ֻ�������򲻸���
			if (!this.isView) {
				this.isView = myEditorGrid.myForm.isViewForm;
			}

		this.sm = new Ext.grid.RowSelectionModel({
					singleSelect : true
				});
		var oCM = []; // ��ģʽ����
		oCM.push(new Ext.grid.RowNumberer()); // �к�������
		var oRecord = []; // ����ƥ�����������飬����ȡ����ת����Record
		function doGridItems(structure) {
			for (var i = 0; i < structure.length; i++) {
				var c = structure[i];
				var editorVar = "";
				if (c.header) {
					// ���⴦��dateֻ������
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
					getCellEditor : function(colIndex, rowIndex, comboE) { // ��дgetCellEditor������չ��ÿ�������Է�������ؼ�
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
					// this.storeListeners//Ϊ��ʹĬ��load�¼�����ִ�У��������������storeListeners��ֵ
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
		// ѭ�����store�¼�
		for (var f in this.storeListeners) {
			myEditorGrid.store.addListener(f, this.storeListeners[f]);
		}
		this.store.grid = this;// ����store�¼������grid
		// url��Ϊ�����ʼ����������
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
				// ��Ӱ�ť
				var addButton = {
					text : '���',
					iconCls : 'add',
					scope : this,
					handler : this.addFn
				};
				this.tbar.push(addButton);
			}
			if (this.isDelButton) {
				// ɾ����ť
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
				// ˢ�°�ť
				var refreshButton = {
					text : 'ˢ��',
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
		// ����Զ��尴ť
		if (!Ext.isEmpty(this.pluginsButtons)) {
			this.tbar.push(this.pluginsButtons);
		}
	},
	reset : function() {

	},
	/**
	 * ��������
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
	 * ɾ������
	 */
	delFn : function() {
		var myEditorGrid = this;
		var e = myEditorGrid.getSelectionModel().getSelected();
		if (!e) {
			myEditorGrid.getSelectionModel().selectFirstRow();
			e = myEditorGrid.getSelectionModel().getSelected();
		} else {
			if (e.data.id && myEditorGrid.deleteUrl != null) {
				var deleteBo = myEditorGrid.title ? myEditorGrid.title : '����';
				Ext.MessageBox.confirm("ɾ��", "��ȷ��Ҫɾ����" + deleteBo + "��?",
						function(x) {
							if (x == "yes") {

								Ext.Ajax.request({
											url : myEditorGrid.deleteUrl,
											method : 'POST',
											success : function(result, request) {
												var json = result.responseText;
												var o = eval("(" + json + ")");
												// �����̨ɾ���ɹ���ɾ��ҳ���¼
												if (o.success == true) {
													myEditorGrid.stopEditing();
													myEditorGrid.store
															.remove(e);
													// ɾ��֮���¼�
													if (myEditorGrid.afterDel) {
														myEditorGrid
																.afterDel(e);
													}

												}
												Ext.Msg.info({
															message : o.message
																	? o.message
																	: 'ɾ�����ݳɹ���'
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