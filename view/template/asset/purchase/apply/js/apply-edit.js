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
	$("#purchaseProductTable").yxeditgrid({
		objName : 'apply[applyItem]',
		// delTagName : 'isDelTag',
		url : '?model=asset_purchase_apply_applyItem&action=editListJson',
		param : {
			applyId : $("#applyId").val()
		},
		isAdd : false,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '���ϱ���',
			name : 'productCode',
			tclass : 'readOnlyTxtNormal',
			width :��120,
			readonly : true
		}, {
			display : '��������',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			width :��200,
			readonly : true
		}, {
			display : '�ʲ�����',
			name : 'inputProductName',
			width :��200,
			validation : {
				required : true
			},
			tclass : 'txtlong'
		}, {
			display : '�ʲ�����',
			name : 'description',
			validation : {
				required : true
			},
			width :��200,
			tclass : 'txt'
		}, {
			display : '���',
			name : 'pattem',
			width :��100,
			validation : {
				required : true
			}
		}, {
			display : '��������',
			name : 'applyAmount',
			tclass : 'txtshort',
			width :��60,
			validation : {
				custom : ['onlyNumber']
			},
			event : {
				blur : function(e){
					var rownum = $(this).data('rowNum');// �ڼ���
					var grid = $(this).data('grid');// ������

					var applyAmount = grid.getCmpByRowAndCol(rownum, 'applyAmount').val();
					var maxAmount = grid.getCmpByRowAndCol(rownum, 'maxAmount').val();

					if(!isNum($(this).val())){
						alert("������������");
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
			type : 'money',
			tclass : 'txtshort'
		}, {
			display : '�������',
			name : 'suggestion',
			type : 'textarea',
			width : 200
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
	// ѡ����Ա���
	$("#userName").yxselect_user({
		hiddenId : 'userId',
		isGetDept : [true, "useDetId", "useDetName"]
	});
	$("#applicantName").yxselect_user({
		hiddenId : 'applicantId',
		isGetDept : [true, "applyDetId", "applyDetName"]
	});

	// ���ݲɹ���������ʾ�����ֶΣ��ƻ���š�Ԥ���ܼۣ�
	if ($("#purchaseType").val() != "CGLX-JHN") {
		$("#hiddenA").hide();
		// $("#hiddenB").hide();
	}

	// ���ݲɹ���������ʾ�ʲ���;
	$('#purchCategory').change(function() {
		$('#assetUseCode').empty();
		assetUseArr = getData($('#purchCategory').val());
		addDataToSelect(assetUseArr, 'assetUseCode');
		if ($('#assetUseCode').val()) {
			$('#assetUse').val($('#assetUseCode').get(0).options[$('#assetUseCode').get(0).selectedIndex].innerText);
		}
	});

	$('#purchaseType').change(function() {
		if ($("#purchaseType").val() == "CGLX-JHN") {
			$("#hiddenA").show();
			// $("#hiddenB").show();
		} else {
			$('#planYear').val("");
			$("#hiddenA").hide();
			// $("#hiddenB").hide();
		}
	});

	// ���ݲɹ�����Ϊ���з��ࡱʱ����ʾ�����ֶΣ��ɹ����ࡢ�ش�ר�����ơ�ļ���ʽ���Ŀ�������з���Ŀ��
	// alert($("#purchCategory").val());
	if ($("#purchCategory").val() == "CGZL-YFL") {
		$("#hiddenC").hide();
		$("#hiddenD").show();
		$("#hiddenE").show();
	} else {
		$("#hiddenC").show();
		$("#hiddenD").hide();
		$("#hiddenE").hide();
	}

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
		"businessBelongName" : {
			required : true
		},
		"userName" : {
			required : true
		},
		"applicantName" : {
			required : true
		},
		"applyTime" : {
			custom : ['date']
		},
		"amounts_v" : {
			required : true
		}
	});

	// �������������¼
	if(typeof($("#extra_approvalItems").val()) != 'undefined' &&  $("#extra_approvalItems").val() == 'xq'){
		xq_approval();
	}
});

//���ȷ��
function confirmAudit() {
	if(checkForm()){
		$("#purchaseProductTable").yxeditgrid("showRow",2);
		if (confirm("ȷ��Ҫ�ύ��?")) {
			var purchaseDept = $('#purchaseDept').val();
			var audit = 'submit'; //(purchaseDept == '1')? 'noaudit' : 'submit';
			var action ='editBeforeConfirm';// (purchaseDept == '1')? 'edit' : 'editBeforeConfirm';
			// $("#form1").attr("action","?model=asset_purchase_apply_apply&action=edit&actType="+audit);
			$("#form1").attr("action","?model=asset_purchase_apply_apply&action="+action+"&actType="+audit);
			$("#form1").submit();
		} else {
			return false;
		}
	}
}

//���ݴӱ�Ĳ�ֵ��̬����Ӧ���ܽ��
function countAmount() {
	// ��ȡ��ǰ����������Ƭ���ʲ���
	var curRowNum = $("#purchaseProductTable").yxeditgrid("getCurRowNum");
	var rowAmountVa = 0;
	var cmps = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "amounts");
	cmps.each(function() {
		rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
	});
	$("#amounts").val(rowAmountVa);
	$("#amounts_v").val(moneyFormat2(rowAmountVa));
	return true;
}

//�ύʱ��֤
function checkForm(){
	if($("#purchaseProductTable").yxeditgrid("getCurShowRowNum") === 0){
		alert("�ɹ�������ϸ����Ϊ��");
		return false;
	}
	return true;
}