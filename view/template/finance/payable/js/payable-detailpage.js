/** 到款列表* */

var show_page = function(page) {
	$("#payableGrid").yxgrid("reload");
};

$(function() {

	$("#payableGrid").yxgrid({

		model : 'finance_payable_payable',
		action : 'detailPageJson',
		title : '应付款明细表 -- 供应商 : ' + $("#supplierName").val() + " -- " + $("#year").val() + "年" + $("#beginMonth").val() + "月至"  + $("#endMonth").val() + "月" ,
		param : { 'salesmanId' : $("#salesmanId").val(), 'deptIds' : $("#deptId").val() , "supplierId" : $("#supplierId").val() ,'beginMonth' : $("#beginMonth").val() ,'endMonth' : $("#endMonth").val() , 'year' : $("#year").val() },
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
					case 'blue': return '蓝字发票';break;
					case 'red' : return '<span class="red">红字发票</span>';break;
					case 'CWYF-01' : return '付款单';break;
					case 'CWYF-02' : return '预付款单';break;
					case 'CWYF-03' : return '<span class="red">退款单</span>';break;
					default : return '';
				}
			}
		},
		{
			name: 'subjects',
			display: '往来科目'
		},
		{
			name: 'supplierName',
			display: '供应商名称',
			width : 140
		},
		{
			name: 'departments',
			display: '部门'
		},
		{
			name: 'salesman',
			display: '业务员'
		},
		{
			name: 'amount',
			display: '本期应付',
			process : function(v){
				if(v >= 0 ){
					return moneyFormat2(v);
				}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			}
		},
		{
			name: 'needPay',
			display: '本期实付',
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
					location = "?model=finance_payable_payable&action=toDetailPage"
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
					case 'blue': showOpenWin("?model=finance_invpurchase_invpurchase&action=init&perm=view&id=" + row.objId + "&skey=" + row.skey_ );break;
					case 'red' : showOpenWin("?model=finance_invpurchase_invpurchase&action=init&perm=view&id=" + row.objId  + "&skey=" + row.skey_ );break;
					case 'CWYF-01' : showOpenWin("?model=finance_payables_payables&action=initWin&perm=view&id=" + row.objId  + "&skey=" + row.skey_ );break;
					case 'CWYF-02' : showOpenWin("?model=finance_payables_payables&action=initWin&perm=view&id=" + row.objId  + "&skey=" + row.skey_ );break;
					case 'CWYF-03' : showOpenWin("?model=finance_payables_payables&action=initWin&perm=view&id=" + row.objId  + "&skey=" + row.skey_ );break;
					default : return '';
				}
			}
		}],
		sortname : 'formDate',
		sortorder : 'ASC'
	});
});