var show_page = function(page) {
	$("#rewardGrid").yxgrid("reload");
};
$(function() {
	$("#rewardGrid").yxgrid({
		model : 'hr_tutor_reward',
		title : '导师奖励管理',
		event : {
			'row_dblclick' : function(e, row, data) {
				showModalWin("?model=hr_tutor_reward&action=toView&id="
						+ data.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			}
		},
//		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
		bodyAlign:'center',
		customCode : 'hr_reward_deptlist',

		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'code',
			display : '编号',
			sortable : true,
			width : 100,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=hr_tutor_reward&action=toView&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			name : 'name',
			display : '方案名称',
			sortable : true,
			width : 150
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			width : 80,
			sortable : true
		}, {
			name : 'isGrant',
			display : '奖励发放状态',
			width : 80,
			sortable : true,
			process: function (v,row){
			   if(v=='0'){
			      return "未发放";
			   }else if(v==1){
			      return "已发放";
			   }
			}
		}, {
			name : 'isPublish',
			display : '奖励信息是否发布',
			sortable : true,
			width : 80,
			process: function (v){
			   if(v=='0'){
			      return "否";
			   }else if(v==1){
			      return "是";
			   }
			},
			width :120
		}, {
			name : 'createName',
			display : '申请人',
			width : 80,
			sortable : true
		}, {
			name : 'createTime',
			display : '申请时间',
			width : 100,
			sortable : true
		}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toViewByDept'
		},
		// 扩展右键菜单
		menusEx : [{
			text : '修改',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row) {
               showModalWin("?model=hr_tutor_reward&action=toEdit&id="+row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}

		},{
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成' || row.ExaStatus == '部门审批') {
					return false;
				}
				return true;
			},
			action : function(row) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_tutor_reward&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								$("#rewardGrid").yxgrid("reload");
							}
						}
					});
				}
			}

		}],
		searchitems : [{
			display : "编号",
			name : 'code'
		},{
			display : "方案名称",
			name : 'name'
		}]
	});
});