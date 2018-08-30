
var myTree = new GridTree();
/**
 * 主要的测试方法
 */
function newRoleTreeGrid() {
	var projectId=$('#projectId').val();

	var GridColumnType = [
			/**
			 * { header : 'id', headerIndex : 'id', width : '10%' },
			 */
			{
		header : '名称',
		headerIndex : 'roleName',
		align : 'left',
		width : '40%',
		// val为默认传入值,row当前行对象,cell为当前列,tc为表格对象
		render : function(val, row, cell, tc) {
			return "<img src='" + tc.imgPath + "user.gif'>" + val;
		}
	}, {
		header : '备注',
		headerIndex : 'notes'
	}];
	var content = {
		columnModel : GridColumnType,
		dataUrl : "?model=rdproject_role_rdrole&action=pageAll",
		idColumn : 'id',// id所在的列,一般是主键(不一定要显示出来)
		parentColumn : 'parentId', // 父亲列id parentId
		param : {
			projectId : $('#projectId').val()
		},
		pageBar : true,
		pageSize : 15,
		pageBar : true,
		debug : false,
		analyzeAtServer : false,// 设置了dataUrl属性的时候，如果此属性是false表示分析树形结构在前台进行，默认是后台分析（仅支持java）,体现在json格式不用！
		// multiChooseMode : 4,// 选择模式，共有1，2，3，4，5种。
		tableId : 'testTable',// 表格树的id
		checkOption : '',// 1:出现单选按钮,2:出现多选按钮,其他:不出现选择按钮
		rowCount : true,
		// hidddenProperties : ['name', 'projectName'],//
		// 用于隐藏在一行中的属性,适合传递值的一种方式.
		contextMenu : {
			// width : 150,
			// alias:"1-1",
			items : [{
				text : "编辑",
				alias : "1-1",
				icon: oa_cMenuImgArr['edit'],
				action : function(row) {
					showThickboxWin('?model=rdproject_role_rdrole&action=init&id='
							+ row.pid
							+ '&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=650');
				}
			}, {
				text : "删除",
				alias : "1-2",
				icon: oa_cMenuImgArr['del'],
				action : function(row) {
					GridTree.prototype
							._delete('rdproject_role_rdrole', row.pid);
				}
			}, {
				text : "授权",
				alias : "1-3",
				icon: oa_cMenuImgArr['focus'],
				action : function(row) {
					var proCenter=$('#proCenter').val();
					showThickboxWin('?model=rdproject_role_rdrole&action=authorize&id='
							+ row.pid+'&projectId='+projectId+'&proCenter='+proCenter
							+ '&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=600');
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
		el : 'roleTableTree'// 要进行渲染的div id
	};
	myTree.loadData(content);
	myTree.makeTable();

	// 展开全部节点
	// _$('bt3').onclick=function(){myTree.expandAll();};
	// 展开第一层节点
	// _$('bt4').onclick=function(){myTree.closeAll();};
}
