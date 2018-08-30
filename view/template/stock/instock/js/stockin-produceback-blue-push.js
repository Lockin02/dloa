$(function() {
	var itemsObj = $("#items")
	itemsObj.yxeditgrid({
		url : '?model=produce_plan_backitem&action=listJson',
		param : {
			backId : $("#relDocId").val()
		},
		objName : 'stockin[items]',
		isAddAndDel : false,
		colModel : [{
			display : '源单明细id',
			name : 'relDocId',
			process : function ($input ,row) {
				$input.val(row.id);
			},
			type : 'hidden'
		},{
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '物料编号',
			name : 'productCode',
			tclass : 'readOnlyTxtItem',
			readonly : true
		},{
			display : '物料名称',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		},{
			display : '规格型号',
			name : 'pattern',
			tclass : 'readOnlyTxtItem',
			readonly : true
		},{
			display : '单位',
			name : 'unitName',
			tclass : 'readOnlyTxtShort',
			readonly : true
		},{
			display : '应收数量',
			name : 'storageNum',
			tclass : 'readOnlyTxtShort',
			readonly : true,
			width : '10%',
			process : function ($input ,row) {
				$input.val(row.applyNum - row.backNum);
			}
		},{
			display : '实收数量',
			name : 'actNum',
			validation : {
				required : true,
				custom : ['onlyNumber']
			},
			width : '10%',
			process : function ($input ,row) {
				var validNum = row.applyNum - row.backNum;
				var rowNum = $input.data("rowNum");
				$input.val(validNum).change(function () {
					if ($(this).val() > validNum) {
						alert('实收数量不能超过应收数量');
						$(this).val(validNum);
					} else {
						var price = itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"price").val();
						itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"subPrice").val($(this).val() * price);
					}
				});
			}
		},{
			display : '批次号',
			name : 'batchNum'
		},{
			display : '收料仓库',
			name : 'inStockName',
			readonly : true,
			validation : {
				required : true
			},
			process : function ($input) {
				var rowNum = $input.data("rowNum");
				$input.yxcombogrid_stockinfo( {
					hiddenId : 'items_cmp_inStockId' + rowNum,
					nameCol : 'items_cmp_inStockName' + rowNum,
					gridOptions : {
						showcheckbox : false,
						model : 'stock_stockinfo_stockinfo',
						action : 'pageJson',
						event : {
							'row_dblclick' : function(e, row, data) {
								itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"inStockCode").val(data.stockCode);
								itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"inStockName").val(data.stockName);
							}
						}
					}
				});
			}
		},{
			display : '收料仓库Code',
			name : 'inStockCode',
			type : 'hidden'
		},{
			display : '收料仓库Id',
			name : 'inStockId',
			type : 'hidden'
		},{
			display : '单价',
			name : 'price',
			validation : {
				custom : ['money']
			},
            type : 'hidden',
			width : '10%',
			process : function ($input) {
				var rowNum = $input.data("rowNum");
				$input.val(0).change(function () {
					var actNum = itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"actNum").val();
					itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"subPrice").val($(this).val() * actNum);
				});
			}
		},{
			display : '金额',
			name : 'subPrice',
			tclass : 'readOnlyTxtShort',
			width : '10%',
			readonly : true,
            type : 'hidden',
			process : function ($input) {
				$input.val(0);
			}
		},{
			display : '保质期（月）',
			name : 'shelfLife',
			validation : {
				custom : ['onlyNumber'],
				required : false
			},
			width : '10%'
		},{
			display : '备注',
			name : 'remark',
			type : 'textarea',
			rows : 2,
			width : '20%',
			process : function ($input) {
				$input.val('');
			}
		}]
	});

	$("#inStockName").yxcombogrid_stockinfo( {
		hiddenId : 'inStockId',
		nameCol : 'stockName',
		gridOptions : {
			showcheckbox : false,
			model : 'stock_stockinfo_stockinfo',
			action : 'pageJson',
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#inStockCode").val(data.stockCode);
					var len = itemsObj.yxeditgrid('getCurShowRowNum');
					for(var i = 0; i < len; i ++){
						itemsObj.yxeditgrid("getCmpByRowAndCol" ,i ,"inStockCode").val(data.stockCode);
						itemsObj.yxeditgrid("getCmpByRowAndCol" ,i ,"inStockName").val(data.stockName);
						itemsObj.yxeditgrid("getCmpByRowAndCol" ,i ,"inStockId").val(data.id);
					}
				}
			}
		}
	});

	validate({
		"inStockName" : {
			required : true
		}
	});
});

//提交检验
function checkDate() {
	return true;
}

//审核
function confirmAudit() {
	if (confirm("审核后单据将不可修改，你确定要审核吗?")) {
		if (checkDate()) {
			$("#form1").attr("action" ,"?model=stock_instock_stockin&action=add&actType=audit");
			$("#docStatus").val("YSH");
			$("#form1").submit();
		}
	}
}