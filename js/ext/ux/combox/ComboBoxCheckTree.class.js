/**
 * ������ѡ���ؼ�
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
	// folder:ֻ��ѡ���׽ڵ�
	Ext.ux.combox.ComboBoxCheckTree.superclass.constructor.apply(this,
			arguments);
}

/**
 * ������ѡ�����,��Ext.ux.tree.MyTree���
 * 
 * @class Ext.ux.combox.ComboBoxCheckTree
 * @extends Ext.form.ComboBox
 */
Ext.extend(Ext.ux.combox.ComboBoxCheckTree, Ext.form.ComboBox, {
	/**
	 * all:����ѡ�����нڵ� leaf:ֻ��ѡ��Ҷ�ӽڵ�
	 * 
	 * @type String
	 */
	selectValueModel : 'leaf',
	/**
	 * ֵ����
	 * 
	 * @type String
	 */
	valueName : 'id',
	/**
	 * ��ʾֵ��ڵ�ķָ��
	 * 
	 * @type String
	 */
	separator : ',',
	/**
	 * ��ʼ���¼�
	 */
	initEvents : function() {
		Ext.ux.combox.ComboBoxCheckTree.superclass.initEvents.apply(this,
				arguments);
		this.keyNav.tab = false;

	},
	/**
	 * ��ʼ�����
	 */
	initComponent : function() {
		this.on({
					scope : this
				});

	},
	/**
	 * ��дչ���¼�
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
								// �������ݵ�ʱ������ѡ��������ģʽ��
								combox.tree.checkModel = 'multiple';
							} else {
								// ���뼶��ģʽ
								combox.tree.checkModel = 'cascade';
							}
						}
						combox.setValue(node, checked);
					});
			/**
			 * ��������ʱ�����������
			 * 
			 * @1��ֱ�ӴӺ�̨��ȡ���ݺ����ѡ��������������������������
			 * @2��ѡ��ڵ㣬��Ҫ������������������
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
	 * ��д��ֵ�¼�
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
		if (!node) {// �������������á�
			if (this.hiddenField)
				document.getElementById(this.hiddenField).value = null;
			Ext.form.ComboBox.superclass.setValue.call(this, '');
			return;
		}
		if (!node.text) { // ��������ֶ�ѡ��ڵ㣬��Ϊ���������Զ�ѡ��ڵ�
			this.isLoadData = true;// ��־�Ӽ��������Զ�ѡ��ڵ�
			this.setRawValue(node);
		}
		var checkedValues = [];
		var checkedTexts = [];
		var valueObj = '';
		// hiddenField������ģʽ��һ�ִӱ�hidden�ؼ�����һ�ִ�ҳ��hidden������ȡֵ��ʽ��һ��
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
		if (node.text) { // ����check�ٷ��¼�

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
					for (var i = 0; i < checkedValues.length; i++) { // �жϹ�ѡ�ڵ���ԭ��ֵ���Ƿ����
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
					for (var i = 0; i < checkedValues.length; i++) { // �жϹ�ѡ�ڵ���ԭ��ֵ���Ƿ����
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
	 * ��дȡֵ����
	 * 
	 * @return {}
	 */
	getValue : function() {
		return this.value || '';
	},
	/**
	 * ������ֵ����
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