var show_page = function(page) {
	$("#inventoryGrid").yxgrid("reload");
};

$(function() {
		var userAccount = $("#userAccount").val();
		var userNo = $("#userNo").val();
	$("#inventoryGrid").yxgrid({
		model : 'hr_invent_inventory',
		title : '人员盘点信息',
		bodyAlign:'center',
		isOpButton:false,
       	showcheckbox:false,
       	param:{"userNo":userNo},
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'userNo',
				display : '员工编号',
					width:70,
				sortable : true
			}, {
//				name : 'userAccount',
//				display : '员工账号',
//				sortable : true
//			}, {
				name : 'userName',
				display : '员工姓名',
				sortable : true,
					width:60,
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
				display : '部门',
				sortable : true
			}, {
				name : 'position',
				display : '职位',
				sortable : true
			}, {
				name : 'entryDate',
				display : '入职日期',
					width:80,
				sortable : true
			}, {
				name : 'inventoryDate',
				display : '盘点日期',
					width:80,
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
					width:80,
				sortable : true
			}, {
				name : 'isCore',
				display : '核心保留人才',
					width:80,
				sortable : true
			}, {
				name : 'recruitment',
				display : '市场招聘难度',
					width:80,
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
				display : '是否为预淘汰人员',
					width:90,
				sortable : true
			}, {
				name : 'remark',
				display : '是否可能流失',
					width:80,
				sortable : true
			}, {
				name : 'adjust',
				display : '对此员工的后续调整方向',
				sortable : true
			}],
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
		sortorder : "DESC",
		sortname : "id",
		title : '员工盘点信息'
	});
});