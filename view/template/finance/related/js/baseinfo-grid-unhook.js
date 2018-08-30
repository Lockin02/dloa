var show_page = function(page) {
	$("#baseinfoGrid").yxgrid("reload");
};
$(function() {
	$("#baseinfoGrid").yxgrid({
		model: 'finance_related_baseinfo',
		param : {"ids" : $('#ids').val(),"hookMainId" : $("#hookMainId").val()},
		action : 'pageJsonRelated',
		title: '钩稽日志',
		showcheckbox: false,
		isToolBar : true,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		//列信息
		colModel: [{
			display: '钩稽序号',
			name: 'relatedId',
			sortable: true,
			width : 60
		},{
			display: '条目id',
			name: 'id',
			hide :true
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
					return '外购入库单';
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
			text : '查看单据',
			icon : 'view',
			action : function(row) {
				if(row.hookObj == 'invpurchase'){
					showOpenWin('?model=finance_invpurchase_invpurchase&action=init&perm=view&id=' + row.hookMainId
						+ "&skey=" + row.skey_ ,1);
				}else if(row.hookObj == 'invcost'){
					showOpenWin('?model=finance_invcost_invcost&action=init&perm=view&id=' + row.hookMainId
						+ "&skey=" + row.skey_ ,1);
				}else{
					showOpenWin('?model=stock_instock_stockin&action=toView&docType=RKPURCHASE&id=' + row.hookMainId
						+ "&skey=" + row.skey_ ,1);
				}
			}
		},{
			text : '查看钩稽结果',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=finance_related_baseinfo&action=init&perm=view&id=' + row.relatedId +
					"&placeValuesBefore&TB_iframe=true&modal=false&height=" +
					550 + "&width=" + 800);
			}
		},{
			text : '反钩稽',
			icon : 'delete',
			action : function(row) {
				if (window.confirm(("确定反钩稽?"))) {
						$.ajax({
							type : "POST",
							url : "?model=finance_related_baseinfo&action=unHook",
							data : {
								id : row.relatedId
							},
							success : function(msg) {
								if (msg == 1) {
									alert('反钩稽成功！');
									self.window.close();
									self.opener.show_page(1);
								}else{
									alert("反钩稽失败! ");
								}
							}
						});
					}
			}
		}],buttonsEx : [{
			separator : true
		},{
			name : 'close',
			text : "关闭",
			icon : 'edit',
			action : function() {
				self.window.close();
				self.opener.show_page(1);
			}
		}]
	});
});