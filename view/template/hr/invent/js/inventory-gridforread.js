var show_page = function(page) {
	$("#inventoryGrid").yxgrid("reload");
};

$(function() {

	buttonsArr = [
        {
			name : 'view',
			text : "查询",
			icon : 'view',
			action : function() {
				showThickboxWin("?model=hr_invent_inventory&action=toSearch"
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
			}
        }
    ];

	//表头按钮数组
	excelOutArr = {
		name : 'exportIn',
		text : "导入",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_invent_inventory&action=toImport"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};

	$.ajax({
		type : 'POST',
		url : '?model=hr_personnel_personnel&action=getLimits',
		data : {
			'limitName' : '导入权限'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(excelOutArr);
			}
		}
	});

	$("#inventoryGrid").yxgrid({
		model : 'hr_invent_inventory',
		action : 'pageJsonForRead',
		title : '人员盘点信息',
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'userNo',
				display : '员工编号',
				sortable : true
			}, {
//				name : 'userAccount',
//				display : '员工账号',
//				sortable : true
//			}, {
				name : 'userName',
				display : '员工姓名',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=hr_invent_inventory&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
				}
			}, {
//				name : 'companyType',
//				display : '公司类型',
//				sortable : true
//			}, {
//				name : 'companyName',
//				display : '公司名称',
//				sortable : true
//			}, {
				name : 'deptNameS',
				display : '部门名称',
				sortable : true
			}, {
//				name : 'deptNameT',
//				display : '三级部门',
//				sortable : true
//			}, {
				name : 'entryDate',
				display : '入职日期',
				sortable : true
			}, {
				name : 'inventoryDate',
				display : '盘点日期',
				sortable : true
			}, {
				name : 'alternative',
				display : '此职位的市场可替代性',
				sortable : true
			}, {
				name : 'matching',
				display : '现工作能力与现在职位的匹配度',
				sortable : true
			}, {
				name : 'critical',
				display : '员工关键性',
				sortable : true
			}, {
				name : 'isCore',
				display : '核心保留人才',
				sortable : true
			}, {
				name : 'recruitment',
				display : '市场招聘难度',
				sortable : true
			}, {
				name : 'recruitment',
				display : '对绩效达成情况的评价',
				sortable : true
			}, {
				name : 'examine',
				display : '上一季度考核是否排后5%',
				sortable : true
			}, {
				name : 'preEliminated',
				display : '预淘汰',
				sortable : true
			}, {
				name : 'remark',
				display : '是否可能流失',
				sortable : true
			}, {
				name : 'adjust',
				display : '对此员工的后续调整方向',
				sortable : true
			}],

//		buttonsEx : buttonsArr,
		menusEx : [{
			name : 'view',
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_invent_inventory&action=toView&id="
							+ row.id
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=900");
				} else {
					alert("请选中一条数据");
				}
			}
		}],

		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '员工编号',
			name : 'userNoM'
		}, {
			display : '员工名称',
			name : 'userNameM'
		}, {
//			display : '公司名称',
//			name : 'companyName'
//		}, {
			display : '部门名称',
			name : 'deptName'
		}],
		sortorder : "DESC",
		sortname : "id",
		title : '员工盘点信息'
	});
});