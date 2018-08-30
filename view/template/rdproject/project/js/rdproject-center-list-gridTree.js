function show_page(page) {
	myTree._reload();
}

/*
 * 搜索项目组合跟项目
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
 * 主要的测试方法
 */
function newProjectTreeGrid() {
	var GridColumnType = [
			/**
			 * { header : 'id', headerIndex : 'id', width : '10%' },
			 */
			{
		header : '名称',
		headerIndex : 'name',
		align : 'left'
	}, {
		header : '所属部门',
		headerIndex : 'depName'
	}, {
		header : "责任人",
		headerIndex : 'managerName'
	}, {
		header : "状态",
		headerIndex : 'status'
	}, {
		header : "偏差率",
		headerIndex : 'warpRate'
	}, {
		header : "已投入工作量",
		headerIndex : 'workload'
	}, {
		header : "当前里程碑",
		headerIndex : 'milestonePoint'
	}, {
		header : "编号",
		headerIndex : 'businessCode'
	}, {
		header : "项目类型",
		headerIndex : 'projectType'
	}, {
		header : "计划启动日期",
		headerIndex : 'planDateStart'
	}, {
		header : "计划关闭日期",
		headerIndex : 'planDateClose'
	}];
	var content = {
		columnModel : GridColumnType,
		dataUrl : "?model=rdproject_group_rdgroup&action=pageGroupAndProjectAll",
		lazyLoadUrl : "?model=rdproject_group_rdgroup&action=pageGroupAndProject",
		idColumn : 'id',// id所在的列,一般是主键(不一定要显示出来)
		parentColumn : 'parentId', // 父亲列id
		pageBar : true,
		pageSize : 15,
		pageBar : true,
		debug : false,
		analyzeAtServer : false,// 设置了dataUrl属性的时候，如果此属性是false表示分析树形结构在前台进行，默认是后台分析（仅支持java）,体现在json格式不用！
		multiChooseMode : 5,// 选择模式，共有1，2，3，4，5种。
		tableId : 'testTable',// 表格树的id
		checkOption : '',// 1:出现单选按钮,2:出现多选按钮,其他:不出现选择按钮
		rowCount : true,
		hidddenProperties : ['name'],// 用于隐藏在一行中的属性,适合传递值的一种方式.
		contextMenu : {
			width : 150,
			items : [{
				text : "打开",
				icon : "images/ico1.gif",
				alias : "1-1",
				action : function(t) {
					// 根据是否叶子判断选择的是项目组合还是项目
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
			// alert('懒加载执行完了..');
		},
		onSuccess : function(gt) {
			// alert('初次加载表格树执行完了..');
		},
		onPagingSuccess : function(gt) {
			// alert('翻页执行完了..');
		},
		lazy : false,// 使用懒加载模式（此时打开全部，关闭全部功能不可使用）
		leafColumn : 'leaf',// 用于判断节点是不是树叶
		el : 'newtableTree'// 要进行渲染的div id
	};
	myTree.loadData(content);
	myTree.makeTable();

	// 展开全部节点
	// _$('bt3').onclick=function(){myTree.expandAll();};
	// 展开第一层节点
	// _$('bt4').onclick=function(){myTree.closeAll();};
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
		alert('没有选择');
}

function openAll() {
	myTree.expandAll();
}

function closeAll() {
	myTree.closeAll();
}