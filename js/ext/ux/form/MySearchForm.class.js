$import("Ext.FormPanel");
$import("Ext.data.HttpProxy");
$import("Ext.data.JsonReader");
$import("Ext.data.Store");
$import("Ext.data.MemoryProxy");
$package("Ext.ux.form");
$import("Ext.ux.form.RangeField");
Ext.namespace("Ext.ux.form");
/**
 * 高级搜索表单
 * 
 * @class Ext.ux.form.MySearchForm
 * @extends Ext.FormPanel
 */
Ext.ux.form.MySearchForm = Ext.extend(Ext.FormPanel, {
			/**
			 * 表单label宽度
			 * 
			 * @type Number
			 */
			labelWidth : 80,
			/**
			 * 表单控件label默认局右
			 * 
			 * @type String
			 */
			labelAlign : 'right',
			/**
			 * 是否自动滚动
			 * 
			 * @type Boolean
			 */
			autoScroll : true,
			/**
			 * 是否自动高度
			 * 
			 * @type Boolean
			 */
			autoHeight : true,
			/**
			 * 表单背景透明
			 * 
			 * @type Boolean
			 */
			frame : true,
			/**
			 * 表单边框
			 * 
			 * @type Boolean
			 */
			border : false,
			/**
			 * 按钮在表单上的位置,3.0 默认是right 2.x默认是center
			 * 
			 * @type String
			 */
			buttonAlign : 'right',
			/**
			 * 查询完是否关闭form
			 * 
			 * @type Boolean
			 */
			isCloseSearchForm : true,
			/**
			 * 高级搜索表单数据结构
			 * 
			 * @type Array
			 */
			advSearchStructure : [],
			/**
			 * 是否是查询渲染表格
			 * 
			 * @type Boolean
			 */
			isXtypeGrid : false,
			/**
			 * 表单默认高度
			 * 
			 * @type Number
			 */
			height : 400,
			// width : 500,

			/**
			 * 初始化组件
			 */
			initComponent : function() {
				this.init();
				Ext.ux.form.MySearchForm.superclass.initComponent.call(this);

			},
			/**
			 * 初始化事件
			 */
			initEvents : function() {
				Ext.ux.form.MySearchForm.superclass.initEvents.call(this);

			},
			/**
			 * 初始化表单结构
			 * 
			 * @param {}
			 *            id
			 */
			init : function(id) {
				var mygrid = this.myGrid ? this.myGrid : this;
				var myform = this;
				this.labelWidth = mygrid.searchLabelWidth
						? mygrid.searchLabelWidth
						: 80;// 表单标签长度

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

					var oField = [];// 表单控件数组

					// ========== 初始化字段信息 开始==============
					for (var i = 0; i < structure.length; i++) {
						var c = structure[i];
						c.formType = c.formType || 'textfield'; // 默认类型为textfield
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
										vtype : c.vtype ? c.vtype : '',// 默认有alpha字母，alphanum字母数字，email,url
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
								case 'numberfield' :// 如果为数字型，搜索改成>name>形式

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
								case 'editgrid' :// 动态编辑表格
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
														: mygrid.boName + '根',
												parentFieldType : c.parentFieldType
														? c.parentFieldType
														: ''
											});
									oField[oField.length] = {
										xtype : 'combotree',
										emptyText : '请选择...',
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
												: '',// 设定默认下拉宽度
										// width : 250,
										tree : tree,
										isOneRow : c.isOneRow
									};

									break;
								case 'combogrid' :// 下拉表格
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
									// 如果grid没初始化，通过对组件的name来获取objName，这样在加载表单的时候则可以获取到显示值
									// c.myGrid.objName = c.name.substring(0,
									// c.name.indexOf('.'));
									// }
									// // c.myGrid.removeListener('rowdblclick',
									// // c.myGrid.editFunction);// 屏蔽表格双击编辑事件
									// oField[oField.length] = {
									// xtype : 'combogrid',
									// fieldLabel : c.header,
									// anchor : c.anchor,
									// id : c.id ? c.id : fieldId,
									// name : c.name,
									// hiddenName : fieldName,
									// myGrid : c.myGrid,
									// gridName : c.gridName,// 下拉表格显示的属性
									// gridValue : c.gridValue,
									// isOneRow : c.isOneRow
									// // 下拉表格实际值属性
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
									if (!c.myGrid.objName) {// 如果grid没初始化，通过对组件的name来获取objName，这样在加载表单的时候则可以获取到显示值
										c.myGrid.objName = c.name.substring(0,
												c.name.indexOf('.'));
									}
									// c.myGrid.removeListener('rowdblclick',
									// c.myGrid.editFunction);// 屏蔽表格双击编辑事件
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
												gridName : c.gridName,// 下拉表格显示的属性
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
							}// switch结束
						}// if结束
					}// for结束

					var fieldArr = []; // 总体变量
					var evenArr = []; // 左变量
					var oddArr = []; // 右变量
					var j = 1;// j用于构建奇偶列函数时遇到单行表单需重新赋值
					for (var i = 0, l = oField.length; i < l; i++) {
						if (formCol == 1 || oField[i].hideTag == true
								|| oField[i].xtype == 'hidden') { // 单列或者隐藏域
							// 直接赋值
							fieldArr.push(oField[i]);
						} else {
							if (oField[i].isOneRow != true) {
								if (j++ % 2 == 0)
									oddArr.push(oField[i]);
								else
									evenArr.push(oField[i]);
							} else {
								fieldArr = pushColumn(evenArr, oddArr, fieldArr); // 设置列对象
								fieldArr.push(oField[i]); // 设置oneRow对象
								// 清空变量重新赋值
								evenArr = [];
								oddArr = [];
								j = 1;
							}
						}
					}
					// 最后一行如果是OneRow则在上面循环已经处理完毕，如果不是则设置列对象处理
					if (!oField[oField.length - 1].isOneRow) {
						fieldArr = pushColumn(evenArr, oddArr, fieldArr); // 设置列对象
					}

					return fieldArr;
				};
				// 设置列对象
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

				this.items = doFormItems(this.structure, this.formCol);// 构建表单控件

				this.buttons = [{
							text : '搜索',
							iconCls : 'save',
							handler : function() {
								doSubmitSearchForm()
							}
						}, {
							text : '重置',
							iconCls : 'clean',
							handler : function() {
								myform.getForm().reset();
							}
						}];
				this.buttons[this.buttons.length] = {
					text : '关闭',
					iconCls : 'close',
					handler : function() {
						myform.closeWin();
					}
				};

				// 提交搜索表单
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
						// 动态构建提交搜索的参数,更改mygrid store
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
			 * @功能：初始化combo控件数据
			 * 
			 */
			initCombo : function(c) {
				var ds = null;
				if (typeof c.fobj != 'object') {// fobj为静态json数组数据
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
				// 初始化下拉列表数据
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
					emptyText : '请选择...',
					typeAhead : true,// 输入过程中是否自动匹配剩余部分文本
					triggerAction : 'all',// 单击触发按钮时执行的默认操作
					selectOnFocus : true,// 当获得焦点时立刻选择一个已经存在的表项
					forceSelection : c.forceSelection ? false : true,// 输入值是否为待选列表中存在的值
					pageSize : c.pageSize ? c.pageSize : '',
					queryParam : 'searchValue',// 这里设置会把此combo输入的值作为参数传入action,默认是query
					minChars : 1,// 自动选择前需输入最小字符数量
					resizable : true,
					allowBlank : c.queryRequired ? false : true,
					listeners : c.listeners
				}
			}
		})