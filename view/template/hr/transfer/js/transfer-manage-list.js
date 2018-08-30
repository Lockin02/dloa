var show_page = function(page) {
	$("#transferGrid").yxgrid("reload");
};
$(function() {
	$("#transferGrid").yxgrid({
		model : 'hr_transfer_transfer',
		param : {
			'ExaStatus' : "完成",
			'deptId' : $("#deptId").val()
		},
		title : '调动管理',
		isAddAction:false,
		isEditAction:false,
		isViewAction:false,
		isDelAction : false,
		isOpButton:false,
		bodyAlign:'center',
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			},  {
				name : 'formCode',
				display : '单据编号',
				sortable : true,
				width:120,
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
			},{
				name : 'stateC',
				display : '单据状态',
				width : 70
			},  {
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
				name : 'preJobName',
				display : '调动前单位',
				sortable : true,
				hide : true
			}, {
				name : 'preUnitName',
				display : '调动前公司',
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
				name : 'preJobName',
				display : '调动前职位',
				sortable : true
			}, {
				name : 'afterJobName',
				display : '调动后单位类型',
				sortable : true,
				hide : true
			}, {
				name : 'afterUnitName',
				display : '调动后公司',
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
				name : 'afterJobName',
				display : '调动后职位',
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
			}],
			lockCol:['formCode','userNo','userName'],//锁定的列名
			menusEx:[
			{  text:'查看',
			   icon:'view',
			   action:function(row){
			   		if(row){
						 showThickboxWin("?model=hr_transfer_transfer&action=toViewJobTran&id="
						 + row.id +
						 "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=800");
			   		}
			   }
			},
			{  text:'填写交接内容',
			   icon:'edit',
			   showMenuFn : function(row) {
				if (row.employeeOpinion==1 && row.status == 3) {
					return true;
				}
				return false;
				},
			   action:function(row){
			   		if(row){
						 location = "?model=hr_transfer_transfer&action=toLeaderView&id="+ row.id;
			   		}
			   }
			}],
		toAddConfig : {
			formHeight : 500,
			formWidth : 900
		},
		toEditConfig : {
			action : 'toEdit',
			formHeight : 500,
			formWidth : 900
		},
		toViewConfig : {
			action : 'toView',
			formHeight : 500,
			formWidth : 900
		},
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '单据编号',
			name : 'formCode'
		},{
			display : '员工编号',
			name : 'userNoSearch'
		},{
			display : '员工姓名',
			name : 'userNameSearch'
		},{
			display : '入职日期',
			name : 'entryDate'
		},{
			display : '申请日期',
			name : 'applyDate'
		},  {
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