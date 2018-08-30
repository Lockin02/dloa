/**
 * �鿴ҵ�������ϸ��Ϣ��������ض�����Ϣ����
 */
$import("Ext.Viewport");
$import("Ext.TabPanel");
$import("Ext.Panel");
$package("Ext.ux.panel");
$import("Ext.ux.form.MyForm");
$import("Ext.ux.util.TabCloseMenu");
Ext.namespace("Ext.ux.panel");
/**
 * ͨ�ò��֣�ҵ����������Ϣ���֣��ڲ鿴һ��ҵ�������Ϣ��ʱ���������ʾ��ҵ����������Ϣ�ڵ㣬����鿴��ϸ
 * 
 * @class Ext.ux.panel.MyObjPanel
 * @extends Ext.Viewport
 */
Ext.ux.panel.MyObjPanel = Ext.extend(Ext.Viewport, {
			layout : 'border',
			border : false,
			bodyStyle : "background:white",
			// height : 700,
			initComponent : function() {
				this.initStructure();
				Ext.ux.panel.MyObjPanel.superclass.initComponent.call(this);

			},
			initStructure : function() {
				var viewport = this;
				// ���������Ϣ�����
				this.objTree = new Ext.tree.TreePanel({
							title : this.titleText + '�����Ϣ',
							titleCollapse : true,// ����������κεط���������
							collapseMode : 'mini',// �ڷָ��ߴ����ְ�ť
							split : true,
							// frame : true,
							collapsible : true,
							region : 'west',
							root : new Ext.tree.AsyncTreeNode({
										text : this.titleText + '�����Ϣ',
										draggable : false,
										children : this.relateJson
									}),
							rootVisible : false,// �Ƿ���ʾ���ڵ�
							margins : '5 0 5 5',// ��������
							width : 180,
							minSize : 100,
							maxSize : 300
						});

				if (!this.myForm) {
					var mygrid = this.myGrid;
					mygrid.formHeight = '100%';
					mygrid.isClose = false;
					this.myForm = new Ext.ux.form.MyForm({
								myGrid : mygrid,
								border : false,
								margins : '5 5 5 5'
							});
					mygrid.myForm = this.myForm;
					this.myForm.initForm();// ��ʼ��������
				}
				viewport.myForm.height = document.body.clientHeight - 43;
				// �м��
				this.centerPanel = new Ext.TabPanel({
							region : 'center',
							enableTabScroll : true,
							plugins : new Ext.ux.util.TabCloseMenu(),// ����tab���
							tabWidth : 125,
							activeTab : 0,
							// autoScroll : true,
							width : document.body.clientWidth - 180,
							margins : '5 5 5 0',// ��������
							items : [new Ext.Panel({
										id : "objForm" + id,
										// layout : 'border',
										title : this.titleText + "��Ϣ",
										border : false,
										autoScroll : true,
										bodyStyle : "background:white",
										margins : '5 5 5 5',
										items : viewport.myForm
									})]
						});

				// ���������ڵ��¼�
				var objTreeClickAction = function(node) {
					addTab(node, this.centerPanel)
				}
				this.objTree.on('click', objTreeClickAction, this);

				this.items = [];
				this.items.push(this.objTree);
				this.items.push(this.centerPanel);
			},
			init : function() {
				this.objTree.expandAll();
			}
		})