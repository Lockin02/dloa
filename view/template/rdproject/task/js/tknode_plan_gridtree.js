
//alert(planId)
function show_page(page) {
	myTree._reload();
	//	var planId=$("#planId").val();
	//	var planName=$("#planName").val();
	//	var projectId=$("#projectId").val();
	//	var projectName=$("#projectName").val();
	//
	//	this.location = "?model=rdproject_task_rdtask&action=toPlanTaskPage&pnId="+planId+"&pnName="+planName+"&pjId="+projectId+"&pjName="+projectName;
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

//	$.get('index1.php', {
//					model : 'system_datadict_datadict',
//					async: false,
//					action : 'getDataJsonByCode',
//					code : "ZYJJ"
//	}, function(data) {return  data;
//		})
//���ȼ�HashMap
var priorityMap = new HashMap();
priorityMap.put("PTBJJ", "��ͨ������");
priorityMap.put("PTJJ", "��ͨ����");
priorityMap.put("ZYBJJ", "��Ҫ������");
priorityMap.put("ZYJJ", "��Ҫ����");
var statusMap = new HashMap();
statusMap.put("WFB", "δ����");
statusMap.put("QZZZ", "ǿ����ֹ");
statusMap.put("ZT", "��ͣ");
statusMap.put("WTG", "δͨ��");
statusMap.put("TG", "ͨ����δ�رգ�");
statusMap.put("DSH", "�����");
statusMap.put("JXZ", "������");
statusMap.put("WQD", "δ����");
var taskTypeMap = new HashMap();
taskTypeMap.put("YJJC", "Ӳ������");
taskTypeMap.put("PZGL", "���ù���");
taskTypeMap.put("QA", "QA");
taskTypeMap.put("PINGS", "����");
taskTypeMap.put("XMGL", "��Ŀ����");
taskTypeMap.put("SSBS", "ʵʩ����");
taskTypeMap.put("XTCS", "ϵͳ����");
taskTypeMap.put("RJKF", "�������");
taskTypeMap.put("FXSJ", "�������");
taskTypeMap.put("XQDY", "�������");

var myTree = new GridTree();
/**
 * ��Ҫ�Ĳ��Է���
 */
function newTkNodeGridTree() {
	var planId = $("#planId").val();
	var planName = $("#planName").val();
	var projectId = $("#projectId").val();
	var projectCode = $("#projectCode").val();
	var projectName = $("#projectName").val();
	var GridColumnType = [{
		header : '��ʾ��',
		headerIndex : 'warnIcon',
		width : "3%",
		render : function(val, row, cell, tc) {
			if (row.rowData.warpRate) {
				if (row.rowData.warpRate == 0)
					return "<img src='images/Knob_Green1.gif'>";
				else
					return "<img src='images/Knob_Red1.gif'>";
			}
			return "";
		}

	}, {
		header : '��������',
		headerIndex : 'name',
		align : 'left',
		width : "15%",
		render : function(v, r, c, t) {
			if(r.rowData.rgt - r.rowData.lft == 1 || r.rowData.leaf==1){
				return "<img src='images/ico6.gif'></img>" + v;
			}else{
				return "<img src='js/ext/resources/images/default/tree/folder.gif'></img>" + v;
			}
		}
	}, {
		header : "������Ŀ",
		headerIndex : 'projectName',
		width : "15%"
	}, {
		header : "���ȼ�",
		headerIndex : 'priority',
		width : "5%",
		render : function(val, row, cell, tc) {
			return priorityMap.get(val);
		}
	}, {
		header : "״̬",
		headerIndex : 'status',
		width : "8%",
		render : function(val, row, cell, tc) {
			return statusMap.get(val);
		}
	}, {
		header : "�����",
		headerIndex : 'effortRate',
		width : "8%"
	}, {
		header : "ƫ����",
		headerIndex : 'warpRate',
		width : "8%"
	}, {
		header : "������",
		headerIndex : 'chargeName',
		width : "8%"
	}, {
		header : "������",
		headerIndex : 'publishName',
		width : "8%"
	}, {
		header : "�������ʱ��",
		headerIndex : 'updateTime',
		width : "5%"
	}, {
		header : "��������",
		headerIndex : 'taskType',
		width : "5%",
		render : function(val, row, cell, tc) {
			return taskTypeMap.get(val);
		}
	}];
	var content = {
		columnModel : GridColumnType,
		dataUrl : "?model=rdproject_task_tknode&action=getTkNodeByParentId&planId="
				+ $("#planId").val(),
		lazyLoadUrl : "?model=rdproject_task_tknode&action=spreadTkNodeByNode&planId="
				+ $("#planId").val(),
		idColumn : 'oid',// id���ڵ���,һ��������(��һ��Ҫ��ʾ����)
		parentColumn : 'oParentId', // ������id
		pageBar : true,
		pageSize : 15,
		debug : false,
		iconShowIndex : 1,
		analyzeAtServer : true,// ������dataUrl���Ե�ʱ�������������false��ʾ�������νṹ��ǰ̨���У�Ĭ���Ǻ�̨��������֧��java��,������json��ʽ���ã�
		multiChooseMode : 5,// ѡ��ģʽ������1��2��3��4��5�֡�
		tableId : 'gridTree',// �������id
		checkOption : '',// 1:���ֵ�ѡ��ť,2:���ֶ�ѡ��ť,����:������ѡ��ť
		rowCount : true,
		hidddenProperties : ['status', 'projectName', 'projectId'],
		contextMenu : {
			items : [{
				text : "�鿴",
				icon : "images/icon/icon103.gif",
				alias : "1-1",
				action : function(row) {
					if (row.status)
						showThickboxWin('?model=rdproject_task_rdtask&action=toReadTask&id='
								+ row.pid
								+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=900');
					else
						showThickboxWin('?model=rdproject_task_tknode&action=toTkNodeView&id='
								+ row.pid
								+ '&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=550');

				}
			}, {
				text : "�½�����",
				icon : "images/icon/icon141.gif",
				alias : "1-9",
				showMenuFn : function(row) {
					if (!row.status) {
						return true;
					}
					return false;
				},
				action : function(row) {
					showThickboxWin("?model=rdproject_task_rdtask&action=toPlanAddTask&nodeId="
							+ row.pid
							+ "&planId="
							+ planId
							+ "&planName="
							+ planName
							+ "&projectId="
							+ projectId
							+ "&projectCode="
							+ projectCode
							+ "&projectName="
							+ projectName
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=950");
				}
			}, {
				text : "����",
				icon : "images/icon/icon141.gif",
				alias : "1-2",
				showMenuFn : function(row) {
					if (row.status == "WFB" && row.rowData.chargeName != "") {
						return true;
					}
					return false;
				},
				action : function(row) {
					$.get('index1.php', {
						model : 'rdproject_task_rdtask',
						action : 'publishTask',
						id : row.pid
					}, function(data) {
						alert(data);
						show_page();
						return;
					})
				}
			}, {
				text : "��ͣ",
				icon : "images/icon/icon141.gif",
				alias : "1-3",
				showMenuFn : function(row) {
					if (row.status != "ZT" && row.status != "WFB"
							&& row.status != "QZZZ" && row.status
							&& row.status != "TG" && row.status != "DSH") {
						return true;
					}
					return false;
				},
				action : function(row) {
					showThickboxWin('?model=rdproject_task_stophistory&action=toStopTask&id='
							+ row.pid
							+ "&status="
							+ row.status
							+ '&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=600');
					// alert("��δ����")
				}
			}, {
				text : "�ָ�",
				icon : "images/icon/icon141.gif",
				alias : "1-4",
				showMenuFn : function(row) {
					if (row.status == "ZT") {
						return true;
					}
					return false;
				},
				action : function(row) {
					showThickboxWin('?model=rdproject_task_stophistory&action=toReBackTask&id='
							+ row.pid
							+ '&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=600');
				}
			}, {
				text : "���",
				icon : "images/icon/icon141.gif",
				alias : "1-5",
				showMenuFn : function(row) {
					if (row.status != "WFB" && row.status != "QZZZ"
							&& row.status && row.status != "TG"
							&& row.status != "DSH") {
						return true;
					}
					return false;
				},

				action : function(row) {
					showThickboxWin('?model=rdproject_task_rdtask&action=toChangeTask&id='
							+ row.pid
							+ '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900');

				}
			}, {
				text : "ǿ�ƹر�",
				icon : "images/icon/icon141.gif",
				alias : "1-6",
				showMenuFn : function(row) {
					if (row.status != "WFB" && row.status != "QZZZ"
							&& row.status && row.status != "TG"
							&& row.status != "DSH") {
						return true;
					}
					return false;
				},

				action : function(row) {
					showThickboxWin('?model=rdproject_task_tkover&action=toAdd&id='
							+ row.pid
							+ '&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=600');
				}
			}, {

				text : "�༭",
				icon : "images/icon/icon141.gif",
				alias : "1-7",
				showMenuFn : function(row) {

					if (row.status == "WFB" || !row.status) {
						return true;
					}
					return false;
				},

				action : function(row) {
					if (row.status)
						showThickboxWin('?model=rdproject_task_rdtask&action=toEditTask&actType=editPtask&id='
								+ row.pid
								+ "&projectId="
								+ row.projectId
								+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
					else
						showThickboxWin('?model=rdproject_task_tknode&action=init&id='
								+ row.pid
								+ '&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=700');
				}

			}, {
				text : "ɾ��",
				icon : "images/icon/delete.gif",
				alias : "1-8",
				showMenuFn : function(row) {
					if (row.status == "WFB" || !row.status) {
						return true;
					}
					return false;
				},
				action : function(row) {
					if (row.status) {
						if (confirm('ȷ��Ҫɾ��������?')) {
							$.get('index1.php', {
								model : 'rdproject_task_rdtask',
								action : 'ajaxDeleteByPk',
								id : row.pid
							}, function(data) {
								alert("ɾ���ɹ�");
								show_page(1);
							})
						}
					} else {
						if (confirm('ȷ��Ҫɾ���˽ڵ�?')) {
							$.get('index1.php', {
								model : 'rdproject_task_tknode',
								action : 'ajaxDeleteByPk',
								id : row.pid
							}, function(data) {
								alert(data);
								show_page(1);

							})
						}
					}
				}
			}]
		},
		onLazyLoadSuccess : function(gt) {
			// alert('������ִ������..');
		},
		onSuccess : function(gt) {
			//			 alert('���μ��ر����ִ������..');
		},
		onPagingSuccess : function(gt) {
			// alert('��ҳִ������..');
		},
		lazy : true,// ʹ��������ģʽ����ʱ��ȫ�����ر�ȫ�����ܲ���ʹ�ã�
		leafColumn : 'leaf',// �����жϽڵ��ǲ�����Ҷ
		el : 'tkNodeGridTree'// Ҫ������Ⱦ��div id
	};
	myTree.loadData(content);
	myTree.makeTable();
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