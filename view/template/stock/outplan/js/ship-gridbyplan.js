var show_page = function(page) {
	$("#shipGrid").yxgrid("reload");
};
$(function() {
	$("#shipGrid").yxgrid({
		model : 'stock_outplan_ship',
		param : { planId : $('#planId').val() },
		title : '发货单',
		showcheckbox :false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		customCode : 'shipGrid',


		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=stock_outplan_ship&action=toView&id='
						+ row.id
						+ '&docType='
						+ row.docType
						+ '&skey='
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=550&width=800');
			}
		}],


		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'docId',
			display : '鼎利合同Id',
			hide : true,
			sortable : true
		}, {
			name : 'shipCode',
			display : '发货单号',
			width : 120,
			sortable : true
		}, {
			name : 'docCode',
			display : '鼎利合同号',
			width : 180,
			sortable : true
		}, {
			name : 'customerContCode',
			display : '客户合同号',
			width : 120,
			sortable : true
		}, {
			name : 'shipType',
			display : '发货方式',
			sortable : true,
			process : function(v) {
				if (v == 'order') {
					return "发货";
				}else if (v == 'borrow') {
					return "借用";
				}else if (v == 'lease'){
				    return "租用";
				}else if (v == 'trial'){
				    return "试用";
				}else if (v == 'change'){
				    return "更换";
				}
			}
		}, {
			name : 'customerName',
			display : '客户名称',
			sortable : true
		}, {
			name : 'docType',
			display : '发货类型',
			sortable : true,
			process : function(v) {
				if (v == 'oa_contract_contract') {
					return "合同发货";
				}else if (v == 'oa_present_present') {
					return "赠送发货";
				}else if (v == 'oa_contract_exchangeapply'){
				    return "换货发货";
				}else if (v == 'oa_borrow_borrow'){
				    return "借用发货";
				}
			}
		}, {
			name : 'linkman',
			display : '联系人',
			hide : true,
			sortable : true
		}, {
			name : 'mobil',
			display : '手机号',
			hide : true,
			sortable : true
		}, {
			name : 'postCode',
			display : '邮编',
			hide : true,
			sortable : true
		}, {
			name : 'remark',
			display : '备注',
			hide : true,
			sortable : true
		}, {
			name : 'outstockman',
			display : '出库人',
			sortable : true
		}, {
			name : 'shipman',
			display : '发货人',
			sortable : true
		}, {
			name : 'auditman',
			display : '审核人',
			sortable : true
		}, {
			name : 'shipDate',
			display : '发货日期',
			sortable : true
		}, {
			name : 'isMail',
			display : '是否邮寄',
			hide : true,
			sortable : true
		}, {
			name : 'isSign',
			display : '是否签收',
			process : function(v){
					(v == '1')? (v = '是'):(v = '否');
					return v;
			},
			sortable : true
		}, {
			name : 'signman',
			display : '签收人',
			hide : true,
			sortable : true
		}, {
			name : 'createTime',
			display : '创建时间',
			hide : true,
			sortable : true
		}, {
			name : 'createName',
			display : '创建人名称',
			hide : true,
			sortable : true
		}, {
			name : 'createId',
			display : '创建人',
			hide : true,
			sortable : true
		}, {
			name : 'updateName',
			display : '修改人名称',
			hide : true,
			sortable : true
		}, {
			name : 'updateTime',
			display : '修改时间',
			hide : true,
			sortable : true
		}, {
			name : 'updateId',
			display : '修改人',
			hide : true,
			sortable : true
		}, {
			name : 'signDate',
			display : '签收日期',
			hide : true,
			sortable : true
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '备注',
			name : 'itemRemark'
		}]
	});
});