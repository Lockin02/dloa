// jQuery.noConflict();
/**
 * ŷ��2010-09-17 ��Ӽ��ݴ��� ��ע���� jQuery.noConflict();
 */

$ = jQuery.noConflict();
jQuery = $;
/**
 * ���캯�����涨��һЩ���õı�������ڲ�����.
 */
function GridTree() {

}

/**
 * ��ʼ��������������һЩ������ֵ.
 */
GridTree.prototype._initData = function() {
	parentToChildMap = new HashMap();
	nodeMap = new HashMap();
	firstLevelNodes = [];
	firstLevelParentIds = [];
	childToFatherMap = new HashMap();
	_idToNumMap = new HashMap();
	parents = [];
	_usHandler = [];
	_lastSelectRowId = "";
	pagingInfo = {};
	_usnoclick = 0, _usnomsover = 0, _usnomsout = 0;
	if (document.all) {
		isIE = true;
	} else {
		isIE = false;
	}
}

/**
 * ��ʼ�����
 */
GridTree.prototype.loadData = function(obj) {
	this._initData();
	GridTree.prototype.tc = tc = jQuery.extend({
		columnModel : {},// �е���Ⱦ��ʽ,����
		idColumn : null,// ����ʾ�����ԡ�������
		parentColumn : null,// �����������ԡ�������
		tableId : null,// �����id���ԡ�������
		el : null,// ����Ⱦdiv��id�����Բ�����
		exchangeColor : true,// ��˫�н�����ʾ��ɫ
		imgPath : 'js/treetable/images/',// �����ͼƬ��Ŀ¼
		pageBarId : 'pageBarTable',// Ĭ�ϵķ�ҳ���������idΪ'pageBarTable'
		pageSize : 15,// Ĭ�Ϸ�ҳ��ÿҳ��ʾ10��
		pageBar : false,// �Ƿ���з�ҳ
		styleOption : '2',// ��ѡΪ1,2,1��Ĭ�ϵ�ectable��ʽ,2Ϊext����ʽ
		rowCount : true,// �Ƿ�Ҫ�Զ���ʾ�к�
		countColumnDesc : '���',// ��ʾ�кŵĻ�,������Ĭ��Ϊ'����'
		iconShowIndex : 0,// ��ʾ+-ͼ�����
		pageAtServer : true,// �Ƿ���ܺ�̨����
		analyzeAtServer : false,// ������к�̨����,�Ƿ��ں�̨���з���.true��false�����json����ʽ��ͬ!
		checkColumnDesc : '��ѡ��',// Ĭ�ϵ�ѡ������Ϊ'��ѡ��'
		dataUrl : null,// ��̨ȡ���ݵ������ַ
		param : {},// Ĭ��dataUrl POST�ύ�Ĳ���
		showMenu : true,// �Ƿ�����Ҽ��˵�
		contextMenu : null,// �Ҽ��˵�Ҫ��ʾ����������,����[{text:'�޸�',handle:function(){}}]
		disabled : false,// Ĭ�ϱ���е�input���ǿ���״̬.
		debug : false,// �Ƿ���ֵ�����Ϣ
		height : '10px',// ������ĸ߶�,���Զ���Ӧ��.
		width : '100%',// ������Ŀ��
		checkOption : '',// 1/2 ѡ������ʽ,''û��ѡ���У�1.radio(��ѡ),2.mult(��ѡ)
		hidddenProperties : null,// ����������һ���е�����,�ʺϴ���ֵ��һ�ַ�ʽ.
		handleCheck : null,// �����ѡ��ť,��������ѡ��ť�ĵ����¼�,��һ��function
		data : null,// ��̬��������,��һ�����鼴��
		lazy : true,// �Ƿ�������������ķ�ʽ
		lazyLoadUrl : null,// �����������ģʽ����Ҫ����һ���ַ�����ʾ��ѯ�ӽڵ�.
		handler : null,// ���ñ������¼�,��һ����������,����[{'onclick':function(){}},{'onmouseover':function(){}}]
		leafColumn : null,// ��ʾ�Ƿ����ڵ������
		rowCountOption : 1,// ���Ҫ��������,ѡ������ķ�ʽ,��1,2,3��
		expandAll : false,// �Ƿ�Ĭ��չ��ȫ���Ľڵ�
		allCheck : true,// ����Ƕ�ѡ�ı����,Ĭ�ϳ���ȫѡ��ť
		disableOptionColumn : null,// ����һ�����Ա�ʾ���ݴ�����������ѡ��ť�Ƿ�Ĭ�Ͽ���,�������ַ���
		onSuccess : null,// ���ûص�����,��������ʱִ��
		onPagingSuccess : null,// ���÷�ҳ��ϵĻص�����
		onLazyLoadSuccess : null,// �������������ִ�еĻص�����
		chooesdOptionColumn : null,// ����һ�����Ա�ʾ���ݴ�����������ѡ��ť�Ƿ�Ĭ��ѡ��,�������ַ���
		explandAll : false,// �Ƿ�ȫ��չ��
		multiChooseMode : 4
			// ����Ƕ�ѡ����ʽ,����ѡ���ģʽ,��1,2,3,4,5��,Ĭ��4��ѡ���׽ڵ��Զ�ѡ������ڵ㣬ѡ������ڵ㲻�Զ�ѡ���׽ڵ�
		}, obj);
	elct = _$(tc.el);
	// ����ͼ��
	tc.closeImg = tc.imgPath + 'minus.gif', // �����ڵ�Ĺر�ͼ��
	tc.openImg = tc.imgPath + 'plus.gif', // �����ڵ�Ĵ�ͼ��
	tc.lazyLoadImg = tc.imgPath + 'plus2.gif', // ������ʱ��ʾ��ͼ��
	tc.blankImg = tc.imgPath + 'blank.gif', // �հ׽ڵ�
	tc.noparentImg = tc.imgPath + 'leaf.gif', // ��Ҷ�ڵ��ͼ��

	headColumns = [];
	_isValid = true;
	if (tc.pageBar == null || tc.pageBar == false) {
		// ��ʾ����ҳ
		tc.pageSize = -1;
	}

	// Ĭ�ϵ���ʽΪectable��ʽ
	if (tc.styleOption == null || (tc.styleOption + '') != '2') {
		_style = 2;
		importcss("GridTree2.css");
	} else {
		_style = tc.styleOption;
		// importcss("GridTree.css");
		// importcss("js/treetable/GridTree.css");
	}

	// ֻҪ������dataUrl���Ծ�Ϊ��̨��ҳģʽ.��Ҫ������pageAtServer������...
	if (tc.dataUrl != null) {
		if (tc.analyzeAtServer)
			_serverPagingMode = 'server';
		else
			_serverPagingMode = 'analyzeAtPage';
		if (tc.lazy) {
			// �����������ģʽ,Ĭ�Ͼ�ʹ�ú�̨������ǰ̨���з���,������json�Ƚϼ�.
			tc.analyzeAtServer = false;
			_serverPagingMode = 'analyzeAtPage';
			// �����������ģʽ,ǿ��ʹ�õ�3��������кŵķ�ʽ,��ʡ��.
			tc.rowCountOption = '3';
			// ����ǿ��ʹ�ü򵥵�ѡ��ģʽ.
			tc.multiChooseMode = 1;
			// �����������ģʽ,������ʹ��'չ��ȫ��'����
			tc.expandAll = false;
			if (!tc.lazyLoadUrl) {
				// alert("������ģʽ,������������[lazyLoadUrl]!");
				return;
			}
			if (!tc.leafColumn) {
				// alert("������ģʽ,������������[leafColumn ]!");
				return;
			}
		}
		// ׼��һ����ʾ��Ϣ,�����ں�̨��ҳ��ʱ���õ�
		this._createMsgDiv();
	} else {
		_serverPagingMode = 'client';
	}

	// ����������ǵ���ģʽ.
	if (tc.debug) {
		this._createDebugDiv();
	}
}

/**
 * ����������ָ����div�γɱ����
 */
GridTree.prototype.makeTable = function() {
	if (!elct) {
		alert('����:Ҫ��Ⱦdiv������!');
		return false;
	}
	if (!_isValid)
		return false;
	var tableTree = document.createElement("table");
	tableTree.style.height = tc.height;
	// tableTree.style.width = tc.width;//Ĭ����class���
	tableTree.id = '' + tc.tableId;
	tableTree.className = 'main_table';
	/** ********** ����json���ݷ�������,�õ����׽ڵ�ļ���,�Լ����׽ڵ�ͺ��ӽڵ��ӳ���ϵ. */
	if (tc.data != null) {
		GridTree.prototype._makeTable(tableTree, tc.data);
	} else {
		tc.param.analyze = tc.analyzeAtServer;
		tc.param.limit = tc.pageSize;
		tc.param.lazy = tc.lazy;
		jQuery.ajax({
					type : "POST",
					url : tc.dataUrl,
					async : true,
					data : tc.param,
					success : function(msg) {
						eval("var e=" + msg);
						if (e && e.total != 0) {
							// if (msg) {
							try {
								// alert("grid")
								if (!GridTree.prototype._verifyAjaxAns(msg)) {
									_isValid = false;
									return;
								}
								GridTree.prototype._makeTable(tableTree, msg);
								if (tc.explandAll == true) {
									$("#explandAllMsg").trigger('click');
								}
							} catch (e) {
								GridTree.prototype
										._makeTableWithNoData(tableTree);
							}
						} else {
							GridTree.prototype._makeTableWithNoData(tableTree);
						}
					}
				});
	}
	// ��������˱����Ϊ������״̬,�Ͱѱ�����е�ȫ���ı����ȫ������Ϊ������.
	if (tc.disabeld) {
		this.setDisabled(true);
	}
}
/**
 * ɾ��һ����¼
 * 
 * @param {}
 *            objectName
 * @param {}
 *            id
 */
GridTree.prototype._delete = function(objectName, id) {
	if (window.confirm("ȷ��Ҫɾ����")) {
		var url = "?model=" + objectName + "&action=ajaxdeletes";
		$.post(url, {
					id : id
				}, function(data) {
					if (data == 1) {
						GridTree.prototype._reload();
						alert('ɾ���ɹ���');
					} else {
						alert('ɾ��ʧ�ܣ��ö�������Ѿ�������!');
					}
				});
	}
}
/**
 * ����ɾ��
 * 
 * @param {}
 *            objectName
 * @return {Boolean}
 */
GridTree.prototype._deletes = function(objectName) {
	var checkIDS = checkOne();// ������businesspage.js
	var ids = checkIDS.substring(0, checkIDS.length - 1);
	if (checkIDS.length == 0) {
		alert("��ʾ: ��ѡ��һ����Ϣ.");
		return false;
	}
	if (window.confirm("ȷ��Ҫɾ����")) {
		var url = "?model=" + objectName + "&action=ajaxdeletes";
		$.post(url, {
					id : ids
				}, function(data) {
					if (data == 1) {
						GridTree.prototype._reload();
						alert('ɾ���ɹ���');
					} else {
						alert('ɾ��ʧ�ܣ��ö�������Ѿ�������!');
					}
				});
	}
}

/**
 * ���±������
 */
GridTree.prototype._reload = function() {
	GridTree.prototype._searchGrid({});
}

/**
 * ���ݴ���Ĳ������˱��
 * 
 * @param {}
 *            param
 */
GridTree.prototype._searchGrid = function(param) {
	var searchParam = {};
	jQuery.extend(searchParam, tc.param, param);// �ϲ�
	// param.limit = tc.pageSize;
	tc.param.isSearch = true;// ��־�Ƿ�����ģʽ
	var pnum = _$('_pageNum'), mdiv = _$('_msgDiv'), mcel = _$('msgCell'), tb = _$(tc.tableId);
	GridTree.prototype._showMsg(1);
	jQuery.ajax({
				type : "POST",
				url : tc.dataUrl,
				data : searchParam,
				async : 1,
				success : function(msg) {
					var o = new Date();
					mdiv.innerText = '���ڻ������...';
					$(elct).find('TBODY').empty();
					GridTree.prototype._newPagingServerMakeTable(tb, msg);
					GridTree.prototype._showMsg(0);
					if (tc.pageBar == true) {
						pnum.value = pagingInfo.currentPage;
						// ���¼����ҳ��Ϣ
						eval("tc.allDataInfoWithPageInfo=" + msg);
						var pagesCount = Math
								.ceil(tc.allDataInfoWithPageInfo.total
										/ pagingInfo.pageSize * 1.0);
						// elct.innerHTML = '';
						_$("_pageCount").innerHTML = pagesCount;
						_$("_allCount").innerHTML = tc.allDataInfoWithPageInfo.total;
					}

				}
			});
	// GridTree.prototype._initPageInfo();
}

