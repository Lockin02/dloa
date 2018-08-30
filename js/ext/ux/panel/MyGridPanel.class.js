/**
 * 主区域布局，主要用于左边树，中间表格，右边工具栏布局
 */
$import("Ext.Viewport");
$import("Ext.data.HttpProxy");
$import("Ext.Ajax");
$package("Ext.ux.panel");
Ext.namespace("Ext.ux.panel");
$import("Ext.ux.tree.MyTree");
/**
 * 通用布局类：左边树，中间表格，适用于树数据与表格数据相同或者有上下级关联关系,如数据字典，产品类别及产品等布局
 * 
 * @class Ext.ux.panel.MyGridPanel
 * @extends Ext.Viewport
 */
Ext.ux.panel.MyGridPanel = Ext.extend(Ext.Viewport, {
	layout : 'border',
	width : '100%',
	border : false,
	autoScroll : true,
	myGrid : null,
	/**
	 * 是否左边树
	 * 
	 * @type Boolean
	 */
	isWestTree : true,
	/**
	 * 左边树
	 * 
	 * @type
	 */
	westTree : null,
	/**
	 * 树是否支持右键菜单
	 * 
	 * @type Boolean
	 */
	isRightMenu : true,
	/**
	 * 树中文对象名称
	 * 
	 * @type String
	 */
	treeBoName : '',
	/**
	 * 树绑定po对象名称
	 * 
	 * @type String
	 */
	treeObjName : '',
	/**
	 * 节点是否可以拖动
	 * 
	 * @type Boolean
	 */
	enableDD : true,
	/**
	 * 是否点叶子刷新表格
	 * 
	 * @type Boolean
	 */
	isClickLeaf : false,
	/**
	 * 树右键菜单
	 * 
	 * @type String
	 */
	treeMenu : '',
	/**
	 * 是否展开树所有节点
	 * 
	 * @type Boolean
	 */
	fnExpandAll : false,
	/**
	 * 点击树节点url传递树对象
	 * 
	 * @type String
	 */
	nodeClickValue : 'id',
	/**
	 * 树菜单新增创建表单的默认执行函数
	 * 
	 * @type String
	 */
	addTreeFormFun : '',

	initComponent : function() {
		this.initStructure();
		Ext.ux.panel.MyGridPanel.superclass.initComponent.call(this);

	},
	/**
	 * 初始化结构函数
	 */
	initStructure : function() {
		// this.height = mainHeight;// 获取mainPanel高度
		var panelItems = [];
		var mygrid = this.myGrid;
		// mygrid.parentFieldType = this.parentFieldType;
		var myGridPanel = this;
		if (this.isWestTree) {
			var nodeClickUrl = this.nodeClickUrl;
			this.westTree = new Ext.ux.tree.MyTree({
				title : this.treeBoName,
				rootText : this.westTitle + '树',
				addTreeFormFun : this.addTreeFormFun,
				rootVisible : this.rootVisible == true ? true : false,
				// jsonData : this.jsonData,
				collapseMode : 'mini',
				myGrid : mygrid,
				treeFormStruct : this.treeFormStruct,
				treeObjName : this.treeObjName,
				urlAction : this.urlAction,
				url : this.url,
				parentCmpName : this.parentCmpName,
				parentCmpId : this.parentCmpId,
				region : 'west',
				margins : '5 0 5 5',// 上右下左
				formCol : 2,
				width : 180,
				minSize : 100,
				maxSize : 300,
				enableDD : this.enableDD ? true : false,
				isRightMenu : this.isRightMenu,
				treeMenu : this.treeMenu,
				titleCollapse : true,// 点击标题栏任何地方都能收缩
				split : true,// 能否拖动改变panle大小
				collapsible : true,
				listeners : {
					click : function(node) {
						var urlFn = true;
						if (myGridPanel.isClickLeaf) {
							if (!node.leaf) {
								urlFn = false;
							}
						}
						if (urlFn) {
							var nodeClickValue = node.attributes[myGridPanel.nodeClickValue];
							mygrid.getStore().proxy = new Ext.data.HttpProxy({
										url : nodeClickUrl + nodeClickValue
									})
							mygrid.getStore().reload();
						}
					}

				}

			});
			if (this.enableDD) {
				// 设置成 叶子节点也可以 成为 拖动目的地
				this.westTree.on('nodedragover', function(e) {
							e.target.leaf = false;
						});
				this.westTree.on('movenode', function(t, node, op, np, i) {
							var parent = np.id;
							if (np.attributes.code) {
								parent = np.attributes.code;
							}
							Ext.Ajax.request({
										url : myGridPanel.urlAction
												+ "dragEdit.shtml?"
												+ mygrid.objName + '.id='
												+ node.id + '&parentId='
												+ parent,
										success : function(result, request) {
											var json = result.responseText;
											var o = eval("(" + json + ")");
											Ext.Msg.info({
														message : o.message
																? o.message
																: '拖动节点成功！'
													});
										}
									})
						});
			}
			if (this.fnExpandAll) {
				this.westTree.expandAll();
			}
			panelItems[panelItems.length] = this.westTree;
		}

		// 设置mygrid边框
		// mygrid.border=true;
		// mygrid.bodyStyle = "width:100%;height:100%;";
		// mygrid.getTopToolbar().style = "";
		// mygrid.getBottomToolbar().style = "";
		// mygrid.doLayout();
		panelItems[panelItems.length] = mygrid;

		this.items = panelItems;
	}
})