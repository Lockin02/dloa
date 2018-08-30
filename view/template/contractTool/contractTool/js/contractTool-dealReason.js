var show_page = function(page) {
	$("#dealReasonGrid").yxgrid("reload");
};
$(function() {
	//初始化右键按钮数组
	menusArr = [{
		text : '录入超期原因',
		icon : 'add',
		showMenuFn : function(row) {
			if (row) {
				return true;
			}
			return false;
		},
		action : function(row) {
			showThickboxWin('?model=contract_contract_receiptplan&action=toUpdateDealReason&id='
					+ row.id
					+ "&skey="
					+ row['skey_']
					+ '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=800');
		}
	}];
	$("#dealReasonGrid").yxgrid({
		model : 'contract_contract_receiptplan',
		action : 'dealPageJson',
		param : {
			'prinvipalId' : $("#userId").val()
		},

		title : '合同信息',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		// 扩展右键菜单
		menusEx : menusArr,
//		lockCol : ['flag', 'exeStatus', 'status2'],// 锁定的列名
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'contractCode',
			display : '合同编号',
			sortable : true,
			width : 180,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
						+ row.contractId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>" + v + "</fo  nt>" + '</a>';
			}
		}, {
			name : 'paymentterm',
			display : '付款条件',
			sortable : true,
			width : 60
		}, {
			name : 'paymentPer',
			display : '付款百分比',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'money',
			display : '计划付款金额',
			sortable : true,
			width : 100,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'Tday',
			display : '财务T-日',
			sortable : true,
			width : 80
		}, {
			name : 'invoiceMoney',
			display : '开票金额',
			sortable : true,
			width : 100,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'incomMoney',
			display : '到款金额',
			sortable : true,
			width : 100,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'dealDay',
			display : '收款到期日期',
			sortable : true,
			width : 100
		}, {
			name : 'dealD',
			display : '超期天数',
			sortable : true,
			width : 100,
			process : function(v,row){
				var Nowdate = formatDate(new Date());
                var days = DateDiff(row.dealDay,Nowdate);
                if(row.dealDay){
                	return days;
                }

			}

		}],

		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '合同编号',
			name : 'contractCode'
		}],
		sortname : "id"
	});
});
