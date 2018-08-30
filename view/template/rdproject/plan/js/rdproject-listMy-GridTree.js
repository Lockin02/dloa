function show_page(page) {
	this.location = "?model=rdproject_group_rdgroup&action=page";
}

/*
 * ������Ŀ��ϸ���Ŀ
 */
function search() {
	var searchfield = $('#searchfield').val();
	var searchvalue = $('#searchvalue').val();
	var param = {};
	if (searchfield != '')
		param[searchfield] = searchvalue;
	myTree._searchGrid(param);
}

var myTree = new GridTree();
alert(123)
/**
 * ��Ҫ�Ĳ��Է���
 */
function newProjectTreeGrid() {
	var GridColumnType = [
			/**
			 * { header : 'id', headerIndex : 'id', width : '10%' },
			 */
			{
		header : '��Ŀ����',
		headerIndex : 'projectName',
		width: "15%",
		align : 'left'
	}, {
		header : '��Ŀ������',
		width: "8%",
		headerIndex : 'managerName'
	}, {
		header : "�ƻ�����",
		width: "15%",
		headerIndex : 'planName'
	}, {
		header : "�ƻ���ʼ����",
		width: "10%",
		headerIndex : 'planDateStart'
	}, {
		header : "�ƻ��������",
		width: "10%",
		headerIndex : 'planDateClose'
	}, {
		header : "ƫ����",
		width: "7%",
		headerIndex : 'warpRate'
	}, {
		header : "�����",
		width: "7%",
		headerIndex : 'milestonePoint'
	}, {
		header : "��Ŀ״̬",
		width: "5%",
		headerIndex : 'statusCN'
	}, {
		header : "��Ŀ���",
		headerIndex : 'projectCode'
	}];
	var content = {
		columnModel : GridColumnType,
		dataUrl : "?model=rdproject_project_rdproject&action=rpAjaxMyPlan",
		lazyLoadUrl : "?model=rdproject_plan_rdplan&action=rpAjaxMyPlan",
		idColumn : 'oid',// id���ڵ���,һ��������(��һ��Ҫ��ʾ����)
		parentColumn : 'oParentId', // ������id
		iconShowIndex:2,
		pageBar : true,// ��ʾҪչʾ��ҳ����Ҳ���ǻ���ַ�ҳ��Ч��
		pageSize : 15,
		debug : false,//����һ��չʾ������ļ�����Ϣ��div��
		analyzeAtServer : false,// ������dataUrl���Ե�ʱ�������������false��ʾ�������νṹ��ǰ̨���У�Ĭ���Ǻ�̨��������֧��java��,������json��ʽ���ã�
		multiChooseMode : 5,// ѡ��ģʽ������1��2��3��4��5�֡�
		tableId : 'testTable',// �������id
		checkOption : '',// 1:���ֵ�ѡ��ť,2:���ֶ�ѡ��ť,����:������ѡ��ť
		rowCount : true,//Ĭ����û����һ��
		postProperties:['projectName','planName'],
		hidddenProperties : ['name','projectId','projectName','planName'],// ����������һ���е�����,�ʺϴ���ֵ��һ�ַ�ʽ.
		contextMenu : {
			// width : 150,
			// alias:"1-1",
			items : [{
				text : "�½��ƻ�",
				alias : "1-1",
				type : 'group',
				items : [{
							text : "�½��հ׼ƻ�",
							alias : "1-1-1",
							action : function(row) {
								//debugObjectInfo(row)
								if( showMenuFnA(row) ){
									showThickboxWin('?model=rdproject_plan_rdplan&action=toAdd&pnId='
										+ row.pid + "&pjId=" + row.projectId
										+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
								}else{
									showThickboxWin('?model=rdproject_plan_rdplan&action=toAdd&pnId=&pjId='
										+ row.projectId
										+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
								}
							}
						},{
							text : "��ģ�嵼��ƻ�",
							alias : "1-1-2",
							action : function(row) {

							}
						}]
			},{
				type:"splitLine" //����
			},{
				text : "�鿴",
				// ����true��ʾ�˵�������flase����ʾ�˵�
				// showMenuFn : function(row) {
				// return true;
				// },
				alias : "1-2",
				action : function(row) {
					//�����Ƿ�����Ŀ�����ж�����Ŀ�������
					if ( showMenuFnP(row) ) {
						showThickboxWin('?model=rdproject_group_rdgroup&action=rgRead&gpId='
								+ row.pid
								+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=950');
					} else {
						showThickboxWin('?model=rdproject_project_rdproject&action=rpRead&pjId='
								+ row.pid
								+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
					}
					// alert(t.pid)
				}
			}, {
				text : "�޸�",
				alias : "1-3",
				action : function(row) {
					if ( showMenuFnP(row) ) {
						showThickboxWin('?model=rdproject_group_rdgroup&action=rgUpdateTo&gpId='
								+ row.pid
								+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=950');
					} else {
						showThickboxWin('?model=rdproject_project_rdproject&action=rpUpdateTo&pjId='
								+ row.pid
								+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
					}
				}
			}, {
				text : "�ر�",
				alias : "1-4",
				showMenuFn : showMenuFnA
				//TODO:
			}, {
				text : "ɾ��",
				alias : "1-5",
				showMenuFn : showMenuFnP,
				action : function(row) {
					alert("1");
				}
				//TODO:
			}, {
				type:"splitLine" //����
			},{
				text : "����Ϊģ��",
				alias : "1-8",
				showMenuFn : function(row) {
					if (row.projectName) {
						return true;
					}
					return false;
				}
				//TODO:
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
		lazy : true,// ʹ��������ģʽ����ʱ��ȫ�����ر�ȫ�����ܲ���ʹ�ã�
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

//�ж��Ƿ��Ǽƻ�
function showMenuFnA(obj){
	if(obj.planName){
		return true;
	}else{
		return false;
	}
}

//�ж��Ƿ�����Ŀ
function showMenuFnP(obj){
	if(obj.projectName){
		return true;
	}else{
		return false;
	}
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

//�鿴ѡ��Ľڵ�
function showChoosed() {
	var ans = getAllCheckValue();
	if (ans != '')
		alert(ans);
	else
		alert('û��ѡ��');
}

//�����нڵ�
function openAll() {
	myTree.expandAll();
}

//�ر����нڵ�
function closeAll() {
	myTree.closeAll();
}