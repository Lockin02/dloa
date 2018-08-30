$(document).ready(function() {
	//�ʼ���Ⱦ
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check'
	});
	/**
	 * ��֤��Ϣ
	 */
	validate({
		"amount_v" : {
			required : true
		}
	});
	$("#purchaseProductTable").yxeditgrid({
		objName:'scrap[item]',
		url:'?model=asset_disposal_scrapitem&action=listJson',
		param:{allocateID:$("#allocateID").val()},
		isAddAndDel : false,
	colModel : [
		{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '��ʧ��Id',
			name : 'loseId',
			type : 'hidden'
		}, {
			display : '�ʲ�Id',
			name : 'assetId',
			type : 'hidden'
		}, {
			display:'�����ʲ�����',
			name : 'assetCode',
			type : 'statictext',
			width : 120
		}, {
			display:'�ʲ�����',
			name : 'assetName',
			type : 'statictext',
			width : 100
		},{
			display:'����ͺ�',
			name : 'spec',
			type : 'statictext',
			width : 100
		}, {
			display:'��������',
			name : 'buyDate',
			type : 'statictext',
			width : 100
		}, {
			display:'�ʲ�ԭֵ',
			name : 'origina',
			type : 'money',
			type : 'statictext',
			width : 100
		}, {
			display:'��ֵ',
			name : 'salvage',
			type : 'money',
			// blur ʧ������������
			event : {
				blur : function() {
					countAmount();
				}
			}
		}, {
			display : '��ֵ',
			name : 'netValue',
			type : 'money',
			// blur ʧ������������
			event : {
				blur : function() {
					countAmount();
				}
			}
		}, {
			display:'�����۾�',
			name : 'depreciation',
			type : 'money',
			type : 'statictext',
			width : 100
		}, {
			display:'��ע',
			name : 'remark',
			type : 'statictext',
			width : 100
		}]
   })
});
/*
 * �˶��ʲ���������
 */
function confirmCheck() {
	if (confirm("ȷ��Ҫ�˶��ʲ�����������?")) {
		$("#form1").attr("action",
				"?model=asset_disposal_scrap&action=confirmRequire&actType=check");
		$("#form1").submit();

	} else {
		return false;
	}
}
/*
 * ����ʲ���������
 */
function confirmBack() {
	if (confirm("ȷ��Ҫ����ʲ�����������?")) {
		$("#form1").attr("action",
				"?model=asset_disposal_scrap&action=confirmRequire&actType=back");
		$("#form1").submit();

	} else {
		return false;
	}
}
/*
 * ���ݴӱ�����ܲ�ֵ,�ܾ�ֵ
 */
function countAmount() {
	var rowsalvageVa = 0;
	var rownetValueVa = 0;
	var salvages = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "salvage");
	salvages.each(function() {
		rowsalvageVa = accAdd(rowsalvageVa, $(this).val(), 2);
	});
	var netValues = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "netValue");
	netValues.each(function() {
		rownetValueVa = accAdd(rownetValueVa, $(this).val(), 2);
	});
	//�ܲ�ֵ
	$("#salvage").val(rowsalvageVa);
	$("#salvage_v").val(moneyFormat2(rowsalvageVa));
	//�ܾ�ֵ
	$("#netValue").val(rownetValueVa);
	$("#netValue_v").val(moneyFormat2(rownetValueVa));
	return true;
}