/**
 * ��ʽ�Ŀ�ʼ�γɱ��������. tree:����table��dom���� data:���������������
 */
GridTree.prototype._makeTable = function(tree, data) {
	var o = new Date();
	this._analyseData(data);
	// ���з�ҳ��Ϣ�Ĵ���
	this._initPageInfo();
	// ��ӱ�������Ϣ
	this._addTitleHead(tree);

	/** ******** ����������,���濪ʼ�������װ�� *************** */
	// ��ʾȫ����Ϣ
	if (tc.pageBar != true) {
		// alert("ȫ��")
		if (tc.lazy)
			this._showLazyTable(tree, tc.allDataInfoWithPageInfo.data);
		else
			this._showTable(tree, 0, firstLevelNodes.length);
	}
	// ��ҳ��ʾ
	else {
		// alert("����Ҳ")
		// ����ж�ҳ���͵�һ��ֻ��ʾ��һҳ
		if (!tc.lazy) {
			if (tc.pageSize > firstLevelNodes.length) {
				this._showTable(tree, 0, firstLevelNodes.length);
			}
			// ����ֻ��һҳ����ʾȫ��
			else {
				this._showTable(tree, 0, tc.pageSize);
			}
		} else {
			// ****��ҳ������*******
			this._showLazyTable(tree, tc.allDataInfoWithPageInfo.data);
		}
		/** ***** ��ӷ�ҳ�� *************** */
		this._makePageBar(tree, tree.rows.lenght);
	}

	elct.innerHTML = '';
	// ���õ�˫����ɫ
	if (tc.exchangeColor)
		this._setColor(tree);
	elct.appendChild(tree);
	/** ****** ���Ƿ�������չ��ȫ�� ********* */
	if (tc.expandAll)
		this.expandAll();
	// �������÷�ҳ��ť����ʽ
	this._resetPageBtns();
	// ��������˵���ģʽ,���Կ������ĵ�ʱ��
	var gotime = new Date() - o;
	this._wirteDebug('��һ����ʾǰ̨����ʱ��:' + gotime);

	// ��������˱����Ϊ������״̬,�Ͱѱ�����е�ȫ���ı����ȫ������Ϊ������.
	if (tc.disabled)
		this.setDisabled(1);
	if (tc.onSuccess)
		tc.onSuccess(elct);
}

/**
 * ����ҳģʽ����ʾ��������.
 * 
 * @param {}
 *            tableTree ������
 * @param {}
 *            data ��������
 */
GridTree.prototype._showLazyTable = function(tableTree, data) {
	// alert("����ҳģʽ����ʾ��������")
	this._clearContent();
	var rowCount = 1;
	if (data.length == 0) {
		$(elct).find('TBODY').append('<tr><td colspan="100">���������Ϣ</td></tr>');
	}else{
		$(elct).find('TBODY').empty();
	}
	for (var i = 0, j = data.length; i < j; i++) {
		// �����ҵ�Ҫչʾ�ĵ�һ��ڵ�,��ӵ���񣨵�һ��ڵ㣩
		var oneObj = data[i];
		if (typeof oneObj == 'string') {
			eval("oneObj=" + oneObj);
		}
		// �����ֱ��ʾΪ:������,���������,�����һ�е�json����,�������
		var newRow = this._addOneLazyRowByData(rowCount, oneObj, 1);
		if (isIE)
			jQuery("tbody:last", tableTree).append(newRow);
		else
			jQuery(tableTree).append(newRow);
		rowCount = rowCount + 1;
	}
}

/**
 * ����ҳģʽ�µ���ʾ�����
 * 
 * @param {}
 *            tableTree ������
 * @param {}
 *            startParentIndex ��ʼ�ĸ��׽ڵ���һ���е�λ��
 * @param {}
 *            endParentIndex ��ֹ�ĸ��׽ڵ��ڵ�һ���е�λ��
 */
GridTree.prototype._showTable = function(tableTree, startParentIndex,
		endParentIndex) {
	// alert("����ҳ")
	this._clearContent();
	// �õ����������ĵ���ʼλ��,��Ϊ���ڱ����к������,������ʼλ����1
	var rowCount = 1;
	/** *** ������� ********* */
	var lenlen = firstLevelNodes.length;
	// ѭ�����ÿһ�е�����
	for (var ind = startParentIndex; ind < endParentIndex && ind < lenlen; ind++) {
		// �����ҵ�Ҫչʾ�ĵ�һ��ڵ�,��ӵ���񣨵�һ��ڵ㣩
		var _parentId = firstLevelNodes[ind];
		var oneObj = nodeMap.get(_parentId);
		if (typeof oneObj == 'string') {
			eval("oneObj=" + oneObj);
		}
		// �����ֱ��ʾΪ:������,���������,�����һ�е�json����,�������
		this._addOneRowByData(tableTree, rowCount, oneObj, 1);
		// ����������1
		rowCount = rowCount + 1;
		// �����ֱ��ʾ:���׽ڵ�,�������ڵ��������
		addChildByParentNode(oneObj, 2);
	}
	/**
	 * ���һ�����ڵ�����к��ӵ������(����һ���ڲ�����,�������ڲ���!)
	 */
	function addChildByParentNode(parentNode, level) {
		var _id = parentNode[tc.idColumn];
		var _parent = parentNode[tc.parentColumn];
		var _isP = GridTree.prototype.isParent(parentNode);
		var _children = parentToChildMap.get('_node' + _id);
		// ��һ��ڵ���������ֹ����Ľڵ�,û�к��ӽڵ�
		if (_children != null) {
			for (var i = 0; i < _children.length; i++) {
				var oneObj;
				oneObj = nodeMap.get(_children[i]);
				if (typeof oneObj == 'string') {
					eval("oneObj=" + oneObj);
				}
				GridTree.prototype._addOneRowByData(tableTree, rowCount,
						oneObj, level);
				rowCount = rowCount + 1;
				// ���������ӽڵ�ͬʱ���к���,�ͼ�����������
				if (GridTree.prototype.isParent(oneObj) == '1') {
					// ���ӵĺ��ӵ������������Ҫ��1!!
					addChildByParentNode(oneObj, level + 1);
				}
			}
		}
	}
}

/**
 * ����¼���ָ����obj����
 * 
 * @param {dom����}
 *            obj
 * @param {�¼�����}
 *            funObj{"�¼���":�¼�����������}
 */
GridTree.prototype._addEventToObj = function(obj, funObj) {
	if (isIE) {
		for (eventName in funObj) {
			var fun = funObj[eventName];
			obj.attachEvent(eventName, function() {
						fun(obj);
					});
		}
	} else {
		for (eventName in funObj) {
			var fun = funObj[eventName];
			var len = eventName.length;
			var newfun = fun.bind(null, obj);
			obj.addEventListener(eventName.substring(2, len), newfun, false);
		}
	}
}

/**
 * ����ѡ����е�id
 * 
 * @return {}
 */
GridTree.prototype._getSelectRowId = function() {
	return _lastSelectRowId;
}

/**
 * ����һ���ڵ�idͬʱѡ�и��׽ڵ�����Ķ�ѡ��.ֻ��Զ�ѡ��ѡ���ʱ����Ч
 * 
 * @param {}
 *            checkboxDom
 */
GridTree.prototype._chooseParentNode = function(checkboxDom) {
	var nodeId = checkboxDom.value;
	// �����ѡ��ǰ�Ķ�ѡ��,��ͬʱѡ�и��׵Ķ�ѡ��.
	if (checkboxDom.checked) {
		while (1) {
			var parentId = _$('_node' + nodeId)._node_parent;
			if (_$(parentId) != null) {
				// �õ�ʵ����Ч�Ľڵ�id(ȥ��ǰ׺)
				nodeId = parentId.replace('_node', '');
				var obj = _$('_chk' + nodeId).firstChild;
				if (_notBindDisabled(obj))
					obj.checked = 1;
			} else {
				break;
			}
		}
	}
}

/**
 * ѡ���׽ڵ��Զ��Ѻ���ȫ��ѡ��.ֻ��Զ�ѡ��ѡ������
 * 
 * @param {��ѡ�����}
 *            checkboxDom
 */
GridTree.prototype._chooseChildrenNode = function(checkboxDom) {
	var nodeId = checkboxDom.value;
	// �����ѡ��ǰ�Ķ�ѡ��,��ͬʱѡ�и��׵Ķ�ѡ��.
	if (checkboxDom.checked) {
		this._chooseAllChildrenNode('_node' + nodeId, true);
	}
}

/**
 * ȡ��ѡ���׽ڵ��Զ��Ѻ���ȫ��ȡ��ֻ��Զ�ѡ��ȡ��ѡ�е����
 * 
 * @param {��ѡ�����}
 *            checkboxDom
 */
GridTree.prototype._cancleChildrenNode = function(checkboxDom) {
	var nodeId = checkboxDom.value;
	// �����ѡ��ǰ�Ķ�ѡ��,��ͬʱѡ�и��׵Ķ�ѡ��.
	if (!checkboxDom.checked) {
		this._chooseAllChildrenNode('_node' + nodeId, false);
	}
}

/**
 * ѡ���׽ڵ��Զ��Ѻ��ӽڵ�ȫ��ѡ�����ȫ��ȡ��
 * 
 * @param {�ڵ�id}
 *            nodeId
 * @param {ֵ}
 *            v
 */
GridTree.prototype._chooseAllChildrenNode = function(nodeId, v) {
	var children = this.seeChildren(nodeId);
	if (children) {
		var len = children.length;
		for (var i = 0; i < len; i++) {
			var nodeId = children[i].replace('_node', '');
			var obj = _$('_chk' + nodeId).firstChild;
			if (_notBindDisabled(obj))
				obj.checked = v;
			if (findInArray(parents, children[i]) != -1) {
				this._chooseAllChildrenNode(children[i], v);
			}
		}
	}
}

/**
 * ȡ����ǰ�Ķ�ѡ��,������ֵܽڵ㶼û��ѡ��,����Ҳ��Ϊ��ѡ��״̬.ֻ��Զ�ѡ��ȡ����ʱ����Ч.
 * 
 * @param {}
 *            checkboxDom
 */
GridTree.prototype._cancelFaher = function(checkboxDom) {
	var nodeId = checkboxDom.value;
	// ���ȡ����ǰ�Ķ�ѡ��,������ֵܽڵ㶼û��ѡ��,����Ҳ��Ϊ��ѡ��״̬
	if (!checkboxDom.checked) {
		while (1 == 1) {
			var parentId = _$('_node' + nodeId)._node_parent;
			if (_$(parentId)) {
				nodeId = parentId.replace('_node', '');
				if (!this._anyChildChoosed(parentId)) {
					var obj = _$('_chk' + nodeId).firstChild;
					if (_notBindDisabled(obj))
						obj.checked = 0;
				}
			} else {
				break;
			}
		}
	}
}

/**
 * �ж�һ�����׽ڵ�ĺ������Ƿ�������һ����ѡ��.�оͷ���true,�����Ӷ�û��ѡ��ͷ���false.
 * 
 * @param {���׽ڵ��id}
 *            parentId
 */
GridTree.prototype._anyChildChoosed = function(parentId) {
	var children = this.seeChildren(parentId);
	var len = children.length;
	for (var i = 0; i < len; i++) {
		var nodeId = children[i].replace('_node', '');
		if (_$('_chk' + nodeId).firstChild.checked) {
			return true;
		}
	}
	return false;
}

/**
 * �������õ����ͷ���ָ����input�ؼ���
 * 
 * @param {���͵���ϸ��������}
 *            showTypeDesc
 * @param {һ�е�����ֵ}
 *            onerow
 * @param {��������}
 *            dataColumn
 * @param {idֵ}
 *            idValue
 */
