Ext.namespace("Ext.ux.grid");
/**
 * ��װ��ͨ�õ�Grid�ؼ����������¹��ܣ�
 * 
 * 1.�������޸ģ�ɾ�� 2.�����������߼����� 3.��ҳ 4.���� 6.�Ҽ� 7.��ͼ 8.������ 9.����excel
 * 
 * @class Ext.ux.grid.MyGrid
 * @extends Ext.grid.GridPanel
 */
Ext.ux.grid.MyGrid = Ext.extend(Ext.grid.GridPanel, {
			/**
			 * ������ṹ���飬�˽ṹ����Ҳ����ֱ�Ӹ���ʹ��
			 * 
			 * @type Array
			 */
			structure : [],

			/**
			 * ���������ṹ����
			 * 
			 * @type String
			 */
			searchStructure : [],

			/**
			 * �߼������ṹ����
			 * 
			 * @type String
			 */
			advSearchStructure : [],

			/**
			 * ���/���󶨵�ҵ����������ƣ����̨action�ж������ƶ�Ӧ��
			 * 
			 * @type String
			 */
			objName : '',

			/**
			 * store�Ƿ��ӳټ���
			 * 
			 * @type Boolean
			 */
			lazyLoad : true,

			/**
			 * �Ƿ�Զ������Ĭ����
			 * 
			 * @type Boolean
			 */
			remoteSort : true,
			/**
			 * �Ƿ��Զ���Ӧ��ȣ�true�����ֺ��������
			 * 
			 * @type Boolean
			 */
			forceFit : true,
			/**
			 * ��񲼾�
			 */
			bodyStyle : "width:100%;height:100%;",// border-left:0px;border-right:0px
			/**
			 * �븸�ؼ��ı߾�
			 */
			margins : '10 10 10 10',

			/**
			 * ����Ƿ��Զ��߶�
			 * 
			 * @type Boolean
			 */
			autoHeight : false,
			/**
			 * ����Ƿ��Զ����
			 * 
			 * @type Boolean
			 */
			autoWidth : true,

			/**
			 * ÿҳ��ʾ����
			 * 
			 * @type Number
			 */
			pageSize : 10,
			/**
			 * �������һ��panel�У�Ĭ�ϲ���Ϊcenter
			 * 
			 * @type String
			 */
			region : 'center',

			/**
			 * �Ƿ���Ҫ���߿�
			 * 
			 * @type Boolean
			 */
			border : true,

			bodyBorder : true,

			/**
			 * ����е�Ĭ�Ͽ�
			 * 
			 * @type Number
			 */
			fieldwidth : 200,

			/**
			 * reader���������Ϊjson��ʱ����ôurl���ܿգ���Ϊarray��ʱ��dataObject����Ϊ��
			 * 
			 * @type String
			 */
			readType : 'json',

			/**
			 * ��ȡ���ݵ�URLǰ׺����urlAction : 'customer!',
			 * 
			 * @type String
			 */
			urlAction : null,

			/**
			 * ��ѯʹ��URL(�粻����Ĭ��ʹ�õ�ǰGrid��URL)
			 * 
			 * @type String
			 */
			searchUrl : null,
			/**
			 * �����������Ϊ����ʱ���ݶ���
			 * 
			 * @type Array
			 */
			dataObject : null,

			/**
			 * �������
			 * 
			 * @type String
			 */
			keyField : 'id',

			/**
			 * �Ƿ���Ҫ���飬Ĭ��Ϊfalse���������Ϊtrue����������������һ��ΪgroupField��myGroupTextTpl
			 * 
			 * @type Boolean
			 */
			needGroup : false,

			/**
			 * ������ֶ�����
			 * 
			 * @type String
			 */
			groupField : null,
			/**
			 * ������ʾ��ģ�壬eg��{text} ({[values.rs.length]} {[values.rs.length > 1 ?
			 * "Items" : "Item"]})
			 * 
			 * @type String
			 */
			myGroupTextTpl : '',

			/**
			 * ��ż�б�ɫ
			 * 
			 * @type Boolean
			 */
			stripeRows : true,

			/**
			 * ��ģʽ��ѡ��ģʽ,Ĭ��Ϊcheck������ѡģʽ;''��Ϊ��ѡģʽ��
			 * 
			 * @type String
			 */
			selectType : '',// selectType : 'check',

			/**
			 * Ĭ�������ֶ�
			 * 
			 * @type
			 */
			defaultSortField : 'c.id',

			/**
			 * Ĭ������ʽ ASC������ DESC������
			 * 
			 * @type String
			 */
			defaultSortdirection : 'DESC',

			/**
			 * �Ƿ���Ҫ��ҳ������
			 * 
			 * @type Boolean
			 */
			needPage : true,
			/**
			 * �Ƿ���Ҫ���÷�ҳ��
			 * 
			 * @type Boolean
			 */
			pagSizePlugins : true,
			/**
			 * �����Ƿ�͸��
			 * 
			 * @type Boolean
			 */
			frame : false,

			/**
			 * �Ƿ���۵�
			 * 
			 * @type Boolean
			 */
			collapsible : false,

			/**
			 * �Ƿ�������(�۵���ʱ��)
			 * 
			 * @type Boolean
			 */
			animCollapse : true,

			/**
			 * �Ƿ��н�����
			 * 
			 * @type Boolean
			 */
			loadMask : true,
			/**
			 * �Ƿ���ʾ�Ҽ��˵������Ϊflase�����Ҽ��˵�ʧЧ
			 * 
			 * @type Boolean
			 */
			isRightMenu : true,
			/**
			 * �Ƿ���ʾ������
			 * 
			 * @type Boolean
			 */
			isToolBar : true,
			/**
			 * �Ƿ���ʾ��Ӱ�ť/�˵�
			 * 
			 * @type Boolean
			 */
			isAddButton : true,
			/**
			 * �Ƿ���ʾ�鿴��ť/�˵�
			 * 
			 * @type Boolean
			 */
			isViewButton : false,
			/**
			 * �Ƿ���ʾ�޸İ�ť/�˵�
			 * 
			 * @type Boolean
			 */
			isEditButton : true,
			/**
			 * �Ƿ���ʾɾ����ť/�˵�
			 * 
			 * @type Boolean
			 */
			isDelButton : true,
			/**
			 * �Ƿ���ʾ��������
			 * 
			 * @type Boolean
			 */
			isSearch : true,
			/**
			 * �Ƿ���ʾ�߼�������ť
			 * 
			 * @type Boolean
			 */
			isAdvanceSearch : true,
			/**
			 * Ĭ�ϻ�ȡ�������url
			 * 
			 * @type String
			 */
			listUrl : 'pageJson',
			/**
			 * Ĭ��ɾ����Ŷ������url
			 * 
			 * @type String
			 */
			deleteUrl : 'delete.action',

			/**
			 * ҵ������������ƣ���ͻ�����Ŀ
			 * 
			 * @type String
			 */
			boName : '',
			/**
			 * ��ʼ�������������ֶ����飬���ڶ�̬���ı����������,��initSearchFields : ['customerName']
			 * �������������ᱻ��գ�ע����searchFields������
			 * 
			 * @type Array
			 */
			initSearchFields : [],

			/**
			 * ��ʼ�������������ֶ�ֵ���飬���ڶ�̬���ı������������ֵ,��initSearchValues : ['ͬ��']
			 * ����������ֵ���ᱻ��գ�ע����searchValues������
			 * 
			 * @type Array
			 */
			initSearchValues : [],
			/**
			 * ���������ֶ�����,�����������ᱻ��� �磺searchFields : ['customerName']
			 * 
			 * @type Array
			 */
			searchFields : [],
			/**
			 * ��������ֵ����,�����������ᱻ��� �磺searchValues : ['ͬ��']
			 * 
			 * @type Array
			 */
			searchValues : [],
			/**
			 * ����������������飬��Ҫ�ǰ�ť
			 * 
			 * @type Array
			 */
			buttonArr : [],
			/**
			 * ���Ĭ�ϸ߶�
			 * 
			 * @type Number
			 */
			height : 200,

			/**
			 * ��ʼ�����
			 */
			initComponent : function() {
				if (this.structure) {
					this.initStructure();
				}
				Ext.ux.grid.MyGrid.superclass.initComponent.call(this);
			},

			/**
			 * ��ʼ�����ṹ
			 */
			initStructure : function() {
				var mygrid = this;

				// �����Ƿ��Զ��������ֺ��������
				if (this.forceFit == true) {
					this.viewConfig = {
						forceFit : true
					};
				}

				var oCM = []; // ��ģʽ����
				var oRecord = []; // ����ƥ�����������飬����ȡ����ת����Record
				this.buttonArr = [];

				var rowNumberer = new Ext.grid.RowNumberer();
				if (this.rowNumRenderer)
					rowNumberer.renderer = this.rowNumRenderer;
				oCM.push(rowNumberer); // �к�������
				// �жϱ���ѡ��ģʽ
				if (this.selectType == 'check') {
					var sm = new Ext.grid.CheckboxSelectionModel();
					oCM.push(sm);// ����ģʽ���������checkboxģʽ��ť
					this.sm = sm;// ����selModel����Ϊcheckģʽ
				}

				var gridOrder = 0;// �ֶ��ڱ���е�˳��
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
										sortField : c.sortField,// �������
										dateFormat : c.type == 'date'
												|| c.type == 'datetime'
												? 'Y-m-d H:i:s'
												: ''
									});
							if (c.hiddenName && c.hiddenName.indexOf('.') == -1) {// ��ʱ��֧����customer.id��ʽ
								oRecord.push({
											name : c.hiddenName
										})
							}
						}
					}
				}
				doGridItems(this.structure);
				// ����������
				oCM.sort(function(x, y) {
							return (x.inGridOrder ? x.inGridOrder : 0)
									- (y.inGridOrder ? y.inGridOrder : 0);
						});

				// ����columnModel
				this.cm = new Ext.grid.ColumnModel(oCM);
				// Ĭ�Ͽ�����
				this.cm.defaultSortable = true;

				// ���ɱ����������
				this.record = Ext.data.Record.create(oRecord);

				// �ж϶�ȡ���Ŀǰֻʵ����jsonreader��arrayReader
				var reader;
				// �ж�defaultSortField�Ƿ�գ������ж����ݽṹ�����Ƿ���createTime��������createTime��ΪĬ������
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

				// �ж��Ƿ���Ҫ����
				if (this.needGroup) {
					this.view = new Ext.grid.GroupingView({
								groupByText : '�������Է���',
								showGroupsText : '�Ƿ����',
								// forceFit : true, //ȥ�� Ҫ��Column�����������
								groupTextTpl : '{text} ({[values.rs.length]} {["��"]})'
							});
				}

				// ��ҳ������
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

				// store��ȡ����ǰ�¼�
				var beforeLoad = function(store, options) {
					var searchFields = [];
					var searchValues = [];
					if (mygrid.initSearchFields.length > 0) {
						if (mygrid.searchFields.length <= 0) {
							searchFields = mygrid.initSearchFields;
							searchValues = mygrid.initSearchValues;
						} else {
							// �������
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
					// ��������params��searchFields��values���ܱ����
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
			 * ������������������searchFields��searchValues��ֵ
			 */
			cleanSearch : function() {
				this.searchFields = [];
				this.searchValues = [];
				this.searchCmps = [];
				this.schemeCode = '';
			},
			/**
			 * �������ʼ��������������initSearchFields��initSearchValues��ֵ
			 */
			cleanInitSearch : function() {
				this.initSearchFields = [];
				this.initSearchValues = [];
				this.schemeCode = '';
			},
			/**
			 * �����������������Ϣ
			 */
			cleanAllSearch : function() {
				this.cleanSearch();
				this.cleanInitSearch();
			}

		});
Ext.reg('mygrid', Ext.ux.grid.MyGrid);