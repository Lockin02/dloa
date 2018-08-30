function show_page(page) {
	myTree._reload();
}

/*
 * ������Ŀ��ϸ���Ŀ
 */
function search() {
	var searchfield = $('#searchfield').val();
	var searchvalue = $('#searchvalue').val();
	var param = {};
	param[searchfield] = searchvalue;
	myTree._searchGrid(param);
}

var myTree = new GridTree();

/**
 * ��Ҫ�Ĳ��Է���
 */
function newProjectTreeGrid() {
	var GridColumnType = [
			/**
			 * { header : 'id', headerIndex : 'id', width : '10%' },
			 */
			{
		header : '����',
		headerIndex : 'name',
		align : 'left'
	}, {
		header : '��������',
		headerIndex : 'depName'
	}, {
		header : "������",
		headerIndex : 'managerName'
	}, {
		header : "״̬",
		headerIndex : 'status'
	}, {
		header : "ƫ����",
		headerIndex : 'warpRate'
	}, {
		header : "��Ͷ�빤����",
		headerIndex : 'workload'
	}, {
		header : "��ǰ��̱�",
		headerIndex : 'milestonePoint'
	}, {
		header : "���",
		headerIndex : 'businessCode'
	}, {
		header : "��Ŀ����",
		headerIndex : 'projectType'
	}, {
		header : "�ƻ���������",
		headerIndex : 'planDateStart'
	}, {
		header : "�ƻ��ر�����",
		headerIndex : 'planDateClose'
	}];
	var content = {
		columnModel : GridColumnType,
		dataUrl : "?model=rdproject_group_rdgroup&action=pageGroupAndProjectAll",
		lazyLoadUrl : "?model=rdproject_group_rdgroup&action=pageGroupAndProject",
		idColumn : 'id',// id���ڵ���,һ��������(��һ��Ҫ��ʾ����)
		parentColumn : 'parentId', // ������id
		pageBar : true,
		pageSize : 15,
		pageBar : true,
		debug : false,
		analyzeAtServer : false,// ������dataUrl���Ե�ʱ�������������false��ʾ�������νṹ��ǰ̨���У�Ĭ���Ǻ�̨��������֧��java��,������json��ʽ���ã�
		multiChooseMode : 5,// ѡ��ģʽ������1��2��3��4��5�֡�
		tableId : 'testTable',// �������id
		checkOption : '',// 1:���ֵ�ѡ��ť,2:���ֶ�ѡ��ť,����:������ѡ��ť
		rowCount : true,
		hidddenProperties : ['name'],// ����������һ���е�����,�ʺϴ���ֵ��һ�ַ�ʽ.
		contextMenu : {
			width : 150,
			items : [{
				text : "��",
				icon : "images/ico1.gif",
				alias : "1-1",
				action : function(t) {
					// �����Ƿ�Ҷ���ж�ѡ�������Ŀ��ϻ�����Ŀ
					if (t.leaf == 0) {
						showThickboxWin('?model=rdproject_group_rdgroup&action=rgUpdateTo&gpId='
								+ t.pid
								+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
					} else {
						showThickboxWin('?model=rdproject_project_rdproject&action=rpUpdateTo&pjId='
								+ t.pid
								+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
					}
					// alert(t.pid)
				}
			}]
		},
		onLazyLoadSuccess : function(gt) {
			// alert('������ִ������..');
		},
		onSuccess : function(gt) {
			// alert('���μ��ر����ִ������..');
		},
		onPagingSuccess : function(gt) {
			// alert('��ҳִ������..');
		},
		lazy : false,// ʹ��������ģʽ����ʱ��ȫ�����ر�ȫ�����ܲ���ʹ�ã�
		leafColumn : 'leaf',// �����жϽڵ��ǲ�����Ҷ
		el : 'newtableTree'// Ҫ������Ⱦ��div id
	};
	myTree.loadData(content);
	myTree.makeTable();

	// չ��ȫ���ڵ�
	// _$('bt3').onclick=function(){myTree.expandAll();};
	// չ����һ��ڵ�
	// _$('bt4').onclick=function(){myTree.closeAll();};
}

/**
 * ˫���¼�,˫��һ�е��ø÷���.
 * 
 * @param {�ж���}
 *            obj
 */
function doubleClickOnRow(obj) {
	debugObjectInfo(obj);
}

/**
 * �����鿴һ�����������
 */
function debugObjectInfo(obj) {
	traceObject(obj);

	function traceObject(obj) {
		var str = '';
		if (obj.tagName && obj.name && obj.id)
			str = "<table border='1' width='100%'><tr><td colspan='2' bgcolor='#ffff99'>traceObject ����tag: &lt;"
					+ obj.tagName
					+ "&gt;���� name = \""
					+ obj.name
					+ "\" ����id = \"" + obj.id + "\" </td></tr>";
		else {
			str = "<table border='1' width='100%'>";
		}
		var key = [];
		for (var i in obj) {
			key.push(i);
		}
		key.sort();
		for (var i = 0; i < key.length; i++) {
			var v = new String(obj[key[i]]).replace(/</g, "&lt;").replace(/>/g,
					"&gt;");
			if (typeof obj[key[i]] == 'string' && v != null && v != '')
				str += "<tr><td valign='top'>" + key[i] + "</td><td>" + v
						+ "</td></tr>";
		}
		str = str + "</table>";
		writeMsg(str);
	}
	function trace(v) {
		var str = "<table border='1' width='100%'><tr><td bgcolor='#ffff99'>";
		str += String(v).replace(/</g, "&lt;").replace(/>/g, "&gt;");
		str += "</td></tr></table>";
		writeMsg(str);
	}
	function writeMsg(s) {
		traceWin = window.open("", "traceWindow",
				"height=600, width=800,scrollbars=yes");
		traceWin.document.write(s);
	}
}

function showHtml() {
	jQuery('#ans').text(jQuery('#newtableTree').html());
}

function setGridTreeDisabled(v) {
	myTree.setDisabled(v);
}

function showChoosed() {
	var ans = getAllCheckValue();
	if (ans != '')
		alert(ans);
	else
		alert('û��ѡ��');
}

function openAll() {
	myTree.expandAll();
}

function closeAll() {
	myTree.closeAll();
}