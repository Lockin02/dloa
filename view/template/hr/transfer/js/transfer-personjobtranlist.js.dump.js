var show_page = function(page) {
	$("#transferGrid").yxgrid("reload");
};
$(function() {
	$("#transferGrid").yxgrid({
		model : 'hr_transfer_transfer',
		param : {
			'userNo' : $("#userNo").val(),
			'ExaStatus' : "完成",
			'status' : '2,3,4'
		},
		title : '人员调岗记录',
		isAddAction:false,
		isEditAction:false,
		isViewAction:false,
		isDelAction : false,
		showcheckbox:false,
		isOpButton:false,
		bodyAlign:'center',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'formCode',
			display : '单据编号',
			width:120,
			sortable : true,
			process : function(v,row){
				return "<a href='#' onclick='location=\"?model=hr_transfer_transfer&action=toViewJobTran&id=" + row.id +"\"'>" + v + "</a>";
			}
		},{
			name : 'userNo',
			display : '员工编号',
			sortable : true,
			width : 80
		},{
			name : 'userName',
			display : '员工姓名',
			sortable : true,
			width : 80
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 80
		}, {
			name : 'entryDate',
			display : '入职日期',
			sortable : true,
			width : 80
		}, {
			name : 'applyDate',
			display : '申请日期',
			sortable : true,
			width : 80
		}, {
			name : 'transferTypeName',
			display : '调动类型',
			sortable : true,
			width : 200
		}, {
			name : 'preUnitTypeName',
			display : '调动前单位',
			sortable : true,
			hide : true
		}, {
			name : 'preUnitName',
			display : '调动前公司',
			sortable : true
		}, {
			name : 'afterUnitTypeName',
			display : '调动后单位类型',
			sortable : true,
			hide : true
		}, {
			name : 'afterUnitName',
			display : '调动后公司',
			sortable : true
		}, {
			name : 'preBelongDeptName',
			display : '调动前所属部门',
			sortable : true
		}, {
			name : 'afterBelongDeptName',
			display : '调动后所属部门',
			sortable : true
		}, {
			name : 'preDeptNameS',
			display : '调动前二级部门',
			sortable : true
		}, {
			name : 'preDeptNameT',
			display : '调动前三级部门',
			sortable : true
		}, {
			name : 'afterDeptNameS',
			display : '调动后二级部门',
			sortable : true
		}, {
			name : 'afterDeptNameT',
			display : '调动后三级部门',
			sortable : true
		}, {
			name : 'preJobName',
			display : '调动前职位',
			sortable : true
		}, {
			name : 'afterJobName',
			display : '调动后职位',
			sortable : true
		}, {
			name : 'preUseAreaName',
			display : '调动前归属区域',
			sortable : true
		}, {
			name : 'afterUseAreaName',
			display : '调动后归属区域',
			sortable : true
		}, {
			name : 'reason',
			display : '调动原因',
			sortable : true,
			hide : true,
			width : 130
		}, {
			name : 'remark',
			display : '备注说明',
			sortable : true,
			hide : true,
			width : 130
		}, {
			name : 'managerName',
			display : '申请人',
			sortable : true
		}, {
			name : 'employeeOpinion',
			display : '员工是否同意',
			sortable : true,
			hide : true
		}],
		toViewConfig : {
			action : 'toView',
			formHeight : 500,
			formWidth : 900
		},
		//拓展右键菜单
		menusEx:[{  text:'查看',
			   icon:'view',
			   action:function(row){
			   		if(row){
						 location = "?model=hr_transfer_transfer&action=toViewJobTran&id="+ row.id;
			   		}
			   }
			},
			{  text:'员工意见',
			   icon:'edit',
			   showMenuFn : function(row) {
					if (row.employeeOpinion!=1) {
						return true;
				}
				return false;
				},
			   action:function(row){
			   		if(row){
						 location = "?model=hr_transfer_transfer&action=toOpinionView&id="+ row.id ;
			   		}
			   }
			},
			{
			name : 'aduit',
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '打回' || row.ExaStatus == '完成' || row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_personnel_transfer&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '单据编号',
			name : 'formCode'
		}, {
			display : '调动前公司',
			name : 'preUnitName'
		}, {
			display : '调动前所属部门',
			name : 'preBelongDeptName'
		},{
			display : '调动前二级部门',
			name : 'preDeptNameS'
		},{
			display : '调动前三级部门',
			name : 'preDeptNameT'
		}, {
			display : '调动后公司',
			name : 'afterUnitName'
		}, {
			display : '调动后所属部门',
			name : 'afterBelongDeptName'
		}, {
			display : '调动后二级部门',
			name : 'afterDeptNameS'
		}, {
			display : '调动后三级部门',
			name : 'afterDeptNameT'
		}, {
			display : '调动前职位',
			name : 'preJobName'
		}, {
			display : '调动后职位',
			name : 'afterJobName'
		},  {
			display : '调动前归属区域',
			name : 'preUseAreaName'
		}, {
			display : '调动后归属区域',
			name : 'afterUseAreaName'
		},{
			display : '申请人',
			name : 'managerName'
		}]
	});
});