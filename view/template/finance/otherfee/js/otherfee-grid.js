var show_page = function(page) {
	$("#otherfeeGrid").yxgrid("reload");
};
$(function() {
	

	$("#otherfeeGrid").yxgrid({
		model : 'finance_otherfee_otherfee',
		title : '非报销类费用',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'accountYear',
			display : '会计年度',
			sortable : true
		}, {
			name : 'accountPeriod',
			display : '会计期间',
			sortable : true
		}, {
			name : 'summary',
			display : '摘要',
			sortable : true
		}, {
			name : 'subjectName',
			display : '科目名称',
			sortable : true
		}, {
			name : 'debit',
			display : '借方金额',
			sortable : true,
			process : function(v){
				return moneyFormat2(v);
				}
		}, {
			name : 'chanceCode',
			display : '商机编号',
			sortable : true
		}, {
			name : 'trialProjectCode',
			display : '试用项目编号',
			sortable : true
		}, {
			name : 'feeDeptName',
			display : '费用归属部门',
			sortable : true
		}, {
			name : 'contractCode',
			display : '合同编号',
			sortable : true
		}, {
			name : 'province',
			display : '省份',
			sortable : true
		}],

		buttonsEx : [{
			name : 'exportIn',
			text : "导入",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=finance_otherfee_otherfee&action=toEportExcelIn"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
		}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},

		searchitems : [{
			display : "会计年度",
			name : 'accountYear'
		},{
			display : "会计期间",
			name : 'accountPeriod'
		},{
			display : "科目名称",
			name : 'subjectName'
		},{
			display : "商机编号",
			name : 'chanceCode'
		},{
			display : '试用项目编号',
			name : 'trialProjectCode'
		},{
			display : "费用归属部门",
			name : 'feeDeptName'
		},{
			display : "合同编号",
			name : 'contractCode'
		},{ 
			display : "省份",
			name : 'province'
		}]
	});
});