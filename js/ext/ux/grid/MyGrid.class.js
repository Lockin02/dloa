Ext.namespace("Ext.ux.grid");
/**
 * 封装的通用的Grid控件，具有如下功能：
 * 
 * 1.新增，修改，删除 2.快速搜索，高级搜索 3.分页 4.排序 6.右键 7.视图 8.工具栏 9.导出excel
 * 
 * @class Ext.ux.grid.MyGrid
 * @extends Ext.grid.GridPanel
 */
Ext.ux.grid.MyGrid = Ext.extend(Ext.grid.GridPanel, {
			/**
			 * 储存表格结构数组，此结构数组也可以直接给表单使用
			 * 
			 * @type Array
			 */
			structure : [],

			/**
			 * 快速搜索结构数组
			 * 
			 * @type String
			 */
			searchStructure : [],

			/**
			 * 高级搜索结构数组
			 * 
			 * @type String
			 */
			advSearchStructure : [],

			/**
			 * 表格/表单绑定的业务对象名名称（与后台action中对象名称对应）
			 * 
			 * @type String
			 */
			objName : '',

			/**
			 * store是否延迟加载
			 * 
			 * @type Boolean
			 */
			lazyLoad : true,

			/**
			 * 是否远端排序，默认是
			 * 
			 * @type Boolean
			 */
			remoteSort : true,
			/**
			 * 是否自动适应宽度，true不出现横向滚动条
			 * 
			 * @type Boolean
			 */
			forceFit : true,
			/**
			 * 表格布局
			 */
			bodyStyle : "width:100%;height:100%;",// border-left:0px;border-right:0px
			/**
			 * 与父控件的边距
			 */
			margins : '10 10 10 10',

			/**
			 * 表格是否自动高度
			 * 
			 * @type Boolean
			 */
			autoHeight : false,
			/**
			 * 表格是否自动宽度
			 * 
			 * @type Boolean
			 */
			autoWidth : true,

			/**
			 * 每页显示条数
			 * 
			 * @type Number
			 */
			pageSize : 10,
			/**
			 * 如果是在一个panel中，默认布局为center
			 * 
			 * @type String
			 */
			region : 'center',

			/**
			 * 是否需要表格边框
			 * 
			 * @type Boolean
			 */
			border : true,

			bodyBorder : true,

			/**
			 * 表格列的默认宽
			 * 
			 * @type Number
			 */
			fieldwidth : 200,

			/**
			 * reader类型如果当为json的时候那么url不能空，当为array的时候dataObject不能为空
			 * 
			 * @type String
			 */
			readType : 'json',

			/**
			 * 获取数据的URL前缀：如urlAction : 'customer!',
			 * 
			 * @type String
			 */
			urlAction : null,

			/**
			 * 查询使用URL(如不设置默认使用当前Grid的URL)
			 * 
			 * @type String
			 */
			searchUrl : null,
			/**
			 * 表格数据类型为数组时数据对象
			 * 
			 * @type Array
			 */
			dataObject : null,

			/**
			 * 表格主键
			 * 
			 * @type String
			 */
			keyField : 'id',

			/**
			 * 是否需要分组，默认为false，如果设置为true须再设置两个参数一个为groupField和myGroupTextTpl
			 * 
			 * @type Boolean
			 */
			needGroup : false,

			/**
			 * 分组的字段名称
			 * 
			 * @type String
			 */
			groupField : null,
			/**
			 * 分组显示的模板，eg：{text} ({[values.rs.length]} {[values.rs.length > 1 ?
			 * "Items" : "Item"]})
			 * 
			 * @type String
			 */
			myGroupTextTpl : '',

			/**
			 * 单偶行变色
			 * 
			 * @type Boolean
			 */
			stripeRows : true,

			/**
			 * 列模式的选择模式,默认为check，即多选模式;''则为单选模式。
			 * 
			 * @type String
			 */
			selectType : '',// selectType : 'check',

			/**
			 * 默认排序字段
			 * 
			 * @type
			 */
			defaultSortField : 'c.id',

			/**
			 * 默认排序方式 ASC：升序 DESC：降序
			 * 
			 * @type String
			 */
			defaultSortdirection : 'DESC',

			/**
			 * 是否需要分页工具栏
			 * 
			 * @type Boolean
			 */
			needPage : true,
			/**
			 * 是否需要设置分页数
			 * 
			 * @type Boolean
			 */
			pagSizePlugins : true,
			/**
			 * 背景是否透明
			 * 
			 * @type Boolean
			 */
			frame : false,

			/**
			 * 是否可折叠
			 * 
			 * @type Boolean
			 */
			collapsible : false,

			/**
			 * 是否开启动画(折叠的时候)
			 * 
			 * @type Boolean
			 */
			animCollapse : true,

			/**
			 * 是否有进度条
			 * 
			 * @type Boolean
			 */
			loadMask : true,
			/**
			 * 是否显示右键菜单，如果为flase，则右键菜单失效
			 * 
			 * @type Boolean
			 */
			isRightMenu : true,
			/**
			 * 是否显示工具栏
			 * 
			 * @type Boolean
			 */
			isToolBar : true,
			/**
			 * 是否显示添加按钮/菜单
			 * 
			 * @type Boolean
			 */
			isAddButton : true,
			/**
			 * 是否显示查看按钮/菜单
			 * 
			 * @type Boolean
			 */
			isViewButton : false,
			/**
			 * 是否显示修改按钮/菜单
			 * 
			 * @type Boolean
			 */
			isEditButton : true,
			/**
			 * 是否显示删除按钮/菜单
			 * 
			 * @type Boolean
			 */
			isDelButton : true,
			/**
			 * 是否显示快速搜索
			 * 
			 * @type Boolean
			 */
			isSearch : true,
			/**
			 * 是否显示高级搜索按钮
			 * 
			 * @type Boolean
			 */
			isAdvanceSearch : true,
			/**
			 * 默认获取表格数据url
			 * 
			 * @type String
			 */
			listUrl : 'pageJson',
			/**
			 * 默认删除白哦个数据url
			 * 
			 * @type String
			 */
			deleteUrl : 'delete.action',

			/**
			 * 业务对象中文名称，如客户，项目
			 * 
			 * @type String
			 */
			boName : '',
			/**
			 * 初始化的搜索条件字段数组，用于动态更改表格搜索条件,如initSearchFields : ['customerName']
			 * 此搜索条件不会被清空，注意与searchFields的区别
			 * 
			 * @type Array
			 */
			initSearchFields : [],

			/**
			 * 初始化的搜索条件字段值数组，用于动态更改表格搜索条件的值,如initSearchValues : ['同望']
			 * 此搜索条件值不会被清空，注意与searchValues的区别
			 * 
			 * @type Array
			 */
			initSearchValues : [],
			/**
			 * 搜索条件字段数组,此搜索条件会被清空 如：searchFields : ['customerName']
			 * 
			 * @type Array
			 */
			searchFields : [],
			/**
			 * 搜索条件值数组,此搜索条件会被清空 如：searchValues : ['同望']
			 * 
			 * @type Array
			 */
			searchValues : [],
			/**
			 * 顶部工具栏组件数组，主要是按钮
			 * 
			 * @type Array
			 */
			buttonArr : [],
			/**
			 * 表格默认高度
			 * 
			 * @type Number
			 */
			height : 200,

			/**
			 * 初始化组件
			 */
			initComponent : function() {
				if (this.structure) {
					this.initStructure();
				}
				Ext.ux.grid.MyGrid.superclass.initComponent.call(this);
			},

			/**
			 * 初始化表格结构
			 */
			initStructure : function() {
				var mygrid = this;

				// 布局是否自动，不出现横向滚动条
				if (this.forceFit == true) {
					this.viewConfig = {
						forceFit : true
					};
				}

				var oCM = []; // 列模式数组
				var oRecord = []; // 用于匹配表格数据数组，将获取数据转换成Record
				this.buttonArr = [];

				var rowNumberer = new Ext.grid.RowNumberer();
				if (this.rowNumRenderer)
					rowNumberer.renderer = this.rowNumRenderer;
				oCM.push(rowNumberer); // 行号生成器
				// 判断表格的选择模式
				if (this.selectType == 'check') {
					var sm = new Ext.grid.CheckboxSelectionModel();
					oCM.push(sm);// 在列模式数组中添加checkbox模式按钮
					this.sm = sm;// 并将selModel设置为check模式
				}

				var gridOrder = 0;// 字段在表格中的顺序
				function doGridItems(structure) {
					for (var i = 0, l = structure.length; i < l; i++) {
						var c = structure[i];
						if (c.formType == 'fieldset') {
							doGridItems(c.items);
							continue;
						}
						c.type = c.type ? c.type : "string";
						if (c.formType == 'datefield' || c.type == 'date') {
							c.type = 'date';
							c.renderer = c.renderer
									? c.renderer
									: Ext.util.Format.dateRenderer('Y-m-d');
							// c.mapping = c.name + '.time';
						} else if (c.formType == 'datetimefield'
								|| c.type == 'datetime') {
							c.type = 'date';
							c.renderer = c.renderer
									? c.renderer
									: Ext.util.Format
											.dateRenderer('Y-m-d H:i:s');
							// c.mapping = c.name + '.time';
						}

						c.mapping = c.mapping ? c.mapping : c.name;

						if (c.isInGrid != false) {
							if (mygrid.displayFields) {
								if (mygrid.displayFields.indexOf(c.name) < 0) {
									c.hidden = true;
								} else
									c.hidden = false;
							}

							oCM.push({
										header : c.header,
										tooltip : c.header,
										dataIndex : c.name,
										hidden : c.hidden || false,
										width : c.width
												? c.width
												: mygrid.fieldwidth,
										align : c.align ? c.align : 'left',
										renderer : c.renderer
												? c.renderer
												: extUtil.toolTip,
										sortable : c.sortable == false
												? false
												: true,
										inGridOrder : c.inGridOrder
												? c.inGridOrder
												: gridOrder++
									});
							oRecord.push({
										name : c.name,
										type : c.type,
										mapping : c.mapping,
										sortField : c.sortField,// 排序参数
										dateFormat : c.type == 'date'
												|| c.type == 'datetime'
												? 'Y-m-d H:i:s'
												: ''
									});
							if (c.hiddenName && c.hiddenName.indexOf('.') == -1) {// 暂时不支持如customer.id形式
								oRecord.push({
											name : c.hiddenName
										})
							}
						}
					}
				}
				doGridItems(this.structure);
				// 进行列排序
				oCM.sort(function(x, y) {
							return (x.inGridOrder ? x.inGridOrder : 0)
									- (y.inGridOrder ? y.inGridOrder : 0);
						});

				// 生成columnModel
				this.cm = new Ext.grid.ColumnModel(oCM);
				// 默认可排序
				this.cm.defaultSortable = true;

				// 生成表格数据容器
				this.record = Ext.data.Record.create(oRecord);

				// 判断读取类别，目前只实现了jsonreader和arrayReader
				var reader;
				// 判断defaultSortField是否空，是则判断数据结构里面是否有createTime，有则用createTime作为默认排序
				if (Ext.isEmpty(this.defaultSortField)) {
					for (var i = this.structure.length - 1; i > 0; i--) {
						if (this.structure[i].name == 'createTime') {
							this.defaultSortField = 'c.createTime';
							this.defaultSortdirection = "DESC";
							break;
						}
					}
				}
				switch (this.readType) {
					case 'json' :
						reader = new Ext.data.JsonReader({
									totalProperty : "totalSize",
									root : "collection",
									id : this.keyField
								}, oRecord);
						this.store = new Ext.data.GroupingStore({
									proxy : new Ext.data.HttpProxy({
												url : this.urlAction
														+ this.listUrl
											}),
									reader : reader,
									sortInfo : {
										field : this.defaultSortField,
										direction : this.defaultSortdirection
									},
									remoteSort : this.remoteSort,
									groupField : this.groupField
								});

						break;

					case 'array' :
						reader = new Ext.data.ArrayReader({}, this.record);
						this.store = new Ext.data.GroupingStore({
									reader : reader,
									data : this.dataObject,
									sortInfo : {
										field : this.defaultSortField,
										direction : this.defaultSortdirection
									},
									groupField : this.groupField
								});
						break;
					default :
						break;
				}

				// 判断是否需要分组
				if (this.needGroup) {
					this.view = new Ext.grid.GroupingView({
								groupByText : '按此属性分组',
								showGroupsText : '是否分组',
								// forceFit : true, //去掉 要不Column不会出滚动条
								groupTextTpl : '{text} ({[values.rs.length]} {["条"]})'
							});
				}

				// 分页工具栏
				if (this.needPage) {
					this.bbar = new Ext.PagingToolbar({
								// plugins : this.pagSizePlugins == true
								// ? new Ext.ux.grid.pPageSize()
								// : null,
								displayInfo : true,
								pageSize : this.pageSize,
								store : this.store
							});

				}

				var keyField = this.keyField;

				// store获取数据前事件
				var beforeLoad = function(store, options) {
					var searchFields = [];
					var searchValues = [];
					if (mygrid.initSearchFields.length > 0) {
						if (mygrid.searchFields.length <= 0) {
							searchFields = mygrid.initSearchFields;
							searchValues = mygrid.initSearchValues;
						} else {
							// 组合数组
							searchFields = mygrid.searchFields
									.concat(mygrid.initSearchFields);// .strip()
							searchValues = mygrid.searchValues
									.concat(mygrid.initSearchValues);
						}
					} else {
						searchFields = mygrid.searchFields;
						searchValues = mygrid.searchValues;

					}
					options.params.limit=mygrid.pageSize;
					if (searchFields.length != 0) {
						this.baseParams = {};
						for (var i = 0, l = searchFields.length; i < l; i++) {
							
							this.baseParams[searchFields[i]] = searchValues[i];
						}
						Ext.apply(options.params, this.baseParams); // 3.0
					} else {
						for (var o in options.params) {
							// if (o != 'searchFields' && o != 'searchValues') {
							this.baseParams[o] = options.params[o];
							// }
						}
						options.params = this.baseParams;
					}
					// options.params = {};//
					// 如果不清空params，searchFields跟values不能被清空
					// Ext.apply(options.params, this.baseParams); // 3.0
					// update
				};

				this.store.on('beforeload', beforeLoad);
				if (this.lazyLoad == false) {
					this.store.load({
								params : {
									start : 0,
									limit : mygrid.pageSize
								}
							});
				}

				this.store.on('load', function(t) {
					var message = t.reader.jsonData.message;
					if (message != null && message != '') {
						Ext.Msg.info({
									message : message
								});
					}
						// alert(t.getCount()+" "+t.getTotalCount());
					});

			},

			/**
			 * 清除表格搜索条件，即searchFields和searchValues的值
			 */
			cleanSearch : function() {
				this.searchFields = [];
				this.searchValues = [];
				this.searchCmps = [];
				this.schemeCode = '';
			},
			/**
			 * 清除表格初始化搜索条件，即initSearchFields和initSearchValues的值
			 */
			cleanInitSearch : function() {
				this.initSearchFields = [];
				this.initSearchValues = [];
				this.schemeCode = '';
			},
			/**
			 * 清除所有搜索条件信息
			 */
			cleanAllSearch : function() {
				this.cleanSearch();
				this.cleanInitSearch();
			}

		});
Ext.reg('mygrid', Ext.ux.grid.MyGrid);