// �������������¼��ķ���
function xq_approval(){
	var itemType = $("#xq_itemType").val();
	var pid = $("#xq_pid").val();
	var relDocId = $("#xq_relDocId").val();
	var isChange = $("#isChange").val();
	if ($("#isChange").length == 0) {//�Ƿ��������������
		var isChange = 0;
	} else {
		var isChange = $("#isChange").val();
	}
	if ($("#appFormName").length == 0) {
		var appFormName = "";
	} else {
		var appFormName = $("#appFormName").val();
	}
	if ($("#isPrint").length == 0) {//�Ƿ��������������
		var isPrint = 0;
	} else {
		var isPrint = $("#isPrint").val();
	}

	$.post("?model=common_approvalView&action=getXqApproval", {
		pid : pid,
		relDocId : relDocId,
		itemtype : itemType,
		isChange : isChange,
		isPrint : isPrint
	}, function(data) {
		if (data == 0) { //û���������ʱ����ֵΪ����
			var datahtml = "<tr><td></td></tr>";
		}
		if (data != 0) {
			if(isPrint == "1"){
				var $html = $('<table width="100%"  class="form_in_table" id="approvalTable">'
					+ '<thead>'
					+ '<tr  > '
					+ '<td width="100%" colspan="6" class="form_header"><B>'
					+ appFormName
					+ '����������¼</B></td>'
					+ '</tr>'
					+ '<tr class="main_tr_header">'
					+ '<th width="12%">������</th>'
					+ '<th width="12%">������</th>'
					+ '<th width="18%" nowrap="nowrap">��������</th>'
					+ '<th width="10%">�������</th>'
					+ '<th>�������</th>'
					+ '</tr>' + '</thead>');
			}else{
				var $html = $('<table width="100%"  class="form_in_table" id="approvalTable">'
					+ '<thead>'
					+ '<tr  > '
					+ '<td width="100%" colspan="6" class="form_header"><B>'
					+ appFormName
					+ '����������¼</B></td>'
					+ '</tr>'
					+ '<tr class="main_tr_header">'
					+ '<th width="12%">���</th>'
					+ '<th width="12%">������</th>'
					+ '<th width="12%">������</th>'
					+ '<th width="18%" nowrap="nowrap">��������</th>'
					+ '<th width="10%">�������</th>'
					+ '<th>�������</th>'
					+ '</tr>' + '</thead>');
			}
			var $html2 = $('</table>');
			if (data == 0) {
				var $tr = $(datahtml);
			} else {
				var $tr = $(data);
			}
			$html.append($tr);
			$html.append($html2);
			$("#xq_approvalView").append($html);
		}
	});
}

