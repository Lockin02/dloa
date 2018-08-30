/** 租赁合同列表* */
var show_page = function(page) {
	$("#rentalcontractGrid").yxgrid("reload");
};

$(function() {
	$("#rentalcontractGrid").yxgrid({
		model : 'contract_rental_rentalcontract',
		param : {'ExaStatuss' : '打回,完成'},
		action : 'pageJsonYes',
		title : '已审批租赁合同',
		isToolBar : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,

		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			display : 'task',
			name : 'task',
			sortable : true,
			hide : true
		},{
			display : '合同id',
			name : 'contractId',
			sortable : true,
			hide : true
		},{
			name : 'orderCode',
			display : '鼎利合同号',
  			sortable : true
		},{
    		name : 'orderTempCode',
  			display : '临时合同号',
  			sortable : true
        },{
			name : 'orderName',
  			display : '合同名称',
  			sortable : true
        },{
    		name : 'orderDate',
  			display : '合同签订日期',
  			sortable : true
        },{
    		name : 'orderPrincipal',
  		    display : '销售负责人',
  			sortable : true
        },{
    		name : 'district',
  			display : '所属区域',
  			sortable : true
        },{
    		name : 'saleman',
  			display : '销售员',
  			sortable : true
        },{
			display : '申请时间',
			name : 'createTime',
			width : 150
		}, {
			display : '申请人',
			name : 'createName'
		}, {
			display : '审批状态',
			name : 'ExaStatus'
		}],

		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('?model=contract_rental_rentalcontract&action=init&perm=view&id='
							+ row.contractId + "&skey="+row['skey_']
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");

				}

			}
		},{
			text : '审批情况',
			icon : 'view',
			action : function(row) {
				showThickboxWin('controller/common/readview.php?itemtype=oa_sale_lease&pid='
						+ row.contractId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		}],

		searchitems : [{
			display : '申请单号',
			name : 'applyNo'
		}],
		sortname : 'id',
		sortorder : 'DESC'
	});
});