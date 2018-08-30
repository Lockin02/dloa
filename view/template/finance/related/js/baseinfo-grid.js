var show_page = function(page) {
	$("#baseinfoGrid").yxgrid("reload");
};
$(function() {
	$("#baseinfoGrid").yxgrid({
		model : 'finance_related_baseinfo',
		action : 'pageJsonRelated',
		title : '������־',
		showcheckbox : false,
		isToolBar : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		// ����Ϣ
		colModel : [{
					display : '�������',
					name : 'relatedId',
					sortable : true,
					width : 60
				}, {
					display : '��Ŀid',
					name : 'id',
					hide : true
				}, {
					display : '��id',
					name : 'hookMainId',
					hide : true
				}, {
					name : 'years',
					display : '�������',
					sortable : true,
					width : 60
				}, {
					name : 'hookDate',
					display : '����ʱ��',
					sortable : true,
					width : 80
				}, {
					name : 'createName',
					display : '������',
					sortable : true
				}, {
					name : 'supplierName',
					display : '��Ӧ��',
					sortable : true,
					width : 150
				}, {
					name : 'hookObj',
					display : ' ��������',
					sortable : true,
					process : function(v) {
						if (v == 'invpurchase') {
							return '�ɹ���Ʊ';
						} else if (v == 'invcost') {
							return '���÷�Ʊ';
						} else {
							return '�⹺��ⵥ';
						}
					},
					width : 80
				}, {
					name : 'hookObjCode',
					display : '�����',
					sortable : true
				}, {
					name : 'productNo',
					display : '���ϱ��',
					sortable : true
				}, {
					name : 'productName',
					display : '��Ʒ����',
					sortable : true,
					width : 120
				}, {
					name : 'number',
					display : '��������',
					sortable : true,
					width : 60
				}, {
					name : 'amount',
					display : '�������',
					sortable : true,
					process : function(v) {
						return moneyFormat2(v);
					}
				}],
		menusEx : [{
			text : '�鿴����',
			icon : 'view',
			action : function(row) {
				if (row.hookObj == 'invpurchase') {
					showOpenWin('?model=finance_invpurchase_invpurchase&action=init&perm=view&id='
							+ row.hookMainId + "&skey=" + row.skey_);
				} else if (row.hookObj == 'invcost') {
					showOpenWin('?model=finance_invcost_invcost&action=init&perm=view&id='
							+ row.hookMainId + "&skey=" + row.skey_);
				} else {
					showOpenWin('?model=stock_instock_stockin&action=toView&docType=RKPURCHASE&id='
							+ row.hookMainId + "&skey=" + row.skey_);
				}
			}
		}, {
			text : '�鿴�������',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=finance_related_baseinfo&action=init&perm=view&id='
						+ row.relatedId
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 550 + "&width=" + 900);
			}
		}],
		searchitems : [{
					display : '�����',
					name : 'hookObjCode'
				}, {
					display : '�������',
					name : 'id'
				}, {
					display : '��Ӧ������',
					name : 'supplierName'
				}, {
					display : '������',
					name : 'createName'
				}],
		// �߼�����
		advSearchOptions : {
			modelName : 'baseinfo',
			// ѡ���ֶκ��������ֵ����
			selectFn : function($valInput) {
				$valInput.yxselect_user("remove");
				$valInput.yxcombogrid_supplier("remove");
				$valInput.yxcombogrid_product("remove");
			},
			searchConfig : [{
						name : '��������',
						value : 'c.createTime',
						changeFn : function($t, $valInput) {
							$valInput.click(function() {
										WdatePicker({
													dateFmt : 'yyyy-MM-dd'
												});
									});
						}
					}, {
						name : '������',
						value : 'c.createName',
						changeFn : function($t, $valInput, rowNum) {
							if (!$("#createId" + rowNum)[0]) {
								$hiddenCmp = $("<input type='hidden' id='createId"
										+ rowNum + "' value=''>");
								$valInput.after($hiddenCmp);
							}
							$valInput.yxselect_user({
										hiddenId : 'createId' + rowNum,
										height : 200,
										width : 550,
										gridOptions : {
											showcheckbox : false
										}
									});
						}
					}, {
						name : '��Ӧ��',
						value : 'c.supplierName',
						changeFn : function($t, $valInput, rowNum) {
							if (!$("#supplierId" + rowNum)[0]) {
								$hiddenCmp = $("<input type='hidden' id='supplierId"
										+ rowNum + "' value=''>");
								$valInput.after($hiddenCmp);
							}
							$valInput.yxcombogrid_supplier({
										hiddenId : 'supplierId' + rowNum,
										height : 200,
										width : 550,
										gridOptions : {
											showcheckbox : false
										}
									});
						}
					}, {
						name : '��������',
						value : 'd.hookObj',
						type : 'select',
						options : [{
									dataCode : 'invpurchase',
									dataName : '�ɹ���Ʊ'
								}, {
									dataCode : 'invcost',
									dataName : '���÷�Ʊ'
								}, {
									dataCode : 'storage',
									dataName : '�⹺��ⵥ'
								}]
					}, {
						name : '���ϱ��',
						value : 'd.productNo',
						changeFn : function($t, $valInput, rowNum) {
							if (!$("#productId" + rowNum)[0]) {
								$hiddenCmp = $("<input type='hidden' id='productId"
										+ rowNum + "' value=''>");
								$valInput.after($hiddenCmp);
							}
							$valInput.yxcombogrid_product({
										hiddenId : 'productId' + rowNum,
										height : 200,
										width : 550,
										gridOptions : {
											showcheckbox : false
										}
									});
						}
					}, {
						name : '��������',
						value : 'd.productName',
						changeFn : function($t, $valInput, rowNum) {
							if (!$("#productId" + rowNum)[0]) {
								$hiddenCmp = $("<input type='hidden' id='productId"
										+ rowNum + "' value=''>");
								$valInput.after($hiddenCmp);
							}
							$valInput.yxcombogrid_product({
										nameCol : 'productName',
										hiddenId : 'productId' + rowNum,
										height : 200,
										width : 550,
										gridOptions : {
											showcheckbox : false
										}
									});
						}
					}, {
						name : '��������',
						value : 'd.number'
					}, {
						name : '�������',
						value : 'd.amount'
					}]
		}
	});
});