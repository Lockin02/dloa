$(document).ready(function () {
	var productObj = $("#productItem");
	productObj.yxsubgrid({
		url: '?model=produce_plan_produceplan&action=caculateListJson',
		param: {
			ids: $('#idStr').val(),
			groupBy: 'c.id,s.id'
		},
		title:'物料计算',
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
			url: '?model=produce_plan_produceplan&action=codelistJson&ids=' + $('#idStr').val() + '&planIds=' + $('#planIdStr').val(),
			param: [{
				paramId: 'productCode',
				colId: 'productCode'
			}],
			subgridcheck: true,
			colModel: [{
				display: '计划单号',
				name: 'planCode',
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
		buttonsEx: [{
			name: 'picking',
			text: "领料申请",
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
								alert("没有可以下达领料的单据");
								return false;
							}
						}
					});
				}else{
					var datas = g.getAllSubSelectRowDatas();
					var len = datas.length;
					if(len === 0){
						alert("请至少选择1条数据进行领料");
						return false;
					}else{
						var planId = [];
						var productId = [];
						for(var i = 0; i < len; i++){
//							if(planId == ''){
//								planId = datas[i]['id'];
//							}else if(planId !== datas[i]['id']){
//								alert("不同生产计划单的物料不能生成同张领料单");
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
			text: "采购申请",
			icon: 'add',
			action: function (row, rows, idArr) {
				if (idArr !== undefined) {
					showModalWin('?model=purchase_external_external&action=toAddByCaculate&productCodes=' + idArr.toString() + '&taskIds=' + $("#idStr").val(), '1');
	            }else{
					alert("请至少选择1条数据进行采购");
					return false;
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
			display: "计划单号",
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