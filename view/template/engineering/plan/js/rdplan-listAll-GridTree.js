function show_page(page) {
	myTree._reload();
}

$(document).ready(function() {
    //ʵ����������б�
    newProjectTreeGrid();
});

function search() {
	var searchfield = $('#searchfield').val();
	var searchvalue = $('#searchvalue').val();
	var param = {};
	if (searchfield != '')
		param[searchfield] = searchvalue;
	else{
		show_page(1);
	}
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
		header : '״̬���',
		width : "5%",
		headerIndex : 'warpRateMig',
		render:function(v){
			if(!isNaN(v)){
				if(v > 0){
					return "<img src='"+oa_cMenuImgArr['red']+"' title='ƫ���ʴ���0'/>";
				}else{
					return "<img src='"+oa_cMenuImgArr['green']+"' title='ƫ����С�ڵ���0'/>";
				}
			}else{
				return v;
			}
		}
	},{
		header : '��Ŀ����',
		headerIndex : 'name',
		width : "15%",
		align : 'left'
	}, {
		header : '������',
		width : "8%",
		headerIndex : 'managerName'
	}, {
		header : "�ƻ�����",
		width : "15%",
		headerIndex : 'planName',
		align : 'left',
		render:function(v){
			if(v!=undefined){
				return v;
			}else{
				return "��̱��ƻ�";
			}
		}
	}, {
		header : "�ƻ���ʼ����",
		width : "10%",
		headerIndex : 'planDateStart'
	}, {
		header : "�ƻ��������",
		width : "10%",
		headerIndex : 'planDateClose'
	}, {
		header : "ƫ����",
		width : "7%",
		headerIndex : 'warpRate'
	}, {
		header : "�����",
		width : "7%",
		headerIndex : 'effortRate'
	}, {
		header : "״̬",
		width : "5%",
		headerIndex : 'statusCN'
	}, {
		header : "��Ŀ���",
		headerIndex : 'projectCode'
	}];
	var content = {
		columnModel : GridColumnType,
		dataUrl : "?model=engineering_project_engineering&action=rpAjaxAllPlan",
		lazyLoadUrl : "?model=engineering_plan_rdplan&action=rpAjaxAllPlan",
		idColumn : 'oid',// id���ڵ���,һ��������(��һ��Ҫ��ʾ����)
		parentColumn : 'oParentId', // ������id
		pageBar : true,// ��ʾҪչʾ��ҳ����Ҳ���ǻ���ַ�ҳ��Ч��
		pageSize : 15,
		debug : false,// ����һ��չʾ������ļ�����Ϣ��div��
		analyzeAtServer : false,// ������dataUrl���Ե�ʱ�������������false��ʾ�������νṹ��ǰ̨���У�Ĭ���Ǻ�̨��������֧��java��,������json��ʽ���ã�
		multiChooseMode : 5,// ѡ��ģʽ������1��2��3��4��5�֡�
		tableId : 'testTable',// �������id
		checkOption : '',// 1:���ֵ�ѡ��ť,2:���ֶ�ѡ��ť,����:������ѡ��ť
		rowCount : true,// Ĭ����û����һ��
		postProperties : ['planName'],
		hidddenProperties : ['name', 'projectId', 'projectName', 'planName','purviewKey'],// ����������һ���е�����,�ʺϴ���ֵ��һ�ַ�ʽ.
		iconShowIndex : 3,
		contextMenu : {
			// width : 150,
			// alias:"1-1",
			items : [{
				text : "�½��ƻ�",
				alias : "1-1",
				type : 'group',
				icon : oa_cMenuImgArr['add'],
				showMenuFn : showMenuFnC,
				items : [{
					text : "�½��հ׼ƻ�",
					alias : "1-1-1",
					icon : oa_cMenuImgArr['add'],
					action : function(row) {
						// debugObjectInfo(row)
						if (showMenuFnB(row)) {
							showThickboxWin('?model=engineering_plan_rdplan&action=toAdd&pnId='
									+ row.pid
									+ '&pjId='
									+ row.projectId
									+ '&type=myPlan&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
						} else {
							showThickboxWin('?model=engineering_plan_rdplan&action=toAdd&pnId=&pjId='
									+ row.projectId
									+ '&type=myPlan&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
						}
					}
				}
				, {
					text : "��ģ�嵼��ƻ�",
					alias : "1-1-2",
					icon : oa_cMenuImgArr['add'],
					action : function(row) {
						if (showMenuFnB(row)) {
							showThickboxWin('?model=engineering_plan_rdplan&action=toImport&planId='
								+ row.pid
								+ "&planName="
								+ row.planName
								+ "&projectId="
								+ row.projectId
								+ "&projectName="
								+ row.projectName
								+ '&type=myPlan&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
						}else{
							showThickboxWin('?model=engineering_plan_rdplan&action=toImport&planId='
								+ "&planName="
								+ "&projectId="
								+ row.projectId
								+ "&projectName="
								+ row.projectName
								+ '&type=myPlan&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
						}
					}
				}
				]
			},{
				text : "����̱�",
				alias : "1-6",
				icon : oa_cMenuImgArr['open'],
				showMenuFn : showMenuFnP,
				action : function(row) {
						showThickboxWin('?model=engineering_milestone_rdmilestone&action=rmListCenter&pjId='
								+ row.projectId
								+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=950');
					}
			},{
				text : "�򿪼ƻ�����",
				alias : "1-9",
				icon : oa_cMenuImgArr['open'],
				showMenuFn : showMenuFnB,
				action : function(row) {
						location='?model=engineering_task_rdtask&action=toPlanTaskPage&pnId='
								+ row.pid
								+ "&pnName="
								+ row.planName
								+ "&pjId="
								+ row.projectId
								+ "&pjName="
								+ row.projectName
								+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750';
					}
			}, {
				type : "splitLine" // ����
			}, {
				text : "�鿴",
				alias : "1-2",
				icon : oa_cMenuImgArr['read'],
				action : function(row) {
					if(!showMenuFnB(row)){
						showOpenWin('?model=engineering_project_engineering&action=rpRead'
									+ "&pjId="
									+ row.projectId
									+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=950');
					}else{
						showThickboxWin('?model=engineering_plan_rdplan&action=view'
									+ "&pnId="
									+ row.pid
									+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
					}
				}
			}, {
				text : "�༭",
				alias : "1-3",
				icon : oa_cMenuImgArr['edit'],
				showMenuFn : showMenuFnA,
				action : function(row) {
					showThickboxWin('?model=engineering_plan_rdplan&action=toEdit&pnId='
									+ row.pid
									+ "&pjId="
									+ row.projectId
									+ '&type=allPlan&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
				}
			}, {
				text : "ɾ��",
				alias : "1-5",
				icon: oa_cMenuImgArr['del'],
				showMenuFn : showMenuFnA,
				action : function(row) {
					showThickboxWin('?model=engineering_plan_rdplan&action=delectPlan&pnId='
									+ row.pid
									+ '&type=allPlan&placeValuesBefore&TB_iframe=true&modal=false&height=150&width=300');
				}
					// TODO:
				}, {
				text : "����Ϊģ��",
				alias : "1-8",
				icon: oa_cMenuImgArr['template'],
				showMenuFn : showMenuFnA,
				action : function(row){
					showThickboxWin('?model=engineering_template_rdtplan&action=toAdd&pnId='
						+ row.pid
						+ "&pnName="
						+ row.planName
						+ "&pjId="
						+ row.projectId
						+ "&pjName="
						+ row.projectName
						+ '&type=myPlan&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=600');
				}
					// TODO:
			}
//				, {
//				type : "splitLine" // ����
//			}, {
//				text : "����Ϊģ��",
//				alias : "1-8",
//				icon: oa_cMenuImgArr['template'],
//				action : function(row) {
//					alert('������');
//				}
//					// TODO:
//				}
				]
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

// �ж��Ƿ��Ǽƻ� ���ж�Ȩ��
function showMenuFnA(obj) {
	if (obj.planName) {
		if(obj.purviewKey == 1){
			return true;
		}else
			return false;
	} else {
		return false;
	}
}

//�ж��Ƿ��Ǽƻ�
function showMenuFnB(obj) {
	if (obj.planName) {
		return true;
	} else {
		return false;
	}
}

// �ж��Ƿ�����Ŀ
function showMenuFnP(obj) {
	if (obj.name) {
		return true;
	} else {
		return false;
	}
}

//�ж��Ƿ�����Ŀ
function showMenuFnC(obj){
	if(obj.name){
		return true;
	}else{
		if(obj.purviewKey == 1){
			return true;
		}else
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

// �鿴ѡ��Ľڵ�
function showChoosed() {
	var ans = getAllCheckValue();
	if (ans != '')
		alert(ans);
	else
		alert('û��ѡ��');
}

// �����нڵ�
function openAll() {
	myTree.expandAll();
}

// �ر����нڵ�
function closeAll() {
	myTree.closeAll();
}