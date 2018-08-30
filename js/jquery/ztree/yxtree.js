/**
 * ���������
 */

(function($) {
	// ���²���Ϊyxtree����Ĺؼ���
	// var NODE_CLICK = "node_click";// �����ڵ�
	// var NODE_DBLCLICK = "node_dblclick";// ˫���ڵ� add by chengl
	// var NODE_RCLICK = "node_rclick";// �Ҽ��ڵ� add by chengl
	// var NODE_CHANGE = "node_change";// ��ѡ�ڵ�
	// var NODE_RENAME = "node_rename";// ���Ľڵ�����
	// var NODE_REMOVE = "node_remove";// �Ƴ��ڵ�
	// var NODE_DRAG = "node_drag";// �϶��ڵ�
	// var NODE_DROP = "node_drop";// ���ýڵ�
	// var NODE_EXPAND = "node_expand";// �ڵ�չ��
	// var NODE_COLLAPSE = "node_collapse"; // �ڵ�����
	// var NODE_ASYNC_SUCCESS = "node_success";// �ڵ���سɹ�
	// var NODE_ASYNC_ERROR = "node_error";// �ڵ����ʧ��

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

	var ps = new Array();// ���ֶ������ʱ�����ڴ洢ÿ��������������
	var yxtreeId = 0;

	// yxtree���캯��
	$.woo.component.subclass('woo.yxtree', {
		options : {
			// �߶ȣ�auto�Զ�ȡdocument�߶�
			height : 'auto',
			// Tree Ψһ��ʶ����UL��ID
			treeObjId : "",
			// �Ƿ���ʾCheckBox
			checkable : false,
			// �Ƿ��ڱ༭״̬
			editable : false,
			// �༭״̬�Ƿ���ʾ�޸İ�ť
			edit_renameBtn : true,
			// �༭״̬�Ƿ���ʾɾ���ڵ㰴ť
			edit_removeBtn : true,
			// �Ƿ���ʾ������
			showLine : true,
			// ��ǰ��ѡ���TreeNode
			curTreeNode : null,
			// ��ǰ�����༭��TreeNode
			curEditTreeNode : null,
			// �Ƿ�����ק�ڼ� 0: not Drag; 1: doing Drag
			dragStatus : 0,
			dragNodeShowBefore : false,
			// ѡ��CheckBox �� Radio
			checkStyle : Check_Style_Box,
			// checkBox�����Ӱ�츸�ӽڵ����ã�checkStyle=Check_Style_Radioʱ��Ч��
			/**
			 * Y ���Զ��� checkbox ����ѡ�������� N ���Զ��� checkbox ȡ����ѡ�������� "p"
			 * ��ʾ������Ӱ�츸���ڵ㣻 "s" ��ʾ������Ӱ���Ӽ��ڵ�
			 */
			checkType : {
				"Y" : "ps",
				"N" : "ps"
			},
			// radio �������������ͣ�ÿһ���ڵ����� ��
			// ����Tree��ȫ���ڵ����ƣ�checkStyle=Check_Style_Boxʱ��Ч��
			checkRadioType : Radio_Type_Level,
			// checkRadioType = Radio_Type_All ʱ�����汻ѡ��ڵ�Ķ�ջ
			checkRadioCheckedList : [],
			// �Ƿ��첽��ȡ�ڵ�����
			async : false,
			// ��ȡ�ڵ����ݵ�URL��ַ
			url : "",
			// ��ȡ�ڵ�����ʱ��������������ƣ����磺id��name
			param : ['id', 'name'],
			// ������������ {id:1}
			paramOther : {},
			// �û��Զ���������
			nameCol : "name",
			// �û��Զ����ӽڵ���
			nodesCol : "nodes",
			// �۵���չ����Ч�ٶ�
			expandSpeed : "fast",
			// �۵���չ��Trigger����
			expandTriggerFlag : false,
			root : {
				isRoot : true,
				nodes : []
			},
			// ��̬���ݼ�
			data : [],
			// ��ѡ�ж�����
			checkedObjId : "",
			// ��ѡ�е�checkboxֵ
			appendData : "",
			// �Ҽ��˵�
			menus : [],
			// ��չ�İ�ť
			buttonsEx : [],
			// event Function (ע�⣺ͨ��callback������¼��޷��ۼӣ���Ҫ�ۼӵ��¼���ͨ��el.bind����ע��)
			/**
			 * ע�����¼�(Ϊ�˷���鿴��Ӧ�¼���ʵ�ʲ�������)
			 */
			registerEven : [
					// �����ڵ�
					'node_click',
					// ˫���ڵ�
					'node_dblclick',
					// �Ҽ��ڵ�
					'node_rclick',
					// ��ѡ�ڵ�
					'node_change',
					// ���Ľڵ�����
					'node_rename',
					// �Ƴ��ڵ�
					'node_remove',
					// �϶��ڵ�
					'node_drag',
					// ���ýڵ�
					'node_drop',
					// �ڵ�չ��
					'node_expand',
					// �ڵ�����
					'node_collapse',
					// �ڵ���سɹ�
					'node_success',
					// �ڵ����ʧ��
					'node_error']
			/*******************************************************************
			 * ��ǰ�����¼�***************** callback : { beforeClick : null,
			 * beforeChange : null, beforeDrag : null, beforeDrop : null,
			 * beforeRename : null, beforeRemove : null, beforeExpand : null,
			 * beforeCollapse : null, click : null, change : null, drag : null,
			 * drop : null, rename : null, remove : null, expand : null,
			 * collapse : null, asyncSuccess : null, asyncError : null }
			 ******************************************************************/
		},
		_create : function() {
			var t = this, el = this.el, p = this.options;
			// ����Ҫ��������޸�domԪ��ֻ����ul������
			// if ($(el).context.nodeName != "UL") {
			// var ul = $("ul").attr("id",el.attr('id'));
			// el=this.el = ul;
			// }
			if (p.height == 'auto') {
				el.parent().height($(document).height());
			}
			el.addClass("tree");
			if (p.url)// ���������url����Ĭ��Ϊ�첽��ȡ�ڵ�����
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

			// edit by show ��������,����Ҫ����
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
		 * ��ӹ�������ť
		 */
		addToolbarButtons : function() {
			var el = this.el, t = this, p = t.options;
			var btnDiv = $("<div>");
			var refleshButton = $("<button title='ˢ��'  type='button' class='ico refresh'></button>");
			refleshButton.click(function() {
						t.reload();
					});
			el.append(refleshButton);

			var explandButton = $("<button title='չ����'  type='button' class='ico expandNode'></button>");
			explandButton.click(function() {
						t.expandAll();
					});
			el.append(explandButton);
			// ��ʱֻ׷�ӵ�����
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
		 * ˢ��ĳ���ڵ������ݣ����û����ڵ��������ˢ��������
		 */
		reload : function(treeNode) {
			if (!treeNode) {
				var el = this.el, t = this, p = t.options;
				if (!p.data || p.data == '') {
					$(el).children().remove("li");
					t.asyncGetNode();
				}
			} else {
				// ˢ��ĳ���ڵ�������
			}
		},
		/**
		 * ˢ������ add by chengl 2011-05-31
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
		// ���� id ��ȡ �ڵ�����ݶ���
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

			// ���ܻ�ȡroot��Ϣ
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

			// ���ṩroot��Ϣupdate
			$.extend(true, p.checkType, tmp_checkType);
			$.extend(p.callback, tmp_callback);
			p.treeObjId = treeObjId;

		},

		/**
		 * չ�����нڵ㣬��ʱ�����첽����
		 */
		expandAll : function(expandSign) {
			// $(this.el).children().remove("li");
			this.expandCollapseSonNode(null, expandSign, true);
		},

		expandNode : function(treeNode, expandSign, sonSign) {
			if (expandSign) {
				// ���չ��ĳ�ڵ㣬�����չ����ȫ�����ڵ�
				this.expandCollapseParentNode(treeNode, expandSign, false);
			}

			if (sonSign) {
				// ���ͼ��ͬʱ���ж��������²������ӳٺ����ô���׼ȷ���񶯻����ս���ʱ��
				// ���Ϊ�˱�֤׼ȷ���ڵ�focus���ж�λ�������js�����ڵ�ʱ�������ж���
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
			// ���ѡ��ĳ�ڵ㣬�����չ����ȫ�����ڵ�
			// ���ͼ��ͬʱ���ж��������²������ӳٺ����ô���׼ȷ���񶯻����ս���ʱ��
			// ���Ϊ�˱�֤׼ȷ���ڵ�focus���ж�λ�������js�����ڵ�ʱ�������ж���
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
		/** **************���¿�ʼΪ˽�з���**************** */
		/**
		 * �����¼�
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
		 * ��ʼ������ʾ�ڵ�Json����
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

				// �����ڷǿսڵ������ӽڵ�
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

					// ֻ��ĩ���ڵ�����һ������checkBox����
					if (p.checkable && i == treeNodes.length - 1) {
						t.repairParentChkClass(node);
					}
				}
			}
		},
		/**
		 * ��ʾ�����ڵ�
		 */
		showTree : function(treeNode) {
			var t = this, p = this.options, el = this.el;
			// ��ȡ���ڵ�
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

			var switchObj = $("#" + treeNode.tId + IDMark_Switch);// �ڵ�ǰ���+-
			var aObj = $("#" + treeNode.tId + IDMark_A).data("treeNode",
					treeNode);// ����ڵ�洢����domԪ�ذ� add by chengl
			var nObj = $("#" + treeNode.tId + IDMark_Span);
			var ulObj = $("#" + treeNode.tId + IDMark_Ul);

			nObj.text(treeNode[p.nameCol]);
			var icoObj = $("#" + treeNode.tId + IDMark_Icon);

			// ����Line��Ico��css����
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
				// update by chengl 2011-06-12 ���ýڵ�ͼ��
				icoObj.attr("class", "ico_" + treeNode.icon);
				// icoObj.attr("style", "background:url(" + treeNode.icon
				// + ") 0 0 no-repeat;");
			}

			// �������ڵ�չ�����ر��¼�
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
						// ����Ĭ���¼�����ֹ�ı���ѡ��
						window.getSelection ? window.getSelection()
								.removeAllRanges() : document.selection.empty();
						// ���ýڵ�Ϊѡ��״̬
						t.selectNode(treeNode);
						// ����click�¼�
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

			// ��ʾCheckBox Or Radio
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
				aObj.data('checkObj', checkObj);// �ѽڵ�jquery������ѡ����
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
						// �����ӽڵ�
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

					// ���� CheckBox ����¼�
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

			// �༭��ɾ����ť
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

				// �Ҽ�������ק
				if (eventMouseDown.button == 2 || !p.editable)
					return;

				var doc = document;
				var curNode;
				var tmpTarget;
				var mouseDownX = eventMouseDown.clientX;
				var mouseDownY = eventMouseDown.clientY;

				$(doc).mousemove(function(event) {

					// Ϊ�����������������������������ƶ���겻����ק�ڵ�
					if (treeNode.editNameStatus) {
						return true;
					}

					// ����Ĭ���¼�����ֹ�ı���ѡ��
					window.getSelection ? window.getSelection()
							.removeAllRanges() : document.selection.empty();

					// �����������������ڵ�һ���ƶ�С��MinMoveSizeʱ����������ק����
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
						// ����beforeDrag alertʱ���õ�����ֵ֮ǰ������ק��Bug
						p.dragStatus = -1;
						// var beforeDrag = true;
						// if ((typeof p.callback.beforeDrag) == "function")
						// beforeDrag = p.callback.beforeDrag(p.treeObjId,
						// treeNode);
						// if (beforeDrag == false)
						// return;

						p.dragStatus = 1;
						showIfameMask(true);

						// ���ýڵ�Ϊѡ��״̬
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

						// ���� DRAG ��ק�¼�������������ק��Դ���ݶ���
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
							// �Ǹ��ڵ� �Ƶ� ��
							tmpTarget = el;
							tmpTarget.addClass(Class_TmpTargetTree);

						} else if (event.target.id
								&& el.find("#" + event.target.id).length > 0) {
							// ����ڵ� �Ƶ� �����ڵ�
							var targetObj = $("#" + event.target.id);
							while (!targetObj.is("li")
									&& targetObj.attr("id") != p.treeObjId) {
								targetObj = targetObj.parent();
							};

							// ����Ƶ��Լ� �����Լ��ĸ���/�Ӽ������ܵ�����ʱĿ��
							if (treeNode.parentNode
									&& targetObj.attr("id") != treeNode.tId
									&& targetObj.attr("id") != treeNode.parentNode.tId
									&& $("#" + treeNode.tId).find("#"
											+ targetObj.attr("id")).length == 0) {
								// �Ǹ��ڵ��ƶ�
								targetObj.children("a")
										.addClass(Class_TmpTargetNode);
								tmpTarget = targetObj.children("a");
							} else if (treeNode.parentNode == null
									&& targetObj.attr("id") != treeNode.tId
									&& $("#" + treeNode.tId).find("#"
											+ targetObj.attr("id")).length == 0) {
								// ���ڵ��ƶ�
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

					// ��ʾ���� �ƶ���Ľڵ�
					if (tmpTarget) {
						var tmpTargetNodeId = "";
						if (tmpTarget.attr("id") == p.treeObjId) {
							// ת�Ƶ����ڵ�
							tmpTargetNodeId = null;
						} else {
							// ת�Ƶ��ӽڵ�
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

						// ���� DROP ��ק�¼���������ק��Ŀ�����ݶ���
						el.trigger(NODE_DROP, [p.treeObjId, treeNode,
										dragTargetNode]);

					} else {
						// ���� DROP ��ק�¼�������null
						el.trigger("node_drop", [p.treeObjId, null, null]);
					}
				});

					// return false;
			});

		},

		// ɾ�� �༭��ɾ����ť
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
				// ����ڵ㴦��tree�����Ҳ࣬Ϊ�����޷�����������ť�����������ʾ
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
				// ����ڵ㴦��tree�����Ҳ࣬Ϊ�����޷�����������ť�����������ʾ
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
						// ����remove�¼�
						el.trigger("node_remove", [p.treeObjId, treeNode]);
						return false;
					}).bind('mousedown', function(eventMouseDown) {
						return true;
					}).show();
		},

		// ����CheckBox��Class���ͣ���Ҫ������ʾ�ӽڵ��Ƿ�ȫ����ѡ�����ʽ
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
		// �������ڵ�ѡ�����ʽ
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
		// �����ӽڵ�ѡ�����ʽ
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

		// ���չ�����۵��ڵ�
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
			// �����ж����Ƿ���Ҫ�Ӻ�̨��ȡ����
			if (treeNode && treeNode[p.nodesCol]
					&& treeNode[p.nodesCol].length > 0) {
				this.expandAndCollapseNode(treeNode, !treeNode.open);
			} else if (p.async && !p.editable) {
				this.asyncGetNode(treeNode);
			}
			// .net��ˢ��ҳ���bug���ܻ���button��ɵģ���˽�һ�´�����ʱ����
			// if(window.event) window.event.returnValue = null;
			// return false;
		},
		/**
		 * �첽����һ���ڵ��µ��ӽڵ� isRecursive:�Ƿ�ݹ�չ�� isCheck:�Ƿ�ѡ��
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
								// update by chengl 2012-1-3 �첽��ѡʵ��
								if (isCheck) {
									for (var i = 0; i < newNodes.length; i++) {
										var aObj = $("#" + newNodes[i].tId
												+ IDMark_A);
										var checkObj = aObj.data('checkObj');
										checkObj.trigger('click');
									}
								}
								if (isRecursive) {// ����ݹ�add by chengl
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

		// չ�� ���� �۵� �ڵ��¼�
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
						// ����expand�¼�
						el.trigger("node_expand", [p.treeObjId, treeNode]);
					} else {
						// ����collapse�¼�
						el.trigger("node_collapse", [p.treeObjId, treeNode]);
					}
				};
				p.expandTriggerFlag = false;
			}

			var switchObj = $("#" + treeNode.tId + IDMark_Switch);
			var icoObj = $("#" + treeNode.tId + IDMark_Icon);
			icoObj.data('treeNode', treeNode);// add by chengl 2011-06-12
			// �л�ͼ���ʱ������õ��ڵ�
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

		// �����ӽڵ�չ�� �� �۵�
		expandCollapseSonNode : function(treeNode, expandSign, animateSign,
				callback) {
			var t = this, p = this.options;
			var treeNodes = (treeNode)
					? treeNode[p.nodesCol]
					: p.root[p.nodesCol];

			// ��Զ��������Ż�,һ����˵ֻ���ڵ�һ���ʱ�򣬲Ž��ж���Ч��
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
			// ��֤callbackִֻ��һ��
			t
					.expandAndCollapseNode(treeNode, expandSign, animateSign,
							callback);

		},

		// �������ڵ�չ�� �� �۵�
		expandCollapseParentNode : function(treeNode, expandSign, animateSign,
				callback) {
			var t = this;
			// ��Զ��������Ż�,һ����˵ֻ���ڵ�һ���ʱ�򣬲Ž��ж���Ч��
			if (!treeNode.parentNode) {
				// ��֤callbackִֻ��һ��
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

		// �������ڵ�����checkbox
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

		// �����ӽڵ�����checkbox
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

		// �����ӽڵ�����level,��Ҫ�����ƶ��ڵ��Ĵ���
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

		// �����ӽڵ�
		addTreeNodes : function(parentNode, newNodes, isSilent) {
			var t = this, p = this.options, el = this.el;
			if (parentNode) {
				// Ŀ��ڵ�����ڵ�ǰ����
				if (el.find("#" + parentNode.tId).length == 0)
					return;

				target_switchObj = $("#" + parentNode.tId + IDMark_Switch);
				target_icoObj = $("#" + parentNode.tId + IDMark_Icon);
				target_aObj = $("#" + parentNode.tId + IDMark_A);
				target_ulObj = $("#" + parentNode.tId + IDMark_Ul);

				// ����ڵ���Ŀ��ڵ��ͼƬ����
				if (!parentNode.open) {
					replaceSwitchClass(target_switchObj, FolderMark_Close);
					replaceIcoClass(target_icoObj, FolderMark_Close);
					parentNode.open = false;
					target_ulObj.css({
								"display" : "none"
							});
				}

				// ���Ŀ��ڵ㲻�Ǹ��ڵ㣬�������ڵ�չ�����ر��¼�
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
				// ���ѡ��ĳ�ڵ㣬�����չ����ȫ�����ڵ�
				if (!isSilent) {
					t.expandCollapseParentNode(parentNode, true);
				}
			} else {
				t.addTreeNodesData(p.root, newNodes);
				t.initTreeNodes(0, newNodes, null);
			}
		},

		// ���ӽڵ�����
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

		// �ƶ��ӽڵ�
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
				// ת�Ƶ����ڵ�
				targetObj = el;
				target_ulObj = targetObj;

			} else {
				// ת�Ƶ��ӽڵ�
				target_switchObj = $("#" + targetNode.tId + IDMark_Switch);
				target_icoObj = $("#" + targetNode.tId + IDMark_Icon);
				target_aObj = $("#" + targetNode.tId + IDMark_A);
				target_ulObj = $("#" + targetNode.tId + IDMark_Ul);
			}

			// ����ڵ���Ŀ�괦��ͼƬ����
			replaceSwitchClass(target_switchObj, FolderMark_Open);
			replaceIcoClass(target_icoObj, FolderMark_Open);
			targetNode.open = true;
			target_ulObj.css({
						"display" : "block"
					});

			// ���Ŀ��ڵ㲻�Ǹ��ڵ㣬�Ҳ��Ǹ����������ڵ�չ�����ر��¼�
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

			// �������ݽṹ����
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
				// ����Ŀ��ڵ��е�ǰ���һ���ڵ��ͼ�ꡢ��
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
					// �ڵ� �Ƶ� ��������ԭ��ֻ��һ�����ڵ�
					replaceSwitchClass(tmp_switchObj, LineMark_Roots);

				} else {
					replaceSwitchClass(tmp_switchObj, LineMark_Center);
				}
			}

			// ���ݽڵ�ת��
			if (targetNodeIsRoot) {
				// ��Ϊ���ڵ㣬�򲻲���Ŀ��ڵ�����
				treeNode.parentNode = null;
			} else {
				// ��Ϊ�ӽڵ�
				targetNode.isParent = true;
				treeNode.parentNode = targetNode;
			}
			t.setSonNodeLevel(treeNode.parentNode, treeNode);
			targetNode[p.nodesCol].splice(targetNode[p.nodesCol].length, 0,
					treeNode);

			treeNode.isLastNode = true;
			treeNode.isFirstNode = (targetNode[p.nodesCol].length == 1);
			// ���ñ��ƶ��ڵ�Ϊ���һ���ڵ�
			if (p.showLine) {
				replaceSwitchClass(src_switchObj, LineMark_Bottom);
				src_ulObj.removeClass(LineMark_Line);
			}

			// ����ԭ�ڵ�ĸ��ڵ��ͼ�ꡢ��
			if (oldParentNode[p.nodesCol].length < 1) {
				// ԭ���ڸ��ڵ����ӽڵ�
				oldParentNode.isParent = false;
				var tmp_ulObj = $("#" + oldParentNode.tId + IDMark_Ul);
				var tmp_switchObj = $("#" + oldParentNode.tId + IDMark_Switch);
				var tmp_icoObj = $("#" + oldParentNode.tId + IDMark_Icon);
				replaceSwitchClass(tmp_switchObj, FolderMark_Docu);
				replaceIcoClass(tmp_icoObj, FolderMark_Docu);
				tmp_ulObj.css("display", "none");

			} else if (p.showLine) {
				// ԭ���ڸ��ڵ����ӽڵ�
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
						// ԭΪ���ڵ� �����ƶ���ֻ��һ�����ڵ�
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

			// �ƶ��������չ����λ�õ�ȫ�����ڵ�
			t.expandCollapseParentNode(targetNode, true, animateSign);
		},

		// �༭�ӽڵ�����
		editTreeNode : function(treeNode) {
			treeNode.editNameStatus = true;
			this.selectNode(treeNode);
		},

		// ɾ���ӽڵ�
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

			// �������ݽṹ����
			var tmpSrcIndex = -1;
			for (var i = 0; i < parentNode[p.nodesCol].length; i++) {
				if (parentNode[p.nodesCol][i].tId == treeNode.tId)
					tmpSrcIndex = i;
			}
			if (tmpSrcIndex >= 0) {
				parentNode[p.nodesCol].splice(tmpSrcIndex, 1);
			}

			// ����ԭ�ڵ�ĸ��ڵ��ͼ�ꡢ��
			if (parentNode[p.nodesCol].length < 1) {
				// ԭ���ڸ��ڵ����ӽڵ�
				parentNode.isParent = false;
				var tmp_ulObj = $("#" + parentNode.tId + IDMark_Ul);
				var tmp_switchObj = $("#" + parentNode.tId + IDMark_Switch);
				var tmp_icoObj = $("#" + parentNode.tId + IDMark_Icon);
				replaceSwitchClass(tmp_switchObj, FolderMark_Docu);
				replaceIcoClass(tmp_icoObj, FolderMark_Docu);
				tmp_ulObj.css("display", "none");

			} else if (p.showLine) {
				// ԭ���ڸ��ڵ����ӽڵ�
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
						// ԭΪ���ڵ� �����ƶ���ֻ��һ�����ڵ�
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

		// ���� tId ��ȡ �ڵ�����ݶ���
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
		// ���� id ��ȡ �ڵ�����ݶ��� add by chengl 2011-06-01
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

		// ȡ��֮ǰѡ�нڵ�״̬
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
		// ȡ��֮ǰ�༭�ڵ�״̬
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

		// ���ýڵ�Ϊ��ǰѡ�нڵ�
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

				// ����A��click dblclick����
				inputObj.bind('blur', function(event) {
							treeNode[p.nameCol] = this.value;
							// ����rename�¼�
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

		// ��ȡȫ�� checked = true or false �Ľڵ㼯��
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

	// ��ȡ����ľ�������
	function getAbsPoint(obj) {
		var r = new Array(2);
		oRect = obj.getBoundingClientRect();
		r[0] = oRect.left;
		r[1] = oRect.top;
		return r;
	}

	// ���ù��λ�ú���
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
	// ��ʾ������ Iframe�����ֲ㣨��Ҫ���ڱ�����ק��������
	function showIfameMask(showSign) {
		// �����������
		while (dragMaskList.length > 0) {
			dragMaskList[0].remove();
			dragMaskList.shift();
		}
		if (showSign) {
			// ��ʾ����
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

	// ����button�滻class ƴ���ַ���
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