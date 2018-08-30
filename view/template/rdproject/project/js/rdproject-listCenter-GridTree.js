var myTree = new GridTree();

function myfun(jquery){

}($);

/**
 * 主要的测试方法
 */
function newProjectTreeGrid() {
	var GridColumnType = [
			/**
			 * { header : 'id', headerIndex : 'id', width : '10%' },
			 */
	{
		header : ' ',
		headerIndex : 'warpRateMig'
	},{
		header : '编号',
		headerIndex : 'code',
		align : 'left',
		// val为默认传入值,row当前行对象,cell为当前列,tc为表格对象
		render : function(val, row, cell, tc) {
			if ( showMenuFnG(row) ) {// 如果是组合，前面加上图标
				return "<img src='" + tc.imgPath + "group.gif'>" + val;
			}else{
				return "<img src='" + tc.imgPath + "project.gif'>" + val;
			}
		}
	}, {
		header : '名称',
		headerIndex : 'name',
		align : 'left',
		// val为默认传入值,row当前行对象,cell为当前列,tc为表格对象
		render : function(val, row, cell, tc) {
			if ( showMenuFnG(row) ) {// 如果是组合，前面加上图标
				return "<img src='" + tc.imgPath + "group.gif'>" + val;
			}else{
				return "<img src='" + tc.imgPath + "project.gif'>" + val;
			}
		}
	}, {
		header : '所属部门',
		headerIndex : 'depName'
	}, {
		header : "责任人",
		headerIndex : 'managerName'
	}, {
		header : "状态",
		headerIndex : 'statusCN'
	}, {
		header : "偏差率",
		headerIndex : 'warpRate'
	}, {
		header : "完成率",
		headerIndex : 'effortRate'
	}, {
		header : "当前里程碑",
		headerIndex : 'pointName'
	}, {
		header : "项目类型",
		headerIndex : 'projectTypeC'
	}, {
		header : "计划启动日期",
		headerIndex : 'planDateStart'
	}, {
		header : "计划关闭日期",
		headerIndex : 'planDateClose'
	}];
	var statusStr = $('#statusStr').val();
	var url='';
	if(statusStr.length==0){
		var url='';
	}else{
		url='&'+statusStr+'=1';
	}
	var content = {
		iconShowIndex : 1,
		columnModel : GridColumnType,
		dataUrl : "?model=rdproject_project_rdproject&action=projectAndGroup&parentId=-1&projectType=" + $('#projectType').val() + url,
		lazyLoadUrl : "?model=rdproject_project_rdproject&action=projectAndGroup",
		idColumn : 'oid',// id所在的列,一般是主键(不一定要显示出来)
		parentColumn : 'oParentId', // 父亲列id
		pageBar : true,// 表示要展示分页栏，也就是会出现分页的效果
		pageSize : 15,
		debug : false,//出现一个展示表格树的加载信息的div框
		analyzeAtServer : false,// 设置了dataUrl属性的时候，如果此属性是false表示分析树形结构在前台进行，默认是后台分析（仅支持java）,体现在json格式不用！
		multiChooseMode : 5,// 选择模式，共有1，2，3，4，5种。
		tableId : 'testTable',// 表格树的id
		checkOption : '',// 1:出现单选按钮,2:出现多选按钮,其他:不出现选择按钮
		rowCount : true,//默认是没有这一列
		explandAll : true,//全展
		hidddenProperties : ['projectName','status','type'],// 用于隐藏在一行中的属性,适合传递值的一种方式.
		contextMenu : {
			// width : 150,
			// alias:"1-1",
			items : [{
				text : "添加",
				alias : "1-1",
				showMenuFn : showMenuFnG,
				type : 'group',
				icon: oa_cMenuImgArr['add'],
				items : [{
							text : "新建组合",
							alias : "1-1-1",
							icon: oa_cMenuImgArr['add'],
							action : function(row) {
								showThickboxWin('?model=rdproject_group_rdgroup&action=toAdd&gpId='
									+ row.pid
									+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
							}
						},{
							text : "新建项目",
							alias : "1-1-2",
							icon: oa_cMenuImgArr['add'],
							action : function(row) {
								showThickboxWin('?model=rdproject_project_rdproject&action=rpToAdd&gpId='
									+ row.pid
									+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
							}
						},{
							text : "新建项目(不经审批)",
							alias : "1-1-3",
							icon: oa_cMenuImgArr['add'],
							action : function(row) {
								showThickboxWin('?model=rdproject_project_rdproject&action=toAddNoApproval&gpId='
									+ row.pid
									+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
							}
						}]
			},{
				text : "查看",
				// 返回true显示菜单，返回flase不显示菜单
				// showMenuFn : function(row) {
				// return true;
				// },
				alias : "1-2",
				icon: oa_cMenuImgArr['read'],
				action : function(row) {
					//根据是否有项目名称判断是项目还是组合
					if ( showMenuFnG(row) ) {
						showOpenWin('?model=rdproject_group_rdgroup&action=rgRead&gpId='
								+ row.pid
								+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
					} else {
						showOpenWin('?model=rdproject_project_rdproject&action=rpRead&pjId='
								+ row.pid);
					}
					// alert(t.pid)
				}
			},{
				text : "管理",
				alias : "1-3",
				showMenuFn : function(obj){
					if(showMenuFnP(obj)&&obj.status!=7&&obj.status!=8  ){
						return true;
					}else{
						return false;
					}
				},
				icon: oa_cMenuImgArr['open'],
				action : function(row) {
				showOpenWin('?model=rdproject_project_rdproject&action=rpOpenManage&proCenter=1&pjId='+ row.pid);
				}
			},{
				text : "修改",
				alias : "1-4",
				icon: oa_cMenuImgArr['edit'],
				showMenuFn : function(obj){
					if(obj.status!=1 && obj.status!=4 && showMenuFnP(obj) ){
						return false;
					}else{
						return true;
					}
				},
				action : function(row) {
					if ( showMenuFnG(row) ) {
						showThickboxWin('?model=rdproject_group_rdgroup&action=rgUpdateTo&gpId='
								+ row.pid
								+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
					} else {
						showOpenWin('?model=rdproject_project_rdproject&action=rpUpdateTo&pjId='
								+ row.pid);
					}
				}
			},{
				text : "旧研发数据修改",
				alias : "1-5",
				icon: oa_cMenuImgArr['edit'],
				showMenuFn : function(obj){
					if( !showMenuFnP(obj) ){
						return false;
					}else{
						return true;
					}
				},
				action : function(row) {
					showOpenWin('?model=rdproject_project_rdproject&action=rpUpdateToOld&pjId='
							+ row.pid);
				}
			},{
				text : "删除",
				alias : "1-6",
				icon: oa_cMenuImgArr['del'],
				showMenuFn : function(obj){
					if(obj.status!=1 && obj.status!=4 && showMenuFnP(obj) ){
						return false;
					}else{
						return true;
					}
				},
				action : function(row) {
					if( showMenuFnG(row) ){
						if( confirm("确认删除吗？如果此组合下存在项目或者组合则不可删除") ){
							location = "?model=rdproject_group_rdgroup&action=rgDel&gpId="+row.pid;
						}
					}else{
						location = "?model=rdproject_project_rdproject&action=rpDel&pjId="+row.pid;
					}
				}
				//TODO:
//			},{
//				text : "修改",
//				alias : "1-4",
//				icon: oa_cMenuImgArr['edit'],
//				showMenuFn : function(obj){
//					if( obj.status!=6 && showMenuFnP(obj) ){
//						return false;
//					}else{
//						return true;
//					}
//				},
//				action : function(row) {
//					if ( row ) {
//						showThickboxWin('?model=rdproject_project_rdproject&action=toEditProject&pjId='
//								+ row.pid
//								+ '&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750');
//					}
//				}
			}
//			,{
//				text : "设置为模板",
//				alias : "1-8",
//				icon: oa_cMenuImgArr['template']
//				//TODO:
//			}
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
}

//判断是否是组合
function showMenuFnG(obj){
	if(obj.type==2){
		return true;
	}else{
		return false;
	}
}

//判断是否是项目
function showMenuFnP(obj){
	if(obj.type==1){
		return true;
	}else{
		return false;
	}
}

function show_page(page) {
	myTree._reload();
}

$(function(){
	var importExcel = "?model=rdproject_task_rdtask&action=toImportExcel"
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=700";
	$("#importTask").attr("alt", importExcel);
});

