
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
 * 搜索项目组合跟项目
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
//优先级HashMap
	var priorityMap=new HashMap();
		priorityMap.put("PTBJJ","普通不紧急");
		priorityMap.put("PTJJ","普通紧急");
		priorityMap.put("ZYBJJ","重要不紧急");
		priorityMap.put("ZYJJ","重要紧急");
	var statusMap=new HashMap();
		statusMap.put("WFB","未发布");
		statusMap.put("QZZZ","强制终止");
		statusMap.put("ZT","暂停");
		statusMap.put("WTG","未通过");
		statusMap.put("TG","通过（未关闭）");
		statusMap.put("DSH","待审核");
		statusMap.put("JXZ","进行中");
		statusMap.put("WQD","未启动");
	var taskTypeMap=new HashMap();
		taskTypeMap.put("YJJC","硬件集成");
		taskTypeMap.put("PZGL","配置管理");
		taskTypeMap.put("QA","QA");
		taskTypeMap.put("PINGS","评审");
		taskTypeMap.put("XMGL","项目管理");
		taskTypeMap.put("SSBS","实施部署");
		taskTypeMap.put("XTCS","系统测试");
		taskTypeMap.put("RJKF","软件开发");
		taskTypeMap.put("FXSJ","分析设计");
		taskTypeMap.put("XQDY","需求调研");

var myTree = new GridTree();
/**
 * 主要的测试方法
 */
function newTkNodeGridTree() {
	var GridColumnType = [{
				header : '提示灯',
				headerIndex : 'warnIcon',
				width:"3%",
				render : function(val, row, cell, tc){
					if(row.rowData.warpRate){
						if(row.rowData.warpRate==0)
							return "<img src='images/Knob_Green1.gif'>";
						else
							return "<img src='images/Knob_Red1.gif'>";
					}
					return "";
				}

			}, {
				header : '任务名称',
				headerIndex : 'name',
				align : 'left',
				width:"15%",
				render:function(v,r,c,t){
					return "<img src='images/ico6.gif'></img>"+v;
				}
			}, {
				header : "所属项目",
				headerIndex : 'projectName',
				width:"15%"
			}, {
				header : "优先级",
				headerIndex : 'priority',
				width:"5%",
				render : function(val, row, cell, tc){
					return priorityMap.get(val);
				}
			}, {
				header : "状态",
				headerIndex : 'status',
				width:"8%",
				render:function(val, row, cell, tc){
					return statusMap.get(val);
				}
			}, {
				header : "完成率",
				headerIndex : 'effortRate',
				width:"8%"
			}, {
				header : "偏差率",
				headerIndex : 'warpRate',
				width:"8%"
			}, {
				header : "责任人",
				headerIndex : 'chargeName',
				width:"8%"
			}, {
				header : "发布人",
				headerIndex : 'publishName',
				width:"8%"
			}, {
				header : "最近更新时间",
				headerIndex : 'updateTime',
				width:"5%"
			}, {
				header : "任务类型",
				headerIndex : 'taskType',
				width:"5%",
				render:function(val, row, cell, tc){
					return taskTypeMap.get(val);
				}
			}];
	var content = {
		columnModel : GridColumnType,
		dataUrl : "?model=rdproject_task_tknode&action=getTkNodeByParentId&planId="+$("#planId").val(),
		lazyLoadUrl : "?model=rdproject_task_tknode&action=spreadTkNodeByNode&planId="+$("#planId").val(),
		idColumn : 'oid',// id所在的列,一般是主键(不一定要显示出来)
		parentColumn : 'oParentId', // 父亲列id
		pageBar : false,
		//pageSize : 15,
		debug : false,
		iconShowIndex : 1,
		analyzeAtServer : true,// 设置了dataUrl属性的时候，如果此属性是false表示分析树形结构在前台进行，默认是后台分析（仅支持java）,体现在json格式不用！
		multiChooseMode : 5,// 选择模式，共有1，2，3，4，5种。
		tableId : 'gridTree',// 表格树的id
		checkOption : '',// 1:出现单选按钮,2:出现多选按钮,其他:不出现选择按钮
		rowCount : true,
		hidddenProperties : ['status', 'projectName','projectId'],
		contextMenu : {
			items : [{
				text : "查看",
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
			}]
		},
		onLazyLoadSuccess : function(gt) {
			// alert('懒加载执行完了..');
		},
		onSuccess : function(gt) {
//			 alert('初次加载表格树执行完了..');
		},
		onPagingSuccess : function(gt) {
			// alert('翻页执行完了..');
		},
		lazy : true,// 使用懒加载模式（此时打开全部，关闭全部功能不可使用）
		leafColumn : 'leaf',// 用于判断节点是不是树叶
		el : 'tkNodeGridTree'// 要进行渲染的div id
	};
	myTree.loadData(content);
	myTree.makeTable();

}

/**
 * 双击事件,双击一行调用该方法.
 *
 * @param {行对象}
 *            obj
 */
function doubleClickOnRow(obj) {
	debugObjectInfo(obj);
}

/**
 * 用来查看一个对象的属性
 */
function debugObjectInfo(obj) {
	traceObject(obj);

	function traceObject(obj) {
		var str = '';
		if (obj.tagName && obj.name && obj.id)
			str = "<table border='1' width='100%'><tr><td colspan='2' bgcolor='#ffff99'>traceObject 　　tag: &lt;"
					+ obj.tagName
					+ "&gt;　　 name = \""
					+ obj.name
					+ "\" 　　id = \"" + obj.id + "\" </td></tr>";
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
		alert('没有选择');
}

function openAll() {
	myTree.expandAll();
}

function closeAll() {
	myTree.closeAll();
}