GridTree.prototype._createContent = function(showTypeDesc, onerow, dataColumn,
		idValue) {
	var tp = showTypeDesc.inputtype;
	var cssName = showTypeDesc.style;
	var allvalues = showTypeDesc.values;
	var texts = [];
	var value = onerow[dataColumn];
	var inputName = showTypeDesc.nameId + idValue;
	var setVisible = showTypeDesc.visibledIndex;
	var setDisabled = showTypeDesc.disabledIndex;
	var node = document.createElement("div");
	// ���û������showTypeDesc.visibledIndex����,����������������Զ��Ҷ�Ӧ������ֵΪ1,��˵��Ҫ��ʾ����Զ�����.
	if (setVisible == null || onerow[setVisible] + '' == '1') {
		// ���������showTypeDesc.disabledIndex������Զ��Ҷ�Ӧ��ֵ��1,��˵��Ҫ��������Զ����в�����״̬!
		if (onerow[setDisabled] + '' == '1') {
			setDisabled = 'disabled';
		} else {
			setDisabled = '';
		}

		node.style.textAlign = 'center';
		if (tp == 'textbox') {
			node.innerHTML = ["<input type='text' class='", cssName,
					"'  value='", value, "' name='", inputName, "' ",
					setDisabled, " userSetDisabled='", setDisabled, "'/>"]
					.join('');
		} else if (tp == 'html') {
			texts = showTypeDesc.htmlStr;
			node.setAttribute('id', inputName);
			node.innerHTML = texts.replace(/[$]/g, value);
		} else if (tp == 'select') {
			texts = showTypeDesc.texts;
			var ans = [];
			for (var i = 0; i < allvalues.length; i++) {
				if (allvalues[i] == value) {
					ans.push(["<option value='", allvalues[i], "' selected>",
							texts[i], "</option>"].join(''));
				} else {
					ans.push(["<option value='", allvalues[i], "'>", texts[i],
							"</option>"].join(''));
				}
			}
			ans.push("</select>");
			node.innerHTML = ans.join('');
		} else if (tp == 'radiobox') {
			texts = showTypeDesc.texts;
			node.appendChild(createHidden(inputName, inputName, value));
			var f = function() {
				if (showTypeDesc.onclick)
					showTypeDesc.onclick();
				_$(inputName).value = this.value;
			};
			for (var i = 0; i < allvalues.length; i++) {
				if (value == allvalues[i]) {
					createRadio(node, setDisabled, '', 'rd_' + inputName,
							allvalues[i], f, texts[i], cssName, 'checked');
				} else {
					createRadio(node, setDisabled, '', 'rd_' + inputName,
							allvalues[i], f, texts[i], cssName, '');
				}
			}
		} else if (tp == 'checkbox') {
			texts = showTypeDesc.texts;
			var chArry = value.split(',');
			var f = function(e) {
				if (showTypeDesc.onclick)
					showTypeDesc.onclick();
			};
			for (var i = 0; i < allvalues.length; i++) {
				if (findInArray(chArry, allvalues[i]) != -1) {
					createCheckbox(node, setDisabled, '', inputName,
							allvalues[i], f, texts[i], cssName, 'checked');
				} else {
					createCheckbox(node, setDisabled, '', inputName,
							allvalues[i], f, texts[i], cssName, '');
				}
			}
		} else {
			node.innerHTML = '<font color="red">���ñ�����������ͳ���.</font>';
		}
	}
	return node;
}

/**
 * ���ݵ�ǰҳ���������÷�ҳ��ť��״̬
 */
GridTree.prototype._resetPageBtns = function() {
	var f1t = _$("_firstPageBtn"), pre = _$("_prePageBtn"), lst = _$("_lastPageBtn"), nex = _$("_nextPageBtn"), tpg = _$("_toPageBtn");
	// �����ҳ��ֻ��1ҳ���������ĸ���ҳ��ť��Ϊ��������ʽ
	if (pagingInfo.pagesCount == 1 || pagingInfo.pagesCount == 0) {
		jQuery(f1t).removeClass("firstPage").addClass("disFirstPage").attr(
				"disabled", true);
		jQuery(pre).removeClass("prevPage").addClass("disPrevPage").attr(
				"disabled", true);
		jQuery(lst).removeClass("lastPage").addClass("disLastPage").attr(
				"disabled", true);
		jQuery(nex).removeClass("nextPage").addClass("disNextPage").attr(
				"disabled", true);
		if (pagingInfo.pagesCount == 0) {
			jQuery(tpg).removeClass("nextPage").addClass("disFirstPage").attr(
					"disabled", true);
		}
	} else {
		// ����ǵ�һҳ��������ǰһҳ�͵�һҳ��ťΪ��������ʽ
		if (pagingInfo.currentPage == 1) {
			jQuery(f1t).removeClass("firstPage").addClass("disFirstPage").attr(
					"disabled", true);
			jQuery(pre).removeClass("prevPage").addClass("disPrevPage").attr(
					"disabled", true);
			jQuery(lst).removeClass("disLastPage").addClass("lastPage").attr(
					"disabled", false);
			jQuery(nex).removeClass("disNextPage").addClass("nextPage").attr(
					"disabled", false);
		}
		// �����ǰҳ�������һҳ�����ú�һҳ�����һҳ��ťΪ��������ʽ
		else if (pagingInfo.currentPage == pagingInfo.pagesCount) {
			jQuery(f1t).removeClass("disFirstPage").addClass("firstPage").attr(
					"disabled", false);
			jQuery(pre).removeClass("disPrevPage").addClass("prevPage").attr(
					"disabled", false);
			jQuery(lst).removeClass("lastPage").addClass("disLastPage").attr(
					"disabled", true);
			jQuery(nex).removeClass("nextPage").addClass("disNextPage").attr(
					"disabled", true);
		}
		// ��������������ĸ���ҳ��ť�������õ���ʽ
		else {
			jQuery(f1t).removeClass("disFirstPage").addClass("firstPage").attr(
					"disabled", false);
			jQuery(pre).removeClass("disPrevPage").addClass("prevPage").attr(
					"disabled", false);
			jQuery(lst).removeClass("disLastPage").addClass("lastPage").attr(
					"disabled", false);
			jQuery(nex).removeClass("disNextPage").addClass("nextPage").attr(
					"disabled", false);
		}
	}
}

/**
 * ����һ����ʾ��Ϣ��,��ʾ���ڼ���. v:��ʾ���ǲ���ʾ,true����ʾ������ʾ
 */
GridTree.prototype._showMsg = function(v) {
	divNode = _$("_msgDiv");
	var table = _$('_trhead');
	if (v) {
		divNode.innerText = '�����ύ...';
		divNode.style.top = table.offsetTop + 10;
		divNode.style.left = table.offsetLeft + table.offsetWidth / 9 * 8;
		divNode.style.display = 'inline';
	} else {
		divNode.style.display = 'none';
	}
}

/**
 * ����һ��div,������ʾ�������ڼ��ص���ʾ��Ϣ
 */
GridTree.prototype._createMsgDiv = function() {
	msgDiv = document.createElement("div");
	msgDiv.setAttribute('id', '_msgDiv');
	msgDiv.className = 'msgdiv';
	msgDiv.appendChild(document.createTextNode("�����ύ..."));
	document.body.appendChild(msgDiv);
	GridTree.prototype._showMsg(0);// ������������ʧ
}

function importcss(csspath) {
	var scripts = document.getElementsByTagName("link");
	for (var i = 0; i < scripts.length; i++) {
		if (csspath == scripts[i].getAttribute("href")) {
			return;
		}
	}
	// ��ie��.
	if (isIE)
		document.createStyleSheet(csspath);
	// �ڻ����
	else {
		var headElement = document.getElementsByTagName("head")[0];
		var tempStyleElement = document.createElement('link');// w3c
		tempStyleElement.setAttribute("rel", "stylesheet");
		tempStyleElement.setAttribute("type", "text/css");
		tempStyleElement.setAttribute("href", csspath);
		headElement.appendChild(tempStyleElement);
	}
}

/**
 * ��û�б�������ݵ�ʱ��ֻ��ʾ�����кͷ�ҳ����Ϣ(��ʱע�ͣ�û�����ݵ�ʱ����ʾ������)
 * 
 * @param {����table��dom����}
 *            tree
 */
GridTree.prototype._makeTableWithNoData = function(tree) {
	pagingInfo.allCount = 0;
	pagingInfo.pageSize = 0;
	pagingInfo.pagesCount = 0;
	pagingInfo.currentPage = 0;
	this._addTitleHead(tree);
	elct.innerHTML = '';
	// ��ʾȫ����Ϣ
	if (tc.pageBar) {
		this._makePageBar(tree, 1);
	}

	elct.appendChild(tree);
	// alert($(elct).html())
	$(elct).find('TBODY').append('<tr><td colspan="100">���������Ϣ</td></tr>');
	// // �������÷�ҳ��ť����ʽ
	this._resetPageBtns();
}

/**
 * ����һ����ʾ������Ϣ�Ĳ�
 */
GridTree.prototype._createDebugDiv = function() {
	var debugDiv = document.createElement("div");
	debugDiv.setAttribute('id', '_debugDiv');
	debugDiv.className = 'debugdiv';
	document.body.appendChild(debugDiv);
}

/**
 * ��ʼ������ʱ����з�ҳ��Ϣ�Ĵ���.
 */
GridTree.prototype._initPageInfo = function() {
	if (_serverPagingMode == 'client') {
		// �ܹ��ĵ�һ��ڵ���Ŀ(�������Ϊ��Ϣ����)
		pagingInfo.allCount = firstLevelNodes.length;
		// ÿҳ����ʾ��Ϣ����
		pagingInfo.pageSize = tc.pageSize;
		// �ܹ���ҳ��
		pagingInfo.pagesCount = Math.ceil(pagingInfo.allCount
				/ pagingInfo.pageSize * 1.0);
		// ��ǰҳ��(��1��ʼ����)
		pagingInfo.currentPage = 1;
	} else if (_serverPagingMode == 'analyzeAtPage') {
		pagingInfo.allCount = tc.allDataInfoWithPageInfo.total;
		pagingInfo.pageSize = tc.pageSize;
		pagingInfo.pagesCount = Math.ceil(pagingInfo.allCount
				/ pagingInfo.pageSize * 1.0);
		// ��̨json����ֱ�Ӵ�����ǰҳ��
		pagingInfo.currentPage = tc.allDataInfoWithPageInfo.page;
	} else {
		// ȫ��������
		pagingInfo.allCount = tc.allDataInfoWithPageInfo.allCount;
		pagingInfo.pageSize = tc.allDataInfoWithPageInfo.pageSize;
		pagingInfo.pagesCount = Math.ceil(pagingInfo.allCount
				/ pagingInfo.pageSize * 1.0);
		pagingInfo.currentPage = 1;
	}
}

/**
 * ǰ̨������������.
 * 
 * @param {����ж������������}
 *            data
 */
