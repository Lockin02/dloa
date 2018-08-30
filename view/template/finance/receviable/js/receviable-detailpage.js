/** 到款列表* */

var show_page = function(page) {
	$("#receviableGrid").yxgrid("reload");
};

$(function() {

	$("#receviableGrid").yxgrid({

		model : 'finance_receviable_receviable',
		action : 'detailPageJson',
		title : '应收账款明细 -- 客户 : ' + $("#customerName").val() + " -- " + $("#year").val() + "年" + $("#beginMonth").val() + "月至"  + $("#endMonth").val() + "月" ,
		param : { 'salesmanId' : $("#salesmanId").val(), 'deptIds' : $("#deptId").val() , "customerId" : $("#customerId").val() ,'beginMonth' : $("#beginMonth").val() ,'endMonth' : $("#endMonth").val() , 'year' : $("#year").val() },
		isToolBar : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isShowNum : false,
		usepager : false, // 是否分页

		colModel : [{
			display: 'id',
			name: 'id',
			hide: true
		},{
			display: '表单id',
			name: 'objId',
			hide: true
		},
		{
			name: 'formDate',
			display: '单据日期'
		},
		{
			name: 'objCode',
			display: '单据编号',
			width : 150
		},
		{
			name: 'formType',
			display: '单据类型',
			process : function(v){
				switch(v){
					case 'invoice': return '销售发票';break;
					case 'YFLX-DKD' : return '到款单';break;
					case 'YFLX-YFK' : return '预收款';break;
					case 'YFLX-TKD' : return '退款单';break;
					default : return v;
				}
			},
			width : 80
		},
		{
			name: 'subjects',
			display: '往来科目',
			hide : true
		},
		{
			name: 'customerName',
			display: '客户名称',
			width : 140
		},
		{
			name: 'deptName',
			display: '部门'
		},
		{
			name: 'salesman',
			display: '业务员'
		},
		{
			name: 'amount',
			display: '本期应收',
			process : function(v){
				if(v >= 0 ){
					return moneyFormat2(v);
				}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			}
		},
		{
			name: 'trueReceive',
			display: '本期实收',
			process : function(v){
				if(v >= 0 ){
					return moneyFormat2(v);
				}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			}
		},
		{
			name: 'balance',
			display: '期末余额',
			process : function(v){
				if(v >= 0 ){
					return moneyFormat2(v);
				}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			}
		}],
		buttonsEx : [
			{
				text : '返回',
				icon : 'view',
				action : function(row, rows, grid) {
					location = "?model=finance_receviable_receviable&action=toDetailPage"
				}
			}

		],

		// 扩展右键菜单
		menusEx : [{
			text : '查看详细',
			icon : 'view',showMenuFn:function(row){
		         if(row.objId != undefined ){
		            return true;
		         }
		         return false;
		    },
			action : function(row, rows, grid) {
				switch(row.formType){
					case 'invoice': showOpenWin("?model=finance_invoice_invoice&action=init&perm=view&id=" + row.objId + "&skey=" + row['skey_']);break;
					case 'YFLX-TKD' : showOpenWin("?model=finance_income_income&action=toAllot&perm=view&id=" + row.objId + "&skey=" + row['skey_'] );break;
					case 'YFLX-YFK' : showOpenWin("?model=finance_income_income&action=toAllot&perm=view&id=" + row.objId + "&skey=" + row['skey_'] );break;
					case 'YFLX-DKD' : showOpenWin("?model=finance_income_income&action=toAllot&perm=view&id=" + row.objId + "&skey=" + row['skey_'] );break;
					default : return '';
				}
			}
		}],
		sortname : 'formDate',
		sortorder : 'ASC'
	});
});