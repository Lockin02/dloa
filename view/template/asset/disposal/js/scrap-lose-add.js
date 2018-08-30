$(function() {
	// ѡ����Ա���
	$("#payer").yxselect_user({
		hiddenId : 'payerId'
	});
    //alert($("#loseBillNo").val());
	$("#purchaseProductTable").yxeditgrid({

		objName : 'scrap[item]',
		url : '?model=asset_daily_loseitem&action=listJson',
		event : {
			removeRow : function(t, rowNum, rowData) {
				countAmount();
			}
		},
		param : {
			loseId: $("#loseId").val(),
			loseBillNo : $("#loseBillNo").val(),
			//ֻ��ʾδ���ϵĿ�Ƭ
			isScrap:'0'
		},
		//isAddAndDel : false,
		isAdd : false,
		colModel : [
			{
			display : '��ʧ��Id',
			name : 'loseId',
			type : 'hidden'
		},
//		,{
//			display : '��ʧ�����',
//			name : 'loseBillNo',
//			type : 'hidden',
//			//���ӱ��ı���ֵ
//			process : function($input,row){
//				//var assetId = row.id;
//				var loseBillNo = $("#loseBillNo").val();
//				$input.val(loseBillNo);
//			}
//		},
			{
			display : '�ʲ�Id',
			name : 'assetId',
			type : 'hidden'
		}, {
			display : '��Ƭ���',
			name : 'assetCode',
			validation : {
				required : true
			},
			// blur ʧ������������������ķ���
			event : {
				blur : function() {
					countAmount();
				}
			},
			readonly : true
		}, {
			display : '�ʲ�����',
			name : 'assetName',
			validation : {
				required : true
			},
			// blur ʧ������������������ķ���
			event : {
				blur : function() {
					countAmount();
				}
			},
			readonly : true
		}, {
			display : '����ͺ�',
			name : 'spec',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '��������',
			name : 'buyDate',
			tclass : 'txtshort',
			// type:'date',
			readonly : true
		}, {
			display : '�ʲ�ԭֵ',
			name : 'origina',
			tclass : 'txtshort',
			readonly : true,
			type : 'money'
		}, {
			display : '��ֵ',
			name : 'salvage',
			tclass : 'txtshort',
			readonly : true,
			type : 'money',
			process : function() {
				countAmount();
			}
		}, {
			display : '�����۾�',
			name : 'depreciation',
			tclass : 'txtshort',
			readonly : true,
			type : 'money',
			process : function() {
				countAmount();
			}
		}, {
			display : '����״̬',
			name : 'sellStatus',
			readonly : true,
			//���ӱ��ı���ֵ
			process : function($input,row){
				//var assetId = row.id;
				$input.val('δ����');
			},
			type:'hidden'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	});
	// ѡ����Ա���
	$("#proposer").yxselect_user({
		hiddenId : 'proposerId',
		isGetDept : [true, "deptId", "deptName"]
	});

	/**
	 * ��֤��Ϣ
	 */

	validate({
		"billNo" : {
			required : true

		},
		"proposer" : {
			required : true
		},
		"scrapNum" : {
			required : true,
			custom : ['onlyNumber']
		},
//		"amount" : {
//			required : true,
//			custom : ['money']
//		},
		"salvage" : {
			required : true,
			custom : ['money']
		}
	});

});

// ���ݴӱ�Ĳ�ֵ��̬�����Ӧ���ܲ�ֵ
function countAmount() {
	// ��ȡ��ǰ����������Ƭ���ʲ���  getCurRowNum  getCurShowRowNum
	var curRowNum = $("#purchaseProductTable").yxeditgrid("getCurShowRowNum")
	$("#scrapNum").val(curRowNum);
	var rowAmountVa = 0;
	var cmps = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "salvage");
	cmps.each(function() {
		rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
	});
	$("#salvage").val(rowAmountVa);
	$("#salvage_v").val(moneyFormat2(rowAmountVa));

	return true;
}
/*
 * ���ȷ��
 */
function confirmAudit() {
	if (confirm("��ȷ��Ҫ�ύ�����?")) {
		$("#form1").attr("action",
				"?model=asset_disposal_scrap&action=add&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}
