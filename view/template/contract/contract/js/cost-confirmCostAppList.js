var show_page = function(page) {
	$("#costGrid").yxgrid("reload");
};
$(function() {
	$("#costGrid").yxgrid({
		model : 'contract_contract_cost',
		param : {
			'state' : '1'
		},
		title : '��˲�Ʒ�߳ɱ�����',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�ɱ��������',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.state == '1' && row.ExaState == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=contract_contract_contract&action=confirmCostApp&id='
						+ row.id
						+ "&type=Ser"
						+ "&contractId="+row.contractId
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=750&width=1000');
			}
		}
//		,{
//			text : '���',
//			icon : 'delete',
//			showMenuFn : function(row) {
//				if (row.state == '1' && row.ExaState == '0') {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//
//				if (window.confirm(("ȷ��Ҫ���?"))) {
//					$.ajax({
//						type : "POST",
//						url : "?model=contract_contract_cost&action=ajaxBack",
//						data : {
//							id : row.id
//						},
//						success : function(msg) {
//							if (msg == 1) {
//								alert('��سɹ���');
//								$("#costGrid").yxgrid("reload");
//							}
//						}
//					});
//				}
//			}
//		}
		],

		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'productLineName',
			display : 'ִ����������',
			sortable : true,
			width : 100
		}, {
			name : 'confirmName',
			display : 'ȷ����',
			sortable : true,
			width : 80
		}, {
			name : 'confirmDate',
			display : 'ȷ��ʱ��',
			sortable : true,
			width : 80
		}, {
			name : 'confirmMoney',
			display : 'ȷ�Ͻ��',
			sortable : true,
			width : 80,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'contractCode',
			display : '��ͬ���',
			sortable : true,
			width : 180,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
						+ row.contractId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true,
			width : 150
		}, {
			name : 'customerType',
			display : '�ͻ�����',
			sortable : true,
			process : function(v, row) {
                return getDataByCode(v);
			},
			width : 100
		}, {
			name : 'contractName',
			display : '��ͬ����',
			sortable : true,
			width : 150
		}],

//		comboEx : [ {
//			text : 'ȷ��״̬',
//			key : 'engConfirmStr',
//			value : '0',
//			data : [{
//				text : 'δȷ��',
//				value : '0'
//			}, {
//				text : '��ȷ��',
//				value : '1'
//			}]
//		}],
		comboEx : [{
					text : '���״̬',
					key : 'ExaState',
					value : '0',
					data : [{
								text : 'δ���',
								value : '0'
							}, {
								text : '�����',
								value : '1'
							}]
				}],

		/**
		 * ��������
		 */
		searchitems : [{
			display : '��ͬ���',
			name : 'contractCode'
		},{
			display : '��ͬ����',
			name : 'contractName'
		},{
			display : '�ͻ�����',
			name : 'customerName'
		}],
        // Ĭ������˳��
        sortorder : "DSC",
        sortname : "confirmDate"
			// // �߼�����
			// advSearchOptions : {
			// modelName : 'orderInfo',
			// // ѡ���ֶκ��������ֵ����
			// selectFn : function($valInput) {
			// $valInput.yxcombogrid_area("remove");
			// },
			// searchConfig : [{
			// name : '��������',
			// value : 'c.createTime',
			// changeFn : function($t, $valInput) {
			// $valInput.click(function() {
			// WdatePicker({
			// dateFmt : 'yyyy-MM-dd'
			// });
			// });
			// }
			// }, {
			// name : '��������',
			// value : 'c.areaPrincipal',
			// changeFn : function($t, $valInput, rowNum) {
			// if (!$("#areaPrincipalId" + rowNum)[0]) {
			// $hiddenCmp = $("<input type='hidden' id='areaPrincipalId"
			// + rowNum + "' value=''>");
			// $valInput.after($hiddenCmp);
			// }
			// $valInput.yxcombogrid_area({
			// hiddenId : 'areaPrincipalId' + rowNum,
			// height : 200,
			// width : 550,
			// gridOptions : {
			// showcheckbox : true
			// }
			// });
			// }
			// }]
			//		}
	});
});