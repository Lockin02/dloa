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
			display : 'Դ����ϸid',
			name : 'relDocId',
			process : function ($input ,row) {
				$input.val(row.id);
			},
			type : 'hidden'
		},{
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '���ϱ��',
			name : 'productCode',
			tclass : 'readOnlyTxtItem',
			readonly : true
		},{
			display : '��������',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		},{
			display : '����ͺ�',
			name : 'pattern',
			tclass : 'readOnlyTxtItem',
			readonly : true
		},{
			display : '��λ',
			name : 'unitName',
			tclass : 'readOnlyTxtShort',
			readonly : true
		},{
			display : 'Ӧ������',
			name : 'storageNum',
			tclass : 'readOnlyTxtShort',
			readonly : true,
			width : '10%',
			process : function ($input ,row) {
				$input.val(row.applyNum - row.backNum);
			}
		},{
			display : 'ʵ������',
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
						alert('ʵ���������ܳ���Ӧ������');
						$(this).val(validNum);
					} else {
						var price = itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"price").val();
						itemsObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"subPrice").val($(this).val() * price);
					}
				});
			}
		},{
			display : '���κ�',
			name : 'batchNum'
		},{
			display : '���ϲֿ�',
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
			display : '���ϲֿ�Code',
			name : 'inStockCode',
			type : 'hidden'
		},{
			display : '���ϲֿ�Id',
			name : 'inStockId',
			type : 'hidden'
		},{
			display : '����',
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
			display : '���',
			name : 'subPrice',
			tclass : 'readOnlyTxtShort',
			width : '10%',
			readonly : true,
            type : 'hidden',
			process : function ($input) {
				$input.val(0);
			}
		},{
			display : '�����ڣ��£�',
			name : 'shelfLife',
			validation : {
				custom : ['onlyNumber'],
				required : false
			},
			width : '10%'
		},{
			display : '��ע',
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

//�ύ����
function checkDate() {
	return true;
}

//���
function confirmAudit() {
	if (confirm("��˺󵥾ݽ������޸ģ���ȷ��Ҫ�����?")) {
		if (checkDate()) {
			$("#form1").attr("action" ,"?model=stock_instock_stockin&action=add&actType=audit");
			$("#docStatus").val("YSH");
			$("#form1").submit();
		}
	}
}