var show_page = function(page) {
	$("#viewlistGrid").yxgrid("reload");
};
$(function() {
	$("#viewlistGrid").yxgrid({
		model : 'projectmanagent_trialproject_extension',
		action : "extPageJson",
		param : {'trialprojectId' : $("#proId").val()},
		title : '延期申请',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'trialprojectCode',
			display : '试用项目编号',
			width : 130,
			sortable : true
		}, {
			name : 'affirmMoneyOld',
			display : '原确认金额',
			sortable : true
		}, {
			name : 'endDateOld',
			display : '原结束时间',
			sortable : true,
			hide : true
		}, {
			name : 'extensionDate',
			display : '延期日期',
			sortable : true,
			width : 150
		}, {
			name : 'newProjectDays',
			display : '工期延长至',
			sortable : true
		}, {
			name : 'budgetAll',
			display : '预算',
			sortable : true
		}, {
			name : 'feeAllCount',
			display : '决算',
			sortable : true
		}, {
			name : 'affirmMoney',
			display : '概算',
			sortable : true
		}, {
			name : 'createTime',
			display : '申请时间',
			width : 130,
			sortable : true
		}, {
			name : 'createName',
			display : '申请人',
			sortable : true
		}, {
			name : '',
			display : '商机',
			sortable : true
		}, {
			name : 'extensionTime',
			display : '延期次数',
			sortable : true,
			process : function(v,row){
							return "<span>第"+v+"次</span>";
					}
		}, {
			name : 'costReason',
			display : '理由',
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true
		}, {
			name : 'ExaDT',
			display : '审批日期',
			sortable : true
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
        // 扩展右键菜单
        menusEx : [{
            text : '审批情况',
            icon : 'view',
            showMenuFn : function(row) {
                if (row.ExaStatus == '部门审批') {
                    return true;
                }
                return false;
            },
            action : function(row) {

                showThickboxWin('controller/projectmanagent/trialproject/readview.php?itemtype=oa_trialproject_extension&pid='
                    + row.id
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
            }
        }],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '试用项目编号',
			name : 'trialprojectCode'
		}, {
			display : '申请人',
			name : 'createName'
		}]
	});
});