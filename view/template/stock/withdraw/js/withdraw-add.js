
var equConfig = {
	type : ''// add edit change view
};// 配置属性

// 物料确认 新增
$(function() {
	equConfig.type = "add";
	var rowNum = $('#rowNum').val();
	getWithdrawEqu('?model=projectmanagent_exchange_exchangebackequ&action=listJson');

function changeDate(){
		$.ajax({
			type : 'POST',
			url : '?model=stock_outplan_outplan&action=week',
			data : {
				date : $('#shipPlanDate').val()
			},
			success : function(data) {
				$('#week').val(data)
			}
		});
}

	//主表仓库渲染
	$("#stockName").yxcombogrid_stockinfo({
		hiddenId : 'stockId',
		nameCol : 'stockName',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$('#stockId').val(data.id);
					$('#stockCode').val(data.stockCode);
					$('#stockName').val(data.stockName);
					// 缓存表格对象
					var $withdrawequ = $("#withdrawequ");
					// thisGrid.html("");
					var stockNameObj = $withdrawequ.yxeditgrid("getCmpByCol", "stockName");// 物料id
					var stockCodeObj = $withdrawequ.yxeditgrid("getCmpByCol", "stockCode");// 物料id
					var stockIdObj = $withdrawequ.yxeditgrid("getCmpByCol", "stockId");// 物料id
					stockNameObj.each(function(i, n) {
						this.value=data.stockName;
						stockCodeObj[i].value=data.stockCode;
						stockIdObj[i].value=data.id;
					});
				}
			}
		}
	});


	/**
	 * 验证信息
	 */
	validate({
		"shipPlanDate" : {
			required : true
		}
	});
});





function getWithdrawEqu(url) {
	var equArr = $("#equArrStr").val();
	if(equArr != ''){
		equArr = equArr.split(",");
	}
	/*
	validate({
				"orderNum" : {
					required : true,
					custom : 'onlyNumber'
				}
			});
	 */
	var param = {
    	'exchangeId' : $("#docId").val(),
		'equIdArr' : $("#equIdArr").val()
    },
	url = url ? url : '?model=contract_contract_equ&action=getConEqu';// 产品下物料数据源
	$("#withdrawequ").yxeditgrid({
		objName : 'withdraw[items]',
		isAddOneRow : true,
		isAdd : false,
		url : url,
		param : param,
		colModel : [{
			display : '源单清单id',
			name : 'contEquId'
			,type : 'hidden'
			,process : function($input, rowData) {
				$input.val(rowData.id);
			}
		}, {
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		}, {
			name : 'productCode',
			tclass : 'readOnlyTxtItem',
			display : '物料编号',
			validation : {
				required : true
			},
			readonly : true
		},{
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			display : '物料名称',
			validation : {
				required : true
			},
			readonly : true
		},{
			name : 'productModel',
			tclass : 'readOnlyTxtItem',
			display : '型号/版本',
			readonly : true
		},{
			name : 'stockName',
			tclass : 'txtshort',
			display : '收料仓库',
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_stockinfo({
					hiddenId : 'withdrawequ_cmp_stockId' + rowNum,
					nameCol : 'stockName',
					gridOptions : {
//						param : {
//							'isDel' : '0'
//						},
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $stockId = g.getCmpByRowAndCol(
											rowNum, 'stockId');
									$stockId.val(rowData.id);
									var $stockCode = g.getCmpByRowAndCol(
											rowNum, 'stockCode');
									$stockCode.val(rowData.stockCode);
								}
							})(rowNum)
						}
					}
				});
			}
		},{
			name : 'stockId',
			type : 'hidden',
			display : '收料仓库id'
		},{
			name : 'stockCode',
			type : 'hidden',
			display : '收料仓库code'
		},{
			name : 'number',
			tclass : 'txtshort',
			display : '数量',
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				var defaultVal = $input.val();
				if(equArr != ''){
					$.each(equArr,function(i,item){
						var equVal = item.split(":");
						if(equVal[0] == rowData.id){
							defaultVal = equVal[1];
						}
					});
				}
				defaultVal = (defaultVal <= 0)? 0 : defaultVal;
				$input.val(defaultVal);
				$input.after("<input type='hidden' id='withdrawequ_cmp_maxVar"+rowNum+"' value='"+defaultVal+"'/>");
			},
			event : {
				blur : function() {
					var rowNum = $(this).data("rowNum");
					var maxVal = $("#withdrawequ_cmp_maxVar"+rowNum).val();
					var inputVal = $(this).val();
					maxVal = (maxVal && maxVal != undefined)? parseInt(maxVal) : 0;
					if(isNaN(inputVal)){
						alert("请输入大于0的有效整数。");
						$(this).val(maxVal);
					}else{
						inputVal = Number($(this).val());
						if(inputVal > maxVal){
							alert("下达数量不得超过未下达数量【"+maxVal+"】。");
							$(this).val(maxVal);
						}
					}
				}
			}
		},{
			name : 'remark',
			tclass : 'txt',
			display : '备注'
		}]
	})
}