var show_page = function(page) {
	$("#productItem").yxsubgrid("reload");
};
$(document).ready(function () {
	var productObj = $("#productItem");
	productObj.yxsubgrid({
		url: '?model=produce_task_producetask&action=caculateListJson',
		param: {
			ids: $('#idStr').val(),
			codes: $('#codeStr').val(),
			groupBy: 'c.id,s.id'
		},
		title:'���ϼ���',
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
			url: '?model=produce_task_producetask&action=codelistJson&ids='+$('#idStr').val(),
			param: [{
				paramId: 'productCode',
				colId: 'productCode'
			}],
			subgridcheck: true,
			colModel: [{
				display: '���񵥺�',
				name: 'taskCode',
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
        // ��չ�Ҽ��˵�
        menusEx: [ {
            text: '���ȷ��',
            icon: 'edit',
            showMenuFn: function (row) {
            	return true;
            },
            action: function (row, rows, rowIds, g) {
            	var datas = g.getAllSubSelectRowDatas();
            	var len = datas.length;
            	var taskIds = '';
            	var productCode = row.id;
            	if(len == 0){
            		taskIds = $("#idStr").val();
            	}else{
            		var tempArr = [];
            		for(var i = 0; i < len; i++){
            			if(datas[i]['productCode'] == productCode){
            				tempArr.push(datas[i]['id']);
            			}
            		}
            		taskIds = tempArr.toString();
            	}
                if (row) {
                	showThickboxWin("?model=produce_task_producetask&action=toMark&productCodes="
                        + row.id
                        + '&taskIds=' 
                        + taskIds
                        + "&skey="
                        + row['skey_']
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800")
                } else {
                    alert("��ѡ��һ������");
                }
            }
        }],
		buttonsEx: [{
			name: 'purchase',
			text: "�ɹ�����",
			icon: 'add',
			action: function (row, rows, idArr) {
				if (idArr !== undefined) {
					showModalWin('?model=purchase_external_external&action=toAddByCaculateTask&productCodes=' + 
						idArr.toString() + '&taskIds=' + $("#idStr").val(), '1');
	            }else{
					alert("������ѡ��1�����ݽ��вɹ�");
					return false;
	            }
	        }
		}, {
			name: 'canPlan',
			text: "��������",
			icon: 'add',
			action: function (row, rows, rowIds, g) {
				if(rowIds !== undefined){
					$.ajax({
						type: "POST",
						url: "?model=produce_task_producetask&action=isMeetProduction",
						data: {productCodes : rowIds.toString(),taskIds : $("#idStr").val()},
						async: false,
						success: function(data) {
							if (data == "1") {
								alert("���óɹ�");
								show_page();
							} else {
								alert("����ʧ��");
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
						var result = true;
						for(var i = 0; i < len; i++){
							$.ajax({
								type: "POST",
								url: "?model=produce_task_producetask&action=isMeetProduction",
								data: {productCodes : datas[i]['productCode'],taskIds : datas[i]['id']},
								async: false,
								success: function(data) {
									if (data == "0") {
										result = false;
									}
								}
							});
							if(!result){
								break;
							}
						}
						if(result){
							alert("���óɹ�");
							show_page();
						}else{
							alert("����ʧ��");
							return false;
						}
					}
				}
			}
		}, {
			name: 'cantPlan',
			text: "����������",
			icon: 'add',
			action: function (row, rows, rowIds, g) {
				if(rowIds !== undefined){
					$.ajax({
						type: "POST",
						url: "?model=produce_task_producetask&action=isNotMeetProduction",
						data: {productCodes : rowIds.toString(),taskIds : $("#idStr").val()},
						async: false,
						success: function(data) {
							if (data == "1") {
								alert("���óɹ�");
								show_page();
							} else {
								alert("����ʧ��");
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
						var result = true;
						for(var i = 0; i < len; i++){
							$.ajax({
								type: "POST",
								url: "?model=produce_task_producetask&action=isNotMeetProduction",
								data: {productCodes : datas[i]['productCode'],taskIds : datas[i]['id']},
								async: false,
								success: function(data) {
									if (data == "0") {
										result = false;
									}
								}
							});
							if(!result){
								break;
							}
						}
						if(result){
							alert("���óɹ�");
							show_page();
						}else{
							alert("����ʧ��");
							return false;
						}
					}
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
			display: "���񵥺�",
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