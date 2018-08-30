/**
 * 下拉树组件
 */

(function($) {
	// 以下部分为yxtree定义的关键字
	// var NODE_CLICK = "node_click";// 单击节点
	// var NODE_DBLCLICK = "node_dblclick";// 双击节点 add by chengl
	// var NODE_RCLICK = "node_rclick";// 右键节点 add by chengl
	// var NODE_CHANGE = "node_change";// 多选节点
	// var NODE_RENAME = "node_rename";// 更改节点名称
	// var NODE_REMOVE = "node_remove";// 移除节点
	// var NODE_DRAG = "node_drag";// 拖动节点
	// var NODE_DROP = "node_drop";// 放置节点
	// var NODE_EXPAND = "node_expand";// 节点展开
	// var NODE_COLLAPSE = "node_collapse"; // 节点收缩
	// var NODE_ASYNC_SUCCESS = "node_success";// 节点加载成功
	// var NODE_ASYNC_ERROR = "node_error";// 节点加载失败

	var IDMark_Switch = "_switch";
	var IDMark_Icon = "_ico";
	var IDMark_Span = "_span";
	var IDMark_Input = "_input";
	var IDMark_Edit = "_edit";
	var IDMark_Remove = "_remove";
	var IDMark_Ul = "_ul";
	var IDMark_A = "_a";

	var LineMark_Root = "root";
	var LineMark_Roots = "roots";
	var LineMark_Center = "center";
	var LineMark_Bottom = "bottom";
	var LineMark_NoLine = "noLine";
	var LineMark_Line = "line";

	var FolderMark_Open = "open";
	var FolderMark_Close = "close";
	var FolderMark_Docu = "docu";

	var Class_CurSelectedNode = "curSelectedNode";
	var Class_CurSelectedNode_Edit = "curSelectedNode_Edit";
	var Class_TmpTargetTree = "tmpTargetTree";
	var Class_TmpTargetNode = "tmpTargetNode";

	var Check_Style_Box = "checkbox";
	var Check_Style_Radio = "radio";
	var CheckBox_Default = "chk";
	var CheckBox_False = "false";
	var CheckBox_True = "true";
	var CheckBox_Full = "full";
	var CheckBox_Part = "part";
	var CheckBox_Focus = "focus";
	var Radio_Type_All = "all";
	var Radio_Type_Level = "level";

	var MinMoveSize = "5";

	var ps = new Array();// 出现多棵树的时候，用于存储每棵树的设置数组
	var yxtreeId = 0;

	// yxtree构造函数
	$.woo.component.subclass('woo.yxtree', {
		options : {
			// 高度，auto自动取document高度
			height : 'auto',
			// Tree 唯一标识，主UL的ID
			treeObjId : "",
			// 是否显示CheckBox
			checkable : false,
			// 是否在编辑状态
			editable : false,
			// 编辑状态是否显示修改按钮
			edit_renameBtn : true,
			// 编辑状态是否显示删除节点按钮
			edit_removeBtn : true,
			// 是否显示树的线
			showLine : true,
			// 当前被选择的TreeNode
			curTreeNode : null,
			// 当前正被编辑的TreeNode
			curEditTreeNode : null,
			// 是否处于拖拽期间 0: not Drag; 1: doing Drag
			dragStatus : 0,
			dragNodeShowBefore : false,
			// 选择CheckBox 或 Radio
			checkStyle : Check_Style_Box,
			// checkBox点击后影响父子节点设置（checkStyle=Check_Style_Radio时无效）
			/**
			 * Y 属性定义 checkbox 被勾选后的情况； N 属性定义 checkbox 取消勾选后的情况； "p"
			 * 表示操作会影响父级节点； "s" 表示操作会影响子级节点
			 */
			checkType : {
				"Y" : "ps",
				"N" : "ps"
			},
			// radio 最大个数限制类型，每一级节点限制 或
			// 整棵Tree的全部节点限制（checkStyle=Check_Style_Box时无效）
			checkRadioType : Radio_Type_Level,
			// checkRadioType = Radio_Type_All 时，保存被选择节点的堆栈
			checkRadioCheckedList : [],
			// 是否异步获取节点数据
			async : false,
			// 获取节点数据的URL地址
			url : "",
			// 获取节点数据时，必须的数据名称，例如：id、name
			param : ['id', 'name'],
			// 其它参数，如 {id:1}
			paramOther : {},
			// 用户自定义名称列
			nameCol : "name",
			// 用户自定义子节点列
			nodesCol : "nodes",
			// 折叠、展开特效速度
			expandSpeed : "fast",
			// 折叠、展开Trigger开关
			expandTriggerFlag : false,
			root : {
				isRoot : true,
				nodes : []
			},
			// 静态数据集
			data : [],
			// 被选中对象名
			checkedObjId : "",
			// 被选中的checkbox值
			appendData : "",
			// 右键菜单
			menus : [],
			// 扩展的按钮
			buttonsEx : [],
			// event Function (注意：通过callback传入的事件无法累加，需要累加的事件请通过el.bind进行注册)
			/**
			 * 注册树事件(为了方便查看相应事件，实际并无作用)
			 */
			registerEven : [
					// 单击节点
					'node_click',
					// 双击节点
					'node_dblclick',
					// 右键节点
					'node_rclick',
					// 多选节点
					'node_change',
					// 更改节点名称
					'node_rename',
					// 移除节点
					'node_remove',
					// 拖动节点
					'node_drag',
					// 放置节点
					'node_drop',
					// 节点展开
					'node_expand',
					// 节点收缩
					'node_collapse',
					// 节点加载成功
					'node_success',
					// 节点加载失败
					'node_error']
			/*******************************************************************
			 * 以前的树事件***************** callback : { beforeClick : null,
			 * beforeChange : null, beforeDrag : null, beforeDrop : null,
			 * beforeRename : null, beforeRemove : null, beforeExpand : null,
			 * beforeCollapse : null, click : null, change : null, drag : null,
			 * drop : null, rename : null, remove : null, expand : null,
			 * collapse : null, asyncSuccess : null, asyncError : null }
			 ******************************************************************/
		},
		_create : function() {
			var t = this, el = this.el, p = this.options;
			// 这里要考虑如何修复dom元素只能是ul的问题
			// if ($(el).context.nodeName != "UL") {
			// var ul = $("ul").attr("id",el.attr('id'));
			// el=this.el = ul;
			// }
			if (p.height == 'auto') {
				el.parent().height($(document).height());
			}
			el.addClass("tree");
			if (p.url)// 如果传递了url，则默认为异步获取节点数据
				p.async = true;
			p.treeObjId = el.attr("id");
			p.treeObj = this;
			p.root.tId = -1;
			p.root.name = "TREE ROOT";
			p.root.isRoot = true;
			p.checkRadioCheckedList = [];
			p.curTreeNode = null;
			p.curEditTreeNode = null;
			p.dragNodeShowBefore = false;
			p.dragStatus = 0;
			p.expandTriggerFlag = false;
			if (!p.root[p.nodesCol])
				p.root[p.nodesCol] = [];
			yxtreeId = 0;

			// edit by show 内置数组,不需要定义
			p.appendDataArr = p.appendData.split(",");

			if (p.data) {
				p.root[p.nodesCol] = p.data;
			}
			ps[p.treeObjId] = p;

			el.empty();

			this.addToolbarButtons();
			// this.bindNodeEven();

			if (p.root[p.nodesCol] && p.root[p.nodesCol].length > 0) {
				this.initTreeNodes(0, p.root[p.nodesCol]);
			} else if (p.async && p.url && p.url.length > 0) {
				this.asyncGetNode();
			}
			el.trigger("render");
		},

		/**
		 * 添加工具栏按钮
		 */
		addToolbarButtons : function() {
			var el = this.el, t = this, p = t.options;
			var btnDiv = $("<div>");
			var refleshButton = $("<button title='刷新'  type='button' class='ico refresh'></button>");
			refleshButton.click(function() {
						t.reload();
					});
			el.append(refleshButton);

			var explandButton = $("<button title='展开树'  type='button' class='ico expandNode'></button>");
			explandButton.click(function() {
						t.expandAll();
					});
			el.append(explandButton);
			// 暂时只追加到后面
			if (p.buttonsEx.length > 0) {
				for (var i = 0; l = p.buttonsEx.length, i < l; i++) {
					var b = p.buttonsEx[i];
					var button = $("<button  type='button' ></button>");
					if (b.title) {
						button.attr('title', b.title);
					}
					button.addClass(b.icon ? b.icon : "ico edit");
					if (b.action) {
						button.click(b.action);
					}
					el.append(button);
				}
			}
		},
		/**
		 * 刷新某个节点下数据，如果没传入节点参数，则刷新整棵树
		 */
		reload : function(treeNode) {
			if (!treeNode) {
				var el = this.el, t = this, p = t.options;
				if (!p.data || p.data == '') {
					$(el).children().remove("li");
					t.asyncGetNode();
				}
			} else {
				// 刷新某个节点下数据
			}
		},
		/**
		 * 刷新数据 add by chengl 2011-05-31
		 */
		reloadData : function(data) {
			var el = this.el, t = this, p = t.options;
			$(el).children().remove("li");
			p.data = data;
			this.initTreeNodes(0, data);
		},

		refresh : function() {
			var p = this.options, treeObjId = this.el.attr("id");
			$("#" + treeObjId).empty();
			p.curTreeNode = null;
			p.curEditTreeNode = null;
			p.dragStatus = 0;
			p.dragNodeShowBefore = false;
			p.checkRadioCheckedList = [];
			yxtreeId = 0;
			this.initTreeNodes(0, p.root[p.nodesCol]);
		},

		setEditable : function(editable) {
			this.options.editable = editable;
			return this.refresh();
		},

		getNodes : function() {
			var p = this.options;
			return p.root[p.nodesCol];
		},

		getSelectedNode : function() {
			return this.options.curTreeNode;
		},

		getCheckedNodes : function(selected) {
			var p = this.options;
			selected = (selected != false);
			return this.getTreeCheckedNodes(p.root[p.nodesCol], selected);
		},

		getNodeByTId : function(treeId) {
			var p = this.options;
			return this.getTreeNodeByTId(p.root[p.nodesCol], treeId);
		},
		// 根据 id 获取 节点的数据对象
		getNodeById : function(nodeId) {
			var p = this.options;
			return this.getTreeNodeById(p.root[p.nodesCol], nodeId);
		},

		getNodeIndex : function(treeNode) {
			var p = this.options;
			var parentNode = (treeNode.parentNode == null)
					? p.root
					: treeNode.parentNode;
			for (var i = 0; i < parentNode[p.nodesCol].length; i++) {
				if (parentNode[p.nodesCol][i] == treeNode)
					return i;
			}
			return -1;
		},
		getSetting : function() {
			var treeObjId = this.el.attr("id");
			if (!treeObjId)
				return;
			var yxtreeSetting = ps[treeObjId];
			var p = {
				checkType : {},
				callback : {}
			};

			var tmp_checkType = yxtreeSetting.checkType;
			yxtreeSetting.checkType = undefined;
			var tmp_callback = yxtreeSetting.callback;
			yxtreeSetting.callback = undefined;
			var tmp_root = yxtreeSetting.root;
			yxtreeSetting.root = undefined;

			$.extend(p, yxtreeSetting);

			yxtreeSetting.checkType = tmp_checkType;
			yxtreeSetting.callback = tmp_callback;
			yxtreeSetting.root = tmp_root;

			// 不能获取root信息
			$.extend(true, p.checkType, tmp_checkType);
			$.extend(p.callback, tmp_callback);

			return p;
		},

		updateSetting : function(yxtreeSetting) {
			var treeObjId = this.el.attr("id");
			if (!treeObjId || !yxtreeSetting)
				return;
			var p = ps[treeObjId];

			var tmp_checkType = yxtreeSetting.checkType;
			yxtreeSetting.checkType = undefined;
			var tmp_callback = yxtreeSetting.callback;
			yxtreeSetting.callback = undefined;
			var tmp_root = yxtreeSetting.root;
			yxtreeSetting.root = undefined;

			$.extend(p, yxtreeSetting);

			yxtreeSetting.checkType = tmp_checkType;
			yxtreeSetting.callback = tmp_callback;
			yxtreeSetting.root = tmp_root;

			// 不提供root信息update
			$.extend(true, p.checkType, tmp_checkType);
			$.extend(p.callback, tmp_callback);
			p.treeObjId = treeObjId;

		},

		/**
		 * 展开所有节点，暂时不能异步级联
		 */
		expandAll : function(expandSign) {
			// $(this.el).children().remove("li");
			this.expandCollapseSonNode(null, expandSign, true);
		},

		expandNode : function(treeNode, expandSign, sonSign) {
			if (expandSign) {
				// 如果展开某节点，则必须展开其全部父节点
				this.expandCollapseParentNode(treeNode, expandSign, false);
			}

			if (sonSign) {
				// 多个图层同时进行动画，导致产生的延迟很难用代码准确捕获动画最终结束时间
				// 因此为了保证准确将节点focus进行定位，则对于js操作节点时，不进行动画
				this.expandCollapseSonNode(treeNode, expandSign, false,
						function() {
							$("#" + treeNode.tId + IDMark_Icon).focus().blur();
						});
			} else if (treeNode.open != expandSign) {
				this.switchNode(this.options, treeNode);
				$("#" + treeNode.tId + IDMark_Icon).focus().blur();
			}
		},

		selectNode : function(treeNode) {
			var p = this.options;
			if (!treeNode)
				return;

			this.selectNode(treeNode);
			// 如果选择某节点，则必须展开其全部父节点
			// 多个图层同时进行动画，导致产生的延迟很难用代码准确捕获动画最终结束时间
			// 因此为了保证准确将节点focus进行定位，则对于js操作节点时，不进行动画
			this.expandCollapseParentNode(treeNode, true, false, function() {
						$("#" + treeNode.tId + IDMark_Icon).focus().blur();
					});
		},

		cancleSelectedNode : function() {
			this.canclePreSelectedNode(this.options);
		},

		addNodes : function(parentNode, newNodes, isSilent) {
			if (!newNodes)
				return;
			if (!parentNode)
				parentNode = null;
			this.addTreeNodes(this.options, parentNode, newNodes,
					(isSilent == true));

		},

		updateNode : function(treeNode) {
			var treeObjId = this.el.attr("id");
			if (!treeNode)
				return;

			$("#" + treeNode.tId + IDMark_Span)
					.text(treeNode[this.options.nameCol]);
		},

		moveNode : function(targetNode, treeNode) {
			if (!treeNode)
				return;

			if (targetNode
					&& (treeNode.parentNode == targetNode || $("#"
							+ treeNode.tId).find("#" + targetNode.tId).length > 0)) {
				return;
			} else if (!targetNode) {
				targetNode = null;
			}
			moveTreeNode(targetNode, treeNode, false);
		},

		removeNode : function(treeNode) {
			if (!treeNode)
				return;
			this.removeTreeNode(this.options, treeNode);
		},
		/** **************以下开始为私有方法**************** */
		/**
		 * 绑定树事件
		 *
		 * bindNodeEven : function() { var el = this.el, p = this.options;
		 * el.unbind(NODE_CLICK); el.bind(NODE_CLICK, function(event, treeId,
		 * treeNode, aObj) { if ((typeof p.callback.click) == "function")
		 * p.callback.click(event, treeId, treeNode, aObj); });
		 * el.unbind(NODE_DBLCLICK); el.bind(NODE_DBLCLICK, function(event,
		 * treeId, treeNode, aObj) { if ((typeof p.callback.dblclick) ==
		 * "function") p.callback.dblclick(event, treeId, treeNode, aObj); });
		 * el.unbind(NODE_RCLICK); el.bind(NODE_RCLICK, function(event, treeId,
		 * treeNode, aObj) { if ((typeof p.callback.rclick) == "function")
		 * p.callback.rclick(event, treeId, treeNode, aObj); });
		 * el.unbind(NODE_CHANGE); el.bind(NODE_CHANGE, function(event, treeId,
		 * treeNode) { if ((typeof p.callback.change) == "function")
		 * p.callback.change(event, treeId, treeNode); });
		 *
		 * el.unbind(NODE_RENAME); el.bind(NODE_RENAME, function(event, treeId,
		 * treeNode) { if ((typeof p.callback.rename) == "function")
		 * p.callback.rename(event, treeId, treeNode); });
		 *
		 * el.unbind(NODE_REMOVE); el.bind(NODE_REMOVE, function(event, treeId,
		 * treeNode) { if ((typeof p.callback.remove) == "function")
		 * p.callback.remove(event, treeId, treeNode); });
		 *
		 * el.unbind(NODE_DRAG); el.bind(NODE_DRAG, function(event, treeId,
		 * treeNode) { if ((typeof p.callback.drag) == "function")
		 * p.callback.drag(event, treeId, treeNode); });
		 *
		 * el.unbind(NODE_DROP); el.bind(NODE_DROP, function(event, treeId,
		 * treeNode, targetNode) { if ((typeof p.callback.drop) == "function")
		 * p.callback .drop(event, treeId, treeNode, targetNode); });
		 *
		 * el.unbind(NODE_EXPAND); el.bind(NODE_EXPAND, function(event, treeId,
		 * treeNode) { if ((typeof p.callback.expand) == "function")
		 * p.callback.expand(event, treeId, treeNode); });
		 *
		 * el.unbind(NODE_COLLAPSE); el.bind(NODE_COLLAPSE, function(event,
		 * treeId, treeNode) { if ((typeof p.callback.collapse) == "function")
		 * p.callback.collapse(event, treeId, treeNode); });
		 *
		 * el.unbind(NODE_ASYNC_SUCCESS); el.bind(NODE_ASYNC_SUCCESS,
		 * function(event, treeId, treeNode, msg) { if ((typeof
		 * p.callback.asyncSuccess) == "function")
		 * p.callback.asyncSuccess(event, treeId, treeNode, msg); });
		 *
		 * el.unbind(NODE_ASYNC_ERROR); el.bind(NODE_ASYNC_ERROR,
		 * function(event, treeId, treeNode, XMLHttpRequest, textStatus,
		 * errorThrown) { if ((typeof p.callback.asyncError) == "function")
		 * p.callback.asyncError(event, treeId, treeNode, XMLHttpRequest,
		 * textStatus, errorThrown); }); },
		 */
		/**
		 * 初始化并显示节点Json对象
		 */
		initTreeNodes : function(level, treeNodes, parentNode) {
			var el = this.el, t = this, p = this.options;
			if (!treeNodes)
				return;
			for (var i = 0; i < treeNodes.length; i++) {
				var node = treeNodes[i];
				node.level = level;
				node.tId = p.treeObjId + "_" + (++yxtreeId);
				node.parentNode = parentNode;
				node.checkedNew = (node.checkedNew == undefined)
						? (node.checked == true)
						: node.checkedNew;
				node.check_Focus = false;
				node.check_True_Full = true;
				node.check_False_Full = true;
				node.editNameStatus = false;

				if (node.isParent == "1") {
					node.isParent = true;
				} else if (node.isParent == "0") {
					node.isParent = false;
				}

				var tmpParentNode = (parentNode) ? parentNode : p.root;

				// 允许在非空节点上增加节点
				node.isFirstNode = (tmpParentNode[p.nodesCol].length == treeNodes.length)
						&& (i == 0);
				node.isLastNode = (i == (treeNodes.length - 1));

				if (node[p.nodesCol] && node[p.nodesCol].length > 0) {
					node.open = (node.open) ? true : false;
					node.isParent = true;
					t.showTree(node);
					t.initTreeNodes(level + 1, node[p.nodesCol], node);

				} else {
					node.isParent = (node.isParent) ? true : false;
					t.showTree(node);

					// 只在末级节点的最后一个进行checkBox修正
					if (p.checkable && i == treeNodes.length - 1) {
						t.repairParentChkClass(node);
					}
				}
			}
		},
		/**
		 * 显示单个节点
		 */
		showTree : function(treeNode) {
			var t = this, p = this.options, el = this.el;
			// 获取父节点
			var parent = treeNode.parentNode;
			if (!parent) {
				parent = el;
			} else {
				parent = $("#" + treeNode.parentNode.tId + IDMark_Ul);
			}

			var html = "<li id='" + treeNode.tId + "' class='tree-node'>"
					+ "<button type=\"button\" class=\"switch\" id='"
					+ treeNode.tId + IDMark_Switch
					+ "' title='' onfocus='this.blur();'></button>" + "<a id='"
					+ treeNode.tId + IDMark_A + "' onclick=\""
					+ (treeNode.click || '')
					+ "\" ><button type=\"button\" class=\""
					+ (treeNode.iconSkin ? treeNode.iconSkin : "")
					+ " ico\" id='" + treeNode.tId + IDMark_Icon
					+ "' title='' onfocus='this.blur();'></button><span id='"
					+ treeNode.tId + IDMark_Span + "'></span></a>" + "<ul id='"
					+ treeNode.tId + IDMark_Ul + "'></ul>" + "</li>";
			parent.append(html);

			var switchObj = $("#" + treeNode.tId + IDMark_Switch);// 节点前面的+-
			var aObj = $("#" + treeNode.tId + IDMark_A).data("treeNode",
					treeNode);// 加入节点存储，与dom元素绑定 add by chengl
			var nObj = $("#" + treeNode.tId + IDMark_Span);
			var ulObj = $("#" + treeNode.tId + IDMark_Ul);

			nObj.text(treeNode[p.nameCol]);
			var icoObj = $("#" + treeNode.tId + IDMark_Icon);

			// 设置Line、Ico等css属性
			if (p.showLine) {
				if (treeNode.level == 0 && treeNode.isFirstNode
						&& treeNode.isLastNode) {
					switchObj.attr("class", switchObj.attr("class") + "_"
									+ LineMark_Root);
				} else if (treeNode.level == 0 && treeNode.isFirstNode) {
					switchObj.attr("class", switchObj.attr("class") + "_"
									+ LineMark_Roots);
				} else if (treeNode.isLastNode) {
					switchObj.attr("class", switchObj.attr("class") + "_"
									+ LineMark_Bottom);
				} else {
					switchObj.attr("class", switchObj.attr("class") + "_"
									+ LineMark_Center);
				}
				if (!treeNode.isLastNode) {
					ulObj.addClass(LineMark_Line);
				}
			} else {
				switchObj.attr("class", switchObj.attr("class") + "_"
								+ LineMark_NoLine);
			}
			if (treeNode.isParent) {
				var tmpOpen = (treeNode.open
						? ("_" + FolderMark_Open)
						: ("_" + FolderMark_Close));
				switchObj.attr("class", switchObj.attr("class") + tmpOpen);
				icoObj.attr("class", icoObj.attr("class") + tmpOpen);
			} else {
				switchObj.attr("class", switchObj.attr("class") + "_"
								+ FolderMark_Docu);
				icoObj.attr("class", icoObj.attr("class") + "_"
								+ FolderMark_Docu);
			}
			if (treeNode.icon) {
				// update by chengl 2011-06-12 设置节点图标
				icoObj.attr("class", "ico_" + treeNode.icon);
				// icoObj.attr("style", "background:url(" + treeNode.icon
				// + ") 0 0 no-repeat;");
			}

			// 增加树节点展开、关闭事件
			ulObj.css({
						"display" : (treeNode.open ? "block" : "none")
					});
			if (treeNode.isParent) {
				switchObj.bind('click', {
							tree : t,
							treeObjId : p.treeObjId,
							treeNode : treeNode
						}, t.onSwitchNode);
				// update by chengl 2011-08-11 dblclick
				aObj.bind('click', {
							tree : t,
							treeObjId : p.treeObjId,
							treeNode : treeNode
						}, t.onSwitchNode);
			}
			aObj.bind('click', function(e) {

						// var beforeClick = true;
						// if ((typeof p.callback.beforeClick) == "function")
						// beforeClick = p.callback.beforeClick(p.treeObjId,
						// treeNode);
						// if (beforeClick == false)
						// return;
						// 除掉默认事件，防止文本被选择
						window.getSelection ? window.getSelection()
								.removeAllRanges() : document.selection.empty();
						// 设置节点为选中状态
						t.selectNode(treeNode);
						// 触发click事件
						el.trigger("node_click", [p.treeObjId, treeNode, aObj]);
					});
			aObj.bind('dblclick', function(e) {
						el.trigger("node_dblclick", [p.treeObjId, treeNode,
										aObj]);
					});
			if (p.menus.length > 0) {
				aObj.yxmenu({
							type : 'rclick',
							width : 120,
							items : p.menus
						});
			}
			aObj.bind('contextmenu', function(e) {
						el
								.trigger("node_rclick", [p.treeObjId, treeNode,
												aObj]);
						e.preventDefault();
					});
			icoObj.bind('mousedown', function() {
						treeNode.editNameStatus = false;
					});

			// 显示CheckBox Or Radio
			if (p.checkable) {
				switchObj.after("<BUTTON type='BUTTON' ID='" + treeNode.tId
						+ "_check' onfocus='this.blur();' ></BUTTON>");

				var checkObj = $("#" + treeNode.tId + "_check");

				if (p.checkStyle == Check_Style_Radio
						&& p.checkRadioType == Radio_Type_All
						&& treeNode.checkedNew) {
					p.checkRadioCheckedList = p.checkRadioCheckedList
							.concat([treeNode]);
				}

				t.setChkClass(checkObj, treeNode);
				aObj.data('checkObj', checkObj);// 把节点jquery对象与选择框绑定
				checkObj.bind('click', function(e) {

					// var beforeChange = true;
					// if ((typeof p.callback.beforeChange) == "function")
					// beforeChange = p.callback.beforeChange(p.treeObjId,
					// treeNode);
					// if (beforeChange == false)
					// return;

					treeNode.checkedNew = !treeNode.checkedNew;
					if (p.checkStyle == Check_Style_Radio) {
						if (treeNode.checkedNew) {
							if (p.checkRadioType == Radio_Type_All) {
								for (var i = p.checkRadioCheckedList.length - 1; i >= 0; i--) {
									var pNode = p.checkRadioCheckedList[i];
									pNode.checkedNew = false;
									p.checkRadioCheckedList.splice(i, 1);

									t.setChkClass(
											$("#" + pNode.tId + "_check"),
											pNode);
									if (pNode.parentNode != treeNode.parentNode) {
										t.repairParentChkClassWithSelf(pNode);
									}
								}
								p.checkRadioCheckedList = p.checkRadioCheckedList
										.concat([treeNode]);
							} else {
								var parentNode = (treeNode.parentNode)
										? treeNode.parentNode
										: p.root;
								for (var son = 0; son < parentNode[p.nodesCol].length; son++) {
									var pNode = parentNode[p.nodesCol][son];
									if (pNode.checkedNew && pNode != treeNode) {
										pNode.checkedNew = false;
										t.setChkClass($("#" + pNode.tId
														+ "_check"), pNode);
									}
								}
							}
						} else if (p.checkRadioType == Radio_Type_All) {
							for (var i = 0; i < p.checkRadioCheckedList.length; i++) {
								if (treeNode == p.checkRadioCheckedList[i]) {
									p.checkRadioCheckedList.splice(i, 1);
									break;
								}
							}
						}

					} else {
						// 级联子节点
						if (treeNode.checkedNew
								&& p.checkType.Y.indexOf("s") > -1) {
							t.setSonNodeCheckBox(treeNode, true);
							t.repairSonChkClass(treeNode);
							// if (p.async && !p.editable && !treeNode.open
							// && treeNode.isParent && !treeNode.nodes) {
							// // t.asyncGetNode(treeNode,true);
							// // aObj.trigger("click");
							// // $(this).trigger("click");
							// //t.asyncGetNode(treeNode, false, true);
							// } else if (!treeNode.open) {
							// aObj.trigger("click");
							// }
						}
						if (treeNode.checkedNew
								&& p.checkType.Y.indexOf("p") > -1) {
							t.setParentNodeCheckBox(treeNode, true);

						}
						if (!treeNode.checkedNew
								&& p.checkType.N.indexOf("s") > -1) {
							t.setSonNodeCheckBox(treeNode, false);
							t.repairSonChkClass(treeNode);
						}
						if (!treeNode.checkedNew
								&& p.checkType.N.indexOf("p") > -1) {
							t.setParentNodeCheckBox(treeNode, false);
						}
					}
					t.setChkClass(checkObj, treeNode);
					t.repairParentChkClassWithSelf(treeNode);

					// 触发 CheckBox 点击事件
					el.trigger("node_change", [p.treeObjId, treeNode]);

				});

				/**
				 * 20110514 edit by show
				 */
				if (p.appendData != "") {
					if (treeNode.isParent == false) {
						if (p.appendDataArr.indexOf(treeNode[p.checkedObjId]) != -1) {
							checkObj.trigger("click");
						}
					}
				}

				checkObj.bind('mouseover', function() {
							treeNode.checkboxFocus = true;
							t.setChkClass(checkObj, treeNode);
						});

				checkObj.bind('mouseout', function() {
							treeNode.checkboxFocus = false;
							t.setChkClass(checkObj, treeNode);
						});
			}

			aObj.attr("target", (treeNode.target || "_blank"));
			if (treeNode.url && !p.editable)
				aObj.attr("href", treeNode.url);

			// 编辑、删除按钮
			if (p.editable) {
				aObj.hover(function() {
							if (p.dragStatus == 0) {
								t.removeEditBtn(treeNode);
								t.removeRemoveBtn(treeNode);
								t.addEditBtn(treeNode);
								t.addRemoveBtn(treeNode);

							}
						}, function() {
							t.removeEditBtn(treeNode);
							t.removeRemoveBtn(treeNode);
						});
			}

			aObj.bind('mousedown', function(eventMouseDown) {

				// 右键不能拖拽
				if (eventMouseDown.button == 2 || !p.editable)
					return;

				var doc = document;
				var curNode;
				var tmpTarget;
				var mouseDownX = eventMouseDown.clientX;
				var mouseDownY = eventMouseDown.clientY;

				$(doc).mousemove(function(event) {

					// 为便于输入框正常操作，在输入框内移动鼠标不能拖拽节点
					if (treeNode.editNameStatus) {
						return true;
					}

					// 除掉默认事件，防止文本被选择
					window.getSelection ? window.getSelection()
							.removeAllRanges() : document.selection.empty();

					// 避免鼠标误操作，对于第一次移动小于MinMoveSize时，不开启拖拽功能
					if (p.dragStatus == 0
							&& Math.abs(mouseDownX - event.clientX) < MinMoveSize
							&& Math.abs(mouseDownY - event.clientY) < MinMoveSize) {
						return true;
					}

					$("body").css("cursor", "pointer");
					var switchObj = $("#" + treeNode.tId + IDMark_Switch);

					if (p.dragStatus == 0 && treeNode.isParent && treeNode.open) {
						expandAndCollapseNode(treeNode, !treeNode.open);
						p.dragNodeShowBefore = true;
					}

					if (p.dragStatus == 0) {
						// 避免beforeDrag alert时，得到返回值之前仍能拖拽的Bug
						p.dragStatus = -1;
						// var beforeDrag = true;
						// if ((typeof p.callback.beforeDrag) == "function")
						// beforeDrag = p.callback.beforeDrag(p.treeObjId,
						// treeNode);
						// if (beforeDrag == false)
						// return;

						p.dragStatus = 1;
						showIfameMask(true);

						// 设置节点为选中状态
						t.selectNode(treeNode);
						t.removeEditBtn(treeNode);
						t.removeRemoveBtn(treeNode);

						var tmpNode = $("#" + treeNode.tId).clone();
						tmpNode.attr("id", treeNode.tId + "_tmp");
						tmpNode.css("padding", "0");
						tmpNode.children("#" + treeNode.tId + IDMark_A)
								.removeClass(Class_CurSelectedNode);
						tmpNode.children("#" + treeNode.tId + IDMark_Ul).css(
								"display", "none");

						curNode = $("<ul class='yxtreeDragUL'></ul>")
								.append(tmpNode);
						curNode.attr("id", treeNode.tId + IDMark_Ul + "_tmp");
						curNode.addClass(el.attr("class"));
						curNode.appendTo("body");

						// 触发 DRAG 拖拽事件，返回正在拖拽的源数据对象
						el.trigger("node_drag", [p.treeObjId, treeNode]);
					}

					if (p.dragStatus == 1) {
						if (tmpTarget) {
							tmpTarget.removeClass(Class_TmpTargetTree);
							tmpTarget.removeClass(Class_TmpTargetNode);
						}
						tmpTarget = null;

						if (event.target.id == p.treeObjId
								&& treeNode.parentNode != null) {
							// 非根节点 移到 根
							tmpTarget = el;
							tmpTarget.addClass(Class_TmpTargetTree);

						} else if (event.target.id
								&& el.find("#" + event.target.id).length > 0) {
							// 任意节点 移到 其他节点
							var targetObj = $("#" + event.target.id);
							while (!targetObj.is("li")
									&& targetObj.attr("id") != p.treeObjId) {
								targetObj = targetObj.parent();
							};

							// 如果移到自己 或者自己的父级/子集，则不能当做临时目标
							if (treeNode.parentNode
									&& targetObj.attr("id") != treeNode.tId
									&& targetObj.attr("id") != treeNode.parentNode.tId
									&& $("#" + treeNode.tId).find("#"
											+ targetObj.attr("id")).length == 0) {
								// 非根节点移动
								targetObj.children("a")
										.addClass(Class_TmpTargetNode);
								tmpTarget = targetObj.children("a");
							} else if (treeNode.parentNode == null
									&& targetObj.attr("id") != treeNode.tId
									&& $("#" + treeNode.tId).find("#"
											+ targetObj.attr("id")).length == 0) {
								// 根节点移动
								targetObj.children("a")
										.addClass(Class_TmpTargetNode);
								tmpTarget = targetObj.children("a");
							}
						}
						var dX = (doc.body.scrollLeft == 0)
								? doc.documentElement.scrollLeft
								: doc.body.scrollLeft;
						var dY = (doc.body.scrollTop == 0)
								? doc.documentElement.scrollTop
								: doc.body.scrollTop;
						curNode.css({
									"top" : (event.clientY + dY + 3) + "px",
									"left" : (event.clientX + dX + 3) + "px"
								});
					}

					return false;

				});

				$(doc).mouseup(function(event) {
					$(doc).unbind("mousemove");
					$(doc).unbind("mouseup");
					$("body").css("cursor", "auto");
					if (tmpTarget) {
						tmpTarget.removeClass(Class_TmpTargetTree);
						tmpTarget.removeClass(Class_TmpTargetNode);
					}
					t.showIfameMask(false);

					if (p.dragStatus == 0)
						return;
					p.dragStatus = 0;

					if (treeNode.isParent && p.dragNodeShowBefore
							&& !treeNode.open) {
						t.expandAndCollapseNode(treeNode, !treeNode.open);
						p.dragNodeShowBefore = false;
					}

					if (curNode)
						curNode.remove();

					// 显示树上 移动后的节点
					if (tmpTarget) {
						var tmpTargetNodeId = "";
						if (tmpTarget.attr("id") == p.treeObjId) {
							// 转移到根节点
							tmpTargetNodeId = null;
						} else {
							// 转移到子节点
							tmpTarget = tmpTarget.parent();
							while (!tmpTarget.is("li")
									&& tmpTarget.attr("id") != p.treeObjId) {
								tmpTarget = tmpTarget.parent();
							};
							tmpTargetNodeId = tmpTarget.attr('id');
						}
						var dragTargetNode = tmpTargetNodeId == null ? null : t
								.getTreeNodeByTId(p.root[p.nodesCol],
										tmpTargetNodeId);

						// var beforeDrop = true;
						// if ((typeof p.callback.beforeDrop) == "function")
						// beforeDrop = p.callback.beforeDrop(p.treeObjId,
						// treeNode, dragTargetNode);
						// if (beforeDrop == false)
						// return;

						moveTreeNode(dragTargetNode, treeNode);

						// 触发 DROP 拖拽事件，返回拖拽的目标数据对象
						el.trigger(NODE_DROP, [p.treeObjId, treeNode,
										dragTargetNode]);

					} else {
						// 触发 DROP 拖拽事件，返回null
						el.trigger("node_drop", [p.treeObjId, null, null]);
					}
				});

					// return false;
			});

		},

		// 删除 编辑、删除按钮
		removeEditBtn : function(treeNode) {
			$("#" + treeNode.tId + IDMark_Edit).unbind().remove();
		},
		removeRemoveBtn : function(treeNode) {
			$("#" + treeNode.tId + IDMark_Remove).unbind().remove();
		},
		addEditBtn : function(treeNode) {
			var t = this, p = this.options;
			if (!p.edit_renameBtn || treeNode.editNameStatus
					|| $("#" + treeNode.tId + IDMark_Edit).length > 0) {
				return;
			}

			var aObj = $("#" + treeNode.tId + IDMark_A);
			var editStr = "<button type='button' class='edit' id='"
					+ treeNode.tId
					+ IDMark_Edit
					+ "' title='' onfocus='this.blur();' style='display:none;'></button>";
			aObj.append(editStr);

			var editBtnObj = $("#" + treeNode.tId + IDMark_Edit);
			var right = (p.treeObj.attr("offsetLeft")
					+ p.treeObj.attr("offsetWidth")
					+ p.treeObj.attr("scrollLeft") - aObj.attr("offsetLeft")
					- aObj.attr("offsetWidth") - 2 * editBtnObj.width() - 15);
			if (right < 0) {
				// 如果节点处于tree的最右侧，为避免无法正常操作按钮，则在左侧显示
				editBtnObj.remove();
				aObj.prepend(editStr);
				editBtnObj = $("#" + treeNode.tId + IDMark_Edit);
			}
			editBtnObj.bind('click', function() {
						// var beforeRename = true;
						// if ((typeof p.callback.beforeRename) == "function")
						// beforeRename = p.callback.beforeRename(p.treeObjId,
						// treeNode);
						// if (beforeRename == false)
						// return;
						t.removeEditBtn(treeNode);
						t.removeRemoveBtn(treeNode);
						t.editTreeNode(treeNode);
						return false;
					}).bind('mousedown', function(eventMouseDown) {
						return true;
					}).show();
		},
		addRemoveBtn : function(treeNode) {
			var t = this, p = this.options;
			if (!p.edit_removeBtn
					|| $("#" + treeNode.tId + IDMark_Remove).length > 0) {
				return;
			}

			var aObj = $("#" + treeNode.tId + IDMark_A);
			var removeStr = "<button type='button' class='remove' id='"
					+ treeNode.tId
					+ IDMark_Remove
					+ "' title='' onfocus='this.blur();' style='display:none;'></button>";
			aObj.append(removeStr);

			var removeBtnObj = $("#" + treeNode.tId + IDMark_Remove);
			var right = (p.treeObj.attr("offsetLeft")
					+ p.treeObj.attr("offsetWidth") - aObj.attr("offsetLeft")
					- aObj.attr("offsetWidth") - 1 * removeBtnObj.width() - 15);
			if (right < 0) {
				// 如果节点处于tree的最右侧，为避免无法正常操作按钮，则在左侧显示
				removeBtnObj.remove();
				aObj.prepend(removeStr);
				removeBtnObj = $("#" + treeNode.tId + IDMark_Remove);
			}

			$("#" + treeNode.tId + IDMark_Remove).bind('click', function() {
						// var beforeRemove = true;
						// if ((typeof p.callback.beforeRemove) == "function")
						// beforeRemove = p.callback.beforeRemove(p.treeObjId,
						// treeNode);
						// if (beforeRemove == false)
						// return;
						t.removeTreeNode(treeNode);
						// 触发remove事件
						el.trigger("node_remove", [p.treeObjId, treeNode]);
						return false;
					}).bind('mousedown', function(eventMouseDown) {
						return true;
					}).show();
		},

		// 设置CheckBox的Class类型，主要用于显示子节点是否全部被选择的样式
		setChkClass : function(obj, treeNode) {
			var t = this, p = this.options;
			if (!obj)
				return;
			obj.removeClass();
			var chkName = p.checkStyle
					+ "_"
					+ (treeNode.checkedNew ? CheckBox_True : CheckBox_False)
					+ "_"
					+ ((treeNode.checkedNew || p.checkStyle == Check_Style_Radio)
							? (treeNode.check_True_Full
									? CheckBox_Full
									: CheckBox_Part)
							: (treeNode.check_False_Full
									? CheckBox_Full
									: CheckBox_Part));
			chkName = treeNode.checkboxFocus
					? chkName + "_" + CheckBox_Focus
					: chkName;
			obj.addClass(CheckBox_Default);
			obj.addClass(chkName);
		},
		// 修正父节点选择的样式
		repairParentChkClass : function(treeNode) {
			if (!treeNode || !treeNode.parentNode)
				return;
			this.repairChkClass(treeNode.parentNode);
			this.repairParentChkClass(treeNode.parentNode);
		},
		repairParentChkClassWithSelf : function(treeNode) {
			var p = this.options;
			if (treeNode[p.nodesCol] && treeNode[p.nodesCol].length > 0) {
				this.repairParentChkClass(treeNode[p.nodesCol][0]);
			} else {
				this.repairParentChkClass(treeNode);
			}
		},
		// 修正子节点选择的样式
		repairSonChkClass : function(treeNode) {
			var p = this.options;
			if (!treeNode || !treeNode[p.nodesCol])
				return;
			for (var son = 0; son < treeNode[p.nodesCol].length; son++) {
				if (treeNode[p.nodesCol][son][p.nodesCol]) {
					this.repairSonChkClass(treeNode[p.nodesCol][son]);
				}
			}
			this.repairChkClass(treeNode);
		},
		repairChkClass : function(treeNode) {
			var p = this.options;
			if (!treeNode || !treeNode[p.nodesCol])
				return;
			var trueSign = true;
			var falseSign = true;
			for (var son = 0; son < treeNode[p.nodesCol].length; son++) {
				if (p.checkStyle == Check_Style_Radio
						&& (treeNode[p.nodesCol][son].checkedNew || !treeNode[p.nodesCol][son].check_True_Full)) {
					trueSign = false;
				} else if (p.checkStyle != Check_Style_Radio
						&& treeNode.checkedNew
						&& (!treeNode[p.nodesCol][son].checkedNew || !treeNode[p.nodesCol][son].check_True_Full)) {
					trueSign = false;
				} else if (p.checkStyle != Check_Style_Radio
						&& !treeNode.checkedNew
						&& (treeNode[p.nodesCol][son].checkedNew || !treeNode[p.nodesCol][son].check_False_Full)) {
					falseSign = false;
				}
				if (!trueSign || !falseSign)
					break;
			}
			treeNode.check_True_Full = trueSign;
			treeNode.check_False_Full = falseSign;
			var checkObj = $("#" + treeNode.tId + "_check");
			this.setChkClass(checkObj, treeNode);
		},

		// 点击展开、折叠节点
		onSwitchNode : function(event) {
			var t = event.data.tree, p = t.options;
			var treeNode = event.data.treeNode;
			p.expandTriggerFlag = true;

			if (treeNode.open) {
				// var beforeCollapse = true;
				// if ((typeof p.callback.beforeCollapse) == "function")
				// beforeCollapse = p.callback.beforeCollapse(p.treeObjId,
				// treeNode);
				// if (beforeCollapse == false) {
				// p.expandTriggerFlag = false;
				// return;
				// }
				t.switchNode(treeNode);
			} else {
				// var beforeExpand = true;
				// if ((typeof p.callback.beforeExpand) == "function")
				// beforeExpand = p.callback.beforeExpand(p.treeObjId,
				// treeNode);
				// if (beforeExpand == false) {
				// p.expandTriggerFlag = false;
				// return;
				// }
				t.switchNode(treeNode);
			}
		},

		switchNode : function(treeNode) {
			var p = this.options;
			// 这里判断下是否需要从后台获取数据
			if (treeNode && treeNode[p.nodesCol]
					&& treeNode[p.nodesCol].length > 0) {
				this.expandAndCollapseNode(treeNode, !treeNode.open);
			} else if (p.async && !p.editable) {
				this.asyncGetNode(treeNode);
			}
			// .net下刷新页面的bug可能还是button造成的，因此将一下代码暂时屏蔽
			// if(window.event) window.event.returnValue = null;
			// return false;
		},
		/**
		 * 异步加载一个节点下的子节点 isRecursive:是否递归展开 isCheck:是否选中
		 */
		asyncGetNode : function(treeNode, isRecursive, isCheck) {
			var t = this, p = this.options, el = this.el;
			var tmpParam = "";
			for (var i = 0; treeNode && i < p.param.length; i++) {
				tmpParam += (tmpParam.length > 0 ? "&" : "") + p.param[i] + "="
						+ treeNode[p.param[i]];
			}
			// for (var i = 0; i < p.paramOther.length; i += 2) {
			// tmpParam += (tmpParam.length > 0 ? "&" : "") + p.paramOther[i]
			// + "=" + p.paramOther[i + 1];
			// }
			// add by chengl
			for (var v in p.paramOther) {
				tmpParam += (tmpParam.length > 0 ? "&" : "") + v + "="
						+ p.paramOther[v];
			}

			$.ajax({
						type : "POST",
						url : p.url,
						data : tmpParam,
						success : function(msg) {
							if (!msg || msg.length == 0) {
								return;
							}
							var newNodes = "";
							try {
								newNodes = eval("(" + msg + ")");
							} catch (err) {
							}

							if (newNodes && newNodes != "") {

								t.addTreeNodes(treeNode, newNodes, false);
								// update by chengl 2012-1-3 异步多选实现
								if (isCheck) {
									for (var i = 0; i < newNodes.length; i++) {
										var aObj = $("#" + newNodes[i].tId
												+ IDMark_A);
										var checkObj = aObj.data('checkObj');
										checkObj.trigger('click');
									}
								}
								if (isRecursive) {// 加入递归add by chengl
									for (var i = 0; i < newNodes.length; i++) {
										if (newNodes[i].isParent) {
											t.asyncGetNode(newNodes[i],
													isRecursive, isCheck);
										}
									}
								}
							}

							if (!treeNode) {
								treeNode = p.root;
								treeNode.nodes = newNodes;
							}
							el.trigger("node_success", [p.treeObjId, treeNode,
											msg]);

						},
						error : function(XMLHttpRequest, textStatus,
								errorThrown) {
							p.expandTriggerFlag = false;
							el.trigger("node_error", [p.treeObjId, treeNode,
											XMLHttpRequest, textStatus,
											errorThrown]);
						}
					});
		},

		// 展开 或者 折叠 节点下级
		expandAndCollapseNode : function(treeNode, expandSign, animateSign,
				callback) {
			var t = this, p = this.options, el = this.el;
			if (!treeNode || treeNode.open == expandSign) {
				if (typeof callback == "function")
					callback();
				return;
			}

			if (p.expandTriggerFlag) {
				callback = function() {
					if (treeNode.open) {
						// 触发expand事件
						el.trigger("node_expand", [p.treeObjId, treeNode]);
					} else {
						// 触发collapse事件
						el.trigger("node_collapse", [p.treeObjId, treeNode]);
					}
				};
				p.expandTriggerFlag = false;
			}

			var switchObj = $("#" + treeNode.tId + IDMark_Switch);
			var icoObj = $("#" + treeNode.tId + IDMark_Icon);
			icoObj.data('treeNode', treeNode);// add by chengl 2011-06-12
			// 切换图标的时候可以拿到节点
			var ulObj = $("#" + treeNode.tId + IDMark_Ul);

			if (treeNode.isParent && treeNode[p.nodesCol]
					&& treeNode[p.nodesCol].length > 0) {
				if (!treeNode.open) {
					replaceSwitchClass(switchObj, FolderMark_Open);
					replaceIcoClass(icoObj, FolderMark_Open);
					treeNode.open = true;
					if (animateSign == false) {
						ulObj.show();
						if (typeof callback == "function")
							callback();
					} else {
						ulObj.show(p.expandSpeed, callback);
					}
				} else {
					replaceSwitchClass(switchObj, FolderMark_Close);
					replaceIcoClass(icoObj, FolderMark_Close);
					treeNode.open = false;
					if (animateSign == false) {
						ulObj.hide();
						if (typeof callback == "function")
							callback();
					} else {
						ulObj.hide(p.expandSpeed, callback);
					}
				}
			}
		},

		// 遍历子节点展开 或 折叠
		expandCollapseSonNode : function(treeNode, expandSign, animateSign,
				callback) {
			var t = this, p = this.options;
			var treeNodes = (treeNode)
					? treeNode[p.nodesCol]
					: p.root[p.nodesCol];

			// 针对动画进行优化,一般来说只有在第一层的时候，才进行动画效果
			var selfAnimateSign = (treeNode) ? false : animateSign;
			if (treeNodes) {
				for (var son = 0; son < treeNodes.length; son++) {
					if (treeNodes[son])
						t.expandCollapseSonNode(treeNodes[son], expandSign,
								selfAnimateSign);
				}
			} else {
				if (p.url && treeNode.isParent) {
					t.asyncGetNode(treeNode, true);
				}
			}
			// 保证callback只执行一次
			t
					.expandAndCollapseNode(treeNode, expandSign, animateSign,
							callback);

		},

		// 遍历父节点展开 或 折叠
		expandCollapseParentNode : function(treeNode, expandSign, animateSign,
				callback) {
			var t = this;
			// 针对动画进行优化,一般来说只有在第一层的时候，才进行动画效果
			if (!treeNode.parentNode) {
				// 保证callback只执行一次
				t.expandAndCollapseNode(treeNode, expandSign, animateSign,
						callback);
				return;
			} else {
				t.expandAndCollapseNode(treeNode, expandSign, animateSign);
			}

			if (treeNode.parentNode) {
				t.expandCollapseParentNode(treeNode.parentNode, expandSign,
						animateSign, callback);
			}
		},

		// 遍历父节点设置checkbox
		setParentNodeCheckBox : function(treeNode, value) {
			var t = this, p = this.options;
			var checkObj = $("#" + treeNode.tId + "_check");
			treeNode.checkedNew = value;
			treeNode.checked = value;
			t.setChkClass(checkObj, treeNode);
			if (treeNode.parentNode) {
				var pSign = true;
				if (!value) {
					for (var son = 0; son < treeNode.parentNode[p.nodesCol].length; son++) {
						if (treeNode.parentNode[p.nodesCol][son].checkedNew) {
							pSign = false;
							break;
						}
					}
				}
				if (pSign) {
					t.setParentNodeCheckBox(treeNode.parentNode, value);
				}
			}
		},

		// 遍历子节点设置checkbox
		setSonNodeCheckBox : function(treeNode, value) {
			var t = this, p = this.options;
			if (!treeNode)
				return;
			var t = this, p = this.options;
			var checkObj = $("#" + treeNode.tId + "_check");
			var aObj = $("#" + treeNode.tId + IDMark_A);
			treeNode.checked = value;
			treeNode.checkedNew = value;
			t.setChkClass(checkObj, treeNode);
			// add by chengl
			// checkObj.trigger(NODE_CHANGE, [p.treeObjId, treeNode]);
			if (p.async && !p.editable && !treeNode.open && treeNode.isParent
					&& !treeNode.nodes) {
				t.asyncGetNode(treeNode, false, true);
			} else if (!treeNode.open) {
				aObj.trigger("click");
				//add by chengl 20130411
				aObj.trigger("node_change", [p.treeObjId, treeNode]);
			}
			if (!treeNode[p.nodesCol])
				return;
			for (var son = 0; son < treeNode[p.nodesCol].length; son++) {
				if (treeNode[p.nodesCol][son])
					t.setSonNodeCheckBox(treeNode[p.nodesCol][son], value);
			}
		},

		// 遍历子节点设置level,主要用于移动节点后的处理
		setSonNodeLevel : function(parentNode, treeNode) {
			if (!treeNode)
				return;
			var t = this, p = this.options;
			treeNode.level = (parentNode) ? parentNode.level + 1 : 0;
			if (!treeNode[p.nodesCol])
				return;
			for (var son = 0; son < treeNode[p.nodesCol].length; son++) {
				if (treeNode[p.nodesCol][son])
					t.setSonNodeLevel(treeNode, treeNode[p.nodesCol][son]);
			}
		},

		// 增加子节点
		addTreeNodes : function(parentNode, newNodes, isSilent) {
			var t = this, p = this.options, el = this.el;
			if (parentNode) {
				// 目标节点必须在当前树内
				if (el.find("#" + parentNode.tId).length == 0)
					return;

				target_switchObj = $("#" + parentNode.tId + IDMark_Switch);
				target_icoObj = $("#" + parentNode.tId + IDMark_Icon);
				target_aObj = $("#" + parentNode.tId + IDMark_A);
				target_ulObj = $("#" + parentNode.tId + IDMark_Ul);

				// 处理节点在目标节点的图片、线
				if (!parentNode.open) {
					replaceSwitchClass(target_switchObj, FolderMark_Close);
					replaceIcoClass(target_icoObj, FolderMark_Close);
					parentNode.open = false;
					target_ulObj.css({
								"display" : "none"
							});
				}

				// 如果目标节点不是父节点，增加树节点展开、关闭事件
				if (!parentNode.isParent) {
					target_switchObj.unbind('click');
					target_switchObj.bind('click', function() {
								t.expandAndCollapseNode(parentNode,
										!parentNode.open);
							});
					target_aObj.unbind('dblclick');
					target_aObj.bind('dblclick', {
								treeObjId : p.treeObjId,
								treeNode : parentNode
							}, t.onSwitchNode);
				}

				t.addTreeNodesData(parentNode, newNodes);
				t.initTreeNodes(parentNode.level + 1, newNodes, parentNode);
				// 如果选择某节点，则必须展开其全部父节点
				if (!isSilent) {
					t.expandCollapseParentNode(parentNode, true);
				}
			} else {
				t.addTreeNodesData(p.root, newNodes);
				t.initTreeNodes(0, newNodes, null);
			}
		},

		// 增加节点数据
		addTreeNodesData : function(parentNode, treenodes) {
			var t = this, p = this.options;
			if (!parentNode[p.nodesCol])
				parentNode[p.nodesCol] = [];
			if (parentNode[p.nodesCol].length > 0) {
				var tmpId = parentNode[p.nodesCol][parentNode[p.nodesCol].length
						- 1].tId;
				parentNode[p.nodesCol][parentNode[p.nodesCol].length - 1].isLastNode = false;
				if (parentNode[p.nodesCol][parentNode[p.nodesCol].length - 1].isFirstNode) {
					replaceSwitchClass($("#" + tmpId + IDMark_Switch),
							LineMark_Roots);
				} else {
					replaceSwitchClass($("#" + tmpId + IDMark_Switch),
							LineMark_Center);
				}
				$("#" + tmpId + IDMark_Ul).addClass(LineMark_Line);
			}
			parentNode.isParent = true;
			parentNode[p.nodesCol] = parentNode[p.nodesCol].concat(treenodes);
		},

		// 移动子节点
		moveTreeNode : function(targetNode, treeNode, animateSign) {
			if (targetNode == treeNode)
				return;
			var t = this, p = this.options, el = this.el;
			var oldParentNode = treeNode.parentNode == null
					? p.root
					: treeNode.parentNode;

			var targetNodeIsRoot = (targetNode === null || targetNode == p.root);
			if (targetNodeIsRoot && targetNode === null)
				targetNode = p.root;

			var src_switchObj = $("#" + treeNode.tId + IDMark_Switch);
			var src_ulObj = $("#" + treeNode.tId + IDMark_Ul);

			var targetObj;
			var target_switchObj;
			var target_icoObj;
			var target_aObj;
			var target_ulObj;

			if (targetNodeIsRoot) {
				// 转移到根节点
				targetObj = el;
				target_ulObj = targetObj;

			} else {
				// 转移到子节点
				target_switchObj = $("#" + targetNode.tId + IDMark_Switch);
				target_icoObj = $("#" + targetNode.tId + IDMark_Icon);
				target_aObj = $("#" + targetNode.tId + IDMark_A);
				target_ulObj = $("#" + targetNode.tId + IDMark_Ul);
			}

			// 处理节点在目标处的图片、线
			replaceSwitchClass(target_switchObj, FolderMark_Open);
			replaceIcoClass(target_icoObj, FolderMark_Open);
			targetNode.open = true;
			target_ulObj.css({
						"display" : "block"
					});

			// 如果目标节点不是父节点，且不是根，增加树节点展开、关闭事件
			if (!targetNode.isParent && !targetNodeIsRoot) {
				target_switchObj.unbind('click');
				target_switchObj.bind('click', function() {
							t.expandAndCollapseNode(targetNode,
									!targetNode.open);
						});
				target_aObj.unbind('dblclick');
				target_aObj.bind('dblclick', {
							treeObjId : p.treeObjId,
							treeNode : targetNode
						}, onSwitchNode);
			}

			target_ulObj.append($("#" + treeNode.tId).detach());

			// 进行数据结构修正
			var tmpSrcIndex = -1;
			for (var i = 0; i < oldParentNode[p.nodesCol].length; i++) {
				if (oldParentNode[p.nodesCol][i].tId == treeNode.tId)
					tmpSrcIndex = i;
			}
			if (tmpSrcIndex >= 0) {
				oldParentNode[p.nodesCol].splice(tmpSrcIndex, 1);
			}

			if (!targetNode[p.nodesCol]) {
				targetNode[p.nodesCol] = new Array();
			} else if (p.showLine && targetNode[p.nodesCol].length > 0) {
				// 处理目标节点中当前最后一个节点的图标、线
				targetNode[p.nodesCol][targetNode[p.nodesCol].length - 1].isLastNode = false;
				var tmp_ulObj = $("#"
						+ targetNode[p.nodesCol][targetNode[p.nodesCol].length
								- 1].tId + IDMark_Ul);
				var tmp_switchObj = $("#"
						+ targetNode[p.nodesCol][targetNode[p.nodesCol].length
								- 1].tId + IDMark_Switch);
				tmp_ulObj.addClass(LineMark_Line);
				if (targetNodeIsRoot
						&& targetNode[p.nodesCol][targetNode[p.nodesCol].length
								- 1].isFirstNode) {
					// 节点 移到 根，并且原来只有一个根节点
					replaceSwitchClass(tmp_switchObj, LineMark_Roots);

				} else {
					replaceSwitchClass(tmp_switchObj, LineMark_Center);
				}
			}

			// 数据节点转移
			if (targetNodeIsRoot) {
				// 成为根节点，则不操作目标节点数据
				treeNode.parentNode = null;
			} else {
				// 成为子节点
				targetNode.isParent = true;
				treeNode.parentNode = targetNode;
			}
			t.setSonNodeLevel(treeNode.parentNode, treeNode);
			targetNode[p.nodesCol].splice(targetNode[p.nodesCol].length, 0,
					treeNode);

			treeNode.isLastNode = true;
			treeNode.isFirstNode = (targetNode[p.nodesCol].length == 1);
			// 设置被移动节点为最后一个节点
			if (p.showLine) {
				replaceSwitchClass(src_switchObj, LineMark_Bottom);
				src_ulObj.removeClass(LineMark_Line);
			}

			// 处理原节点的父节点的图标、线
			if (oldParentNode[p.nodesCol].length < 1) {
				// 原所在父节点无子节点
				oldParentNode.isParent = false;
				var tmp_ulObj = $("#" + oldParentNode.tId + IDMark_Ul);
				var tmp_switchObj = $("#" + oldParentNode.tId + IDMark_Switch);
				var tmp_icoObj = $("#" + oldParentNode.tId + IDMark_Icon);
				replaceSwitchClass(tmp_switchObj, FolderMark_Docu);
				replaceIcoClass(tmp_icoObj, FolderMark_Docu);
				tmp_ulObj.css("display", "none");

			} else if (p.showLine) {
				// 原所在父节点有子节点
				oldParentNode[p.nodesCol][oldParentNode[p.nodesCol].length - 1].isLastNode = true;
				oldParentNode[p.nodesCol][oldParentNode[p.nodesCol].length - 1].isFirstNode = (oldParentNode[p.nodesCol].length == 1);
				var tmp_ulObj = $("#"
						+ oldParentNode[p.nodesCol][oldParentNode[p.nodesCol].length
								- 1].tId + IDMark_Ul);
				var tmp_switchObj = $("#"
						+ oldParentNode[p.nodesCol][oldParentNode[p.nodesCol].length
								- 1].tId + IDMark_Switch);
				var tmp_icoObj = $("#"
						+ oldParentNode[p.nodesCol][oldParentNode[p.nodesCol].length
								- 1].tId + IDMark_Icon);
				if (oldParentNode == p.root) {
					if (oldParentNode[p.nodesCol].length == 1) {
						// 原为根节点 ，且移动后只有一个根节点
						replaceSwitchClass(tmp_switchObj, LineMark_Root);
					} else {
						var tmp_first_switchObj = $("#"
								+ oldParentNode[p.nodesCol][0].tId
								+ IDMark_Switch);
						replaceSwitchClass(tmp_first_switchObj, LineMark_Roots);
						replaceSwitchClass(tmp_switchObj, LineMark_Bottom);
					}

				} else {
					replaceSwitchClass(tmp_switchObj, LineMark_Bottom);
				}

				tmp_ulObj.removeClass(LineMark_Line);
			}

			// 移动后，则必须展开新位置的全部父节点
			t.expandCollapseParentNode(targetNode, true, animateSign);
		},

		// 编辑子节点名称
		editTreeNode : function(treeNode) {
			treeNode.editNameStatus = true;
			this.selectNode(treeNode);
		},

		// 删除子节点
		removeTreeNode : function(treeNode) {
			var t = this, p = this.options;
			var parentNode = treeNode.parentNode == null
					? p.root
					: treeNode.parentNode;
			if (p.curTreeNode === treeNode)
				p.curTreeNode = null;
			if (p.curEditTreeNode === treeNode)
				p.curEditTreeNode = null;

			$("#" + treeNode.tId).remove();

			// 进行数据结构修正
			var tmpSrcIndex = -1;
			for (var i = 0; i < parentNode[p.nodesCol].length; i++) {
				if (parentNode[p.nodesCol][i].tId == treeNode.tId)
					tmpSrcIndex = i;
			}
			if (tmpSrcIndex >= 0) {
				parentNode[p.nodesCol].splice(tmpSrcIndex, 1);
			}

			// 处理原节点的父节点的图标、线
			if (parentNode[p.nodesCol].length < 1) {
				// 原所在父节点无子节点
				parentNode.isParent = false;
				var tmp_ulObj = $("#" + parentNode.tId + IDMark_Ul);
				var tmp_switchObj = $("#" + parentNode.tId + IDMark_Switch);
				var tmp_icoObj = $("#" + parentNode.tId + IDMark_Icon);
				replaceSwitchClass(tmp_switchObj, FolderMark_Docu);
				replaceIcoClass(tmp_icoObj, FolderMark_Docu);
				tmp_ulObj.css("display", "none");

			} else if (p.showLine) {
				// 原所在父节点有子节点
				parentNode[p.nodesCol][parentNode[p.nodesCol].length - 1].isLastNode = true;
				parentNode[p.nodesCol][parentNode[p.nodesCol].length - 1].isFirstNode = (parentNode[p.nodesCol].length == 1);
				var tmp_ulObj = $("#"
						+ parentNode[p.nodesCol][parentNode[p.nodesCol].length
								- 1].tId + IDMark_Ul);
				var tmp_switchObj = $("#"
						+ parentNode[p.nodesCol][parentNode[p.nodesCol].length
								- 1].tId + IDMark_Switch);
				var tmp_icoObj = $("#"
						+ parentNode[p.nodesCol][parentNode[p.nodesCol].length
								- 1].tId + IDMark_Icon);
				if (parentNode == p.root) {
					if (parentNode[p.nodesCol].length == 1) {
						// 原为根节点 ，且移动后只有一个根节点
						replaceSwitchClass(tmp_switchObj, LineMark_Root);
					} else {
						var tmp_first_switchObj = $("#"
								+ parentNode[p.nodesCol][0].tId + IDMark_Switch);
						replaceSwitchClass(tmp_first_switchObj, LineMark_Roots);
						replaceSwitchClass(tmp_switchObj, LineMark_Bottom);
					}

				} else {
					replaceSwitchClass(tmp_switchObj, LineMark_Bottom);
				}

				tmp_ulObj.removeClass(LineMark_Line);
			}
		},

		// 根据 tId 获取 节点的数据对象
		getTreeNodeByTId : function(treeNodes, treeId) {
			var p = this.options;
			if (!treeNodes || !treeId)
				return null;
			for (var i = 0; i < treeNodes.length; i++) {
				if (treeNodes[i].tId == treeId) {
					return treeNodes[i];
				}
				var tmp = this.getTreeNodeByTId(treeNodes[i][p.nodesCol],
						treeId);
				if (tmp)
					return tmp;
			}
			return null;
		},
		// 根据 id 获取 节点的数据对象 add by chengl 2011-06-01
		getTreeNodeById : function(treeNodes, nodeId) {
			var p = this.options;
			if (!treeNodes || !nodeId)
				return null;
			for (var i = 0; i < treeNodes.length; i++) {
				if (treeNodes[i].id == nodeId) {
					return treeNodes[i];
				}
				var tmp = this.getTreeNodeById(treeNodes[i]['nodes'], nodeId);
				if (tmp)
					return tmp;
			}
			return null;
		},

		// 取消之前选中节点状态
		canclePreSelectedNode : function() {
			var p = this.options, t = this;
			if (p.curTreeNode) {
				t.removeEditBtn(p.curTreeNode);
				t.removeRemoveBtn(p.curTreeNode);
				$("#" + p.curTreeNode.tId + IDMark_A)
						.removeClass(Class_CurSelectedNode);
				$("#" + p.curTreeNode.tId + IDMark_Span)
						.text(p.curTreeNode[p.nameCol]);
				p.curTreeNode = null;
			}
		},
		// 取消之前编辑节点状态
		canclePreEditNode : function() {
			var p = this.options;
			if (p.curEditTreeNode) {
				$("#" + p.curEditTreeNode.tId + IDMark_A)
						.removeClass(Class_CurSelectedNode_Edit);
				$("#" + p.curEditTreeNode.tId + IDMark_Input).unbind();
				$("#" + p.curEditTreeNode.tId + IDMark_Span)
						.text(p.curEditTreeNode[p.nameCol]);
				p.curEditTreeNode.editNameStatus = false;
				p.curEditTreeNode = null;
			}
		},

		// 设置节点为当前选中节点
		selectNode : function(treeNode) {
			var t = this, p = this.options, el = this.el;
			if (p.curTreeNode == treeNode && !p.editable)
				return;

			this.canclePreSelectedNode();
			this.canclePreEditNode();

			if (p.editable) {
				t.addEditBtn(treeNode);
				t.addRemoveBtn(treeNode);
			}

			if (treeNode.editNameStatus) {
				$("#" + treeNode.tId + IDMark_Span)
						.html("<input type=text class='rename' id='"
								+ treeNode.tId + IDMark_Input + "'>");

				var inputObj = $("#" + treeNode.tId + IDMark_Input);
				inputObj.attr("value", treeNode[p.nameCol]);
				inputObj.focus();
				t
						.setCursorPosition(inputObj.get(0),
								treeNode[p.nameCol].length);

				// 拦截A的click dblclick监听
				inputObj.bind('blur', function(event) {
							treeNode[p.nameCol] = this.value;
							// 触发rename事件
							el.trigger("node_rename", [p.treeObjId, treeNode]);
						}).bind('click', function(event) {
							return false;
						}).bind('dblclick', function(event) {
							return false;
						});

				$("#" + treeNode.tId + IDMark_A)
						.addClass(Class_CurSelectedNode_Edit);
				p.curEditTreeNode = treeNode;
			} else {
				$("#" + treeNode.tId + IDMark_A)
						.addClass(Class_CurSelectedNode);
			}
			p.curTreeNode = treeNode;
		},

		// 获取全部 checked = true or false 的节点集合
		getTreeCheckedNodes : function(treeNodes, checked) {
			if (!treeNodes)
				return [];
			var p = this.options;
			var results = [];
			for (var i = 0; i < treeNodes.length; i++) {
				if (treeNodes[i].checkedNew == checked) {
					results = results.concat([treeNodes[i]]);
				}
				var tmp = this.getTreeCheckedNodes(treeNodes[i][p.nodesCol],
						checked);
				if (tmp.length > 0)
					results = results.concat(tmp);
			}
			return results;
		}

	});

	// 获取对象的绝对坐标
	function getAbsPoint(obj) {
		var r = new Array(2);
		oRect = obj.getBoundingClientRect();
		r[0] = oRect.left;
		r[1] = oRect.top;
		return r;
	}

	// 设置光标位置函数
	function setCursorPosition(obj, pos) {
		if (obj.setSelectionRange) {
			obj.focus();
			obj.setSelectionRange(pos, pos);
		} else if (obj.createTextRange) {
			var range = obj.createTextRange();
			range.collapse(true);
			range.moveEnd('character', pos);
			range.moveStart('character', pos);
			range.select();
		}
	}

	var dragMaskList = new Array();
	// 显示、隐藏 Iframe的遮罩层（主要用于避免拖拽不流畅）
	function showIfameMask(showSign) {
		// 清空所有遮罩
		while (dragMaskList.length > 0) {
			dragMaskList[0].remove();
			dragMaskList.shift();
		}
		if (showSign) {
			// 显示遮罩
			var iframeList = $("iframe");
			for (var i = 0; i < iframeList.length; i++) {
				var obj = iframeList.get(i);
				var r = getAbsPoint(obj);
				var dragMask = $("<div id='yxtreeMask_" + i
						+ "' class='yxtreeMask' style='top:" + r[1]
						+ "px; left:" + r[0] + "px; width:" + obj.offsetWidth
						+ "px; height:" + obj.offsetHeight + "px;'></div>");
				dragMask.appendTo("body");
				dragMaskList.push(dragMask);
			}
		}
	}

	// 对于button替换class 拼接字符串
	function replaceSwitchClass(obj, newName) {
		if (!obj)
			return;

		var tmpName = obj.attr("class");
		if (!tmpName)
			return;
		var tmpList = tmpName.split("_");
		switch (newName) {
			case LineMark_Root :
			case LineMark_Roots :
			case LineMark_Center :
			case LineMark_Bottom :
			case LineMark_NoLine :
				tmpList[1] = newName;
				break;
			case FolderMark_Open :
			case FolderMark_Close :
			case FolderMark_Docu :
				tmpList[2] = newName;
				break;
		}

		obj.attr("class", tmpList.join("_"));
	}
	function replaceIcoClass(obj, newName) {
		var treeNode = obj.data('treeNode');
		if (!obj)
			return;

		var tmpName = obj.attr("class");
		if (tmpName == undefined)
			return;
		var tmpList = tmpName.split("_");
		switch (newName) {
			case FolderMark_Open :
			case FolderMark_Close :
			case FolderMark_Docu :
				tmpList[1] = newName;
				break;
		}
		if (!treeNode || !treeNode.icon) {
			obj.attr("class", tmpList.join("_"));
		}
	}

	function yxtreePlugin() {
		return {};
	};
})(jQuery);