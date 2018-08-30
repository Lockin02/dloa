/**
 * �����򲼾֣���Ҫ������������м����ұ߹���������
 */
$import("Ext.Viewport");
$import("Ext.data.HttpProxy");
$import("Ext.Ajax");
$package("Ext.ux.panel");
Ext.namespace("Ext.ux.panel");
$import("Ext.ux.tree.MyTree");
/**
 * ͨ�ò����ࣺ��������м�������������������������ͬ���������¼�������ϵ,�������ֵ䣬��Ʒ��𼰲�Ʒ�Ȳ���
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
	 * �Ƿ������
	 * 
	 * @type Boolean
	 */
	isWestTree : true,
	/**
	 * �����
	 * 
	 * @type
	 */
	westTree : null,
	/**
	 * ���Ƿ�֧���Ҽ��˵�
	 * 
	 * @type Boolean
	 */
	isRightMenu : true,
	/**
	 * �����Ķ�������
	 * 
	 * @type String
	 */
	treeBoName : '',
	/**
	 * ����po��������
	 * 
	 * @type String
	 */
	treeObjName : '',
	/**
	 * �ڵ��Ƿ�����϶�
	 * 
	 * @type Boolean
	 */
	enableDD : true,
	/**
	 * �Ƿ��Ҷ��ˢ�±��
	 * 
	 * @type Boolean
	 */
	isClickLeaf : false,
	/**
	 * ���Ҽ��˵�
	 * 
	 * @type String
	 */
	treeMenu : '',
	/**
	 * �Ƿ�չ�������нڵ�
	 * 
	 * @type Boolean
	 */
	fnExpandAll : false,
	/**
	 * ������ڵ�url����������
	 * 
	 * @type String
	 */
	nodeClickValue : 'id',
	/**
	 * ���˵�������������Ĭ��ִ�к���
	 * 
	 * @type String
	 */
	addTreeFormFun : '',

	initComponent : function() {
		this.initStructure();
		Ext.ux.panel.MyGridPanel.superclass.initComponent.call(this);

	},
	/**
	 * ��ʼ���ṹ����
	 */
	initStructure : function() {
		// this.height = mainHeight;// ��ȡmainPanel�߶�
		var panelItems = [];
		var mygrid = this.myGrid;
		// mygrid.parentFieldType = this.parentFieldType;
		var myGridPanel = this;
		if (this.isWestTree) {
			var nodeClickUrl = this.nodeClickUrl;
			this.westTree = new Ext.ux.tree.MyTree({
				title : this.treeBoName,
				rootText : this.westTitle + '��',
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
				margins : '5 0 5 5',// ��������
				formCol : 2,
				width : 180,
				minSize : 100,
				maxSize : 300,
				enableDD : this.enableDD ? true : false,
				isRightMenu : this.isRightMenu,
				treeMenu : this.treeMenu,
				titleCollapse : true,// ����������κεط���������
				split : true,// �ܷ��϶��ı�panle��С
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
				// ���ó� Ҷ�ӽڵ�Ҳ���� ��Ϊ �϶�Ŀ�ĵ�
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
																: '�϶��ڵ�ɹ���'
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

		// ����mygrid�߿�
		// mygrid.border=true;
		// mygrid.bodyStyle = "width:100%;height:100%;";
		// mygrid.getTopToolbar().style = "";
		// mygrid.getBottomToolbar().style = "";
		// mygrid.doLayout();
		panelItems[panelItems.length] = mygrid;

		this.items = panelItems;
	}
})