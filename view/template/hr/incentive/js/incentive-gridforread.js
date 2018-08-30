var show_page = function(page) {
	$("#incentiveGrid").yxgrid("reload");
};
$(function() {

	$("#incentiveGrid").yxgrid({
		model : 'hr_incentive_incentive',
		action : 'pageJsonForRead',
		title : '奖惩信息查询',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		bodyAlign:'center',
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'userNo',
				display : '员工编号',
				sortable : true,
				width:70
			},  {
				name : 'userName',
				display : '员工姓名',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=hr_incentive_incentive&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
				},
				width : 60
			}, {
				name : 'deptName',
				display : '所属名称',
				sortable : true,
				hide : true
			},  {
				name : 'incentiveTypeName',
				display : '奖惩属性',
				sortable : true,
				width : 60
			}, {
				name : 'reason',
				display : '奖惩原因',
				sortable : true,
				width : 130
			}, {
				name : 'incentiveDate',
				display : '奖惩日期',
				sortable : true,
				width : 75
			}, {
				name : 'grantUnitName',
				display : '授予单位',
				sortable : true,
				width : 100
			}, {
				name : 'rewardPeriod',
				display : '工资月份',
				sortable : true,
				width : 70
			}, {
				name : 'incentiveMoney',
				display : '奖惩金额',
				sortable : true,
				process : function(v){
					if(v < 0){
						return "<span class='red'>" + moneyFormat2(v) + "</span>";
					}else{
						return moneyFormat2(v);
					}
				},
				width : 80
			}, {
				name : 'description',
				display : '奖惩说明',
				sortable : true
			}, {
				name : 'recordDate',
				display : '记录日期',
				sortable : true,
				width : 75
			}, {
				name : 'recorderName',
				display : '记录人',
				sortable : true,
				width : 60
			},  {
				name : 'remark',
				display : '备注说明',
				sortable : true,
				width : 130
			}],
		lockCol:['userNo','userName','incentiveTypeName'],//锁定的列名
		toViewConfig : {
			action : 'toView',
			formWith : 800,
			formHeight : 400
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
		//下拉过滤
		comboEx : [{
			text : '奖惩属性',
			key : 'incentiveType',
			datacode : 'HRJLSS'
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '员工编号',
			name : 'userNoSearch'
		},{
			display : '员工姓名',
			name : 'userNameSearch'
		}, {
			display : '授予单位',
			name : 'grantUnitName'
		}, {
			display : '奖惩日期',
			name : 'incentiveDateSearch'
		},{
			display : '奖惩原因',
			name : 'reason'
		}, {
			display : '奖惩说明',
			name : 'description'
		},{
			name : 'recordDateSearch',
			display : '记录日期'
		}, {
			display : '记录人',
			name : 'recorderName'
		}]
	});
});