GridTree.prototype._analyseData = function(msg) {
	var data = [];
	// ����Ǻ�̨����ģʽ,��ֱ��ȡ�������Ϣ֮����к����Ĳ���.
	if (_serverPagingMode == 'server') {
		eval("tc.allDataInfoWithPageInfo=" + msg);
		// ��json�����еõ����׽ڵ��id���ϵȶ���ʡȥ����ǰ̨���з������鷳��
		parents = tc.allDataInfoWithPageInfo.parents;
		firstLevelNodes = tc.allDataInfoWithPageInfo.firstLevelNodes;
		parentToChildMap = jsonMapToJsHashMap(tc.allDataInfoWithPageInfo.parentToChildMap);
		nodeMap = jsonMapToJsHashMap(tc.allDataInfoWithPageInfo.idToNodeMap);
		childToFatherMap = jsonMapToJsHashMap(tc.allDataInfoWithPageInfo.idToParent);

		pagingInfo.currentPage = tc.allDataInfoWithPageInfo.currentPage;
		pagingInfo.allCount = tc.allDataInfoWithPageInfo.allCount;
		return;
	} else {
		if (_serverPagingMode == 'client') {
			data = msg;
		} else {
			eval("tc.allDataInfoWithPageInfo=" + msg);
			data = tc.allDataInfoWithPageInfo.data;
			if (typeof data == 'undefined') {
				data = tc.allDataInfoWithPageInfo;
			}
			pagingInfo.currentPage = tc.allDataInfoWithPageInfo.page;
			pagingInfo.allCount = tc.allDataInfoWithPageInfo.total;
		}
	}

	// �������������ģʽ,��Ҫ�������ݵķ���,�����÷���,ֱ����data�����json���������Ⱦ�Ϳ�
	if (!tc.lazy) {
		var len = data.length;
		for (var i = 0; i < len; i++) {
			var _id = data[i][tc.idColumn];
			var _parent = data[i][tc.parentColumn];
			// ����ڸ����������Ҳ���������ף��ͽ����׽ڵ���ӵ�parents��ȥ����ֹ�˳��ָ��׽ڵ��ظ������
			if (findInArray(parents, '_node' + _parent) == -1) {
				parents.push('_node' + _parent);
			}
			// ����Ѿ���map�д����˸ýڵ�ĸ��׽ڵ�,��ȡ���Ѿ����ڵ�����,������µ�id
			if (parentToChildMap.containsKey('_node' + _parent)) {
				var arr = parentToChildMap.get('_node' + _parent);
				arr.push('_node' + _id);
				parentToChildMap.put('_node' + _parent, arr);
			} else {
				var arr = [];
				arr.push('_node' + _id);
				parentToChildMap.put('_node' + _parent, arr);
			}
			// ��Ӻ��ӵ����׵�ӳ���ϵ
			childToFatherMap.put('_node' + _id, '_node' + _parent);
			// ��ӽڵ�id���ڵ��ӳ���ϵ
			nodeMap.put('_node' + _id, data[i]);
		}
		// ����һ������,������ŵ�һ��ڵ�ĸ����ڵ�...
		firstLevelParentIds = removeArrayFromOtherArray(parents, nodeMap.keys());
		// �õ��ڵ��еĸ��׽ڵ㼯��
		parents = removeArrayFromOtherArray(parents, firstLevelParentIds);
		// �������firstLevelParentIds�õ�Ҫ�ڵ�һ����ʾ����Щ�ڵ��id
		for (var ii = 0; ii < firstLevelParentIds.length; ii++) {
			firstLevelNodes = firstLevelNodes.concat(parentToChildMap
					.get(firstLevelParentIds[ii]));
		}
	}
}

/**
 * д������Ϣ
 * 
 * @param {��Ϣ}
 *            msg
 */
GridTree.prototype._wirteDebug = function(msg) {
	if (tc.debug == 1) {
		var dbg = _$('_debugDiv');
		if (dbg.innerHTML != '')
			dbg.innerHTML += '<br>' + msg;
		else {
			dbg.innerHTML = msg;
		}
		dbg.scrollTop = dbg.scrollHeight;
	}
}

/**
 * �ں�̨��ҳģʽ�еĵ����ҳ��ť֮����������װ������.
 * 
 * @param {����dom����}
 *            tree
 * @param {�ӷ���˵õ���json�ַ���}
 *            msg
 */
GridTree.prototype._pagingServerMakeTable = function(tree, msg) {
	eval(" tc.allDataInfoWithPageInfo=" + msg);
	// �õ���̨������json����
	var data = tc.allDataInfoWithPageInfo.data;
	this._initData();
	// ���ȷ�������
	this._analyseData(data);
	// �������õ�ǰҳ
	pagingInfo.currentPage = tc.allDataInfoWithPageInfo.currentPage;
	pagingInfo.allCount = tc.allDataInfoWithPageInfo.allCount;
	pagingInfo.pageSize = tc.allDataInfoWithPageInfo.pageSize;
	pagingInfo.pagesCount = Math.ceil(pagingInfo.allCount / pagingInfo.pageSize
			* 1.0);
	// ��Ȼ�Ǻ�̨��ҳģʽ,��ô��һ����ʾ�����ݿ϶���Ҫ��firstLevelNodes������ȫ����ʾ����!
	// ����ڶ���������1��ԭ����'ǰ����'������
	this._showTable(tree, 0, firstLevelNodes.length + 1);
	// �������÷�ҳ��ť����ʽ
	this._resetPageBtns();
	// �������õ�˫����ɫ
	if (tc.exchangeColor)
		this._setColor(tree);
	elct.appendChild(tree);
	/** ****** ���Ƿ�������չ��ȫ�� ********* */
	if (tc.expandAll) {
		this.expandAll();
	}
}

/**
 * �µĺ�̨��ҳģʽ�����·�ҳ�ķ�������Ҫ���ں�̨�����˷�ҳ�Ĵ��������ֱ���ں�̨������json���ݽ��д���
 * 
 * @param {�������dom����}
 *            tree
 * @param {json����}
 *            msg
 */
GridTree.prototype._newPagingServerMakeTable = function(tree, msg) {
	this._initData();
	this._analyseData(msg);
	// �������õ�ǰҳ
	pagingInfo.pageSize = tc.pageSize;
	pagingInfo.pagesCount = Math.ceil(pagingInfo.allCount / pagingInfo.pageSize
			* 1.0);
	// ��Ȼ�Ǻ�̨��ҳģʽ,��ô��һ����ʾ�����ݿ϶���Ҫ��firstLevelNodes������ȫ����ʾ����!
	// ����ڶ���������1��ԭ����'ǰ����'������
	if (!tc.lazy)
		this._showTable(tree, 0, firstLevelNodes.length + 1);
	else
		this._showLazyTable(tree, tc.allDataInfoWithPageInfo.data);
	// �������÷�ҳ��ť����ʽ
	this._resetPageBtns();
	if (tc.exchangeColor)
		this._setColor(tree);
	elct.appendChild(tree);
	/** ****** ���Ƿ�������չ��ȫ�� ********* */
	if (tc.expandAll) {
		this.expandAll();
	}
	if (tc.onPagingSuccess)
		tc.onPagingSuccess(elct);
}

/**
 * ����ָ���ı�����ĵ�˫����ɫ
 * 
 * @param {���������}
 *            tableTree
 */
GridTree.prototype._setColor = function(tableTree) {
	jQuery("tr:visible", tableTree).each(function(i) {
				if (this.id.indexOf('_node') != -1) {
					if (i % 2 == 0) {
						jQuery(this).removeClass('TrEven').addClass('TrOdd');
					} else {
						jQuery(this).removeClass('TrOdd').addClass('TrEven');
					}
				}
			});
}

/**
 * ��ӱ�����,����tHead���� tableTree:������
 */
GridTree.prototype._addTitleHead = function(tableTree) {
	/** *** ��ӱ�ͷ��һ�м������� */
	var cms = tc.columnModel;
	var tableHeadRow = tableTree.createTHead();
	var newRow = document.createElement("tr");
	newRow.setAttribute('id', '_trhead');
	newRow.setAttribute('className', 'main_tr_header');
	// ���������ѡ��ť�����ڱ��������һ��ѡ��ı�����
	if (tc.checkOption == '1' || tc.checkOption == '2') {
		var checkCell = document.createElement("th");
		jQuery(checkCell).attr('id', 'countCell').width('5%');
		// ����Ƕ�ѡģʽ,������������Ҫ��ȫѡ�İ�ť
		if (tc.checkOption == '2' && tc.allCheck) {
			checkCell.innerHTML = "<input type='checkbox' style='width:20px;border:0px;' id='_checkAll' onclick='GridTree.prototype._chooseAll()'>";
		} else {
			jQuery(checkCell).text(tc.checkColumnDesc);
		}
		jQuery(checkCell).appendTo(newRow);
	}

	// ���������Ҫ�Զ���ʾÿ�е�����,��Ҫ��������һ����ʾ����
	if (tc.rowCount) {
		jQuery("<th>").text(tc.countColumnDesc).attr('id', 'countCell')
				.width('5%').addClass('tablehead').appendTo(newRow);
	}

	var i = 0;
	var lenlen = cms.length;
	// �����һ���������
	for (var ii = 0; ii < lenlen; ii++) {
		var oneColumn = cms[ii];
		var newCell = document.createElement("th");
		if (tc.columnModel[i].width != null && tc.columnModel[i].width != '*')
			newCell.style.width = tc.columnModel[i].width;
		var header = "";

		i = i + 1;

		jQuery(newCell).attr('id', oneColumn.headerIndex)
				.text(oneColumn.header).appendTo(newRow);
		if (tc.iconShowIndex == (i - 1)) {
			var explandAllMsg = $("<img id='explandAllMsg' title='���չ����������' style='CURSOR: hand'  src="
					+ tc.openImg + ">");
			explandAllMsg.toggle(function() {
						explandAllMsg.attr("src", tc.closeImg);
						explandAllMsg.attr("title", "���������������");
						GridTree.prototype.expandAll();
					}, function() {
						explandAllMsg.attr("src", tc.openImg);
						explandAllMsg.attr("title", "���չ����������");
						GridTree.prototype.closeAll();
					});
			jQuery(newCell).prepend(explandAllMsg);

		}
		headColumns.push(oneColumn.headerIndex);
	}
	tableHeadRow.appendChild(newRow);
}

/**
 * ���ȫ��ѡ��İ�ť
 */
GridTree.prototype._chooseAll = function() {
	if (_$('_checkAll').checked) {
		_checkedAll("datacb", true);
	} else {
		_checkedAll("datacb", false);
	}
}

/**
 * �õ���ǰҳ�ĵ�һ�е��ڵ�һ���ڵ��е�λ��
 */
GridTree.prototype._getFirstIndexInThisPage = function() {
	var _firstIndex = 0;
	// �������ҳ�Ļ�,˵����ǰҳ�ĵ�һ��λ�ÿ϶�����0��!
	if (tc.pageBar)
		_firstIndex = (pagingInfo.currentPage - 1) * pagingInfo.pageSize;
	return _firstIndex;
}

/**
 * �������������е�����
 */
GridTree.prototype._clearContent = function() {
	jQuery('#' + tc.tableId + ' tr[id]').each(function(i) {
				if (this.id != '_trpagebar' && this.id != '_trhead') {
					jQuery(this).remove();
				}
			});
}

/**
 * ��װ��ҳ��,����tFoot���� tableObj:������ index:��ҳ�����������
 */
GridTree.prototype._makePageBar = function(tableObj, index) {
	// ������ҳ���ľ�����Ϣ,��һ�����
	var pageBar = document.createElement('div');
	pageBar.setAttribute('className', 'main_foot_wraper');
	pageBar.setAttribute('id', tc.pageBarId);

	// pageBar.cellPadding = '0px';
	// pageBar.cellSpacing = '0px';

	pageBar.innerHTML = '<ul class="main_foot_menu"><li><img src="images/tab_pages_home.gif" width="16" height="16" vspace="5" '
			+ 'onclick="GridTree.prototype._toPage(\'first\')"/></li><li><img src="images/tab_pages_prev.gif" width="16" height="16" vspace="5" '
			+ 'onclick="GridTree.prototype._toPage(\'pre\')"/></li><li>��</li><li class="font"><div align="center"> '
			+ '<input name="textfield" type="text" class="txt" size="5" style="width:50px" '
			+ 'id="_pageNum" value="'
			+ pagingInfo.currentPage
			+ '"/></div></li><li>ҳ</li><li><img src="images/tab_pages_go.gif" '
			+ 'width="20" height="20" vspace="3" id="pageGo" onclick="GridTree.prototype._toPage()"/></li><li> <img src="images/tab_pages_next.gif" width="16" '
			+ 'height="16" vspace="5" onclick="GridTree.prototype._toPage(\'next\')"/></li> <li><img src="images/tab_pages_end.gif" '
			+ 'width="16" height="16" vspace="5" onclick="GridTree.prototype._toPage(\'last\')"/></li><li>'
			+ '<img src="images/tab_pages_line.gif" width="16" height="16" vspace="5"/></li>'
			+ '<li>��<span id="_pageCount">'
			+ pagingInfo.pagesCount
			+ '</span>ҳ</li><li><img src="images/tab_pages_line.gif" width="16" height="16" '
			+ 'vspace="5"/> </li><li>��<span id="_allCount"> '
			+ pagingInfo.allCount
			+ '</span>��</li><li> <img src="images/tab_pages_line.gif" width="16" '
			+ 'height="16" vspace="5"/></li> <li>ÿҳ��ʾ</li><li><div align="center">'
			+ '<select name="select" class="select" style="width:50px"><option>15</option>'
			+ '</select></div></li><li>��</li></ul>';
	$("#main_wraper").after(pageBar);
	$("#_pageNum").keydown(function(event) {
				if (event.keyCode == 13) {
					GridTree.prototype._toPage();
				}
			});
}

