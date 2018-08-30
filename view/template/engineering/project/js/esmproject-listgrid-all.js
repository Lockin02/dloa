$(function() {
	$("#esmprojectGrid").esmprojectgrid({
		action : 'pageJson',
		title : '已下达项目',
		comboEx: [{
			text: "项目状态",
			key: 'status',
			data : [{
				text : '保存',
				value : '1'
				}, {
				text : '审批中',
				value : '2'
				}, {
				text : '打回',
				value : '4'
				}, {
				text : '执行中',
				value : '6'
				}, {
				text : '完成',
				value : '7'
				}, {
				text : '关闭',
				value : '8'
				}, {
				text : '待接收',
				value : '9'
				}, {
				text : '已接收',
				value : '10'
				}
			]
		},{
			text: "项目类型",
			key: 'projectType',
			datacode: 'GCXMXZ'
		},{
			text: "归属",
			key: 'officeId',
			data : [{
				text : ' 西安办事处 ',
				value : '46'
				}, {
				text : ' 成都办事处 ',
				value : '45'
				}, {
				text : ' 长沙办事处 ',
				value : '44'
				}, {
				text : ' 南京办事处 ',
				value : '43'
				}, {
				text : ' 沈阳办事处 ',
				value : '42'
				}, {
				text : ' 广州办事处 ',
				value : '41'
				}
			]
		}],
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=engineering_project_esmproject&action=readTab"
							+ "&id=" + row.id);
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '1' || row.status == '4'  || row.status == '9'  || row.status == '10' ) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
					showOpenWin("?model=engineering_project_esmproject&action=editTab"
							+ "&id=" + row.id);
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			text : '打开',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '6') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
					showOpenWin("?model=engineering_project_esmproject&action=openTab"
							+ "&id=" + row.id);
				} else {
					alert("请选中一条数据");
				}
			}
		}
		, {
			name : 'desmanager',
			text : '指定项目经理',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '2'||row.status == '7'||row.status == '8') {
					return false;
				}
			},
			action : function(row) {
				if(row.managerName != ''){
					if(confirm("确定要变更项目经理？")){
						showThickboxWin("?model=engineering_project_esmproject&action=designateManager"
						+ "&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 250 + "&width=" + 600);
					}
				}else{
					showThickboxWin("?model=engineering_project_esmproject&action=designateManager"
						+ "&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 250 + "&width=" + 600);
				}

			}
		}]
	});

});