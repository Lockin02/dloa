$(document).ready(function () {
	var productObj = $("#productItem");
	productObj.yxsubgrid({
		url: '?model=produce_plan_produceplan&action=caculateListJson',
		param: {
			ids: $('#idStr').val(),
			groupBy: 'c.id,s.id'
		},
		title:'���ϼ���',
		objName: 'picking[item]',
		isViewAction: false,
		isAddAction: false,
		isEditAction: false,
		isDelAction: false,
		isOpButton: false,
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			display: '����id',
			name: 'productId',
			hide: true
		}, {
			display: '���ϱ���',
			name: 'productCode',
			type: 'statictext',
			width: 100,
			isSubmit: true
		}, {
			display: '��������',
			name: 'productName',
			type: 'statictext',
			width: 250,
			isSubmit: true
		}, {
			display: '����ͺ�',
			name: 'pattern',
			type: 'statictext',
			width: 100,
			isSubmit: true
		}, {
			display: '��λ',
			name: 'unitName',
			type: 'statictext',
			width: 80,
			isSubmit: true
		}, {
			display: '���豸��',
			name: 'JSBC',
			type: 'statictext',
			width: 80
		}, {
			display: '�����Ʒ',
			name: 'KCSP',
			type: 'statictext',
			width: 80
		}
//		, {
//			display: '������',
//			name: 'SCC',
//			type: 'statictext',
//			width: 80
//		}
		, {
			display: '��������',
			name: 'number',
			validation: {
				required: true,
				custom: ['onlyNumber']
			},
			width: 80
		}, {
			display: '�ɹ�����',
			name: 'stockoutNum',
			width: 80
		}],
		// ���ӱ������
		subGridOptions: {
			url: '?model=produce_plan_produceplan&action=codelistJson&ids=' + $('#idStr').val() + '&planIds=' + $('#planIdStr').val(),
			param: [{
				paramId: 'productCode',
				colId: 'productCode'
			}],
			subgridcheck: true,
			colModel: [{
				display: '�ƻ�����',
				name: 'planCode',
				width: 150
			}, {
				display: '��ͬ���',
				name: 'relDocCode',
				type: 'statictext',
				width: 150,
				isSubmit: true
			}, {
				display: '�������κ�',
				name: 'productionBatch',
				width: 120,
				type: 'statictext',
				isSubmit: true
			}, {
				display: '���豸��',
				name: 'JSBC',
				type: 'statictext',
				width: 80
			}, {
				display: '�����Ʒ',
				name: 'KCSP',
				type: 'statictext',
				width: 80
			}, {
				display: '��������',
				name: 'number',
				width: 80
			}, {
				display: '�ɹ�����',
				name: 'stockoutNum',
				width: 80
			}]
		},
		buttonsEx: [{
			name: 'picking',
			text: "��������",
			icon: 'add',
			action: function (row, rows, rowIds, g) {
				if(rowIds !== undefined){
					$.ajax({
						type: "POST",
						url: "?model=produce_plan_produceplan&action=pickDeal",
						data: {productCodes : rowIds.toString(),taskIds : $("#idStr").val()},
						async: false,
						success: function(data) {
							data = eval("(" + data + ")");
							if (data.msg == "1") {
								showModalWin('?model=produce_plan_picking&action=toAddByPlan&planId=' + data.planId + '&productId=' + data.productId, '1');
							} else {
								alert("û�п����´����ϵĵ���");
								return false;
							}
						}
					});
				}else{
					var datas = g.getAllSubSelectRowDatas();
					var len = datas.length;
					if(len === 0){
						alert("������ѡ��1�����ݽ�������");
						return false;
					}else{
						var planId = [];
						var productId = [];
						for(var i = 0; i < len; i++){
//							if(planId == ''){
//								planId = datas[i]['id'];
//							}else if(planId !== datas[i]['id']){
//								alert("��ͬ�����ƻ��������ϲ�������ͬ�����ϵ�");
//								return false;
//							}
							planId.push(datas[i]['id']);
							productId.push(datas[i]['productId']);
						}
						showModalWin('?model=produce_plan_picking&action=toAddByPlan&planId=' + planId.toString() + 
							'&productId=' + productId.toString(), '1');
					}
				}
			}
		}, {
			name: 'purchase',
			text: "�ɹ�����",
			icon: 'add',
			action: function (row, rows, idArr) {
				if (idArr !== undefined) {
					showModalWin('?model=purchase_external_external&action=toAddByCaculate&productCodes=' + idArr.toString() + '&taskIds=' + $("#idStr").val(), '1');
	            }else{
					alert("������ѡ��1�����ݽ��вɹ�");
					return false;
	            }
	        }
		}],
		searchitems: [{
			display: '���ϱ���',
			name: 'productCode'
		}, {
			display: '��������',
			name: 'productName'
		}, {
			display: "�ƻ�����",
			name: 'planCode'
		}, {
			display: "��ͬ���",
			name: 'relDocCode'
		}, {
			display: '�������κ�',
			name: 'productionBatch'
		}]
	});
});