/**
 * �����µķ�ҳ�������»������
 * 
 * @param {��ҳ��Ŀ}
 *            pageSize
 */
GridTree.prototype._reMakeTable = function(pageSize) {
	if (pagingInfo.pagesCount != 0) {
		// �����ʹ�õĿͻ��˷�ҳ,�ܼ򵥾�ʵ����.
		if (_serverPagingMode == 'client') {
			// ÿҳ������
			pagingInfo.pageSize = pageSize;
			// �ܹ���ҳ��
			pagingInfo.pagesCount = Math.ceil(pagingInfo.allCount
					/ pagingInfo.pageSize * 1.0);
			// ��ǰҳ��(��1��ʼ����)
			pagingInfo.currentPage = 1;
			_$('msgCell').innerHTML = "��ǰ��" + pagingInfo.currentPage + "ҳ/�ܹ�"
					+ pagingInfo.pagesCount + "ҳ";
			this._toPage(1);
			_$(tc.tableId).focus();
		}
		// �����Ҫ�ߺ�̨.
		else {
			pagingInfo.pageSize = pageSize;
			this._toPage(1, "repaging");
			_$(tc.tableId).focus();
		}
	}
}

/**
 * ����json������ӵ�����������,��������ӵĵ�һ��ڵ�
 * 
 * @param {�������е�λ��}
 *            index
 * @param {json����}
 *            rowData
 * @param {�������(��һ����1)}
 *            level
 * @param {��ʾ��ǰ������ͬ���е�λ��}
 *            nth
 */
GridTree.prototype._addOneLazyRowByData = function(index, rowData, level, nth) {
	var newRow = document.createElement('tr');
	var classCss = ((index % 2) == 0) ? "tr_even" : "tr_odd";
	newRow.setAttribute('className', classCss);
	newRow.setAttribute('rowData', rowData);
	var _id = rowData[tc.idColumn];
	var _parent = rowData[tc.parentColumn];
	_parent = _parent == "" ? '-1' : _parent;// ��ֹ���ָ��׽ڵ�Ϊ�յ����
	var _isP = this.isParent(rowData);
	var pnode = GridTree.prototype.getRowObjById(_parent);
	_parent = '_node' + _parent;
	var parentRowNum = '';
	var parentPath = '';
	if (pnode) {
		parentRowNum = pnode.getAttribute('rownum') + '.';
		parentPath = pnode.getAttribute('_node_path') + ',';
	}
	if (!nth)
		nth = index;
	jQuery(newRow).attr({
				rownum : parentRowNum + nth,
				id : '_node' + _id,
				pid : rowData['id'],// ��ʵid
				icon : rowData['icon'],// ͼ����ʽ
				leaf : rowData['leaf'],
				_node_parent : _parent,
				_node_path : parentPath + _parent,
				_node_isparent : _isP,
				_node_level : level
			});

	$(newRow).dblclick(function() {
				if ($(this).attr('_open') == 'true') {
					GridTree.prototype.closeChildrenTable(this.id, this);
					$(this).attr('_open', 'false');
				} else if ($(this).attr('_open') == 'false') {
					GridTree.prototype.openChildrenTable(this.id, this, true);
					$(this).attr('_open', 'true');
				}
			});
	// ����Ǹ��׽ڵ�,������һ���Ƿ�򿪵ı�־
	if (_isP == '1')
		newRow.setAttribute('_open', 'false');
	this._userSetPros(rowData, newRow);
	this._addCheckOptionCell(rowData, newRow);
	this._addCountCell(rowData, newRow, index, level);
	this._addOneLazyRowContent(rowData, newRow, _id, _isP, level);
	this._addOneRowListerners(newRow);
	return newRow;
}

/**
 * ����һ������Ҫ���json�������һ�� tableObj:������ index:�������е�λ�� rowData:json����
 * level:�������(��һ����1)
 */
GridTree.prototype._addOneRowByData = function(tableObj, index, rowData, level) {
	// ���Ѿ��е�����֮������µ�һ��
	var newRow = tableObj.insertRow(index);
	var classCss = ((index % 2) == 0) ? "tr_even" : "tr_odd";
	newRow.setAttribute('className', classCss);
	var _id = rowData[tc.idColumn];
	var _parent = rowData[tc.parentColumn];
	var _isP = this.isParent(rowData);
	newRow.setAttribute('pid', rowData['id']);// ��ʵid
	// ���ø��е�id
	newRow.setAttribute('id', '_node' + _id);
	// ���ø��еĸ�����id
	_parent = _parent == "" ? '-1' : _parent;// ��ֹ���ָ��׽ڵ�Ϊ�յ����
	_parent = '_node' + _parent;
	newRow.setAttribute('_node_parent', _parent);
	// ����ǵ�һ��Ľڵ㣬��·��������Ϊ'-1'����ʾ�ǵ�һ��Ľڵ�
	if (level == 1)
		newRow.setAttribute('_node_path', '-1');
	// ������·��������Ϊ�ӵ�һ�㸸��һֱ���µ���ǰ�ڵ��·����ʽ
	else {
		newRow.setAttribute('_node_path', this.getNodePath('_node' + _id)
						.join(','));
	}
	newRow.setAttribute('_node_isparent', _isP);
	newRow.setAttribute('_node_level', level);
	newRow.setAttribute('class', 'tr_even');// tr_odd
	newRow.setAttribute('icon', rowData['icon']);
	newRow.setAttribute('rowData', rowData);

	// ����Ǹ��׽ڵ�,������һ���Ƿ�򿪵ı�־
	if (_isP == '1')
		newRow.setAttribute('_open', 'false');
	// ������ص����Ե��ж�����
	this._userSetPros(rowData, newRow);
	// Ϊÿһ�����ѡ����С�
	this._addCheckOptionCell(rowData, newRow);
	// ���������
	this._addCountCell(rowData, newRow, index, level);
	// ����еľ������ݡ�
	this._addOneRowContent(rowData, newRow, _id, _isP, level);
	// ��ÿһ������е��¼�����
	this._addOneRowListerners(newRow);
}

/**
 * ���������ģʽ�µ�һ�����ݵ���Ҫ����.
 * 
 * @param {������Դ}
 *            rowData
 * @param {�ж���}
 *            newRow
 * @param {idֵ}
 *            _id
 * @param {�Ƿ��׽ڵ�}
 *            _isP
 * @param {����}
 *            level
 */
GridTree.prototype._addOneLazyRowContent = function(rowData, newRow, _id, _isP,
		level) {
	// ��������һ������ĸ�����,��ʾ��˳���Ǹ�����columns������Ⱥ�˳��չʾ��.
	var i = 0;
	var iconShowIndex = tc.iconShowIndex ? tc.iconShowIndex : 0;
	for (; i < headColumns.length; i++) {
		var newSmallCell = document.createElement("td");
		newSmallCell.setAttribute('_td_pro', headColumns[i]);
		var _t = rowData[headColumns[i]];
		var renderFn = tc.columnModel[i].render;
		var columnModel = tc.columnModel[i];
		if (columnModel.align == 'left') {
			newSmallCell.setAttribute('className', 'main_td_align_left');
		} else if (columnModel.align == 'right') {
			newSmallCell.setAttribute('className', 'main_td_align_right');
		}
		// �ڵ�һ�н���ͼ�������
		if (i == iconShowIndex) {
			if (_t != '') {
				var ct = level - 1;
				var ans = [];
				for (var ii = 0; ii < ct; ii++) {
					ans.push('<IMG src="' + tc.blankImg + '"/>');
				}
				// ����Ǹ��׽ڵ�
				if (_isP == '1') {
					ans
							.push(
									"<IMG pid='",
									newRow.pid + "' id='img_" + _id,
									"' style='CURSOR: hand' onclick='javascript:GridTree.prototype.openChildrenTable(this.id,this);' src='"
											+ tc.openImg + "'/>");
				} else {
					if (!renderFn) {
						ans.push("<IMG id='img_" + _id + "' src='"
										+ tc.noparentImg, "'/>");
					}
				}
				newSmallCell.innerHTML = ans.join('');
				var showTypeDesc = columnModel.columntype;
				// �����һ��������������ʾ�������
				if (showTypeDesc != null) {
					newSmallCell
							.appendChild(GridTree.prototype._createContent(
									showTypeDesc, rowData, headColumns[i], _id).firstChild);
				} else {
					if (renderFn) {
						newSmallCell.innerHTML += renderFn(_t, newRow,
								newSmallCell, tc);
					} else {
						newSmallCell.innerHTML += _t;
					}

				}
			} else
				newSmallCell.innerHTML = '&nbsp;';
		}
		// �����о�ֱ���������ݼ���
		else {
			if (_t == '' || _t == undefined) {
				newSmallCell.innerHTML = '&nbsp;';
			} else {
				var showTypeDesc = columnModel.columntype;
				if (showTypeDesc != null) {
					newSmallCell.appendChild(GridTree.prototype._createContent(
							showTypeDesc, rowData, headColumns[i], _id));
				} else {
					if (renderFn) {
						newSmallCell.innerHTML += renderFn(_t, newRow,
								newSmallCell, tc);
					} else {
						newSmallCell.innerHTML += _t;
					}
				}
			}

		}

		newRow.appendChild(newSmallCell);
	}
}

/**
 * ����еľ�������
 * 
 * @param {������Դ}
 *            rowData
 * @param {�ж���}
 *            newRow
 * @param {idֵ}
 *            _id
 * @param {�Ƿ��׽ڵ�}
 *            _isP
 * @param {����}
 *            level
 */
GridTree.prototype._addOneRowContent = function(rowData, newRow, _id, _isP,
		level) {
	// ��������һ������ĸ�����,��ʾ��˳���Ǹ�����columns������Ⱥ�˳��չʾ��.
	var i = 0;
	for (; i < headColumns.length; i++) {
		var newSmallCell = newRow.insertCell(newRow.cells.length);
		var columnModel = tc.columnModel[i];
		var renderFn = tc.columnModel[i].render;
		if (columnModel.align == 'left') {
			newSmallCell.setAttribute('className', 'main_td_align_left');
		} else if (columnModel.align == 'right') {
			newSmallCell.setAttribute('className', 'main_td_align_right');
		}
		newSmallCell.setAttribute('_td_pro', headColumns[i]);
		var _t = rowData[headColumns[i]];
		// �ڵ�һ�н���ͼ�������
		if (i == 0) {
			if (_t != '') {
				// ��������������������Ĵ�С
				var ct = level - 1;
				var ans = [];
				for (var ii = 0; ii < ct; ii++) {
					ans.push('<IMG src="' + tc.blankImg + '"/>');
				}

				// ����Ǹ��׽ڵ�
				if (_isP == '1') {
					ans
							.push(
									"<IMG pid='"
											+ newRow.pid
											+ "' id='img_"
											+ _id
											+ "' style='CURSOR: hand' onclick='javascript:GridTree.prototype.openChildrenTable(this.id,this);' src='"
											+ tc.openImg, "'/>");
					// ��Ȼ�Ǹ��׽ڵ�,���ǲ��ڵ�һ��ڵ���,���Բ�Ӧ����ʾ����
					if (findInArray(firstLevelNodes, '_node' + _id) == -1) {
						newRow.style.display = 'none';
					}
				} else {
					// ������ǵ�һ��ڵ�Ҫ��ʾ��������Ҷ,������Ϊ������(��Ϊ�ڵ�һ��ڵ�Ҫ��ʾ�����Ĺ����ڵ����ɼ�)
					if (findInArray(firstLevelNodes, '_node' + _id) == -1) {
						if (!renderFn) {
							ans.push(["<IMG id='img_", _id, "' src='",
									tc.noparentImg, "'/>"].join(''));
						}
						newRow.style.display = 'none';
					}
					// ���ڹ����ڵ�(�ڵ�һ����û�и���Ҳû�к��ӵĽڵ�)��������ʾ.��Ҫ��ͼ���ļ���ʾ����,����û������Ϊ���ɼ�.
					else {
						if (!renderFn) {
							ans.push("<IMG id='img_" + _id + "' src='",
									tc.noparentImg + "'/>");
						}
					}
				}

				newSmallCell.innerHTML = ans.join('');
				var showTypeDesc = columnModel.columntype;
				// �����һ��������������ʾ�������
				if (showTypeDesc != null) {
					newSmallCell
							.appendChild(GridTree.prototype._createContent(
									showTypeDesc, rowData, headColumns[i], _id).firstChild);
				} else {
					if (renderFn) {
						newSmallCell.innerHTML += renderFn(_t, newRow,
								newSmallCell, tc);
					} else {
						newSmallCell.innerHTML += _t;
					}
				}
			} else
				newSmallCell.innerHTML = '&nbsp;';
		}
		// �����о�ֱ���������ݼ���
		else {
			if (_t == '' || _t == undefined) {
				newSmallCell.innerHTML = '&nbsp;';
			} else {
				var showTypeDesc = columnModel.columntype;
				if (showTypeDesc != null) {
					newSmallCell.appendChild(GridTree.prototype._createContent(
							showTypeDesc, rowData, headColumns[i], _id));
				} else {
					if (renderFn) {
						newSmallCell.innerHTML += renderFn(_t, newRow,
								newSmallCell, tc);
					} else {
						newSmallCell.innerHTML += _t;
					}
				}
			}
		}
	}
}

