$(document).ready(function(){
	//������
	$("#salesmanName").yxselect_user({
		hiddenId : 'salesmanCode'
	});

	//���ϲֿ�
	$("#stockName").yxcombogrid_stockinfo({
		hiddenId : 'stockId',
		nameCol : 'stockName',
		gridOptions : {
			showcheckbox : false,
			event : {
				row_dblclick : function(e ,row ,data) {
					$("#stockCode").val(data.stockCode);
					var stockObjs = $("#itemtable").yxeditgrid('getCmpByCol' ,'stockName');
					for(var i = 0 ;i < stockObjs.length ;i++) {
						$("#itemtable_cmp_stockId" + i).val(data.id);
						$("#itemtable_cmp_stockCode" + i).val(data.stockCode);
						$("#itemtable_cmp_stockName" + i).val(data.stockName);
					}
				}
			}
		}
	});

	//�ж��Ƿ������Ȩ��
	if($('#auditLimit').val() != "1") {
		$("#auditButton").hide();
	}

	$("#itemtable").yxeditgrid({
		objName : 'stockout[items]',
		url : '?model=produce_plan_pickingitem&action=listJson',
		param : {
			pickingId : $("#relDocId").val()
		},
		isAdd : false,
		colModel : [{
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '���ϱ��',
			name : 'productCode',
			type : 'statictext',
			isSubmit : true,
			width : 100
		},{
			display : '��������',
			name : 'productName',
			type : 'statictext',
			isSubmit : true,
			width : 300
		},{
			display : '����ͺ�',
			name : 'pattern',
			type : 'statictext',
			isSubmit : true,
			width : 100
		},{
			display : '��λ',
			name : 'unitName',
			type : 'statictext',
			isSubmit : true,
			width : 80
		},{
			display : '����',
			name : 'batchNum'
		},{
			display : '���ϲֿ�',
			name : 'stockName',
			validation : {
				required : true
			},
			process : function ($input) {
				var rowNum = $input.data("rowNum");
				$input.yxcombogrid_stockinfo({
					hiddenId : 'itemtable_cmp_stockId' + rowNum,
					nameCol : 'itemtable_cmp_stockName' + rowNum,
					gridOptions : {
						showcheckbox : false,
						event : {
							row_dblclick : function(e ,row ,data) {
								$("#itemtable_cmp_stockName" + rowNum).val(data.stockName);
								$("#itemtable_cmp_stockCode" + rowNum).val(data.stockCode);
							}
						}
					}
				});
			}
		},{
			display : '���ϲֿ�Id',
			name : 'stockId',
			type : 'hidden'
		},{
			display : '���ϲֿ�Code',
			name : 'stockCode',
			type : 'hidden'
		},{
			display : 'Դ��Id',
			name : 'relDocId',
			type : 'hidden',
			process : function ($input ,row) {
				$input.val(row['id']);
			}
		},{
			display : '��ͬId',
			name : 'contractId',
			type : 'hidden',
			process : function ($input ,row) {
				$input.val($('#contractId').val());
			}
		},{
			display : '��ͬ����',
			name : 'contractName',
			type : 'hidden',
			process : function ($input ,row) {
				$input.val($('#contractName').val());
			}
		},{
			display : '��ͬ���',
			name : 'contractCode',
			type : 'hidden',
			process : function ($input ,row) {
				$input.val($('#contractCode').val());
			}
		},{
			display : '��������',
			name : 'applyNum',
			type : 'statictext',
			width : 70
		},{
			display : '��������',
			name : 'shouldOutNum',
			type : 'hidden',
			width : 70,
			process : function ($input ,row) {
				$input.val(row.applyNum);
			}
		},{
			display : 'ʵ������',
			name : 'actOutNum',
			width : 70,
			validation : {
				required : false,
				custom : ['onlyNumber']
			},
			process : function ($input ,row) {
				var validNum = row.applyNum - row.realityNum;
				var rowNum = $input.data("rowNum");
				$input.val(validNum);
				$input.blur(function () {
					if ($(this).val() > validNum) {
						alert('ʵ���������ܳ�����������');
						$(this).val(validNum);
					}else{
						FloatMul('itemtable_cmp_actOutNum' + rowNum ,'itemtable_cmp_cost' + rowNum ,'itemtable_cmp_subCost' + rowNum);
					}
				});
			}
		},{
			display : '���к�',
			name : 'serialnoName',
			tclass : 'readOnlyTxtMiddleLong',
			readonly : true,
			process : function ($input) {
				var rowNum = $input.data("rowNum");
				$input.before('&nbsp;<img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo(' + rowNum + ');" title="ѡ�����к�">&nbsp;');
			}
		},{
			display : '����',
			name : 'cost',
			type : 'moneySix',
			process : function ($input) {
				var rowNum = $input.data("rowNum");
				$input.change(function () {
					$(this).trigger('blur');
					$('#itemtable_cmp_cost' + rowNum).trigger('blur');
				});
				$('#itemtable_cmp_cost' + rowNum).blur(function () {
					FloatMul('itemtable_cmp_actOutNum' + rowNum ,'itemtable_cmp_cost' + rowNum ,'itemtable_cmp_subCost' + rowNum);
				});
			}
		},{
			display : '���',
			name : 'subCost',
			tclass : 'readOnlyTxt',
			readonly : true,
			type : 'money'
		},{
			display : '����/�ɹ�����',
			name : 'prodDate',
			readonly : true,
			type : 'date',
			tclass : 'txtshort'
		},{
			display : '������(��)',
			name : 'shelfLife',
			tclass : 'txtshort'
		},{
			display : '��Ч����',
			name : 'validDate',
			readonly : true,
			type : 'date',
			tclass : 'txtshort'
		}]
	});

	validate({
		"auditDate" : {
			required : true
		}
	});
});