$(function() {
    var requireId = $("#relDocId").val();

	$("#purchaseProductTable").yxeditgrid({
		objName : 'apply[applyItem]',
		event : {
			removeRow : function(t, rowNum, rowData) {
				countAmount();
			}
		},
        url : "?model=asset_purchase_apply_apply&action=getPurchaseDetail",
        param: {requireId: requireId},
		isAdd : false,
		isAddOneRow : true,
		colModel : [{
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '���ϱ���',
			name : 'productCode',
			tclass : 'readOnlyTxtNormal',
			width : 120,
			readonly : true
		}, {
			display : '��������',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '�ʲ�����',
			name : 'inputProductName',
			process : function ($input, rowData) {
				$input.val(rowData['devicename']);
			},
			validation : {
				required : true
			},
			tclass : 'txt'
		}, {
			display : '�ʲ�����',
			name : 'description',
			process : function ($input, rowData) {
				$input.val(rowData['devicedescription']);
			},
			validation : {
				required : true
			},
			tclass : 'txt'
		}, {
			display : 'requireItemId',
			name : 'requireItemId',
			process : function ($input, rowData) {
				$input.val(rowData['detailId']);
			},
			type : "hidden"
		}, {
			display : '�������',
			name : 'productCategoryCode',
			validation : {
				required : true
			},
			tclass : 'txtshort',
			type : 'select',
			datacode : 'CGWLLB',
			processData : function(data) {
				var newData = [{
					dataName : '',
					dataCode : ''
				}];
				for (var i = 0; i < data.length; i++) {
					newData.push(data[i]);
				}
				return newData;
			}
		},{
			display : '���',
			name : 'pattem',
			validation : {
				required : true
			}
		}, {
			display : '��������',
			name : 'applyAmount',
			tclass : 'txtshort',
			validation : {
				custom : ['onlyNumber']
			},
			event : {
				blur : function(e){
					var rownum = $(this).data('rowNum');// �ڼ���
					var grid = $(this).data('grid');// ������

					var applyAmount = grid.getCmpByRowAndCol(rownum, 'applyAmount').val();
					var maxAmount = grid.getCmpByRowAndCol(rownum, 'maxAmount').val();

					if($(this).val() *1 <= 0){
						alert("�����������0��");
						$(this).val(maxAmount);
					}

					if($(this).val() *1 > maxAmount *1){
						alert("�������ܴ���ʣ�����������");
						$(this).val(maxAmount);
					}
				}
			}
		}, {
			display : '�������',
			name : 'maxAmount',
			type : "hidden"
		}, {
			display : 'Ԥ�ƽ��',
			name : 'amounts',
			tclass : 'txtshort',
			type : 'money',
			// blur ʧ������������������ķ���
			event : {
				blur : function() {
					countAmount();
				}
			}
		}, {
			display : '��λ',
			name : 'unitName',
			tclass : 'txtshort',
			validation : {
				required : true
			}
		}, {
			display : 'ϣ����������',
			name : 'dateHope',
			process : function ($input, rowData) {
				$input.val(rowData['borrowdate']);
			},
			type : 'date',
			tclass : 'txtshort',
			validation : {
				custom : ['date']
			}
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}, {
			display : 'ѯ�۽��',
			name : 'inquiryAmount',
			process : function ($input, rowData) {
				$input.val(rowData['inquiryamount']);
			},
			type : 'money',
			tclass : 'txtshort'
		}, {
			display : '�������',
			name : 'suggestion',
			process : function ($input, rowData) {
				$input.val(rowData['advice']);
			},
			type : 'textarea',
			cols : '40',
			rows : '3'
		}]
	});
	//������˾
	$("#businessBelongName").yxcombogrid_branch({
		hiddenId: 'businessBelong',
		height: 250,
		isFocusoutCheck: false,
		gridOptions: {
			showcheckbox: false
		}
	});

	$("#applicantName").yxselect_user({
		hiddenId : 'applicantId',
		isGetDept : [true, "applyDetId", "applyDetName"]
	});

	// ���ݲɹ���������ʾ�ʲ���;
	purchCategoryArr = getData('CGZL');
	addDataToSelect(purchCategoryArr, 'purchCategory');
	$('#purchCategory').change(function() {
		$('#assetUseCode').empty();
		assetUseArr = getData($('#purchCategory').val());
		addDataToSelect(assetUseArr, 'assetUseCode');
		if ($('#assetUseCode').val()) {
			$('#assetUse').val($('#assetUseCode').get(0).options[$('#assetUseCode').get(0).selectedIndex].innerText);
		}
	});

	// ���ݲɹ���������ʾ�����ֶΣ��ƻ���š��ƻ���ȣ�
	$("#hiddenA").hide();
	$('#purchaseType').change(function() {
		if ($("#purchaseType").val() == "CGLX-JHN") {
			$("#hiddenA").show();
		} else {
			$('#planYear').val("");
			$("#hiddenA").hide();
		}
	});

	// ���ݲɹ�����Ϊ���з��ࡱʱ����ʾ�����ֶΣ��ɹ����ࡢ�ش�ר�����ơ�ļ���ʽ���Ŀ�������з���Ŀ��
	$("#hiddenD").hide();
	$("#hiddenE").hide();
	$("#purchCategory").change(function() {
		if ($("#purchCategory").val() == "CGZL-YFL") {
			$('#assetUseCode').val("");
			$('#assetUse').val("");
			$("#hiddenC").hide();
			$("#hiddenD").show();
			$("#hiddenE").show();
		} else {
			$('#assetClass').val("");
			$('#importProject').val("");
			$('#moneyProject').val("");
			$('#otherProject').val("");
			$("#hiddenC").show();
			$("#hiddenD").hide();
			$("#hiddenE").hide();
		}
	});

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"useType" : {
			required : true
		},
		"userName" : {
			required : true
		},
		"applicantName" : {
			required : true
		},
		"businessBelongName" : {
			required : true
		},
		"applyTime" : {
			custom : ['date']
		},
//		"userTel" : {
//			required : false,
//			custom : ['onlyNumber']
//		},
		"assetUseCode" : {
			required : true
		},
		"amounts" : {
			required : true
		}
	});

	$("#purchaseViewTable").yxeditgrid({
		objName : 'apply[applyItem]',
		url : '?model=asset_purchase_apply_applyItem&action=preListJson',
		delTagName : 'isDelTag',
		type : 'view',
		param : {
			relDocId : requireId,
			isDel : '0'
		},
		colModel : [{
			display : '��������',
			name : 'inputProductName',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '���',
			name : 'pattem',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '��������',
			name : 'applyAmount',
			tclass : 'txtshort'
		}, {
			display : '��Ӧ��',
			name : 'supplierName',
			tclass : 'txtmiddle'
		}, {
			display : '��λ',
			name : 'unitName',
			tclass : 'readOnlyTxtItem'
		}, {
//			display : '�ɹ�����',
//			name : 'purchAmount',
//			tclass : 'txtshort'
//		}, {
			display : 'ϣ����������',
			name : 'dateHope',
			type : 'date'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	});

	$("#itemTable").yxeditgrid({
		url : "?model=asset_purchase_apply_apply&action=getRequireDetail",
        param: {requireId: requireId},
		type : 'view',
		colModel : [{
			display : 'id',
			name : 'detailId',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'devicedescription',
			tclass : 'txt'
		}, {
			display : '����',
			name : 'amount',
			tclass : 'txtshort'
		}, {
			display : '�ѷ�������',
			name : 'executedNum',
			tclass : 'txtshort'
		}, {
			display : 'Ԥ�ƽ��',
			name : 'expectAmount',
			tclass : 'txtshort',
			process : function(v,row){
				v = accMul(row.deviceprice,row.amount);
				return moneyFormat2(v);
			}
		}, {
			display : 'Ԥ�ƽ�������',
			name : 'borrowdate',
			type:'date',
			tclass : 'txtshort'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}, {
			display : 'ѯ�۽��',
			name : 'inquiryamount',
			process : function(v,row) {
				return moneyFormat2(v);
			},
			tclass : 'txtshort'
		}, {
			display : '�������',
			name : 'advice',
			type : 'textarea'
		}]
	})
});

