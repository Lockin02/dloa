
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
	var priorityMap=new HashMap();
		priorityMap.put("PTBJJ","��ͨ������");
		priorityMap.put("PTJJ","��ͨ����");
		priorityMap.put("ZYBJJ","��Ҫ������");
		priorityMap.put("ZYJJ","��Ҫ����");
	var statusMap=new HashMap();
		statusMap.put("WFB","δ����");
		statusMap.put("QZZZ","ǿ����ֹ");
		statusMap.put("ZT","��ͣ");
		statusMap.put("WTG","δͨ��");
		statusMap.put("TG","ͨ����δ�رգ�");
		statusMap.put("DSH","�����");
		statusMap.put("JXZ","������");
		statusMap.put("WQD","δ����");
	var taskTypeMap=new HashMap();
		taskTypeMap.put("YJJC","Ӳ������");
		taskTypeMap.put("PZGL","���ù���");
		taskTypeMap.put("QA","QA");
		taskTypeMap.put("PINGS","����");
		taskTypeMap.put("XMGL","��Ŀ����");
		taskTypeMap.put("SSBS","ʵʩ����");
		taskTypeMap.put("XTCS","ϵͳ����");
		taskTypeMap.put("RJKF","�������");
		taskTypeMap.put("FXSJ","�������");
		taskTypeMap.put("XQDY","�������");

var myTree = new GridTree();
/**
 * ��Ҫ�Ĳ��Է���
 */
function newTkNodeGridTree() {
	var GridColumnType = [
			{
				header : '��������',
				headerIndex : 'name',
				align : 'left',
				width:"15%",
				render:function(v,r,c,t){
					return "<img src='images/ico6.gif'></img>"+v;
				}
			},{
				header : "���ȼ�",
				headerIndex : 'priority',
				width:"5%",
				render : function(val, row, cell, tc){
					return priorityMap.get(val);
				}
			}, {
				header : "��������",
				headerIndex : 'taskType',
				width:"5%",
				render:function(val, row, cell, tc){
					return taskTypeMap.get(val);
				}
			}];
	var content = {
		columnModel : GridColumnType,
		dataUrl : "?model=rdproject_template_rdtnode&action=getParentRowsByTemplateId&templateId="+$("#templateId").val(),
		lazyLoadUrl : "?model=rdproject_template_rdtnode&action=getRowsByTemplateId&templateId="+$("#templateId").val(),
		idColumn : 'oid',// id���ڵ���,һ��������(��һ��Ҫ��ʾ����)
		parentColumn : 'oParentId', // ������id
		pageBar : false,
		pageSize : 17,
		debug : false,
		analyzeAtServer : false,// ������dataUrl���Ե�ʱ�������������false��ʾ�������νṹ��ǰ̨���У�Ĭ���Ǻ�̨��������֧��java��,������json��ʽ���ã�
		multiChooseMode : 5,// ѡ��ģʽ������1��2��3��4��5�֡�
		tableId : 'gridTree',// �������id
		checkOption : '',// 1:���ֵ�ѡ��ť,2:���ֶ�ѡ��ť,����:������ѡ��ť
		rowCount : true,
		hidddenProperties : [ 'taskType'],
		iconShowIndex : 0,
		contextMenu : {
			items : [{
				text : "�鿴",
				icon : "images/icon/icon103.gif",
				alias : "1-1",
				action : function(row) {
					if(isNode(row)){
						showThickboxWin('?model=rdproject_template_rdtnode&action=init&perm=view&id='
							+ row.pid
							+ '&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=600');
					}else{
						showThickboxWin('?model=rdproject_template_rdttask&action=init&perm=view&id='
							+ row.pid
							+ '&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800');
					}
				}
			}, {

				text : "�༭",
				icon : "images/icon/icon141.gif",
				alias : "1-7",
				action : function(row) {
					if(isNode(row)){
						showThickboxWin('?model=rdproject_template_rdtnode&action=init&id='
							+ row.pid
							+ '&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=600');
					}else{
						showThickboxWin('?model=rdproject_template_rdttask&action=init&id='
							+ row.pid
							+ '&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800');
					}
				}
			},{
                  text: "ɾ��",
                    icon: "images/icon/delete.gif",
                    alias: "1-8",
                    action: function(row){
                    if(isNode(row)){
						if (window.confirm(("ȷ��Ҫɾ��?"))) {
							$.ajax({
								type : "POST",
								url : '?model=rdproject_template_rdtnode&action=ajaxdeletes',
								data : {
									id : row.pid
								},
								success : function(msg) {
									if (msg == 1) {
										// grid.reload();
										alert('ɾ���ɹ���');
										show_page(1);
									}
								}
							});
						}
					}else{
						if (window.confirm(("ȷ��Ҫɾ��?"))) {
							$.ajax({
								type : "POST",
								url : '?model=rdproject_template_rdttask&action=ajaxdeletes',
								data : {
									id : row.pid
								},
								success : function(msg) {
									if (msg == 1) {
										// grid.reload();
										alert('ɾ���ɹ���');
										show_page(1);
									}
								}
							});
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
 * �����Ƿ��ǽڵ�
 * @param {} obj
 */
function isNode(obj){
	if(obj.taskType){
		return false;
	}else{
		return true;
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