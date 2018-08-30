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
		title:'物料计算',
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
			display: '物料id',
			name: 'productId',
			hide: true
		}, {
			display: '物料编码',
			name: 'productCode',
			type: 'statictext',
			width: 100,
			isSubmit: true
		}, {
			display: '物料名称',
			name: 'productName',
			type: 'statictext',
			width: 250,
			isSubmit: true
		}, {
			display: '规格型号',
			name: 'pattern',
			type: 'statictext',
			width: 100,
			isSubmit: true
		}, {
			display: '单位',
			name: 'unitName',
			type: 'statictext',
			width: 80,
			isSubmit: true
		}, {
			display: '旧设备仓',
			name: 'JSBC',
			type: 'statictext',
			width: 80
		}, {
			display: '库存商品',
			name: 'KCSP',
			type: 'statictext',
			width: 80
		}
//		, {
//			display: '生产仓',
//			name: 'SCC',
//			type: 'statictext',
//			width: 80
//		}
		, {
			display: '申请数量',
			name: 'number',
			validation: {
				required: true,
				custom: ['onlyNumber']
			},
			width: 80
		}, {
			display: '采购数量',
			name: 'stockoutNum',
			width: 80
		}],
		// 主从表格设置
		subGridOptions: {
			url: '?model=produce_task_producetask&action=codelistJson&ids='+$('#idStr').val(),
			param: [{
				paramId: 'productCode',
				colId: 'productCode'
			}],
			subgridcheck: true,
			colModel: [{
				display: '任务单号',
				name: 'taskCode',
				width: 150
			}, {
				display: '合同编号',
				name: 'relDocCode',
				type: 'statictext',
				width: 150,
				isSubmit: true
			}, {
				display: '生产批次号',
				name: 'productionBatch',
				width: 120,
				type: 'statictext',
				isSubmit: true
			}, {
				display: '旧设备仓',
				name: 'JSBC',
				type: 'statictext',
				width: 80
			}, {
				display: '库存商品',
				name: 'KCSP',
				type: 'statictext',
				width: 80
			}, {
				display: '申请数量',
				name: 'number',
				width: 80
			}, {
				display: '采购数量',
				name: 'stockoutNum',
				width: 80
			}]
		},
        // 扩展右键菜单
        menusEx: [ {
            text: '标记确认',
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
                    alert("请选中一条数据");
                }
            }
        }],
		buttonsEx: [{
			name: 'purchase',
			text: "采购申请",
			icon: 'add',
			action: function (row, rows, idArr) {
				if (idArr !== undefined) {
					showModalWin('?model=purchase_external_external&action=toAddByCaculateTask&productCodes=' + 
						idArr.toString() + '&taskIds=' + $("#idStr").val(), '1');
	            }else{
					alert("请至少选择1条数据进行采购");
					return false;
	            }
	        }
		}, {
			name: 'canPlan',
			text: "满足生产",
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
								alert("设置成功");
								show_page();
							} else {
								alert("设置失败");
								return false;
							}
						}
					});
				}else{
					var datas = g.getAllSubSelectRowDatas();
					var len = datas.length;
					if(len === 0){
						alert("请至少选择1条数据进行设置");
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
							alert("设置成功");
							show_page();
						}else{
							alert("设置失败");
							return false;
						}
					}
				}
			}
		}, {
			name: 'cantPlan',
			text: "不满足生产",
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
								alert("设置成功");
								show_page();
							} else {
								alert("设置失败");
								return false;
							}
						}
					});
				}else{
					var datas = g.getAllSubSelectRowDatas();
					var len = datas.length;
					if(len === 0){
						alert("请至少选择1条数据进行设置");
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
							alert("设置成功");
							show_page();
						}else{
							alert("设置失败");
							return false;
						}
					}
				}
			}
		}],
		searchitems: [{
			display: '物料编码',
			name: 'productCode'
		}, {
			display: '物料名称',
			name: 'productName'
		}, {
			display: "任务单号",
			name: 'planCode'
		}, {
			display: "合同编号",
			name: 'relDocCode'
		}, {
			display: '生产批次号',
			name: 'productionBatch'
		}]
	});
});