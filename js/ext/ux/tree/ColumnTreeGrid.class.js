
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
	// 储存表格结构
	structure : '',

	// 表格绑定的表
	tablename : '',

	// 获取数据的URL
	urlAction : '',
//	isToolBar : true,   //是否显示工具拦
//	isRightMenu : true, //是否显示右键菜单
	rootVisible : false,
	autoScroll : true,
	border : false,
	renderTo : '',
	lines : true,// 节点间的虚线条
	enableDrag : true,// 树的节点可以拖动Drag(效果上是),注意不是Draggable
	enableDD : true,// 不仅可以拖动,还可以通过Drag改变节点的层次结构(drap和drop)
	enableDrop : true,// 仅仅drop

	treeType : 1,// 标志内部控件是单选树1，或者多选树2，或者栏目树3 默认单选树

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
//				text : '菜单树根',
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
				// 设置树根
		this.root = new Ext.tree.AsyncTreeNode({
					id : '-1',
					code : '-1',// 全局变量root
					text : this.rootText ? this.rootText : '根节点',
					expanded : true,
					loader : columntree.loader
				});
//		this.loader.on("beforeload", function(loader, node) {
//
//					if (node.text == '菜单树根')
//						node.id = -1;
//					loader.dataUrl = url;
//				});

		var oCM = []; // 列模式数组
		var nodeArr = []; // 列模式数组
		var len = this.structure.length;// 得到结构的长度
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
					// 类型为数字则右对齐
					align : c.align ? c.align : 'left',
					// 结构的渲染函数
					renderer : c.renderer ? c.renderer : extUtil.toolTip
				};
				nodeArr[nodeArr.length] = {
				}
			}
		}
		
		this.columns = oCM;

		// 顶部工具条
		
		// 增加展开树之前事件
//		this.loader.on("beforeload", function(loader, node) {
//					if (node.id == -1) {
//						var parent = node.attributes[columntree.nodeValue];
//						loader.url = treeUrl + parent;
//					}
//				});
	},
	// 查看用户选中的数据
	doView : function(id) {},
	/*
	 * @功能：编辑用户选中的数据 @参数：type 为1则为新增数据 2则为修改数据
	 * 
	 */
	doEdit : function(node, type) {},

	/*
	 * @功能：删除所有选中记录支持批量删除
	 * 
	 */

	doDelete : function(node) {},

	/*
	 * @功能：初始化combo控件数据
	 * 
	 */
	initCombo : function(o) {},

	/*
	 * @功能：初始化单选下拉树控件数据，编辑的时候赋值
	 * 
	 */
	initRadioTree : function(o) {},

	/*
	 * @功能：请求成功显示信息
	 */
	doSuccess : function(action, form) {
		var ogrid = this;
		Ext.Msg.alert('提示', action.result.message
						? action.result.message
						: '操作成功！');
		ogrid.root.reload();
	},

	/*
	 * @功能：请求失败显示信息
	 */
	doFailure : function(action, form) {
		Ext.Msg.alert('请求错误', action.result.message
						? action.result.message
						: '服务器未响应，请稍后再试！');
	},

	/*
	 * @功能:默认的格式化日期函数 @返回格式：2008-09-21
	 */
	formatDate : function(v) {
		return v ? v.dateFormat('Y-m-d') : ''
	}
});
