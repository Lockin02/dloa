Ext.namespace("Ext.ux.tree");
/**
 * 自定义树组件
 * 
 * @class Ext.ux.tree.MyTree
 * @extends Ext.tree.TreePanel
 */
Ext.ux.tree.MyTree = Ext.extend(Ext.tree.TreePanel, {
	/**
	 * 根节点默认id
	 */
	rootId : -1,
	/**
	 * 获取树节点数据url
	 * 
	 * @type String
	 */
	url : '',
	/**
	 * 使用该属性加载展开节点对象url
	 * 
	 * @type String
	 */
	loadAttributes : '',
	/**
	 * 根节点是否可视
	 * 
	 * @type Boolean
	 */
	rootVisible : false,
	/**
	 * 追加工具栏
	 * 
	 * @type
	 */
	myTbar : null,
	/**
	 * 是否自动显示滚动条
	 * 
	 * @type Boolean
	 */
	autoScroll : true,
	/**
	 * 是否具有排序功能
	 * 
	 * @type Boolean
	 */
	isSort : false,
	/**
	 * 是否同级叶子节点排序
	 * 
	 * @type Boolean
	 */
	folderSort : false,
	/**
	 * 升序-asc还是降序-desc
	 * 
	 * @type String
	 */
	dir : 'asc',
	/**
	 * 排序字段后台类型一定要为String 不能是int
	 * 
	 * @type String
	 */
	property : 'text',
	/**
	 * 拖动树时节点值
	 * 
	 * @type String
	 */
	startdragNode : '',
	/**
	 * 节点值，默认id
	 * 
	 * @type String
	 */
	nodeValue : 'id',
	/**
	 * 跟节点默认编码
	 * 
	 * @type String
	 */
	rootCode : 'root',
	/**
	 * 是否具有默认右键菜单功能
	 * 
	 * @type Boolean
	 */
	isRightMenu : false,
	/**
	 * 是否叶子节点才显示右键菜单
	 * 
	 * @type Boolean
	 */
	isOnlyLeafShowMenu : false,
	/**
	 * 右键新增修改form列数; 1 or 2
	 * 
	 * @type Number
	 */
	formCol : 1,
	// isRightMenu : true, //是否存在右键菜单
	/**
	 * 除了右键菜单，追加其他菜单
	 * 
	 * @type
	 */
	myRightMenus : [],
	/**
	 * 重新构造树菜单
	 * 
	 * @type
	 */
	treeMenu : null,
	/**
	 * 树菜单新增创建表单的默认执行函数
	 * 
	 * @type String
	 */
	addTreeFormFun : '',
	// checkModel : 'multiple',
	/**
	 * 初始化组件
	 */
	initComponent : function() {
		this.init();
		Ext.ux.tree.MyTree.superclass.initComponent.call(this);

	},
	init : function() {
		var treeUrl = this.url;
		var mytree = this;
		// 注意loader不仅要设置到mytree，还要设置到根节点属性中
		if (!this.loader) {
			this.loader = new Ext.tree.TreeLoader({
						url : treeUrl
					});
			if (this.param) {
				this.loader.baseParams = this.param;
			}
			/**
			 * 多选: 'multiple' 单选: 'single'
			 * 级联多选:'cascade'(同时选父和子)(默认);'parentCascade'(选父);'childCascade'(选子)
			 */
			if (this.checkModel) {
				this.loader.baseAttrs = {
					uiProvider : Ext.ux.tree.TreeCheckNodeUI
				};
			}
			this.loader.on("beforeload", function(loader, node) {
						if (mytree.loadAttributes == '' || node.id == -1) {
							var parent = node.attributes[mytree.nodeValue];
							loader.url = treeUrl + parent;
						} else {
							loader.url = node.attributes[mytree.loadAttributes];
						}
					});
		};
		this.expandTreeLoader = this.loader;
		// 设置树根
		this.root = new Ext.tree.AsyncTreeNode({
					id : this.rootId,
					code : 'root',// 全局变量root
					text : this.rootText ? this.rootText : '根节点',
					expanded : true,
					loader : mytree.loader
				});

		var mygrid = this.myGrid;

		if (this.isRightMenu && !this.treeMenu) {
			var menuItems = [
			// {
			// text : '新增',
			// handler : function() {
			//					
			// }
			// }, {
			// text : '修改',
			// handler : function() {}
			// }, {
			// text : '删除',
			// handler : function() {}
			// }, {
			// text : '展开节点',
			// handler : function() {
			// mytree.selectedNode.expand(true);
			// }
			// }
			]
			// 添加自定义菜单
			if (!Ext.isEmpty(this.myRightMenus)) {
				menuItems = menuItems.concat(this.myRightMenus);
			}
			this.treeMenu = new Ext.menu.Menu({
						items : menuItems,
						minWidth : 15
					});

		}
		if (this.treeMenu) {
			// 增加右键点击事件
			this.on('contextmenu', function(node, event) {// 声明菜单类型
						mytree.selectedNode = node;
						if (!mytree.isOnlyLeafShowMenu
								|| (mytree.isOnlyLeafShowMenu && node.leaf == true))
							this.treeMenu.showAt(event.getXY());// 取得鼠标点击坐标，展示菜单
					});
		}

		this.on('click', function(node, event) {// 声明菜单类型
					mytree.selectedNode = node;
				});
		this.on('startdrag', function(ntree, node, event) {// 当节点开始拖动时触发事件
					mytree.startdragNode = node;
				});
		// 树排序
		if (this.isSort) {
			this.sorter = Class.forName("Ext.tree.TreeSorter").newInstance(
					this, {
						folderSort : this.folderSort,
						dir : this.dir,
						property : this.property
					});
		}
		// 树工具栏
		this.tbar = [{
					tooltip : "重新加载",
					iconCls : "icon-reload",
					handler : function() {
						mytree.loader = mytree.expandTreeLoader;
						mytree.root.loader = mytree.loader;
						mytree.root.reload();
					}
				}, "-", {
					iconCls : "icon-expand-all",
					tooltip : "全部展开",
					handler : function() {
						mytree.root.expand(true);
					}
				}, {
					iconCls : "icon-collapse-all",
					tooltip : "全部折叠",
					handler : function() {
						mytree.root.collapse(true);
					}
				}];

		if (!Ext.isEmpty(mytree.keyUrl)) {
			this.tbar.push({
				id : 'searchValue',
				xtype : 'textfield',
				width : 100,
				emptyText : '请输入搜索内容',
				listeners : {
					blur : function(t) {
						if (!Ext.isEmpty(t.getValue())) {
							mytree.loader = new Ext.tree.TreeLoader({
										url : mytree.keyUrl + t.getValue()
									});
						} else {
							mytree.loader = mytree.expandTreeLoader;
						}
						mytree.loader.baseAttrs = mytree.expandTreeLoader.baseAttrs;
						mytree.root.loader = mytree.loader;
						mytree.root.reload();
					}
				}
			});
		}
		if (!Ext.isEmpty(this.myTbar)) {
			this.tbar = this.tbar.concat(this.myTbar);
		}
	}

})
Ext.reg('mytree', Ext.ux.tree.MyTree);