/**
 * 下拉多选树控件
 */
Ext.namespace("Ext.ux.combox");
Ext.ux.combox.ComboBoxCheckTree = function() {
	this.treeId = Ext.id() + '-tree';
	this.maxHeight = arguments[0].maxHeight || arguments[0].height
			|| this.maxHeight;
	this.tpl = new Ext.Template('<tpl for="."><div style="text-align:left;height:'
			+ this.maxHeight
			+ 'px"><div id="'
			+ this.treeId
			+ '"></div></div></tpl>');
	this.store = new Ext.data.SimpleStore({
				fields : [],
				data : [[]]
			});
	this.selectedClass = '';
	this.mode = 'local';
	this.triggerAction = 'all';
	this.onSelect = Ext.emptyFn;
	this.editable = false;
	// folder:只能选择父亲节点
	Ext.ux.combox.ComboBoxCheckTree.superclass.constructor.apply(this,
			arguments);
}

/**
 * 下拉多选树组件,与Ext.ux.tree.MyTree组合
 * 
 * @class Ext.ux.combox.ComboBoxCheckTree
 * @extends Ext.form.ComboBox
 */
Ext.extend(Ext.ux.combox.ComboBoxCheckTree, Ext.form.ComboBox, {
	/**
	 * all:可以选择所有节点 leaf:只能选择叶子节点
	 * 
	 * @type String
	 */
	selectValueModel : 'leaf',
	/**
	 * 值名称
	 * 
	 * @type String
	 */
	valueName : 'id',
	/**
	 * 显示值与节点的分割符
	 * 
	 * @type String
	 */
	separator : ',',
	/**
	 * 初始化事件
	 */
	initEvents : function() {
		Ext.ux.combox.ComboBoxCheckTree.superclass.initEvents.apply(this,
				arguments);
		this.keyNav.tab = false;

	},
	/**
	 * 初始化组件
	 */
	initComponent : function() {
		this.on({
					scope : this
				});

	},
	/**
	 * 重写展开事件
	 */
	expand : function() {
		Ext.ux.combox.ComboBoxCheckTree.superclass.expand.call(this);
		var combox = this;
		// var mytree=this.tree;
		if (!this.tree.rendered) {
			// this.tree.height = this.maxHeight;
			this.tree.width = !this.listWidth
					? this.getWidth()
					: this.listWidth;
			this.tree.border = false;
			this.tree.autoScroll = true;
			if (this.tree.xtype) {
				this.tree = new Ext.ComponentMgr().create(this.tree,
						this.tree.xtype);
			}
			this.tree.render(this.treeId);
			if (combox.selectValueModel == 'all') {
				combox.tree.checkModel = 'multiple';
			}
			this.tree.on('check', function(node, checked) {
						// alert(combox.isLoadData)
						if (combox.selectModel == 'all') {
							if (combox.isLoadData == true
									|| node.isLeaf() == false) {
								// 加载数据的时候进入多选（不级联模式）
								combox.tree.checkModel = 'multiple';
							} else {
								// 进入级联模式
								combox.tree.checkModel = 'cascade';
							}
						}
						combox.setValue(node, checked);
					});
			/**
			 * 加载树的时候有两种情况
			 * 
			 * @1是直接从后台获取数据后进行选择，这种情况无需进行联动操作。
			 * @2是选择节点，需要对树进行联动操作。
			 */
			this.tree.loader.on('load', function(t, node, r) {
				var valueArr = combox.getValue().split(combox.separator);
				node.eachChild(function(child) {
					if (valueArr.indexOf(child.attributes[combox.valueName]) >= 0) {
						child.ui.toggleCheck(true);
						child.attributes.checked = true;
					}
				});
			});
			var root = this.tree.getRootNode();
			// if (!root.isLoaded()) {
			// root.reload();
			// }
		}
	},
	/**
	 * 重写设值事件
	 * 
	 * @param {}
	 *            node
	 * @param {}
	 *            checked
	 */
	setValue : function(node, checked) {
		if (node.leaf == 1)
			node.leaf = true;
		this.isLoadData = false;
		if (!node) {
			return;
		}
		var mychecktree = this;
		if (!node) {// 新增，重置所用。
			if (this.hiddenField)
				document.getElementById(this.hiddenField).value = null;
			Ext.form.ComboBox.superclass.setValue.call(this, '');
			return;
		}
		if (!node.text) { // 如果不是手动选择节点，则为加载数据自动选择节点
			this.isLoadData = true;// 标志从加载数据自动选择节点
			this.setRawValue(node);
		}
		var checkedValues = [];
		var checkedTexts = [];
		var valueObj = '';
		// hiddenField有两种模式，一种从表单hidden控件来，一种从页面hidden来，获取值方式不一样
		if (document.getElementById(this.hiddenField)) {
			valueObj = document.getElementById(this.hiddenField).value;
		}
		if (!Ext.isEmpty(valueObj)) {
			checkedValues = valueObj.split(this.separator);
			this.value = valueObj;
			// checkedValues = document.getElementById(this.hiddenField).value
			// .split(this.separator);
			checkedTexts = this.getRawValue().split(this.separator);
		}
		if (node.text) { // 更改check促发事件

			if (this.selectValueModel == 'all'
					|| (this.selectValueModel == 'leaf' && node.isLeaf())
					|| (this.selectValueModel == 'folder' && !node.isLeaf())) {
				var values = [];
				var texts = [];
				var root = this.tree.getRootNode();
				var fn = false;
				// var checkedNodes = this.tree.getChecked();
				// for (var i = 0; i < checkedNodes.length; i++) {
				// var node = checkedNodes[i];
				// if (this.selectValueModel == 'all'
				// || (this.selectValueModel == 'leaf' && node.isLeaf())
				// || (this.selectValueModel == 'folder' && !node.isLeaf())) {
				// values.push(node.id);
				// texts.push(node.text);
				// }
				// }

				if (checked) {
					for (var i = 0; i < checkedValues.length; i++) { // 判断钩选节点在原来值中是否存在
						if (!Ext.isEmpty(checkedValues[i])) {
							values.push(checkedValues[i]);
							texts.push(checkedTexts[i]);
							if (node.attributes[this.valueName] == checkedValues[i]) {
								fn = true;
								continue;
							}
						}
					}
					if (!fn) {
						values.push(node.attributes[this.valueName]);
						texts.push(node.text);
					}
				} else {
					for (var i = 0; i < checkedValues.length; i++) { // 判断钩选节点在原来值中是否存在
						if (!Ext.isEmpty(checkedValues[i])) {
							if (node.attributes[this.valueName] != checkedValues[i]) {
								values.push(checkedValues[i]);
								texts.push(checkedTexts[i]);
							}
						}
					}
				}

				this.value = values.join(this.separator);
				var rawValue = texts.join(this.separator);
				this.setRawValue(rawValue);
				if (this.hiddenField) {
					if (document.getElementById(this.hiddenField))
						document.getElementById(this.hiddenField).value = this.value;
				}
			}
		}
		// tip
		if (!Ext.isEmpty(mychecktree.getRawValue())) {
			this.el.dom.title = mychecktree.getRawValue();
			// if (!this.toolTip || !this.toolTip.body) {
			// this.toolTip = Class.forName("Ext.ToolTip").newInstance({
			// target : this.getEl().id,
			// html : mychecktree.getRawValue()
			// });
			// } else {
			// this.toolTip.body.update(mychecktree.getRawValue());
			// }
		}

	},
	/**
	 * 重写取值函数
	 * 
	 * @return {}
	 */
	getValue : function() {
		return this.value || '';
	},
	/**
	 * 清除组件值函数
	 */
	clearValue : function() {
		this.value = '';
		this.setRawValue(this.value);
		if (this.hiddenField) {
			this.hiddenField.value = '';
		}

		this.tree.getSelectionModel().clearSelections();
	}
});

Ext.reg('combochecktree', Ext.ux.combox.ComboBoxCheckTree);