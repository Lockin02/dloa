
var equConfig = {
	type : ''// add edit change view
};// ��������

// ����ȷ�� ����
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

	//����ֿ���Ⱦ
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
					// ���������
					var $withdrawequ = $("#withdrawequ");
					// thisGrid.html("");
					var stockNameObj = $withdrawequ.yxeditgrid("getCmpByCol", "stockName");// ����id
					var stockCodeObj = $withdrawequ.yxeditgrid("getCmpByCol", "stockCode");// ����id
					var stockIdObj = $withdrawequ.yxeditgrid("getCmpByCol", "stockId");// ����id
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
	 * ��֤��Ϣ
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
	url = url ? url : '?model=contract_contract_equ&action=getConEqu';// ��Ʒ����������Դ
	$("#withdrawequ").yxeditgrid({
		objName : 'withdraw[items]',
		isAddOneRow : true,
		isAdd : false,
		url : url,
		param : param,
		colModel : [{
			display : 'Դ���嵥id',
			name : 'contEquId'
			,type : 'hidden'
			,process : function($input, rowData) {
				$input.val(rowData.id);
			}
		}, {
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		}, {
			name : 'productCode',
			tclass : 'readOnlyTxtItem',
			display : '���ϱ��',
			validation : {
				required : true
			},
			readonly : true
		},{
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			display : '��������',
			validation : {
				required : true
			},
			readonly : true
		},{
			name : 'productModel',
			tclass : 'readOnlyTxtItem',
			display : '�ͺ�/�汾',
			readonly : true
		},{
			name : 'stockName',
			tclass : 'txtshort',
			display : '���ϲֿ�',
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
			display : '���ϲֿ�id'
		},{
			name : 'stockCode',
			type : 'hidden',
			display : '���ϲֿ�code'
		},{
			name : 'number',
			tclass : 'txtshort',
			display : '����',
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
						alert("���������0����Ч������");
						$(this).val(maxVal);
					}else{
						inputVal = Number($(this).val());
						if(inputVal > maxVal){
							alert("�´��������ó���δ�´�������"+maxVal+"����");
							$(this).val(maxVal);
						}
					}
				}
			}
		},{
			name : 'remark',
			tclass : 'txt',
			display : '��ע'
		}]
	})
}