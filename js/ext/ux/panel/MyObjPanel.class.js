/**
 * 查看业务对象详细信息，包括相关对象信息布局
 */
$import("Ext.Viewport");
$import("Ext.TabPanel");
$import("Ext.Panel");
$package("Ext.ux.panel");
$import("Ext.ux.form.MyForm");
$import("Ext.ux.util.TabCloseMenu");
Ext.namespace("Ext.ux.panel");
/**
 * 通用布局：业务对象相关信息布局，在查看一个业务对象信息的时候，左边树显示该业务对象相关信息节点，点击查看详细
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
				// 定义左边信息相关树
				this.objTree = new Ext.tree.TreePanel({
							title : this.titleText + '相关信息',
							titleCollapse : true,// 点击标题栏任何地方都能收缩
							collapseMode : 'mini',// 在分割线处出现按钮
							split : true,
							// frame : true,
							collapsible : true,
							region : 'west',
							root : new Ext.tree.AsyncTreeNode({
										text : this.titleText + '相关信息',
										draggable : false,
										children : this.relateJson
									}),
							rootVisible : false,// 是否显示跟节点
							margins : '5 0 5 5',// 上右下左
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
					this.myForm.initForm();// 初始化表单数据
				}
				viewport.myForm.height = document.body.clientHeight - 43;
				// 中间表单
				this.centerPanel = new Ext.TabPanel({
							region : 'center',
							enableTabScroll : true,
							plugins : new Ext.ux.util.TabCloseMenu(),// 顶部tab插件
							tabWidth : 125,
							activeTab : 0,
							// autoScroll : true,
							width : document.body.clientWidth - 180,
							margins : '5 5 5 0',// 上右下左
							items : [new Ext.Panel({
										id : "objForm" + id,
										// layout : 'border',
										title : this.titleText + "信息",
										border : false,
										autoScroll : true,
										bodyStyle : "background:white",
										margins : '5 5 5 5',
										items : viewport.myForm
									})]
						});

				// 定义点击树节点事件
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