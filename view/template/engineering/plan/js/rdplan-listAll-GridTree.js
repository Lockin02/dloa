function show_page(page) {
	myTree._reload();
}

$(document).ready(function() {
    //实例添加树形列表
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
 * 主要的测试方法
 */
function newProjectTreeGrid() {
	var GridColumnType = [
			/**
			 * { header : 'id', headerIndex : 'id', width : '10%' },
			 */
	{
		header : '状态标记',
		width : "5%",
		headerIndex : 'warpRateMig',
		render:function(v){
			if(!isNaN(v)){
				if(v > 0){
					return "<img src='"+oa_cMenuImgArr['red']+"' title='偏差率大于0'/>";
				}else{
					return "<img src='"+oa_cMenuImgArr['green']+"' title='偏差率小于等于0'/>";
				}
			}else{
				return v;
			}
		}
	},{
		header : '项目名称',
		headerIndex : 'name',
		width : "15%",
		align : 'left'
	}, {
		header : '责任人',
		width : "8%",
		headerIndex : 'managerName'
	}, {
		header : "计划名称",
		width : "15%",
		headerIndex : 'planName',
		align : 'left',
		render:function(v){
			if(v!=undefined){
				return v;
			}else{
				return "里程碑计划";
			}
		}
	}, {
		header : "计划开始日期",
		width : "10%",
		headerIndex : 'planDateStart'
	}, {
		header : "计划完成日期",
		width : "10%",
		headerIndex : 'planDateClose'
	}, {
		header : "偏差率",
		width : "7%",
		headerIndex : 'warpRate'
	}, {
		header : "完成率",
		width : "7%",
		headerIndex : 'effortRate'
	}, {
		header : "状态",
		width : "5%",
		headerIndex : 'statusCN'
	}, {
		header : "项目编号",
		headerIndex : 'projectCode'
	}];
	var content = {
		columnModel : GridColumnType,
		dataUrl : "?model=engineering_project_engineering&action=rpAjaxAllPlan",
		lazyLoadUrl : "?model=engineering_plan_rdplan&action=rpAjaxAllPlan",
		idColumn : 'oid',// id所在的列,一般是主键(不一定要显示出来)
		parentColumn : 'oParentId', // 父亲列id
		pageBar : true,// 表示要展示分页栏，也就是会出现分页的效果
		pageSize : 15,
		debug : false,// 出现一个展示表格树的加载信息的div框
		analyzeAtServer : false,// 设置了dataUrl属性的时候，如果此属性是false表示分析树形结构在前台进行，默认是后台分析（仅支持java）,体现在json格式不用！
		multiChooseMode : 5,// 选择模式，共有1，2，3，4，5种。
		tableId : 'testTable',// 表格树的id
		checkOption : '',// 1:出现单选按钮,2:出现多选按钮,其他:不出现选择按钮
		rowCount : true,// 默认是没有这一列
		postProperties : ['planName'],
		hidddenProperties : ['name', 'projectId', 'projectName', 'planName','purviewKey'],// 用于隐藏在一行中的属性,适合传递值的一种方式.
		iconShowIndex : 3,
		contextMenu : {
			// width : 150,
			// alias:"1-1",
			items : [{
				text : "新建计划",
				alias : "1-1",
				type : 'group',
				icon : oa_cMenuImgArr['add'],
				showMenuFn : showMenuFnC,
				items : [{
					text : "新建空白计划",
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
					text : "从模板导入计划",
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
				text : "打开里程碑",
				alias : "1-6",
				icon : oa_cMenuImgArr['open'],
				showMenuFn : showMenuFnP,
				action : function(row) {
						showThickboxWin('?model=engineering_milestone_rdmilestone&action=rmListCenter&pjId='
								+ row.projectId
								+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=950');
					}
			},{
				text : "打开计划任务",
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
				type : "splitLine" // 隔行
			}, {
				text : "查看",
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
				text : "编辑",
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
				text : "删除",
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
				text : "保存为模板",
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
//				type : "splitLine" // 隔行
//			}, {
//				text : "设置为模板",
//				alias : "1-8",
//				icon: oa_cMenuImgArr['template'],
//				action : function(row) {
//					alert('开发中');
//				}
//					// TODO:
//				}
				]
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
		lazy : true,// 使用懒加载模式（此时打开全部，关闭全部功能不可使用）
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

// 判断是否是计划 切判断权限
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

//判断是否是计划
function showMenuFnB(obj) {
	if (obj.planName) {
		return true;
	} else {
		return false;
	}
}

// 判断是否是项目
function showMenuFnP(obj) {
	if (obj.name) {
		return true;
	} else {
		return false;
	}
}

//判断是否是项目
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

// 查看选择的节点
function showChoosed() {
	var ans = getAllCheckValue();
	if (ans != '')
		alert(ans);
	else
		alert('没有选择');
}

// 打开所有节点
function openAll() {
	myTree.expandAll();
}

// 关闭所有节点
function closeAll() {
	myTree.closeAll();
}