$(function(){
	// �������������¼
	if(typeof($("#extra_approvalItems").val()) != 'undefined' &&  $("#extra_approvalItems").val() == 'xq'){
		xq_approval();
	}
});

/*
 * ���ȷ��
 */
function confirmAudit(act) {
	//�����Ƿ��в�ͬ
//	var deptDiff = false;
//	var markDept;
//	$("select[id^='purchaseProductTable_cmp_purchDept']").each(function(i,n){
//		//�������ɾ�����ݣ��Ŵ���
//		if($("#apply[applyItem]_"+ i +"_isDelTag").length == 0){
//			if(!markDept){
//				markDept = this.value;
//			}else if(markDept != this.value){
//				deptDiff = true;
//				return false;
//			}
//		}
//	});
//	//������Ų�ͬ�������ύ��
//	if(deptDiff == true){
//		alert('�����´�ɹ�������뱣�ֲɹ�����һ��');
//		return false;
//	}

	// if (confirm("ȷ��Ҫ�ύ��?")) {
	// 	$("#form1").attr("action","?model=asset_purchase_apply_apply&action=add&actType=audit").submit();
	// } else {
	// 	return false;
	// }
	if(act == 'submit'){
		if (confirm("ȷ��Ҫ�ύ��?")) {
			var purchaseDept = $('#purchaseDept').val();
			// PMS 636 �����ɹ�����Ҳ��Ҫ���ʲ�����Ա����
			var audit = 'submit';//(purchaseDept == '1')? 'noaudit' : 'submit';
			var action = 'addBeforeConfirm';//(purchaseDept == '1')? 'add' : 'addBeforeConfirm';
			// $("#form1").attr("action","?model=asset_purchase_apply_apply&action=addBeforeConfirm&actType="+audit).submit();
			$("#form1").attr("action","?model=asset_purchase_apply_apply&action="+action+"&actType="+audit).submit();
		} else {
			return false;
		}
	}else{
		// ����
		$("#form1").attr("action","?model=asset_purchase_apply_apply&action=addBeforeConfirm").submit();
	}
}
// ���ݴӱ�Ĳ�ֵ��̬����Ӧ���ܽ��
function countAmount() {
	// ��ȡ��ǰ����������Ƭ���ʲ���
	var curRowNum = $("#purchaseProductTable").yxeditgrid("getCurRowNum")
	$("#amounts").val(curRowNum);

	var rowAmountVa = 0;
	var cmps = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "amounts");
	cmps.each(function() {
		rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
	});
	$("#amounts").val(rowAmountVa);
	$("#amounts_v").val(moneyFormat2(rowAmountVa));
	return true;
}
