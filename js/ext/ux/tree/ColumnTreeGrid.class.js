
// var templateString = "";
$import("Ext.tree.TreeLoader");
$import("Ext.tree.AsyncTreeNode");
$import("Ext.util.Format");
$import("Ext.Msg");
$package("Ext.ux.tree");
$import("Ext.ux.tree.ColumnTree");
$import("Ext.ux.tree.ColumnNodeUI");
Ext.namespace("Ext.ux.tree");
Ext.ux.tree.ColumnTreeGrid = Ext.extend(Ext.ux.tree.ColumnTree, {
	// ������ṹ
	structure : '',

	// ���󶨵ı�
	tablename : '',

	// ��ȡ���ݵ�URL
	urlAction : '',
//	isToolBar : true,   //�Ƿ���ʾ������
//	isRightMenu : true, //�Ƿ���ʾ�Ҽ��˵�
	rootVisible : false,
	autoScroll : true,
	border : false,
	renderTo : '',
	lines : true,// �ڵ���������
	enableDrag : true,// ���Ľڵ�����϶�Drag(Ч������),ע�ⲻ��Draggable
	enableDD : true,// ���������϶�,������ͨ��Drag�ı�ڵ�Ĳ�νṹ(drap��drop)
	enableDrop : true,// ����drop

	treeType : 1,// ��־�ڲ��ؼ��ǵ�ѡ��1�����߶�ѡ��2��������Ŀ��3 Ĭ�ϵ�ѡ��

	columns : '',

	loader : '',
	keyField : 'id',

	formHeight : 400,
	formWidth : 650,
	formAutoHeight : true,

	isAddButton : true,
	isViewButton : true,
	isEditButton : true,
	isDelButton : true,
	isRefreshButton : true,
	isSearch : true,
	gridButtons : '',
	viewButtons : '',
	addButtons : '',
	editButtons : '',
	loadMask : true,
	searchType : 1,
	nodeValue : 'id',

//	root : new Ext.tree.AsyncTreeNode({
//				id : 0,
//				text : '�˵�����',
//				type : 'columnTree'
//			}),
	initComponent : function() {
		if (this.structure != '') {
			this.initStructure();
		}
		Ext.ux.tree.ColumnTreeGrid.superclass.initComponent.call(this);

	},

	initEvents : function() {
		Ext.ux.tree.ColumnTreeGrid.superclass.initEvents.call(this);
//        if(this.loadMask){
//			this.loadMask = new Ext.LoadMask(this.bwrap,
//                    Ext.apply({store:this.store}, this.loadMask));
//        }
	},
	initStructure : function() {
		//var url = this.treeUrl;
		var columntree = this;
		this.loader = new Ext.tree.TreeLoader({
					url : columntree.treeUrl,
					uiProviders : {
						'col' : Ext.ux.tree.ColumnNodeUI
					}
				});
		//this.loader.processResponse = this.processResponse;
				// ��������
		this.root = new Ext.tree.AsyncTreeNode({
					id : '-1',
					code : '-1',// ȫ�ֱ���root
					text : this.rootText ? this.rootText : '���ڵ�',
					expanded : true,
					loader : columntree.loader
				});
//		this.loader.on("beforeload", function(loader, node) {
//
//					if (node.text == '�˵�����')
//						node.id = -1;
//					loader.dataUrl = url;
//				});

		var oCM = []; // ��ģʽ����
		var nodeArr = []; // ��ģʽ����
		var len = this.structure.length;// �õ��ṹ�ĳ���
		for (var i = 0; i < len; i++) {
			var c = this.structure[i];

			if (c.isInGrid != false) {
				if (c.formType == 'datefield' || c.type == 'date') {
					c.type = 'date';
					c.renderer = c.renderer ? c.renderer : Ext.util.Format
							.dateRenderer('Y-m-d');
					// c.mapping = c.name + '.time';
				} else if (c.formType == 'datetimefield'
						|| c.type == 'datetime') {
					c.type = 'date';
					c.renderer = c.renderer ? c.renderer : Ext.util.Format
							.dateRenderer('Y-m-d H:i:s');
					// c.mapping = c.name + '.time';
				}
				oCM[oCM.length] = {
					header : c.header,
					tooltip : c.header,
					dataIndex : c.name,
					hidden : c.hidden || false,
					width : !c.hidden ? c.width : this.fieldwidth,
					// ����Ϊ�������Ҷ���
					align : c.align ? c.align : 'left',
					// �ṹ����Ⱦ����
					renderer : c.renderer ? c.renderer : extUtil.toolTip
				};
				nodeArr[nodeArr.length] = {
				}
			}
		}
		
		this.columns = oCM;

		// ����������
		
		// ����չ����֮ǰ�¼�
//		this.loader.on("beforeload", function(loader, node) {
//					if (node.id == -1) {
//						var parent = node.attributes[columntree.nodeValue];
//						loader.url = treeUrl + parent;
//					}
//				});
	},
	// �鿴�û�ѡ�е�����
	doView : function(id) {},
	/*
	 * @���ܣ��༭�û�ѡ�е����� @������type Ϊ1��Ϊ�������� 2��Ϊ�޸�����
	 * 
	 */
	doEdit : function(node, type) {},

	/*
	 * @���ܣ�ɾ������ѡ�м�¼֧������ɾ��
	 * 
	 */

	doDelete : function(node) {},

	/*
	 * @���ܣ���ʼ��combo�ؼ�����
	 * 
	 */
	initCombo : function(o) {},

	/*
	 * @���ܣ���ʼ����ѡ�������ؼ����ݣ��༭��ʱ��ֵ
	 * 
	 */
	initRadioTree : function(o) {},

	/*
	 * @���ܣ�����ɹ���ʾ��Ϣ
	 */
	doSuccess : function(action, form) {
		var ogrid = this;
		Ext.Msg.alert('��ʾ', action.result.message
						? action.result.message
						: '�����ɹ���');
		ogrid.root.reload();
	},

	/*
	 * @���ܣ�����ʧ����ʾ��Ϣ
	 */
	doFailure : function(action, form) {
		Ext.Msg.alert('�������', action.result.message
						? action.result.message
						: '������δ��Ӧ�����Ժ����ԣ�');
	},

	/*
	 * @����:Ĭ�ϵĸ�ʽ�����ں��� @���ظ�ʽ��2008-09-21
	 */
	formatDate : function(v) {
		return v ? v.dateFormat('Y-m-d') : ''
	}
});
