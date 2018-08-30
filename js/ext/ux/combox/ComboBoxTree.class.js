/*
 * 下拉树控件
 */
Ext.namespace("Ext.ux.combox");
Ext.ux.combox.ComboBoxTree = function() {
	this.treeId = Ext.id() + '-tree';
	this.maxHeight = arguments[0].maxHeight || arguments[0].height
			|| this.maxHeight;
	this.tpl = new Ext.Template('<tpl for="."><div style="text-align:left;height:'
			+ this.maxHeight + 'px"><div id="' + this.treeId
			+ '"></div></div></tpl>');
	this.store = new Ext.data.SimpleStore({
				fields : [],
				data : [[]]
			});
	this.mode = 'local';

	this.triggerAction = 'all';
	this.editable = false;
	this.selectedClass = '';
	this.onSelect = Ext.emptyFn;

	// all:所有结点都可选中
	// exceptRoot：除根结点，其它结点都可选
	// folder:只有目录（非叶子和非根结点）可选
	// leaf：只有叶子结点可选
	this.selectNodeModel = arguments[0].selectNodeModel || 'all';

	Ext.ux.combox.ComboBoxTree.superclass.constructor.apply(this, arguments);
}
/**
 * 下拉单选树组件
 *
 * @class Ext.ux.combox.ComboBoxTree
 * @extends Ext.form.ComboBox
 */
Ext.extend(Ext.ux.combox.ComboBoxTree, Ext.form.ComboBox, {
			emptyText : '请选择...',
			/**
			 * 表格默认选种行
			 *
			 * @type Number
			 */
			selectGridNumEx : 0, // 表格默认选种行
			initComponent : function() {
				this.on({
							expand : this.expandTreeFn,
							// 模糊匹配
							render : {
								single : true,
								scope : this,
								fn : function() {
									this.showTreeTask = new Ext.util.DelayedTask(
											this.showTree, this);
									this.el.on('keyup', this.delayShowTree,
											this);
								}
							}
						}); // 第一次操作是鼠标点击展开 初始化tree
				// this.on('focus', this.expand());
				Ext.ux.combox.ComboBoxTree.superclass.initComponent.call(this);
			},
			/**
			 * 展开树函数
			 */
			expandTreeFn : function() {
				// Ext.ux.combox.ComboBoxTree.superclass.expand.call(this);
				if (!this.tree.rendered) {
					// this.tree.height = this.maxHeight;
					this.tree.width = !this.listWidth
							? this.getWidth()
							: this.listWidth;
					this.tree.border = false;
					this.tree.autoScroll = true;
					if (this.tree.xtype) {
						this.tree = Class.forName("Ext.ComponentMgr").create(
								this.tree, this.tree.xtype);
					}
					this.tree.render(this.treeId);
					var combox = this;
					this.tree.on('click', function(node) {
								var isRoot = (node == combox.tree.getRootNode());
								var selModel = combox.selectNodeModel;
								var isLeaf = node.isLeaf();
								if (isRoot && selModel != 'all') {
									return;
								} else if (selModel == 'folder' && isLeaf) {
									return;
								} else if (selModel == 'leaf' && !isLeaf) {
									return;
								}
								combox.setValue(node);
								combox.collapse();
							});
					this.expandTreeLoader = this.tree.loader; // 缓存loader
					// 在展开所有树时使用
				}
			},
			setValue : function(node) {
				var text = node;
				var id = node;
				this.lastSelectionText = text;

				if (!node) {// 新增，重置所用。
					if (this.hiddenField)
						Ext.get(this.hiddenField).dom.value=this.tree.rootId;//如果空
					Ext.form.ComboBox.superclass.setValue.call(this, '');
					return;
				}
				if (node.text) {
					this.selectNode = node;
					text = node.text;
					if (this.hiddenField)
						Ext.get(this.hiddenField).dom.value=node.id;

				}
				Ext.form.ComboBox.superclass.setValue.call(this, text);
//				if (!Ext.isEmpty(text)) {
//					if (!this.toolTip || !this.toolTip.body) {
//						this.toolTip = Class.forName("Ext.ToolTip")
//								.newInstance({
//											target : this.getEl().id,
//											html : text
//										});
//					} else {
//						this.toolTip.body.update(text);
//					}
//				}

			},

			onRender : function(ct, position) {
				if (this.keyUrl) { // 存在keyUrl则编辑框设置为可输入
					this.editable = true;
					this.enableKeyEvents = true; // 激活键盘事件
					this.on("collapse", function(t) { // 关闭面板效验输入对象是否存在于mygrid数据源中
								hiddenObj = Ext.get(this.hiddenField);
								if (hiddenObj
										&& Ext.isEmpty(hiddenObj.getValue())) {
									t.setValue('');
								}
							});
					this.on('keydown', function(t, e) {
								if (this.hiddenField)
									Ext.get(this.hiddenField).dom.value=""; // 设置隐藏ID为null
								if (e.getKey() == 38) { // 上箭头
								} else if (e.getKey() == 40) { // 下箭头
									var node = t.tree.getNodeById('-1');
									t.tree.getSelectionModel().select(node);
									t.tree.getSelectionModel().selectNext();
								} else if (e.getKey() == 13) {
									// var node =
									// t.tree.getSelectionModel().getSelectedNode();
									// t.setValue(node);
									// t.tree.fireEvent("click", t.tree); //移植事件
									// t.collapse();
								}
							});

				}
				Ext.form.ComboBox.superclass.onRender.call(this, ct, position);
				if (this.hiddenName) {

					/*
					 * this.hiddenField = this.el.insertSibling({ tag : 'input',
					 * type : 'hidden', name : this.hiddenName, id :
					 * (this.hiddenId || this.hiddenName) }, 'before', true);
					 */

					// prevent input submission
					// this.el.dom.removeAttribute('name');
					/*
					 * this.hiddenField = new Ext.form.Hidden({ name :
					 * this.hiddenName, id : (this.hiddenId || this.hiddenName)
					 * });
					 */
				}
				if (Ext.isGecko) {
					this.el.dom.setAttribute('autocomplete', 'off');
				}

				if (!this.lazyInit) {
					this.initList();
				} else {
					this.on('focus', this.initList, this, {
								single : true
							});
				}

				if (!this.editable) {
					this.editable = true;
					this.setEditable(false);
				}
			},

			onKeyUp : function(e) { // 重写
				this.expandTreeFn(); // 第一次操作是按键盘 初始化tree
				if (this.editable !== false && !e.isSpecialKey()) {
					this.lastKey = e.getKey();
					// this.dqTask.delay(this.queryDelay); //去掉此方法，才能使用键盘事件展开表格
					// 不会出现空白的BUG
				}
			},
			/**
			 * 延迟显示树函数
			 *
			 * @param {}
			 *            e
			 */
			delayShowTree : function(e) {
				if (!e.isNavKeyPress()) {
					this.showTreeTask.delay(500);
				}
			},
			/**
			 * 模糊匹配显示树节点数据
			 */
			showTree : function() {
				if (!this.isExpanded()) {
					this.expand();
				}
				var mytree = this.tree;
				if (!Ext.isEmpty(this.getValue())) {
					mytree.loader = new Ext.tree.TreeLoader({
								url : this.keyUrl + this.getValue()
							});
				} else {
					mytree.loader = this.expandTreeLoader;
				}
				// mytree.loader.url = this.keyUrl;
				mytree.root.loader = mytree.loader;
				mytree.root.reload();
			}
		});

Ext.reg('combotree', Ext.ux.combox.ComboBoxTree);