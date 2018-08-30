/*
 * �������ؼ�
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

	// all:���н�㶼��ѡ��
	// exceptRoot��������㣬������㶼��ѡ
	// folder:ֻ��Ŀ¼����Ҷ�ӺͷǸ���㣩��ѡ
	// leaf��ֻ��Ҷ�ӽ���ѡ
	this.selectNodeModel = arguments[0].selectNodeModel || 'all';

	Ext.ux.combox.ComboBoxTree.superclass.constructor.apply(this, arguments);
}
/**
 * ������ѡ�����
 *
 * @class Ext.ux.combox.ComboBoxTree
 * @extends Ext.form.ComboBox
 */
Ext.extend(Ext.ux.combox.ComboBoxTree, Ext.form.ComboBox, {
			emptyText : '��ѡ��...',
			/**
			 * ���Ĭ��ѡ����
			 *
			 * @type Number
			 */
			selectGridNumEx : 0, // ���Ĭ��ѡ����
			initComponent : function() {
				this.on({
							expand : this.expandTreeFn,
							// ģ��ƥ��
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
						}); // ��һ�β����������չ�� ��ʼ��tree
				// this.on('focus', this.expand());
				Ext.ux.combox.ComboBoxTree.superclass.initComponent.call(this);
			},
			/**
			 * չ��������
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
					this.expandTreeLoader = this.tree.loader; // ����loader
					// ��չ��������ʱʹ��
				}
			},
			setValue : function(node) {
				var text = node;
				var id = node;
				this.lastSelectionText = text;

				if (!node) {// �������������á�
					if (this.hiddenField)
						Ext.get(this.hiddenField).dom.value=this.tree.rootId;//�����
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
				if (this.keyUrl) { // ����keyUrl��༭������Ϊ������
					this.editable = true;
					this.enableKeyEvents = true; // ��������¼�
					this.on("collapse", function(t) { // �ر����Ч����������Ƿ������mygrid����Դ��
								hiddenObj = Ext.get(this.hiddenField);
								if (hiddenObj
										&& Ext.isEmpty(hiddenObj.getValue())) {
									t.setValue('');
								}
							});
					this.on('keydown', function(t, e) {
								if (this.hiddenField)
									Ext.get(this.hiddenField).dom.value=""; // ��������IDΪnull
								if (e.getKey() == 38) { // �ϼ�ͷ
								} else if (e.getKey() == 40) { // �¼�ͷ
									var node = t.tree.getNodeById('-1');
									t.tree.getSelectionModel().select(node);
									t.tree.getSelectionModel().selectNext();
								} else if (e.getKey() == 13) {
									// var node =
									// t.tree.getSelectionModel().getSelectedNode();
									// t.setValue(node);
									// t.tree.fireEvent("click", t.tree); //��ֲ�¼�
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

			onKeyUp : function(e) { // ��д
				this.expandTreeFn(); // ��һ�β����ǰ����� ��ʼ��tree
				if (this.editable !== false && !e.isSpecialKey()) {
					this.lastKey = e.getKey();
					// this.dqTask.delay(this.queryDelay); //ȥ���˷���������ʹ�ü����¼�չ�����
					// ������ֿհ׵�BUG
				}
			},
			/**
			 * �ӳ���ʾ������
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
			 * ģ��ƥ����ʾ���ڵ�����
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