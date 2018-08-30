/** 到款列表* */

var show_page = function(page) {
	$("#payableGrid").yxgrid("reload");
};

$(function() {

	$("#payableGrid").yxgrid({

		model : 'finance_payable_payable',
		action : 'countPageJson',
		title : '应付款汇总表 -- 供应商 : ' + $("#supplierName").val() + " -- " + $("#year").val() + "年" + $("#beginMonth").val() + "月至"  + $("#endMonth").val() + "月" ,
		param : {  "supplierId" : $("#supplierId").val() ,'beginMonth' : $("#beginMonth").val() ,'endMonth' : $("#endMonth").val() , 'year' : $("#year").val() },
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
			sortable: true,
			hide: true
		},
		{
			name: 'supplierName',
			display: '供应商名称',
			sortable: true,
			width : 160
		},
		{
			name: 'period',
			display: '期间',
			sortable: true
		},
		{
			name: 'periodBeginBalance',
			display: '期初余额',
			sortable: true,
			process : function(v){
				if(v >= 0 ){
					return moneyFormat2(v);
				}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			},
			width : 110
		},
		{
			name: 'amount',
			display: '本期应付',
			sortable: true,
			process : function(v){
				if(v >= 0 ){
					return moneyFormat2(v);
				}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			},
			width : 110
		},
		{
			name: 'needPay',
			display: '本期实付',
			sortable: true,
			process : function(v){
				if(v >= 0 ){
					return moneyFormat2(v);
				}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			},
			width : 110
		},
		{
			name: 'yearNeedPay',
			display: '本年累计应付',
			sortable: true,
			process : function(v){
				if(v >= 0 ){
					return moneyFormat2(v);
				}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			},
			width : 110
		},
		{
			name: 'yearPayed',
			display: '本年累计实付',
			sortable: true,
			process : function(v){
				if(v >= 0 ){
					return moneyFormat2(v);
				}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			},
			width : 110
		},
		{
			name: 'periodEndBalance',
			display: '期末余额',
			sortable: true,
			process : function(v){
				if(v >= 0 ){
					return moneyFormat2(v);
				}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			},
			width : 110
		}],
		buttonsEx : [
			{
				text : '返回',
				icon : 'view',
				action : function(row, rows, grid) {
					location = "?model=finance_payable_payable&action=toCountPage"
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
					case 'blue': showOpenWin("?model=finance_invpurchase_invpurchase&action=init&perm=view&id=" + row.objId );break;
					case 'red' : showOpenWin("?model=finance_invpurchase_invpurchase&action=init&perm=view&id=" + row.objId );break;
					case 'CWYF-01' : showOpenWin("?model=finance_payables_payables&action=initWin&perm=view&id=" + row.objId );break;
					case 'CWYF-02' : showOpenWin("?model=finance_payables_payables&action=initWin&perm=view&id=" + row.objId );break;
					case 'CWYF-03' : showOpenWin("?model=finance_payables_payables&action=initWin&perm=view&id=" + row.objId );break;
					default : return '';
				}
			}
		}],
		sortname : 'formDate',
		sortorder : 'ASC'
	});
});