/**
 * ��ָ����ҳ
 * 
 * @param {ҳ��}
 *            num(�ں�̨��ҳģʽ��ʱ��,���������ʾ��ǰҳ��,�����ǰ̨��ҳģʽ,���ݵ����µ�ҳ��)
 * @param {����ҳ��Ĳ�����ʽ}
 *            operCode(�ں�̨��ҳģʽ��ʱ�������������)
 */
GridTree.prototype._toPage = function(operCode) {
	var pnum = _$('_pageNum'), mdiv = _$('_msgDiv'), mcel = _$('msgCell'), tb = _$(tc.tableId);

	switch (operCode) {
		case 'first' :
			pagingInfo.currentPage = 1;
			break;
		case 'pre' :
			pagingInfo.currentPage = (pagingInfo.currentPage - 1) < 1
					? 1
					: (pagingInfo.currentPage - 1);
			break;
		case 'next' :
			tempPage = pagingInfo.currentPage * 1 + 1;
			pagingInfo.currentPage = (tempPage) > pagingInfo.pagesCount
					? pagingInfo.pagesCount
					: tempPage;
			break;
		case 'last' :
			pagingInfo.currentPage = pagingInfo.pagesCount;
			break;
		default :
			var n = pnum.value;
			if ((/\D/.test(n))) {
				alert('ҳ�����Ϊ���������֣�');
				pnum.focus();
				pnum.value = ''
				return;
			} else if (n == '' || n > pagingInfo.pagesCount) {
				alert("����ҳ���������Ϊ�գ����������룡");
				pnum.focus();
				pnum.value = ''
				return;
			} else {
				n = parseInt(n);
				pnum.value = n;
			}
			pagingInfo.currentPage = n;
			break;
	}
	var num = pagingInfo.currentPage;

	// �����ǰ̨��ҳ
	if (_serverPagingMode == 'client') {
		pagingInfo.currentPage = num;
		var end = pagingInfo.currentPage * pagingInfo.pageSize > pagingInfo.allCount
				? pagingInfo.allCount
				: pagingInfo.currentPage * pagingInfo.pageSize;
		GridTree.prototype._showTable(document.getElementById(tc.tableId),
				(pagingInfo.currentPage - 1) * pagingInfo.pageSize, end);
		mcel.innerHTML = ["��ǰ��", pagingInfo.currentPage, "ҳ/�ܹ�",
				pagingInfo.pagesCount, "ҳ"].join('');
		/** ****** ���Ƿ�������չ��ȫ�� ********* */
		if (tc.expandAll) {
			GridTree.prototype.expandAll();
		}
		pnum.value = '';
		// ��������ͼƬ��ť����ʽ
		this._resetPageBtns();
		// �������õ�˫����ɫ
		if (tc.exchangeColor)
			this._setColor(tb);
	} else {
		// ��ҳ��ʱ�򴫵ݵ���ֻ̨����ʼλ�ú�ÿҳ��С��������.
		// var param = {
		// idColumn : tc.idColumn,
		// analyze : tc.analyzeAtServer,
		// parentColumn : tc.parentColumn,
		// limit : pagingInfo.pageSize,
		// page : pagingInfo.currentPage
		// };
		tc.param.analyze = tc.analyzeAtServer;
		tc.param.limit = pagingInfo.pageSize;
		tc.param.page = pagingInfo.currentPage == 0
				? 1
				: pagingInfo.currentPage;
		GridTree.prototype._showMsg(1);
		jQuery.ajax({
					type : "POST",
					url : tc.dataUrl,
					data : tc.param,
					async : 1,
					success : function(msg) {
						var o = new Date();
						mdiv.innerText = '���ڻ������...';
						GridTree.prototype._newPagingServerMakeTable(tb, msg);
						GridTree.prototype._showMsg(0);
						// mcel.innerHTML = ["��ǰ��", pagingInfo.currentPage,
						// "ҳ/�ܹ�", pagingInfo.pagesCount, "ҳ"].join('');
						pnum.value = pagingInfo.currentPage;

						if (tc.explandAll == true) {
							GridTree.prototype.expandAll();
						}
						// var gotime = new Date() - o;
						// GridTree.prototype._wirteDebug('��ҳ��ʾǰ̨����ʱ��:' +
						// gotime);
					}
				});
	}
}

/**
 * ����е��Զ�������
 * 
 * @param {������Դ}
 *            rowData
 * @param {�ж���}
 *            newRow
 */
GridTree.prototype._userSetPros = function(rowData, newRow) {
	// ���ø�����������Զ������������
	if (tc.hidddenProperties) {
		for (var i = 0; i < tc.hidddenProperties.length; i++) {
			var proName = tc.hidddenProperties[i];
			newRow.setAttribute(proName, rowData[proName]);
		}
	}
}
/**
 * ����Զ��������չʾ�С�
 * 
 * @param {������Դ}
 *            rowData
 * @param {�ж���}
 *            newRow
 * @param {�����е�λ������}
 *            index
 */
GridTree.prototype._addCountCell = function(rowData, newRow, index, level) {
	var _id = rowData[tc.idColumn];
	var _parent = rowData[tc.parentColumn];
	_parent = _parent == "" ? '-1' : _parent;// ��ֹ���ָ��׽ڵ�Ϊ�յ����
	_parent = '_node' + _parent;
	// ���������Ҫ�Զ���ʾÿ�е�����,��Ҫ��������һ����ʾ����
	if (tc.rowCount) {
		var countCell = document.createElement("td");
		countCell.className = 'countCell';
		var indexNum = 0;
		// ȫ��������ͳһ��������������
		if (tc.rowCountOption + '' == '3') {
			if (!tc.lazy)
				indexNum = index;
			else
				indexNum = newRow.getAttribute('rownum');
		} else if (tc.rowCountOption + '' == '2') {
			// ������еĸ��׽ڵ��ڸ��׽ڵ㼯���У�˵�����п϶������ǵڶ����Ľڵ�����ǵ�һ��ġ�
			if (findInArray(parents, _parent) != -1) {
				var brothers = this.seeChildren(_parent);
				// �õ������ڵ������ֵ
				var parentIndex = _idToNumMap.get(_parent);
				indexNum = parentIndex + '.'
						+ (1 + findInArray(brothers, '_node' + _id));
				// �������ڵ�����ֵ+���+��ǰ���������ֵ
				newRow.setAttribute('_node_num', indexNum);
				_idToNumMap.put('_node' + _id, indexNum);
			} else {
				indexNum = 1 + findInArray(firstLevelNodes, '_node' + _id);
				// _node_num��¼���������ڼ����е�����ֵ
				newRow.setAttribute('_node_num', indexNum);
				_idToNumMap.put('_node' + _id, indexNum);
			}
		} else {
			// ������еĸ��׽ڵ��ڸ��׽ڵ㼯���У�˵�����п϶������ǵڶ����Ľڵ�����ǵ�һ��ġ�
			if (findInArray(parents, _parent) != -1) {
				var brothers = this.seeChildren(_parent);
				// �õ������ڵ������ֵ
				var parentIndex = _idToNumMap.get(_parent);
				indexNum = parentIndex + '.'
						+ (1 + findInArray(brothers, '_node' + _id));
				// �������ڵ�����ֵ+���+��ǰ���������ֵ
				newRow.setAttribute('_node_num', indexNum);
				_idToNumMap.put('_node' + _id, indexNum);
			} else {
				indexNum = 1 + findInArray(firstLevelNodes, '_node' + _id);
				// ����ǿͻ��˷�ҳ,��Ҫ�ټ�ȥ��ҳ��һ�е�����ֵ.
				if (_serverPagingMode == 'client') {
					indexNum = indexNum - this._getFirstIndexInThisPage();
				}
				newRow.setAttribute('_node_num', indexNum);
				_idToNumMap.put('_node' + _id, indexNum);
			}
		}
		jQuery(countCell).text(indexNum).appendTo(newRow);
	}
}

/**
 * ��ÿһ�����ѡ����С������û�����þͲ�������
 * 
 * @param {������Դ}
 *            rowData
 * @param {�ж���}
 *            newRow
 */
GridTree.prototype._addCheckOptionCell = function(rowData, newRow) {
	var _id = rowData[tc.idColumn];
	// ���Ƿ�������Ҫ����ǰ�˵�ѡ��ť�Ƿ��������.
	var setOptionDisabled = '';
	if (tc.disableOptionColumn) {
		if (rowData[tc.disableOptionColumn] + '' == '1') {
			setOptionDisabled = 'disabled';
		}
	}

	// �����Ƿ�������Ĭ��Ҫ���ж�ѡ���ѡ�е���������.
	var setChoosedColumn = tc.chooesdOptionColumn;
	var defalutChoose = 0;
	// ֻ���Ƕ�ѡ���ģʽ����ŵõ�Ĭ��ѡ���ֵ����.����ѡҲ���ԣ���
	if (tc.checkOption == 2 && setChoosedColumn != null) {
		defalutChoose = rowData[setChoosedColumn];
		if (defalutChoose + '' == '1') {
			defalutChoose = 'checked';
		} else {
			defalutChoose = '';
		}
	}

	// ���������ѡ���оͼ�һ����������ѡ��ť,1Ϊ��ѡ��2Ϊ��ѡ
	if (tc.checkOption == '1') {
		var checkCell = document.createElement("td");
		checkCell.className = 'checkCell';
		checkCell.setAttribute('id', '_chk' + _id);
		createRadio(checkCell, setOptionDisabled, 'width:20px;border:0px;',
				'datacb', _id, tc.handleCheck, '', '', '');
		jQuery(checkCell).appendTo(newRow);
	} else if (tc.checkOption == 2) {
		var checkCell = document.createElement("td");
		checkCell.className = 'checkCell';
		checkCell.setAttribute('id', '_chk' + _id);
		// ѡ��ģʽΪ5,A����ѡ���ײ��Զ�ѡ��,B���Ӷ�û��ѡ�������Զ���ѡ��,C����ѡ����ȫ��ѡ��;D����ȡ��,����ȫ����ѡ��.
		if (tc.multiChooseMode == 5) {
			createCheckbox(checkCell, setOptionDisabled,
					'width:20px;border:0px;', 'datacb', _id, function() {
						GridTree.prototype._chooseChildrenNode(this);
						GridTree.prototype._cancleChildrenNode(this);
						GridTree.prototype._chooseParentNode(this);
						GridTree.prototype._cancelFaher(this);
						if (tc.handleCheck)
							tc.handleCheck();
					}, '', '', defalutChoose);
		} else if (tc.multiChooseMode == 4) {
			createCheckbox(checkCell, setOptionDisabled,
					'width:20px;border:0px;', 'datacb', _id, function() {
						GridTree.prototype._chooseChildrenNode(this);
						GridTree.prototype._cancleChildrenNode(this);
						GridTree.prototype._cancelFaher(this);
						if (tc.handleCheck)
							tc.handleCheck();
					}, '', '', defalutChoose);
		}
		// ѡ��ģʽΪ3,A����ѡ���ײ��Զ�ѡ��,B���Ӷ�û��ѡ�������Զ���ѡ��,C����ѡ����ȫ��ѡ��.
		else if (tc.multiChooseMode == 3) {
			createCheckbox(checkCell, setOptionDisabled,
					'width:20px;border:0px;', 'datacb', _id, function() {
						GridTree.prototype._chooseChildrenNode(this);
						GridTree.prototype._chooseParentNode(this);
						GridTree.prototype._cancelFaher(this);
						if (tc.handleCheck)
							tc.handleCheck();
					}, '', '', defalutChoose);
		}
		// ѡ��ģʽΪ2,A����ѡ�����Զ�ѡ��,B���Ӷ�û��ѡ�����Զ���ѡ��,C����ѡ��ͺ����Ƿ�ѡ���޹�.
		else if (tc.multiChooseMode == 2) {
			createCheckbox(checkCell, setOptionDisabled,
					'width:20px;border:0px;', 'datacb', _id, function() {
						GridTree.prototype._chooseParentNode(this);
						GridTree.prototype._cancelFaher(this);
						if (tc.handleCheck)
							tc.handleCheck();
					}, '', '', defalutChoose);
		}
		// ѡ��ģʽΪ1.
		else {
			createCheckbox(checkCell, setOptionDisabled,
					'width:20px;border:0px;', 'datacb', _id, tc.handleCheck,
					'', '', defalutChoose);
		}

		jQuery(checkCell).appendTo(newRow);
	}
}

