$import("Ext.FormPanel");
$import("Ext.data.HttpProxy");
$import("Ext.data.JsonReader");
$import("Ext.data.Store");
$import("Ext.data.MemoryProxy");
$package("Ext.ux.form");
$import("Ext.ux.form.RangeField");
Ext.namespace("Ext.ux.form");
/**
 * �߼�������
 * 
 * @class Ext.ux.form.MySearchForm
 * @extends Ext.FormPanel
 */
Ext.ux.form.MySearchForm = Ext.extend(Ext.FormPanel, {
			/**
			 * ��label���
			 * 
			 * @type Number
			 */
			labelWidth : 80,
			/**
			 * ���ؼ�labelĬ�Ͼ���
			 * 
			 * @type String
			 */
			labelAlign : 'right',
			/**
			 * �Ƿ��Զ�����
			 * 
			 * @type Boolean
			 */
			autoScroll : true,
			/**
			 * �Ƿ��Զ��߶�
			 * 
			 * @type Boolean
			 */
			autoHeight : true,
			/**
			 * ������͸��
			 * 
			 * @type Boolean
			 */
			frame : true,
			/**
			 * ���߿�
			 * 
			 * @type Boolean
			 */
			border : false,
			/**
			 * ��ť�ڱ��ϵ�λ��,3.0 Ĭ����right 2.xĬ����center
			 * 
			 * @type String
			 */
			buttonAlign : 'right',
			/**
			 * ��ѯ���Ƿ�ر�form
			 * 
			 * @type Boolean
			 */
			isCloseSearchForm : true,
			/**
			 * �߼����������ݽṹ
			 * 
			 * @type Array
			 */
			advSearchStructure : [],
			/**
			 * �Ƿ��ǲ�ѯ��Ⱦ���
			 * 
			 * @type Boolean
			 */
			isXtypeGrid : false,
			/**
			 * ��Ĭ�ϸ߶�
			 * 
			 * @type Number
			 */
			height : 400,
			// width : 500,

			/**
			 * ��ʼ�����
			 */
			initComponent : function() {
				this.init();
				Ext.ux.form.MySearchForm.superclass.initComponent.call(this);

			},
			/**
			 * ��ʼ���¼�
			 */
			initEvents : function() {
				Ext.ux.form.MySearchForm.superclass.initEvents.call(this);

			},
			/**
			 * ��ʼ�����ṹ
			 * 
			 * @param {}
			 *            id
			 */
			init : function(id) {
				var mygrid = this.myGrid ? this.myGrid : this;
				var myform = this;
				this.labelWidth = mygrid.searchLabelWidth
						? mygrid.searchLabelWidth
						: 80;// ����ǩ����

				this.formCol = mygrid.formCol;
				if (!mygrid.searchStructure) {
					mygrid.searchStructure = mygrid.structure;
				}

				this.structure = (mygrid.advSearchStructure.length > 0)
						? mygrid.advSearchStructure
						: mygrid.searchStructure;

				this.height = mygrid.searchFormHeight ? mygrid.searchFormHeight
						- 30 : '';
				this.width = mygrid.searchFormWidth
						? mygrid.searchFormWidth
						: 500;
				function doFormItems(structure, formCol) {
					var objName = mygrid.objName;

					var oField = [];// ���ؼ�����

					// ========== ��ʼ���ֶ���Ϣ ��ʼ==============
					for (var i = 0; i < structure.length; i++) {
						var c = structure[i];
						c.formType = c.formType || 'textfield'; // Ĭ������Ϊtextfield
						c.isAdvanceSearch = c.isAdvanceSearch != false
								? true
								: false;
						if (c.isRange == true) {
							c.defaultType = c.formType;
							c.formType = 'rangefield';
						}
						if (c.isAdvanceSearch == true && c.formType != 'hidden') {
							c.anchor = c.anchor ? c.anchor : '95%';
							var fieldId = myform.id + "_" + c.name + '_search';
							var fieldName = c.name;
							switch (c.formType) {
								case 'rangefield' :
									oField[oField.length] = {
										xtype : 'rangefield',
										id : c.id ? c.id : fieldId,
										name : fieldName,
										fieldLabel : c.header,
										anchor : c.anchor,
										listeners : c.listeners,
										isOneRow : c.isOneRow,
										myForm : myform,
										defValue1 : c.defValue1,
										defValue2 : c.defValue2,
										defaultType : c.defaultType
												? c.defaultType
												: 'numberfield'
									};
									break;
								case 'textfield' :
									oField[oField.length] = {
										xtype : 'textfield',
										id : c.id ? c.id : fieldId,
										name : fieldName,
										fieldLabel : c.header,
										anchor : c.anchor,
										vtype : c.vtype ? c.vtype : '',// Ĭ����alpha��ĸ��alphanum��ĸ���֣�email,url
										listeners : c.listeners,
										emptyText : c.emptyText,
										// allowBlank : c.required ? false :
										// true,
										allowBlank : c.queryRequired
												? false
												: true,
										isOneRow : c.isOneRow
									};
									break;
								case 'numberfield' :// ���Ϊ�����ͣ������ĳ�>name>��ʽ

									oField[oField.length] = {
										xtype : 'numberfield',
										id : c.id ? c.id : fieldId,
										name : fieldName,
										fieldLabel : c.header,
										anchor : c.anchor,
										listeners : c.listeners,
										allowBlank : c.queryRequired
												? false
												: true,
										isOneRow : c.isOneRow
									}
									break;
								case 'textarea' :
									oField[oField.length] = {
										xtype : 'textarea',
										id : c.id ? c.id : fieldId,
										name : fieldName,
										fieldLabel : c.header,
										height : c.height,
										anchor : c.anchor,
										isOneRow : c.isOneRow
									};
									break;
								case 'datefield' :
									oField[oField.length] = {
										xtype : 'datefield',
										id : c.id ? c.id : fieldId,
										name : fieldName,
										fieldLabel : c.header,
										value : c.value
												? new Date(c.value)
												: '',
										format : 'Y-m-d',
										anchor : c.anchor,
										// allowBlank : c.required ? false :
										// true,
										allowBlank : c.queryRequired
												? false
												: true,
										isOneRow : c.isOneRow
									}
									break;

								case 'checkbox' :
									oField[oField.length] = {
										xtype : 'checkbox',
										id : c.id ? c.id : fieldId,
										name : fieldName,
										fieldLabel : c.header,
										inputValue : true,
										checked : c.checked == true
												? true
												: false,
										isOneRow : c.isOneRow
									}
									break;
								case 'editgrid' :// ��̬�༭���
									if (c.xtype) {
										c = Class.forName("Ext.ComponentMgr")
												.create(c, c.xtype);
									}
									oField.push(c);
									break;
								case 'combo' :
									myform.comboxFn(c, myform, oField, fieldId,
											fieldName);
									break;
								case 'radio' :
									myform.comboxFn(c, myform, oField, fieldId,
											fieldName);
									break;
								case 'radio2' :
									myform.comboxFn(c, myform, oField, fieldId,
											fieldName);
									break;
								case 'radioArr' :
									myform.comboxFn(c, myform, oField, fieldId,
											fieldName);
									break;
								case 'radioTree' :
									Class.forName("Ext.ux.combox.ComboBoxTree");
									var tree = Class
											.forName("Ext.ux.tree.MyTree")
											.newInstance({
												url : c.url,
												rootVisible : true,
												rootText : c.rootText
														? c.rootText
														: mygrid.boName + '��',
												parentFieldType : c.parentFieldType
														? c.parentFieldType
														: ''
											});
									oField[oField.length] = {
										xtype : 'combotree',
										emptyText : '��ѡ��...',
										fieldLabel : c.header,
										anchor : c.anchor,
										id : c.id ? c.id : fieldId,
										name : c.name,
										hiddenName : fieldName,
										displayField : c.textName,
										keyUrl : c.keyUrl,
										resizable : true,
										listWidth : c.listWidth
												? c.listWidth
												: '',// �趨Ĭ���������
										// width : 250,
										tree : tree,
										isOneRow : c.isOneRow
									};

									break;
								case 'combogrid' :// �������
									// Class
									// .forName("Ext.ux.combox.MyGridComboBox");
									// c.myGrid.selectType = '';
									// c.myGrid.isToExcel = false;
									// c.myGrid.isToPDF = false;
									// if (c.lazyLoad == false) {
									// c.myGrid.lazyLoad = false;
									// c.myGrid = Class
									// .forName("Ext.ComponentMgr")
									// .create(c.myGrid,
									// c.myGrid.xtype);
									// } else
									// c.myGrid.lazyLoad = true;
									// if (!c.myGrid.objName) {//
									// ���gridû��ʼ����ͨ���������name����ȡobjName�������ڼ��ر���ʱ������Ի�ȡ����ʾֵ
									// c.myGrid.objName = c.name.substring(0,
									// c.name.indexOf('.'));
									// }
									// // c.myGrid.removeListener('rowdblclick',
									// // c.myGrid.editFunction);// ���α��˫���༭�¼�
									// oField[oField.length] = {
									// xtype : 'combogrid',
									// fieldLabel : c.header,
									// anchor : c.anchor,
									// id : c.id ? c.id : fieldId,
									// name : c.name,
									// hiddenName : fieldName,
									// myGrid : c.myGrid,
									// gridName : c.gridName,// ���������ʾ������
									// gridValue : c.gridValue,
									// isOneRow : c.isOneRow
									// // �������ʵ��ֵ����
									// };
									// break;

									Class
											.forName("Ext.ux.combox.MyGridComboBox");
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
									if (c.lazyLoad == false) {
										c.myGrid.lazyLoad = false;
										c.myGrid = Class
												.forName("Ext.ComponentMgr")
												.create(c.myGrid,
														c.myGrid.xtype);
									} else
										c.myGrid.lazyLoad = true;
									if (!c.myGrid.objName) {// ���gridû��ʼ����ͨ���������name����ȡobjName�������ڼ��ر���ʱ������Ի�ȡ����ʾֵ
										c.myGrid.objName = c.name.substring(0,
												c.name.indexOf('.'));
									}
									// c.myGrid.removeListener('rowdblclick',
									// c.myGrid.editFunction);// ���α��˫���༭�¼�
									if (c.hiddenName)
										oField.push(new Ext.form.Hidden({
													name : c.hiddenName,
													disabled : c.disabled,
													hideTag : true
												}));
									oField.push({
												xtype : 'combogrid',
												fieldLabel : c.header,
												anchor : c.anchor,
												id : c.id ? c.id : fieldId,
												name : c.name,
												myGrid : c.myGrid,
												myForm : myform,
												listeners : c.listeners,
												hiddenFieldId : c.hiddenName
														? oField[oField.length
																- 1].id
														: null,
												gridName : c.gridName,// ���������ʾ������
												gridValue : c.gridValue,
												isOneRow : c.isOneRow,
												listWidth : c.listWidth
														? c.listWidth
														: '500'
											});
									break;

								case 'fieldset' :
									oField[oField.length] = {
										xtype : 'fieldset',
										title : c.title,
										collapsible : true,
										autoHeight : true,
										items : doFormItems(c.items, 2),
										isOneRow : c.isOneRow
									};
									break;
							}// switch����
						}// if����
					}// for����

					var fieldArr = []; // �������
					var evenArr = []; // �����
					var oddArr = []; // �ұ���
					var j = 1;// j���ڹ�����ż�к���ʱ�������б������¸�ֵ
					for (var i = 0, l = oField.length; i < l; i++) {
						if (formCol == 1 || oField[i].hideTag == true
								|| oField[i].xtype == 'hidden') { // ���л���������
							// ֱ�Ӹ�ֵ
							fieldArr.push(oField[i]);
						} else {
							if (oField[i].isOneRow != true) {
								if (j++ % 2 == 0)
									oddArr.push(oField[i]);
								else
									evenArr.push(oField[i]);
							} else {
								fieldArr = pushColumn(evenArr, oddArr, fieldArr); // �����ж���
								fieldArr.push(oField[i]); // ����oneRow����
								// ��ձ������¸�ֵ
								evenArr = [];
								oddArr = [];
								j = 1;
							}
						}
					}
					// ���һ�������OneRow��������ѭ���Ѿ�������ϣ���������������ж�����
					if (!oField[oField.length - 1].isOneRow) {
						fieldArr = pushColumn(evenArr, oddArr, fieldArr); // �����ж���
					}

					return fieldArr;
				};
				// �����ж���
				function pushColumn(evenArr, oddArr, fieldArr) {
					var columnField = [];
					if (evenArr.length != 0)
						columnField.push({
									columnWidth : .5,
									layout : 'form',
									items : evenArr
								});
					if (oddArr.length != 0)
						columnField.push({
									columnWidth : .5,
									layout : 'form',
									items : oddArr
								});
					if (columnField.length != 0)
						fieldArr.push({
									layout : 'column',
									items : columnField
								});
					return fieldArr;
				}

				this.items = doFormItems(this.structure, this.formCol);// �������ؼ�

				this.buttons = [{
							text : '����',
							iconCls : 'save',
							handler : function() {
								doSubmitSearchForm()
							}
						}, {
							text : '����',
							iconCls : 'clean',
							handler : function() {
								myform.getForm().reset();
							}
						}];
				this.buttons[this.buttons.length] = {
					text : '�ر�',
					iconCls : 'close',
					handler : function() {
						myform.closeWin();
					}
				};

				// �ύ������
				function doSubmitSearchForm() {
					if (myform.form.isValid()) {
						var searchFields = [];
						var searchValues = [];
						var basicForm = myform.form;
						for (var i = 0, il = myform.structure.length; i < il; i++) {
							var c = myform.structure[i];
							c.isAdvanceSearch = c.isAdvanceSearch != false
									? true
									: false;
							c.viewSearch = c.viewSearch != true ? false : true;
							if (c.isAdvanceSearch == true
									&& c.formType != 'hidden'
									&& c.viewSearch == false) {
								if (c.isRange == true) {
									var formField1 = basicForm.findField(c.name
											+ "1");
									var formField2 = basicForm.findField(c.name
											+ "2");
									var v1, v2 = "";
									if (formField1
											&& !Ext.isEmpty(formField1
													.getValue())) {
										searchFields.push(c.name + "1");
										if (c.defaultType == "datefield") {
											v1 = formField1.getValue()
													.format('Y-m-d');
										} else {
											v1 = formField1.getValue();
										}
										searchValues.push(v1);
									}
									if (formField2
											&& !Ext.isEmpty(formField2
													.getValue())) {
										searchFields.push(c.name + "2");
										if (c.defaultType == "datefield") {
											v2 = formField2.getValue()
													.format('Y-m-d');
										} else {
											v2 = formField2.getValue();
										}
										searchValues.push(v2);
									}
								} else {
									if (c.hiddenName) {
										c.name = c.hiddenName;
									}
									var formField = basicForm.findField(c.name);
									if (formField != null
											&& !Ext.isEmpty(formField
													.getValue())) {
										searchFields.push(c.name);
										if (formField.xtype == 'datefield') {
											var value = formField.getValue()
													.format('Y-m-d');
										} else
											var value = formField.getValue();
										searchValues.push(value);
									}
								}
							}
						}
						// ��̬�����ύ�����Ĳ���,����mygrid store
						var searchMygrid = mygrid.isXtypeGrid ? Ext
								.getCmp(mygrid.id) : mygrid;
						searchMygrid.searchFields = searchFields;
						searchMygrid.searchValues = searchValues;
						if (mygrid.searchUrl == null) {
							searchMygrid.getStore().reload();
						} else {
							searchMygrid.store.proxy = new Ext.data.HttpProxy({
										url : mygrid.urlAction
												+ mygrid.searchUrl
									});
							mygrid.store.reload();
						}
						if (mygrid.isCloseSearchForm != false)
							myform.closeWin();
					}
				}
			},

			closeWin : function() {
				this.ownerCt.hide();
			},

			/*
			 * @���ܣ���ʼ��combo�ؼ�����
			 * 
			 */
			initCombo : function(c) {
				var ds = null;
				if (typeof c.fobj != 'object') {// fobjΪ��̬json��������
					var reader = new Ext.data.JsonReader({
								totalProperty : 'totalSize',
								root : 'list'
							}, [{
										name : c.valueField
									}, {
										name : c.displayField
									}, {
										name : c.tips
									}]);

					ds = new Ext.data.Store({
								proxy : new Ext.data.HttpProxy({
											url : c.url
										}),
								reader : reader
							});

					ds.on('beforeload', function() {
								var para = {
									pagesize : c.pageSize,
									name : c.fobj
								};
								Ext.apply(ds.baseParams, para);
							});

				} else {
					ds = new Ext.data.Store({
								proxy : new Ext.data.MemoryProxy(c.fobj),
								reader : new Ext.data.JsonReader({}, [{// new
											// Ext.data.ArrayReader
											name : c.valueField
										}, {
											name : c.displayField
										}, {
											name : c.tips
										}])
							});
					ds.reload();
				}

				return ds;

			},
			comboxFn : function(c, myform, oField, fieldId, fieldName) {
				// ��ʼ�������б�����
				c.valueField = c.valueField || 'dataCode';
				c.displayField = c.displayField || 'dataName';
				c.tips = c.tips || 'tips';
				var ds = myform.initCombo(c);
				oField[oField.length] = {
					xtype : 'combo',
					id : c.id ? c.id : fieldId,
					name : fieldName,
					fieldLabel : c.header,
					anchor : c.anchor,
					isOneRow : c.isOneRow,
					store : ds,
					// value : c.value,
					tpl : '<tpl for="."><div  ext:qtip="{' + c.tips
							+ '}" class="x-combo-list-item">{' + c.displayField
							+ '}</div></tpl>',
					displayField : c.displayField,
					valueField : c.valueField,
					emptyText : '��ѡ��...',
					typeAhead : true,// ����������Ƿ��Զ�ƥ��ʣ�ಿ���ı�
					triggerAction : 'all',// ����������ťʱִ�е�Ĭ�ϲ���
					selectOnFocus : true,// ����ý���ʱ����ѡ��һ���Ѿ����ڵı���
					forceSelection : c.forceSelection ? false : true,// ����ֵ�Ƿ�Ϊ��ѡ�б��д��ڵ�ֵ
					pageSize : c.pageSize ? c.pageSize : '',
					queryParam : 'searchValue',// �������û�Ѵ�combo�����ֵ��Ϊ��������action,Ĭ����query
					minChars : 1,// �Զ�ѡ��ǰ��������С�ַ�����
					resizable : true,
					allowBlank : c.queryRequired ? false : true,
					listeners : c.listeners
				}
			}
		})