var show_page = function(page) {
	$("#baseinfoGrid").yxgrid("reload");
};

$(function() {
	$("#baseinfoGrid").yxgrid({
		model: 'finance_related_baseinfo',
		param : {"id" : $('#relatedId').val()},
		title: '钩稽日志',
		showcheckbox: false,
		isToolBar : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		//列信息
		colModel: [{
			display: '钩稽序号',
			name: 'id',
			sortable: true,
			width : 60
		},{
			display: 'hookMainId',
			name: 'hookMainId',
			hide :true
		},
		{
			name: 'years',
			display: '钩稽年度',
			sortable: true,
			width : 60
		},
		{
			name: 'createTime',
			display: '钩稽时间',
			sortable: true,
			process : function(v){
				return formatDate(v);
			},
			width : 80
		},
		{
			name: 'createName',
			display: '钩稽人',
			sortable: true
		},
		{
			name: 'supplierName',
			display: '供应商',
			sortable: true,
			width : 150
		},{
			name: 'hookObj',
			display: ' 钩稽对象',
			sortable: true,
			process : function(v){
				if(v == 'invpurchase'){
					return '采购发票';
				}else if(v == 'invcost'){
					return '费用发票';
				}else{
					return '采购入库单';
				}
			},
			width : 80
		},{
			name: 'hookObjCode',
			display: '表单编号',
			sortable: true,
			width : 160
		},{
			name: 'productName',
			display: '物品名称',
			sortable: true
		},{
			name: 'productNo',
			display: '物料代码',
			sortable: true
		},{
			name: 'number',
			display: '钩稽数量',
			sortable: true,
			width : 60
		},{
			name: 'amount',
			display: '钩稽金额',
			sortable: true,
			process : function(v){
				return moneyFormat2(v);
			}
		}],
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if(row.hookObj == 'invpurchase'){
					showThickboxWin('?model=finance_invpurchase_invpurchase&action=init&perm=view&id=' + row.hookMainId +
						"&placeValuesBefore&TB_iframe=true&modal=false&height=" +
						550 + "&width=" + 800);
				}
			}
		}]
	});
});