/**
 * �Ա������ÿһ������¼�����
 * 
 * @param {�ж���}
 *            newRow
 */
GridTree.prototype._addOneRowListerners = function(newRow) {
	// ����Ϊÿ������Զ�����д����¼�.����������Զ����¼��ķ���,�ͽ�������Ĵ���.
	if (tc.handler) {
		var lenlen = tc.handler.length;
		for (var i = lenlen - 1; i >= 0; i--) {
			GridTree.prototype._addEventToObj(newRow, tc.handler[i]);
		}
		// �ڵ�һ�ν����¼���ӵ�ʱ��,���û�������¼�ע�ᵽһ���¼��ļ�����.
		// Ȼ�����������Ͽ���û�ж���onclick,onmouse,onmouseover����,���û�ж���Ͳ���Ĭ�ϵ�ʵ��.
		if (!_usHandler.length) {
			for (var i = lenlen - 1; i >= 0; i--) {
				for (eventName in tc.handler[i])
					_usHandler.push(eventName);
			}
			if (findInArray(_usHandler, 'onclick') == -1) {
				_usnoclick = 1;
			}
			if (findInArray(_usHandler, 'onmouseout') == -1) {
				_usnomsout = 1;
			}
			if (findInArray(_usHandler, 'onmouseover') == -1) {
				_usnomsover = 1;
			}
		}
		// ���û�û�ж�����ЩĬ�ϵķ���,�Ͳ���Ĭ��ʵ��.
		if (_usnoclick) {
			newRow.onclick = function() {
				if (_$(_lastSelectRowId)) {
					jQuery(_$(_lastSelectRowId)).removeClass("selectlight");
				}
				jQuery(newRow).addClass("selectlight");
				_lastSelectRowId = newRow.id;
			};
		}
		if (_usnomsover) {
			newRow.onmouseout = function() {
				jQuery(newRow).removeClass('highlight')
			};
		}
		if (_usnomsout) {
			newRow.onmouseover = function() {
				jQuery(newRow).addClass('highlight')
			};
		}
	} else {
		// ��ӽڵ��������Ƴ�����ʽ
		newRow.onmouseover = function() {
			jQuery(newRow).addClass('highlight')
		};

		newRow.onmouseout = function(s) {
			jQuery(newRow).removeClass('highlight')
		};

		newRow.onclick = function() {
			if (_$(_lastSelectRowId)) {
				jQuery(_$(_lastSelectRowId)).removeClass("selectlight");
			}
			jQuery(newRow).addClass("selectlight");
			_lastSelectRowId = newRow.id;
		};
		// �Ҽ��˵�
		if (tc.showMenu == true && tc.contextMenu != null) {
			$(newRow).contextmenu(tc.contextMenu);
		}

	}
}

/**
 * У��ajax�����ַ�������û����Ҫ��idColumn,parentColumn����.
 * 
 * @param {ajax���ص��ַ���}
 *            msg
 * @return true��ʾ���ص�ajax������������ȷ,���򷵻�false
 */
GridTree.prototype._verifyAjaxAns = function(msg) {
	eval(" tempData=" + msg);
	var data = tempData.data != 'undefined' ? tempData.data : tempData;
	// ��֤����ı�ʾ�е�ֵ�Ƿ��ڡ�data�������д���
	var columnName = tc['idColumn'];
	if (typeof data[0][columnName] == 'undefined') {
		alert("���õ�����[idColumn]ֵ����,����!");
		return false;
	}
	// ��֤����ĸ����е�ֵ�Ƿ��ڡ������ԡ��д���
	var columnName = tc['parentColumn'];
	if (typeof data[0][columnName] == 'undefined') {
		alert("���õ�����[parentColumn]ֵ����,����!");
		return false;
	}
	return true;
}
/**
 * ��ָ���ڵ�ĺ��� nodeid:�ڵ� return:���ӽڵ�id��ɵ�����.����ǰ׺'_node'
 */
GridTree.prototype.seeChildren = function(nodeid) {
	var ansArr = parentToChildMap.get(nodeid);
	return ansArr;
}

/**
 * �ж�һ���ڵ��ǲ��Ǹ��׽ڵ㣨�Ǹ��׽ڵ�ͷ���'1',���򷵻ء�0���� rowobj��Ҫ�жϵĽڵ����
 */
GridTree.prototype.isParent = function(rowobj) {
	if (!tc.lazy) {
		var nid = '_node' + rowobj[tc.idColumn];
		if (findInArray(parents, nid) != -1) {
			return '1';
		} else {
			return '0';
		}
	} else {
		return rowobj[tc.leafColumn] == 0 ? '1' : '0';
	}
}

/**
 * ȫ��չ��
 */
GridTree.prototype.expandAll = function() {
	// û��չ���Ľڵ�,ȫ�������
	jQuery('#' + tc.tableId + ' tr[_open=false]').each(function(i) {
				// var nodeId = this.id.replace('_node', '');
				// jQuery('#img_' + nodeId).click();
				GridTree.prototype.openChildrenTable(this.id, this, true);
				this._open = 'true';
			});
}

/**
 * �ر�ȫ���ı�����ڵ�
 */
GridTree.prototype.closeAll = function() {
	// �Ѿ�չ���Ľڵ�,ȫ���������
	jQuery('#' + tc.tableId + ' tr[_open=true]').each(function(i) {
				// var nodeId = this.id.replace('_node', '');
				// jQuery('#img_' + nodeId).click();
				GridTree.prototype.closeChildrenTable(this.id, this);
				this._open = 'false';
			});
}

/**
 * ���ñ�����ı༭״̬.
 * 
 * @param {״̬}
 *            val(true��ȫ�����û���falseȫ������)
 */
GridTree.prototype.setDisabled = function(val) {
	var tabregion = jQuery('.tableRegion');
	// �����true��ȫ������.
	if (val) {
		jQuery('input', tabregion).attr('disabled', 'true');
		jQuery('button', tabregion).attr('disabled', 'true');
		jQuery('select', tabregion).attr('disabled', 'true');
	}
	// ����ȫ������.������Щ�����˲��������Ե�.
	else {
		if (isIE) {
			jQuery('input', tabregion).each(function(i) {
						if (this.userSetDisabled != 'disabled') {
							jQuery(this).removeAttr('disabled');
						}
					});
			jQuery('button', tabregion).each(function(i) {
						if (this.userSetDisabled != 'disabled') {
							jQuery(this).removeAttr('disabled');
						}
					});
			jQuery('select', tabregion).each(function(i) {
						if (this.userSetDisabled != 'disabled') {
							jQuery(this).removeAttr('disabled');
						}
					});
		} else {
			// �����ڻ���е���������ĩ����Сд��!!����������
			jQuery('input', tabregion).each(function(i) {
						if (this.usersetdisabled != 'disabled') {
							jQuery(this).attr('disabled', false);
						}
					});
			jQuery('button', tabregion).each(function(i) {
						if (this.usersetdisabled != 'disabled') {
							jQuery(this).attr('disabled', false);
						}
					});
			jQuery('select', tabregion).each(function(i) {
						if (this.usersetdisabled != 'disabled') {
							jQuery(this).attr('disabled', false);
						}
					});
			jQuery('input[usersetdisabled=disabled]', tabregion).each(
					function(i) {
						this.disabled = true;
					});
			jQuery('button[usersetdisabled=disabled]', tabregion).each(
					function(i) {
						this.disabled = true;
					});
			jQuery('select[usersetdisabled=disabled]', tabregion).each(
					function(i) {
						this.disabled = true;
					});
		}
	}
}

/**
 * �õ�ָ���Ľڵ�ĸ����ڵ������_node_num��������
 * 
 * @param {�ڵ�id}
 *            nid return ���ظ��׽ڵ��_node_num�����Լ���
 */
GridTree.prototype.getSelectedRow = function() {
	return _$(_lastSelectRowId);
}

/**
 * ���ݽڵ�id�õ��ж���
 * 
 * @param {�ڵ�id,������ǰ׺}
 *            nid
 */
GridTree.prototype.getRowObjById = function(nid) {
	return _$('_node' + nid);
}

/**
 * �õ�ָ���ڵ�ĸ��׵�·���� nid���ڵ�id �����丸�ף��游һֱ������ڵ��id��ɵ�һ������
 */
GridTree.prototype.getNodePath = function(nid) {
	// ���еĸ���id��ɵļ���
	var allParents = [];
	if (!tc.lazy) {
		while (1) {
			var pId;
			pId = childToFatherMap.get(nid);
			if (pId != null) {
				allParents.push(pId.replace('_node', ''));
				if (findInArray(parents, pId) != -1) {
					nid = pId;
				} else {
					break;
				}
			} else {
				break;
			}
		}
		return allParents.reverse();
	}
}

/**
 * ���һ��ͼ��,�����ӽڵ�
 */
