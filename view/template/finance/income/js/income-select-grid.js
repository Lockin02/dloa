/** 收款列表* */

var show_page = function(page) {
	$("#incomeGrid").yxgrid("reload");
}

$(function() {
	$("#incomeGrid").yxgrid({
		model : 'finance_income_income',
		action : 'selectPageJson',
		param : {"formType" : "YFLX-DKD" ,'objIdArr' : $("#objId").val() },
		title : '选择到款',
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		customCode : "incomeGrid",
		isOpButton : false,
		// 过滤数据
		comboEx : [{
			text : '状态',
			key : 'status',
			datacode : 'DKZT',
			value : 'DKZT-YFP'
		}],
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '进账单号',
			name : 'inFormNum',
			sortable : true,
			width : 110,
			hide : true
		}, {
			display : '系统单据号',
			name : 'incomeNo',
			sortable : true,
			width : 120,
			process : function(v,row){
				if(row.id == 'noId') return v;
				return "<a href='#' onclick='showOpenWin(\"?model=finance_income_income&action=toAllot&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
			}
		}, {
			display : '到款单位id',
			name : 'incomeUnitId',
			sortable : true,
			hide : true
		}, {
			display : '到款单位',
			name : 'incomeUnitName',
			sortable : true,
			width : 130
		}, {
			display : '到款单位类型',
			name : 'incomeUnitType',
			sortable : true,
			datacode : 'KHLX',
			hide : true
		}, {
			display : '合同单位id',
			name : 'contractUnitId',
			sortable : true,
			hide : true
		}, {
			display : '合同单位',
			name : 'contractUnitName',
			sortable : true,
			width : 130,
			hide : true
		}, {
			display : '到款日期',
			name : 'incomeDate',
			sortable : true,
			width : 80
		}, {
			display : '到款金额',
			name : 'incomeMoney',
			sortable : true,
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 90
		}, {
			display : '录入人',
			name : 'createName',
			sortable : true,
			width : 80
		}, {
			display : '状态',
			name : 'status',
			datacode : 'DKZT',
			sortable : true,
			width : 80
		}, {
			display : '录入时间',
			name : 'createTime',
			sortable : true,
			width : 120,
			hide : true
		}, {
			display : '备注',
			name : 'remark',
			width : 120
		}],
        buttonsEx : [{
			name : 'Add',
			text : "确认选择",
			icon : 'add',
			action: function(row,rows,idArr ) {
				if(row){
					if(window.opener){
						window.opener.setIncomeObj(row);
					}
					//关闭窗口
					window.close();
				}else{
					alert('请选择一行数据');
				}
			}
        }],
		searchitems : [{
			display : '客户名称',
			name : 'incomeUnitName'
		},{
			display : '客户省份',
			name : 'province'
		},{
			display : '系统单据号',
			name : 'incomeNo'
		},{
			display : '到款金额',
			name : 'incomeMoney'
		},{
			display : '进账单号',
			name : 'inFormNum'
		},{
			display : '到款日期',
			name : 'incomeDateSearch'
		}],
		sortname : 'updateTime'
	});
});