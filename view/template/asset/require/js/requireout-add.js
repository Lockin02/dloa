$(document).ready(function() {
	//������֤���Ϊ�գ��ӱ��޷�������֤
	validate({
		"applyDate" : {
			required : true
		}
	});
	$("#itemTable").yxeditgrid({
		objName : 'requireout[items]',
		title : '��Ƭ��Ϣ',
		isAddOneRow : false,
		colModel : [{
			display : '�ʲ����',
			name : 'assetCode',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_asset({
					isFocusoutCheck : false,
					hiddenId : 'itemTable_cmp_assetId' + rowNum,
					nameCol : 'assetCode',
					event : {
						'clear' : function() {
							g.setRowColValue(rowNum,'assetId','');
							g.setRowColValue(rowNum,'assetName','');
							g.setRowColValue(rowNum,'salvage','');
						}
					},
					gridOptions : {
						param : {//ֻ��ʾ���õĿ�Ƭ
							'useStatusCode' : 'SYZT-XZ'
						},
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $cmps = g.getCmpByCol('assetId');
									var isReturn = false;
									$cmps.each(function() {
										if ($(this).val() == rowData.id) {
											alert("�벻Ҫѡ����ͬ���ʲ�.");
											isReturn = true;
										}
									});
									if (!isReturn) {
										selectAssetFn(g, rowNum, rowData);
									} else {
										return false;
									}
								}
							})(rowNum)
						}
					}
				});
			},
			width : 120
		}, {
			display : '�ʲ�����',
			name : 'assetName',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_asset({
					isFocusoutCheck : false,
					hiddenId : 'itemTable_cmp_assetId' + rowNum,
					nameCol : 'assetName',
					event : {
						'clear' : function() {
							g.setRowColValue(rowNum,'assetId','');
							g.setRowColValue(rowNum,'assetCode','');
							g.setRowColValue(rowNum,'salvage','');
						}
					},
					gridOptions : {
						param : {//ֻ��ʾ���õĿ�Ƭ
							'useStatusCode' : 'SYZT-XZ'
						},
						searchId : '',// ����idֵ������������
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $cmps = g.getCmpByCol('assetId');
									var isReturn = false;
									$cmps.each(function() {
										if ($(this).val() == rowData.id) {
											alert("�벻Ҫѡ����ͬ���ʲ�.");
											isReturn = true;
										}
									});
									if (!isReturn) {
										selectAssetFn(g, rowNum, rowData);
									} else {
										return false;
									}
								}
							})(rowNum)
						}
					}
				});
			},
			width : 100
		}, {
			display : '�ʲ�Id',
			name : 'assetId',
			type : 'hidden'
		}, {
			display : '�ʲ���ֵ',
			name : 'salvage',
			type : 'money',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 80
		}, {
			display : '���ϱ��',
			name : 'productCode',
			tclass : 'txtmiddle',
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_product({
					isFocusoutCheck : false,
					closeAndStockCheck : true,
					hiddenId : 'itemTable_cmp_productId' + rowNum,
					height : 250,
					event : {
						'clear' : function() {
							g.setRowColValue(rowNum,'productId','');
							g.setRowColValue(rowNum,'productName','');
							g.setRowColValue(rowNum,'spec','');
						}
					},
					gridOptions : {
						showcheckbox : false,
						isTitle : true,
						event : {
							"row_dblclick" : (function(rowNum, rowData) {
								return function(e, row, productData) {
									g.getCmpByRowAndCol(rowNum, 'productName').val(productData.productName);
									g.getCmpByRowAndCol(rowNum, 'spec').val(productData.pattern);
								}
							})(rowNum, rowData)
						}
					}
				});
			},
			width : 120
		}, {
			display : '��������',
			name : 'productName',
			tclass : 'txt',
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_product({
					isFocusoutCheck : false,
					closeAndStockCheck : true,
					hiddenId : 'itemTable_cmp_productId' + rowNum,
					nameCol : 'productName',
					height : 250,
					event : {
						'clear' : function() {
							g.setRowColValue(rowNum,'productId','');
							g.setRowColValue(rowNum,'productCode','');
							g.setRowColValue(rowNum,'spec','');
						}
					},
					gridOptions : {
						showcheckbox : false,
						isTitle : true,
						event : {
							"row_dblclick" : (function(rowNum, rowData) {
								return function(e, row, productData) {
									g.getCmpByRowAndCol(rowNum, 'productCode').val(productData.productCode);
									g.getCmpByRowAndCol(rowNum, 'spec').val(productData.pattern);
								}								
							})(rowNum, rowData)
						}
					}
				});
			},
			width : 100
		}, {
			display : '����id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '����ͺ�',
			name : 'spec',
			width : 120
		}, {
			display : '����',
			name : 'number',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 60
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt',
			width : 120
		}]
	});
	//����ѡ��Ƭ��Ϣ��ť
	$("#itemTable").find("tr:first td").append("<input type='button' value='ѡ��Ƭ��Ϣ' class='txt_btn_a' style='margin-left:10px;' onclick='selectCard();'/>");
});
//ѡ��Ƭ��Ϣ
function selectCard(){
	showOpenWin("?model=asset_assetcard_assetcard"
			+ "&action=selectCard&showType=requireout",1,500,900);
}
//���ÿ�Ƭ����
function setDatas(rows){
	var objGrid = $("#itemTable");
	for(var i = 0; i < rows.length ; i++){
		//�жϿ�Ƭ�����Ƿ��Ѵ���
		var assetIdArr = objGrid.yxeditgrid("getCmpByCol","assetCode");
		var isExist = false;
		if(assetIdArr.length > 0){
			assetIdArr.each(function(){
				if(this.value == rows[i].assetCode){
					isExist = true;
					alert("�벻Ҫѡ����ͬ���ʲ�" );
					return false;
				}
			});
		}
		//����Ѿ��ظ��ˣ��Ͳ��ܼ���ѡ��
		if(isExist){
			return false;
		}
		//���»�ȡ����
		var tbRowNum = objGrid.yxeditgrid("getAllAddRowNum");
		//������
		objGrid.yxeditgrid("addRow",tbRowNum);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetId",rows[i].id);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetCode",rows[i].assetCode);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetName",rows[i].assetName);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"salvage",rows[i].salvage,true);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"spec",rows[i].spec);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"productId",rows[i].productId);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"productCode",rows[i].productCode);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"productName",rows[i].productName);
		//��������Ϊ1��1�ſ�Ƭ��Ӧ1������
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"number",1);
	}
}
//ѡ��Ƭ���Զ�����������Ϣ
function selectAssetFn(g, rowNum, rowData) {
	g.setRowColValue(rowNum,'assetId',rowData.id);
	g.setRowColValue(rowNum,'assetCode',rowData.assetCode);
	g.setRowColValue(rowNum,'assetName',rowData.assetName);
	g.setRowColValue(rowNum,'salvage',rowData.salvage,true);
	g.setRowColValue(rowNum,'spec',rowData.spec);
	g.setRowColValue(rowNum,'productId',rowData.productId);
	g.setRowColValue(rowNum,'productCode',rowData.productCode);
	g.setRowColValue(rowNum,'productName',rowData.productName);
	//��������Ϊ1��1�ſ�Ƭ��Ӧ1������
	g.setRowColValue(rowNum,'number',1);
}
//����/���ȷ��
function confirmSubmit(type) {
	if(type == 'audit'){
		if (confirm("��ȷ��Ҫ�ύ������?")) {
			$("#form1").attr("action",
					"?model=asset_require_requireout&action=add&actType=audit");
			$("#form1").submit();
		} else {
			return false;
		}
	}else{
		$("#form1").attr("action",
		"?model=asset_require_requireout&action=add");
		$("#form1").submit();
	}
}