GridTree.prototype.openChildrenTable = function(imgid, node, lazyExpandAll) {
	var _id = imgid.replace('img_', '').replace('_node', '');
	var img = $('#img_' + _id);
	if (img[0]) {
		img[0].onclick = function(ee) {
			GridTree.prototype.closeChildrenTable(imgid, node)
		};
	}
	if (!tc.lazy) {
		jQuery('tr[_node_parent=_node' + _id + ']').each(function(i) {
					if (this.getAttribute('_node_isparent') == '1') {
						GridTree.prototype.openChildrenTable(this.id, this);
					}
					jQuery(this).show();
				});
		_$('_node' + _id)._open = 'true';
		jQuery('tr[_node_parent=_node' + _id + ']').show();
		img.attr("src", tc.closeImg);
		if (tc.exchangeColor)
			this._setColor(_$(tc.tableId));
	}
	// �����������ģʽ,��Ҫ���ݵ�ǰ�ڵ��id����̨��ѯ�ӽڵ�ļ���,��������װ������
	else {
		var param = {

		};
		var parentNode = _$('_node' + _id);
		if (tc.postProperties) {
			for (var i = 0; i < tc.postProperties.length; i++) {
				var v = parentNode.getAttribute(tc.postProperties[i]);
				if (v != undefined) {
					param[tc.postProperties[i]] = v;
				}
			}
		}
		param.parentId = node.pid;
		img.attr("src", tc.lazyLoadImg);
		// �����ǰ���ڵ�û�б��򿪹�,��Ҫ����̨��ѯ�ӽڵ�
		if (parentNode.getAttribute('_open') != 'true'
				&& parentNode.getAttribute('_expaned') != 'true') {
			jQuery.ajax({
				type : "GET",
				url : tc.lazyLoadUrl,
				async : false,
				data : param,
				success : function(msg) {
					if (msg != null && msg != "") {
						try {
							var o = new Date();
							var level = parseInt(parentNode
									.getAttribute('_node_level'))
									+ 1;
							var tableTree = _$(tc.tableId);
							var rownumstr = parentNode.getAttribute('rownum')
									.split('.');
							var startIndex = 1;
							for (var i = 0, j = rownumstr.length; i < j; i++) {
								startIndex += parseInt(rownumstr[i]);
							}
							eval('tc.repaintDataInfoDatas = ' + msg);
							var datas = tc.repaintDataInfoDatas;
							for (var i = datas.length - 1; i >= 0; i--) {
								// ������������:����һ��_nth��־��ǰ�ǵڼ�������
								var newRow = GridTree.prototype
										._addOneLazyRowByData(startIndex,
												datas[i], level, i + 1);
								jQuery(parentNode).after(newRow);
								startIndex++;
							}
							jQuery(parentNode).attr('_expaned', 'true');
							if (tc.exchangeColor)
								GridTree.prototype._setColor(tableTree);
							img.attr("src", tc.closeImg);
							var gotime = new Date() - o;
							GridTree.prototype._wirteDebug('��������ʾǰ̨����ʱ��:'
									+ gotime);
							if (tc.onLazyLoadSuccess)
								tc.onLazyLoadSuccess(elct);
							if (lazyExpandAll == true) {
								jQuery('tr[_node_parent=_node' + _id + ']')
										.each(function(i) {
											if (this
													.getAttribute('_node_isparent') == '1') {
												GridTree.prototype
														.openChildrenTable(
																this.id, this,
																true);
											}
											jQuery(this).show();
										});
							}
						} catch (e) {
							// û�����ݣ�ȥ��+ͼ��
							img.css("visibility", 'hidden');
							// GridTree.prototype
							// ._makeTableWithNoData(tableTree);
						}
					} else {
						$(elct).find('TBODY').append('<tr><td colspan="100">���������Ϣ</td></tr>');
					}
				}
			});
		} else {
			jQuery('tr[_node_parent=_node' + _id + ']').each(function(i) {
						if (this.getAttribute('_node_isparent') == '1') {
							GridTree.prototype.openChildrenTable(this.id, this);
						}
						jQuery(this).show();
					});
			jQuery('tr[_node_parent=_node' + _id + ']').show();
			if (tc.exchangeColor)
				this._setColor(_$(tc.tableId));
			img.attr("src", tc.closeImg);
			if (isIE)
				stopBubble();
			else {
				stopBubble(e);
			}
		}
	}
}

/**
 * ���һ��ͼ��,�ر����ӽڵ�
 * 
 * @param {ͼ��id}
 *            imgid
 * @param {}
 *            node
 */
GridTree.prototype.closeChildrenTable = function(imgid, node) {
	var _id = imgid.replace('img_', '').replace('_node', '');
	var img = $('#img_' + _id);
	img.attr("src", tc.openImg);
	if (img[0]) {
		img[0].onclick = function(ee) {
			GridTree.prototype.openChildrenTable(imgid, node)
		};
	}
	_$('_node' + _id)._open = 'false';
	// �ҵ����׽ڵ��ǵ�ǰ�ڵ�Ķ���,����Ϊ���ɼ�.������Щ�ڵ���������ӽڵ�Ҳ�ݹ���е���.
	jQuery('tr[_node_parent=_node' + _id + ']').each(function(i) {
				if (this.getAttribute('_node_isparent') == '1') {
					GridTree.prototype.closeChildrenTable(this.id, this);
				}
				this.style.display = 'none';
			});
	if (tc.exchangeColor)
		this._setColor(_$(tc.tableId));
}

/**
 * ѡ�����ڵ�.
 * 
 * @param {�ڵ�id,������ǰ׺}
 *            nodeId
 */
GridTree.prototype.select = function(nodeId) {
	var obj = jQuery('input[name=datacb][value=' + nodeId + ']')[0];
	if (isIE) {
		if (_notBindDisabled(obj)) {
			jQuery(obj).attr('checked', 'true');
		}
		stopBubble();
	} else {
		if (obj.getAttribute('userSetDisabled') == null
				|| obj.getAttribute('userSetDisabled') != 'disabled') {
			jQuery(obj).attr('checked', 'true');
		}
		stopBubble(window.Event);
	}
}

/**
 * ʵ���������
 * 
 * @param {}
 *            ary
 * @return {}
 */
function toArray(ary) {
	var result = new Array(ary.length);
	for (var i = 0; i < ary.length; i++) {
		result[i] = ary[i]
	}
	return result;
}

/**
 * ����bind����Ϊfunction�Զ����ٶ���Զ���Ĳ����������µĺ������������¼��İ󶨷����д��ݲ�����������
 * 
 * @return {}
 */
Function.prototype.bind = function() {
	var args = toArray(arguments);
	var owner = args.shift();
	var _this = this;
	return function(owner) {
		return _this.apply(owner, args.concat(toArray(arguments)));
	}
}

/**
 * ��json��map����ת��Ϊ�����е�hashMap����
 * 
 * @param {json��map����}
 *            jsonMap
 */
function jsonMapToJsHashMap(jsonMap) {
	var mapObj = new HashMap();
	for (var obj in jsonMap) {
		mapObj.put(obj, jsonMap[obj]);
	}
	return mapObj;
}

/**
 * ��һ�������м�ȥ����һ������ arr1:����1 arr2:����2 ����:arr1 - arr2��������
 */
function removeArrayFromOtherArray(arr1, arr2) {
	var tempArr = [];
	var bingo = [];
	var len1 = arr2.length;
	for (var ii = 0; ii < len1; ii++) {
		bingo.push(findInArray(arr1, arr2[ii]));
	}
	var len2 = arr1.length;
	for (var ii = 0; ii < len2; ii++) {
		if (findInArray(bingo, ii) == -1) {
			tempArr.push(arr1[ii]);
		}
	}
	return tempArr;
}

/**
 * ��һ����������ָ����Ԫ�� arr:������� obj:Ҫ���ҵĶ��� ����ֵ:����ҵ��ͷ��������е�λ��(��0��ʼ),����ͷ���-1
 */
function findInArray(arr, obj) {
	var ans = -1;
	var len = arr.length;
	for (var i = 0; i < len; i++) {
		if (arr[i] == obj) {
			ans = i;
			return ans;
		}
	}
	return -1;
}

/**
 * ���ƶ����ѡ��ť��ѡ��״̬ checkName:��ѡ��ť��name v:���õ�ֵ
 */
function _checkedAll(checkName, v) {
	var objs = document.getElementsByName(checkName);
	var len = objs.length;
	if (isIE) {
		for (var i = 0; i < len; i++) {
			// �ų���Щ�ж������Ƿ��̨ѡ��Ľڵ�.
			if (objs[i].userSetDisabled == null
					|| objs[i].userSetDisabled != 'disabled')
				objs[i].checked = v;

		}
	} else {
		for (var i = 0; i < len; i++) {
			// �ų���Щ�ж������Ƿ��̨ѡ��Ľڵ�.
			if (objs[i].getAttribute('userSetDisabled') == null
					|| objs[i].getAttribute('userSetDisabled') != 'disabled')
				objs[i].checked = v;

		}
	}
}

/**
 * �ж�һ��Ԫ���Ƿ��Զ�����Ϊ������. ���û��userSetDisabled���Ի����������Ϊdisabled.�ͷ���true, ���򷵻�false.
 * 
 * @param {Ԫ�ض���}
 *            o
 */
function _notBindDisabled(o) {
	return jQuery(o).attr('userSetDisabled') != 'disabled';
}

/**
 * ����ָ��Ԫ�ز����û��߿��� obj:Ԫ�� val:������(1)���߿���(0)
 */
function disableDom(obj, val) {
	if (obj)
		obj.disabled = val;
}

/**
 * ����ָ��name��Ԫ���Ƿ����
 * 
 * @param {name����}
 *            domName
 * @param {������(1)���߿���(0)}
 *            val
 */
function disableDomByName(domName, val) {
	jQuery('[name=' + domName + ']').each(function() {
				if (jQuery(this).attr('userSetDisabled') != 'disabled') {
					jQuery(this).attr('disabled', val);
				}
			});
}

/**
 * ��ֹjs�¼���ð��
 */
function stopBubble(e) {
	// һ��������������¼���
	if (!isIE) {
		e.preventDefault();
		e.stopPropagation();
	} else {
		// IEȡ��ð���¼�
		window.event.cancelBubble = true;
	}
}

/**
 * �õ�ѡ���id�е�id
 */
function getAllCheckValue() {
	var ans = '';
	jQuery('[name=datacb]:checked').each(function() {
				ans += this.value + ',';
			});
	return ans.substring(0, ans.length - 1);
}

/**
 * ��̬����һ��radio�ķ���
 * 
 * @param {�������ϼ�domԪ��}
 *            el
 * @param {�Ƿ����}
 *            dis
 * @param {��ʽ�ı�}
 *            sty
 * @param {��������}
 *            name
 * @param {ֵ����}
 *            val
 * @param {�����¼�}
 *            click
 * @param {innerTextֵ}
 *            innertext
 * @param {��ʽ��}
 *            cssname
 * @param {�Ƿ�Ĭ��ѡ��}
 *            chk
 */
function createRadio(el, dis, sty, name, v, click, t, cssname, chk) {
	jQuery(el).append(jQuery("<input type='radio' name='" + name + "'>").attr({
				'style' : sty,
				'userSetDisabled' : dis,
				'value' : v,
				'disabled' : dis,
				'checked' : chk
			}).addClass(cssname).click(click)).append(t);
}

/**
 * ��̬����һ����ѡ��ť
 * 
 * @param {�������ϼ�domԪ��}
 *            el
 * @param {�Ƿ����}
 *            dis
 * @param {��ʽ�ı�}
 *            sty
 * @param {��������}
 *            name
 * @param {ֵ����}
 *            val
 * @param {�����¼�}
 *            click
 * @param {innerTextֵ}
 *            innertext
 * @param {��ʽ��}
 *            cssname
 * @param {�Ƿ�Ĭ��ѡ��}
 *            chk
 */
function createCheckbox(el, dis, sty, name, val, click, innertext, cssname, chk) {
	jQuery(el).append(jQuery("<input type='checkbox' name='" + name + "'>")
			.attr({
						'style' : sty,
						'userSetDisabled' : dis,
						'value' : val,
						'disabled' : dis,
						'checked' : chk
					}).addClass(cssname).click(click)).append(innertext);
}

/**
 * ��̬����hidden��
 * 
 * @param {idֵ}
 *            id
 * @param {����ֵ}
 *            name
 * @param {val����}
 *            val
 * @return {}
 */
function createHidden(id, name, val) {
	return jQuery('<input type="hidden" name="' + name + '">').attr({
				'id' : id,
				"value" : val
			})[0];
}
/**
 * ����һ��ImgԪ��
 * 
 * @param {ͼƬ��Դ}
 *            imgsrc
 */
function createImg(imgsrc) {
	var node = document.createElement('img');
	node.setAttribute('src', imgsrc);
	return node;
}

/**
 * �õ�ָ����Ԫ��id��Ӧ�Ľڵ�.
 * 
 * @param {}
 *            id
 */
function _$(id) {
	return document.getElementById(id);
}

Function.prototype.attachAfter = function(closure, functionOwner) {
	var _this = this;
	return function() {
		this.apply(functionOwner, arguments);
		closure();
	}
}
function attachEvent(obj, eventName, handler) {
	obj[eventName] = (obj[eventName] || function() {
	}).attachAfter(handler, obj);
}