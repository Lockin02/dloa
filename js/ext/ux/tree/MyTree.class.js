Ext.namespace("Ext.ux.tree");
/**
 * �Զ��������
 * 
 * @class Ext.ux.tree.MyTree
 * @extends Ext.tree.TreePanel
 */
Ext.ux.tree.MyTree = Ext.extend(Ext.tree.TreePanel, {
	/**
	 * ���ڵ�Ĭ��id
	 */
	rootId : -1,
	/**
	 * ��ȡ���ڵ�����url
	 * 
	 * @type String
	 */
	url : '',
	/**
	 * ʹ�ø����Լ���չ���ڵ����url
	 * 
	 * @type String
	 */
	loadAttributes : '',
	/**
	 * ���ڵ��Ƿ����
	 * 
	 * @type Boolean
	 */
	rootVisible : false,
	/**
	 * ׷�ӹ�����
	 * 
	 * @type
	 */
	myTbar : null,
	/**
	 * �Ƿ��Զ���ʾ������
	 * 
	 * @type Boolean
	 */
	autoScroll : true,
	/**
	 * �Ƿ����������
	 * 
	 * @type Boolean
	 */
	isSort : false,
	/**
	 * �Ƿ�ͬ��Ҷ�ӽڵ�����
	 * 
	 * @type Boolean
	 */
	folderSort : false,
	/**
	 * ����-asc���ǽ���-desc
	 * 
	 * @type String
	 */
	dir : 'asc',
	/**
	 * �����ֶκ�̨����һ��ҪΪString ������int
	 * 
	 * @type String
	 */
	property : 'text',
	/**
	 * �϶���ʱ�ڵ�ֵ
	 * 
	 * @type String
	 */
	startdragNode : '',
	/**
	 * �ڵ�ֵ��Ĭ��id
	 * 
	 * @type String
	 */
	nodeValue : 'id',
	/**
	 * ���ڵ�Ĭ�ϱ���
	 * 
	 * @type String
	 */
	rootCode : 'root',
	/**
	 * �Ƿ����Ĭ���Ҽ��˵�����
	 * 
	 * @type Boolean
	 */
	isRightMenu : false,
	/**
	 * �Ƿ�Ҷ�ӽڵ����ʾ�Ҽ��˵�
	 * 
	 * @type Boolean
	 */
	isOnlyLeafShowMenu : false,
	/**
	 * �Ҽ������޸�form����; 1 or 2
	 * 
	 * @type Number
	 */
	formCol : 1,
	// isRightMenu : true, //�Ƿ�����Ҽ��˵�
	/**
	 * �����Ҽ��˵���׷�������˵�
	 * 
	 * @type
	 */
	myRightMenus : [],
	/**
	 * ���¹������˵�
	 * 
	 * @type
	 */
	treeMenu : null,
	/**
	 * ���˵�������������Ĭ��ִ�к���
	 * 
	 * @type String
	 */
	addTreeFormFun : '',
	// checkModel : 'multiple',
	/**
	 * ��ʼ�����
	 */
	initComponent : function() {
		this.init();
		Ext.ux.tree.MyTree.superclass.initComponent.call(this);

	},
	init : function() {
		var treeUrl = this.url;
		var mytree = this;
		// ע��loader����Ҫ���õ�mytree����Ҫ���õ����ڵ�������
		if (!this.loader) {
			this.loader = new Ext.tree.TreeLoader({
						url : treeUrl
					});
			if (this.param) {
				this.loader.baseParams = this.param;
			}
			/**
			 * ��ѡ: 'multiple' ��ѡ: 'single'
			 * ������ѡ:'cascade'(ͬʱѡ������)(Ĭ��);'parentCascade'(ѡ��);'childCascade'(ѡ��)
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
		// ��������
		this.root = new Ext.tree.AsyncTreeNode({
					id : this.rootId,
					code : 'root',// ȫ�ֱ���root
					text : this.rootText ? this.rootText : '���ڵ�',
					expanded : true,
					loader : mytree.loader
				});

		var mygrid = this.myGrid;

		if (this.isRightMenu && !this.treeMenu) {
			var menuItems = [
			// {
			// text : '����',
			// handler : function() {
			//					
			// }
			// }, {
			// text : '�޸�',
			// handler : function() {}
			// }, {
			// text : 'ɾ��',
			// handler : function() {}
			// }, {
			// text : 'չ���ڵ�',
			// handler : function() {
			// mytree.selectedNode.expand(true);
			// }
			// }
			]
			// ����Զ���˵�
			if (!Ext.isEmpty(this.myRightMenus)) {
				menuItems = menuItems.concat(this.myRightMenus);
			}
			this.treeMenu = new Ext.menu.Menu({
						items : menuItems,
						minWidth : 15
					});

		}
		if (this.treeMenu) {
			// �����Ҽ�����¼�
			this.on('contextmenu', function(node, event) {// �����˵�����
						mytree.selectedNode = node;
						if (!mytree.isOnlyLeafShowMenu
								|| (mytree.isOnlyLeafShowMenu && node.leaf == true))
							this.treeMenu.showAt(event.getXY());// ȡ����������꣬չʾ�˵�
					});
		}

		this.on('click', function(node, event) {// �����˵�����
					mytree.selectedNode = node;
				});
		this.on('startdrag', function(ntree, node, event) {// ���ڵ㿪ʼ�϶�ʱ�����¼�
					mytree.startdragNode = node;
				});
		// ������
		if (this.isSort) {
			this.sorter = Class.forName("Ext.tree.TreeSorter").newInstance(
					this, {
						folderSort : this.folderSort,
						dir : this.dir,
						property : this.property
					});
		}
		// ��������
		this.tbar = [{
					tooltip : "���¼���",
					iconCls : "icon-reload",
					handler : function() {
						mytree.loader = mytree.expandTreeLoader;
						mytree.root.loader = mytree.loader;
						mytree.root.reload();
					}
				}, "-", {
					iconCls : "icon-expand-all",
					tooltip : "ȫ��չ��",
					handler : function() {
						mytree.root.expand(true);
					}
				}, {
					iconCls : "icon-collapse-all",
					tooltip : "ȫ���۵�",
					handler : function() {
						mytree.root.collapse(true);
					}
				}];

		if (!Ext.isEmpty(mytree.keyUrl)) {
			this.tbar.push({
				id : 'searchValue',
				xtype : 'textfield',
				width : 100,
				emptyText : '��������������',
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