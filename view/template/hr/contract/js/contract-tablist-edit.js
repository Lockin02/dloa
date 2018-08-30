var show_page = function(page) {
	$("#tablistGrid").yxgrid("reload");
};
$(function() {
	var userAccount = $("#userId").val();
	var userNo = $("#userNo").val();
	$("#tablistGrid").yxgrid({
		model : 'hr_contract_contract',
		title : '合同信息',
		param : {"userNoSelect" : $("#userNo").val()},
		showcheckbox:false,
		isAddAction : false,
		isEditAction : true,
		isDelAction:false,
		isOpButton : false,
		bodyAlign:'center',
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'userName',
			display : '员工姓名',
  			width:60,
			sortable : true
		}, {
			name : 'userNo',
			display : '员工编号',
  			width:60,
			sortable : true
		}, {
			name : 'conNo',
			display : '合同编号',
			sortable : true,
			process : function(v,row){
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_contract_contract&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
			}
		}, {
			name : 'conName',
			display : '合同名称',
			sortable : true
		}, {
			name : 'conTypeName',
			display : '合同类型',
			sortable : true
		},  {
			name : 'conStateName',
			display : '合同状态',
  			width:60,
			sortable : true
		}, {
			name : 'beginDate',
			display : '合同开始时间',
  			width:80,
			sortable : true
		}, {
			name : 'closeDate',
			display : '合同结束时间',
  			width:80,
			sortable : true
		}, {
			name : 'conNumName',
			display : '合同次数',
			sortable : true
		}],
		toViewConfig : {
			action : 'toView'
		},
		toEditConfig : {
			action : 'toEdit'
		},
		buttonsEx:[{
				name : 'add',
				text : "新增",
				icon : 'add',
				action : function(row) {
					showThickboxWin("?model=hr_contract_contract&action=toAddEdit&userNo="+userNo+"&userAccount="+userAccount
				          + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800")
				}
			}],
		/**
		 * 快速搜索
		 */
		searchitems : [ {
					display : '合同编号',
					name : 'conNo'
				}, {
					display : '合同名称',
					name : 'conName'
				}, {
					display : '合同类型',
					name : 'conTypeName'
				}, {
					display : '合同状态',
					name : 'conStateName'
				}]
	});
});