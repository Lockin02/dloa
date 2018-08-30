var show_page = function(page) {
	$("#rewardrecordsGrid").yxgrid("reload");
};
$(function() {

	$("#rewardrecordsGrid").yxgrid({
		model : 'hr_reward_rewardrecords',
		action : 'pageJsonForRead',
		title : '薪资信息',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
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
				name : 'userAccount',
				display : '员工账号',
				sortable : true,
				hide : true
			}, {
				name : 'userName',
				display : '员工姓名',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=hr_reward_rewardrecords&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
				}
			}, {
				name : 'deptNameS',
				display : '二级部门',
				sortable : true
			}, {
				name : 'deptIdS',
				display : '二级部门Id',
				sortable : true,
				hide : true
			}, {
				name : 'deptNameT',
				display : '三级部门',
				sortable : true
			}, {
				name : 'deptIdT',
				display : '三级部门Id',
				sortable : true,
				hide : true
			}, {
				name : 'jobId',
				display : '职位id',
				sortable : true,
				hide : true
			}, {
				name : 'rewardPeriod',
				display : '发薪月份',
				sortable : true
			}, {
				name : 'rewardDate',
				display : '薪酬日期',
				sortable : true,
				hide : true
			}, {
				name : 'actRewardDate',
				display : '实发日期',
				sortable : true,
				hide : true
			}, {
				name : 'workDays',
				display : '本月工作日',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'leaveDays',
				display : '事假日',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'sickDays',
				display : '病假日',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'basicWage',
				display : '基本工资',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'provident',
				display : '个人公积金',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'socialSecurity',
				display : '个人社保',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'projectBonus',
				display : '项目奖金',
				sortable : true
			}, {
				name : 'specialBonus',
				display : '特别奖励',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'otherBonus',
				display : '其他奖金',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'mealSubsidies',
				display : '餐费补贴',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'transportSubsidies',
				display : '交通补贴',
				sortable : true,
				hide : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'otherSubsidies',
				display : '其余补贴',
				sortable : true
			}, {
				name : 'sickDeduction',
				display : '病假扣款',
				sortable : true,
				hide : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'leaveDeduction',
				display : '事假扣款',
				sortable : true,
				hide : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'specialDeduction',
				display : '特别扣款',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'preTaxWage',
				display : '税前工资',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'afterTaxWage',
				display : '税后工资',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'taxes',
				display : '扣除税金',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'remark',
				display : '备注信息',
				sortable : true,
				hide : true
			}, {
				name : 'createName',
				display : '创建人名称',
				sortable : true,
				hide : true
			}, {
				name : 'createTime',
				display : '创建时间',
				sortable : true,
				hide : true
			}, {
				name : 'updateName',
				display : '修改人名称',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '修改时间',
				sortable : true,
				hide : true
			}],
		toViewConfig : {
			showMenuFn : function(row) {
				if ((row.id == "noId")) {
					return false;
				}
			},
			action : 'toView',
			formWidth : '900',
			formHeight : '500'
		},
//		buttonsEx : [
//	        {
//				name : 'view',
//				text : "高级查询",
//				icon : 'view',
//				action : function() {
//					alert('功能暂未开发完成');
//					showThickboxWin("?model=finance_payablesapply_payablesapply&action=toSearch&"
//						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
//				}
//	        }
//	    ],
		searchitems : [{
			display : "员工编号",
			name : 'userNoM'
		},{
			display : "员工姓名",
			name : 'userNameM'
		},{
			display : "部门",
			name : 'deptName'
		},{
			display : "发薪月份",
			name : 'rewardPeriodSearch'
		}]
	});
});