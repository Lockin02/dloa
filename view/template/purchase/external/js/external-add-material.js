$(document).ready(function() {
	var data = eval("(" + $("#productJson").text() + ")");
	var productObj = $("#productItem");
	productObj.yxeditgrid({
		objName : 'basic[equipment]',
		data : data,
		colModel : [{
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '���ϱ��',
			name : 'productNumb',
			width : 80,
			process : function ($input) {
				var rowNum = $input.data("rowNum");
				$input.yxcombogrid_product({
					hiddenId : 'productItem_cmp_productId' + rowNum,
					gridOptions : {
						showcheckbox : false,
						event : {
							'row_dblclick' : function(e ,row ,data) {
								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productNumb").val(data.productCode);
								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productName").val(data.productName);
								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"pattern").val(data.pattern);
								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"unitName").val(data.unitName);
								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productTypeId").val(data.proTypeId);
								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productTypeName").val(data.proType);
								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"leastPackNum").val(data.leastPackNum);
								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"leastOrderNum").val(data.leastOrderNum);
								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"amountAll").val(data.leastOrderNum);
								$.ajax({
									type : "POST",
									url : "?model=purchase_external_external&action=ajaxGetStockNum",
									data : {
										productId : data.id
									},
									success : function(msg) {
										productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"stockNum").val(msg);
									}
								});
							}
						}
					}
				});
			}
		},{
			display : '��������',
			name : 'productName',
			tclass : 'readOnlyText',
			width : 250,
			readonly : true
		},{
			display : '����ͺ�',
			name : 'pattern',
			tclass : 'readOnlyTxtMiddle',
			width : 80,
			readonly : true
		},{
			display : '��λ',
			name : 'unitName',
			tclass : 'readOnlyTxtShort',
			width : 80,
			readonly : true
		},{
			display : '��С��װ��',
			name : 'leastPackNum',
			tclass : 'readOnlyTxtMiddle',
			width : 80,
			readonly : true
		},{
			display : '��С������',
			name : 'leastOrderNum',
			tclass : 'readOnlyTxtMiddle',
			width : 80,
			readonly : true
		},{
			display : '�ɹ�����',
			name : 'qualityCode',
			width : 80,
			type : 'select',
			datacode : 'CGZJSX'
		},{
			display : '�������',
			name : 'leastOrderNum',
			tclass : 'readOnlyTxtMiddle',
			width : 80,
			readonly : true
		},{
			display : '�ɹ���������',
			name : 'amountAll',
			tclass : 'txtmiddle amount',
			validation : {
				custom : ['onlyNumber']
			},
			width : 80
		},{
			display : '��������',
			name : 'dateIssued',
			width : 80,
			tclass : 'readOnlyTxtMiddle',
			readonly : true,
			process : function ($input) {
				var nowDate = new Date();
				var year = nowDate.getFullYear(); //��ȡ��
				var month = nowDate.getMonth() + 1; //��ȡ��
				var date = nowDate.getDate(); //��ȡ��
				$input.val(year + '-' + month + '-' + date);
			}
		},{
			display : 'ϣ��������',
			name : 'dateHope',
			width : 80,
			type : 'date',
			readonly : true,
			validation : {
				required : true
			},
			process : function ($input) {
				var nowDate = new Date();
				var year = nowDate.getFullYear(); //��ȡ��
				var month = nowDate.getMonth() + 1; //��ȡ��
				var date = nowDate.getDate(); //��ȡ��
				$input.val(year + '-' + month + '-' + date);
			}
		},{
			display : '��ע',
			name : 'remark',
			type : 'textarea',
			rows : 2,
			width : 150
		}]
	});
});