function checkForm() {// ��У��
	var productIdObjs = $("#itemtable").yxeditgrid('getCmpByCol' ,'productId');
	if (productIdObjs.length == 0) {
		alert("û��������Ϣ��");
		return false;
	}
    // �������
    if($("#module").val() == ""){
    	alert("������鲻��Ϊ��");
    	return false;
    }
	return true;
}

/**
 * �鿴��ͬ�������
 */
function viewContracAudit() {
	if ($("#contractId").val() == "") {
		alert("����ѡ����Ҫ�鿴�ĺ�ͬ");
	} else {
		showThickboxWin('controller/contract/contract/readview.php?itemtype=oa_contract_contract&pid='
			+ $("#contractId").val()
			+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
	}
}

//���ȷ��
function confirmAudit() {
	var auditDate = $("#auditDate").val();
	if (couldAudit(auditDate)) {
		if (confirm("��˺󵥾ݽ������޸ģ���ȷ��Ҫ�����?")) {
			if (checkForm()) {
				//����Ƿ�������б���
				var result = true;
				$.each($('[class*=validate[required]]') ,function (i ,k) {
					if (($.trim($(this).val())).length == 0) {
						result = false;
						return false; //�˳�ѭ��
					}
				});
				if (!result) {
					$("#form1").submit(); //������ʾ����
					return false; //�˳�����
				}
				if (checkProNumIntime()) {
					if (result) {
						$("#docStatus").val("YSH");
						$("#form1").attr("action" ,"?model=stock_outstock_stockout&action=add&actType=audit");
					}
					$("#form1").submit();
				}
			}
		}
	}
}

/**
 * У�鼴ʱ���
 */
function checkProNumIntime() {
	var checkResult = true;
	var productIdObjs = $("#itemtable").yxeditgrid('getCmpByCol' ,'productId');
	var productCodeObjs = $("#itemtable").yxeditgrid('getCmpByCol' ,'productCode');
	var stockIdObjs = $("#itemtable").yxeditgrid('getCmpByCol' ,'stockId');
	var stockNameObjs = $("#itemtable").yxeditgrid('getCmpByCol' ,'stockName');
	var actOutNumObjs = $("#itemtable").yxeditgrid('getCmpByCol' ,'actOutNum');
	for (var i = 0 ;i < productIdObjs.length ;i++) {
		$.ajax({// ��ȡ��Ӧ�����Ϣ
			type : "POST",
			dataType : "json",
			async : false,
			url : "?model=stock_inventoryinfo_inventoryinfo&action=getInTimeObj",
			data : {
				productId : $(productIdObjs[i]).val(),
				stockId : $(stockIdObjs[i]).val()
			},
			success : function(result) {
				if (isNum($(actOutNumObjs[i]).val())) {
					if ($("#isRed").val() == "0") {// ��ɫ�����У��������
						if (result != "0") {
							if (parseInt($(actOutNumObjs[i]).val())>result['exeNum'] ) {
								alert("��治��! " + $(stockNameObjs[i]).val()
									+ "�б��Ϊ\""
									+ $(productCodeObjs[i]).val()
									+ "\"�����Ͽ�ִ��������" + result['exeNum']);
								checkResult = false;
							}
						} else {
							alert("��治��!" + $(stockNameObjs[i]).val()
								+ "�в����ڱ��Ϊ\""
								+ $(productCodeObjs[i]).val() + "\"������");
							checkResult = false;
						}
					}
				} else {
					alert("����������д����!");
					checkResult = false;
				}
			}
		});
		if (!checkResult) {
			return checkResult;
		}
	}
	return true;
}

//�����Ƿ��ѹ���,�������ڲ��������Ƿ��ѽ����ж�
function couldAudit(auditDate) {
	var resultTrue = true;
	$.ajax({
		type : "POST",
		async : false,
		url : "?model=finance_period_period&action=isClosed",
		data : {},
		success : function(result) {
			if (result == 1) {
				alert("�����ѹ��ˣ����ܽ�����ˣ�����ϵ������Ա���з����ˣ�")
				resultTrue = false;
			}
		}
	});
	$.ajax({
		type : "POST",
		async : false,
		url : "?model=finance_period_period&action=isLaterPeriod",
		data : {
			thisDate : auditDate
		},
		success : function(result) {
			if (result == 0) {
				alert("�������ڲ��������Ѿ����ˣ����ܽ�����ˣ�����ϵ������Ա���з����ˣ�")
				resultTrue = false;
			}
		}
	});

	return resultTrue;
}

/**
 * ѡ�����к�
 */
function chooseSerialNo(seNum) {
	var productIdVal = $("#itemtable_cmp_productId" + seNum).val();
	var stockIdVal = $("#itemtable_cmp_stockId" + seNum).val();
	var serialnoId = $("#itemtable_cmp_serialnoId" + seNum).val();
	var serialnoName = $("#itemtable_cmp_serialnoName" + seNum).val();

	var cacheResult = false;
	var productCodeSeNum = $("#itemtable_cmp_productCode" + seNum).val() + "_" + seNum;
	$.ajax({// �������к�
		type : "POST",
		async : false,
		url : "?model=stock_serialno_serialno&action=cacheSerialno",
		data : {
			serialSequence : serialnoName,
			productCodeSeNum : productCodeSeNum
		},
		success : function(result) {
			if (result == 1) {
				cacheResult = true;
			}
		}
	})
	if (cacheResult) {
		if (productIdVal != "") {
			if (stockIdVal != "" && parseInt($("#itemtable_cmp_stockId" + seNum).val()) != 0) {
				showThickboxWin(
					"?model=stock_serialno_serialno&action=toChooseFrame&serialnoId="
						+ serialnoId
						+ "&productId="
						+ productIdVal
						+ "&elNum="
						+ seNum
						+ "&stockId="
						+ stockIdVal
						+ "&isRed="
						+ $("#isRed").val()
						+ "&productCodeSeNum="
						+ productCodeSeNum
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=600",
					"ѡ�����к�")
			} else {
				alert("����ѡ��ֿ�!");
			}
		} else {
			alert("����ѡ������!");
		}
	}
}