//��ʼ��
$(function() {
	//�ʼ���Ⱦ
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check'
	});
	//����ѡ��Ƭ��Ϣ��ť
	$("#purchaseProductTable").find("tr:first td").append("<input type='button' value='ѡ��Ƭ��Ϣ' class='txt_btn_a' style='margin-left:10px;' onclick='selectCard();'/>");
	// ѡ����Ա���
	$("#proposer").yxselect_user({
		hiddenId : 'proposerId',
		isGetDept : [true, "deptId", "deptName"],
		event : {
			select : function(e, returnValue) {
				if (returnValue) {
					$('#applyCompanyCode').val(returnValue.companyCode)
					$('#applyCompanyName').val(returnValue.companyName)
				}
			}
		}
	});
	// ѡ����Ա���
	$("#payer").yxselect_user({
		hiddenId : 'payerId',
		event : {
			select : function(e, returnValue) {
				if (returnValue) {
					$('#TO_NAME').val($('#payer').val());
					$('#TO_ID').val($('#payerId').val());
				}
			}
		}
	});
	/**
	 * ��֤��Ϣ
	 */
	validate({
		"scrapDate" : {
			required : true
		},
		"proposer" : {
			required : true
		},
		"amount_v" : {
			required : true
		},
		"reason" : {
			required : true
		},
		"scrapDeal" : {
			required : true
		},
//		"hasAccount" : {
//			required : true
//		},
		"payer" : {
			required : true
		}
	});
});
//ѡ��Ƭ���Զ��������ԭֵ����Ϣ
function selectAssetFn(g, rowNum, rowData) {
	g.setRowColValue(rowNum,'assetId',rowData.id);
	g.setRowColValue(rowNum,'assetCode',rowData.assetCode);
	g.setRowColValue(rowNum,'assetName',rowData.assetName);
	g.setRowColValue(rowNum,'spec',rowData.spec);
	g.setRowColValue(rowNum,'origina',rowData.origina,true);
	g.setRowColValue(rowNum,'salvage',rowData.salvage,true);
	g.setRowColValue(rowNum,'netValue',rowData.netValue,true);
	g.setRowColValue(rowNum,'buyDate',rowData.buyDate);
	g.setRowColValue(rowNum,'depreciation',rowData.depreciation,true);
	g.setRowColValue(rowNum,'remark',rowData.remark);
}
// ���ݴӱ�Ĳ�ֵ��̬����Ӧ���ܽ��
function countAmount() {
	// ��ȡ��ǰ����������Ƭ���ʲ���
	var curRowNum = $("#purchaseProductTable").yxeditgrid("getCurShowRowNum");
	//��������
	$("#scrapNum").val(curRowNum);
	var rowsalvageVa = 0;
	var rownetValueVa = 0;
	var salvages = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "salvage");
	salvages.each(function() {
		//$(this).val()��ȡ����ֵ
		rowsalvageVa = accAdd(rowsalvageVa, $("#"+$(this).attr('id')+"_v").val(), 2);
	});
	var netValues = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "netValue");
	netValues.each(function() {
		//$(this).val()��ȡ����ֵ
		rownetValueVa = accAdd(rownetValueVa, $("#"+$(this).attr('id')+"_v").val(), 2);
	});
	//�ܲ�ֵ
	$("#salvage").val(rowsalvageVa);
	$("#salvage_v").val(moneyFormat2(rowsalvageVa));
	//�ܾ�ֵ
	$("#netValue").val(rownetValueVa);
	$("#netValue_v").val(moneyFormat2(rownetValueVa));
	return true;
}
//ѡ��Ƭ��Ϣ
function selectCard(){
	showOpenWin("?model=asset_assetcard_assetcard"
			+ "&action=selectCard&showType=scrap"
			,1,500,900);
}
//���ÿ�Ƭ����
function setDatas(rows){
	var objGrid = $("#purchaseProductTable");
	for(var i = 0; i < rows.length ; i++){
		//�жϿ�Ƭ�����Ƿ��Ѵ���
		var assetCodeArr = objGrid.yxeditgrid("getCmpByCol","assetCode");
		var isExist = false;
		if(assetCodeArr.length > 0){
			assetCodeArr.each(function(){
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
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"spec",rows[i].spec);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"origina",rows[i].origina,true);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"salvage",rows[i].salvage,true);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"netValue",rows[i].netValue,true);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"buyDate",rows[i].buyDate);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"depreciation",rows[i].depreciation,true);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"remark",rows[i].remark);
	}
	countAmount();
}
//�ύ�򱣴�ʱ����֤��Ƭ��Ϣ�Ϸ���
function checkCard(){
	var objGrid = $("#purchaseProductTable");
	//�ӱ�����Ϊ��
	if(objGrid.yxeditgrid("getCurShowRowNum") == 0){
		alert("��ѡ��Ҫ���ϵĿ�Ƭ��Ϣ!");
		return false;
	}
	//��֤�Ƿ���ڷ�����״̬�Ŀ�Ƭ�����ڲ������ύ
	var assetIds = objGrid.yxeditgrid("getCmpByCol", "assetId");
	var assetIdArr = [];
	assetIds.each(function() {
		assetIdArr.push($(this).val());
	});
	var responseText = $.ajax({
		type : 'POST',
		url : '?model=asset_assetcard_assetcard&action=checkCardStatus',
		data : {
			'assetIdArr' : assetIdArr
		},
		async : false
	}).responseText;
	var data = eval("(" + responseText + ")");
	if(data.length != 0){
		alert("��Ƭ���Ϊ��"+data+"���Ŀ�Ƭ���������ϴ������������ύ��");
		return false;
	}
	return true;
}