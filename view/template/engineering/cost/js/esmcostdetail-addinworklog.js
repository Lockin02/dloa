//����������
var bliiTypeArr = [];
var billTypeStr;

$(document).ready(function() {
	bliiTypeArr = getBillType();

	//�쳣����
	$("#excApplyCode").yxcombogrid_exceptionapply({
		hiddenId : 'excApplyId',
		height : 250,
		gridOptions : {
			showcheckbox : true,
			isTitle : true,
			param : {'ExaStatus' : '���' , 'applyUserAndRange' : $("#createId").val()}
		}
	});

	//ģ��ѡ����Ⱦ
	$("#modelTypeName").yxcombogrid_expensemodel({
		hiddenId :  'modelType',
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data){
					initTemplate(data.modelType);
				}
			}
		}
	});

	//����Ǳ༭ҳ�棬�������ϼ�
	if($("#isEdit").length > 0){
		//����
		countAll();
	    countInvoiceMoney();
	    countInvoiceNumber();
	}
})

//��ʼ��Ⱦģ�� - ����ʱ��
function initTemplate(modelType){
	//��̨����ģ��ҳ��
	$.ajax({
	    type: "POST",
	    url: "?model=finance_expense_expense&action=initTempAdd",
	    data: {"modelType" : modelType},
	    async: false,
	    success: function(data){
			$("#invbody").html(data);
			// ��� ǧ��λ����
			formateMoney();
		}
	});
}

//��ȡ������
function getBillType() {
	var responseText = $.ajax({
		url : 'index1.php?model=common_otherdatas&action=getBillType',
		type : "POST",
		async : false
	}).responseText;
	var o = eval("(" + responseText + ")");
	return o;
}

//����ѡ���ַ���
function rtBillTypeStr(data, costTypeId) {
	var defaultVal = $("#defaultInvoice" + costTypeId).val();
	var isReplace = $("#isReplace"+ costTypeId).val();
	if(isReplace == 1){
        var title =  '�˷���������Ʊ';
	}else{
        var title =  '�˷��ò�������Ʊ';
	}
	var str ;
	for (var i = 0, l = data.length; i < l; i++) {
		if(defaultVal == data[i].id){
    		str +='<option value="'+ data[i].id +'" selected="selected" title="'+title+'">'+ data[i].name+'</option>';
        }else{
        	if(isReplace == '1'){
           		str +='<option value="'+ data[i].id +'" title="'+title+'">'+ data[i].name+'</option>';
        	}
        }
	}
	return str;
}

//¼���⳵��Ϣ
function initCarRental(thisNum){
	var worklogId = $("#worklogId").val();
	var url = "?model=carrental_records_carrecordsdetail&action=toAddInWorklog"
		+ "&worklogId="
		+ worklogId
	;

	//Ϊ�˽��GOOGLE �������BUG������Ҫʹ�����´���
	var prevReturnValue = window.returnValue; // Save the current returnValue
	window.returnValue = undefined;
	var dlgReturnValue = window.showModalDialog(url, '',"dialogWidth:950px;dialogHeight:500px;");
	if (dlgReturnValue == undefined) // We don't know here if undefined is the real result...
	{
	    // So we take no chance, in case this is the Google Chrome bug
	    dlgReturnValue = window.returnValue;
	}
	window.returnValue = prevReturnValue; // Restore the original returnValue

	//��ֵ
	if(dlgReturnValue){
		var MoneyArr = eval("(" + dlgReturnValue + ")");
//		$.showDump(MoneyArr);

		var detailTable;
		for (i in MoneyArr ){
//		    alert(MoneyArr[i]);
//			$("#costMoney" + i).val(MoneyArr[i]);
			setMoney('costMoney' + i,MoneyArr[i]);
			detailTable = $("select[id^='select_" + i + "_']");
			if(detailTable.length == 1){
				setMoney("invoiceMoney_" + i + "_0",MoneyArr[i]);
				$("#invoiceNumber_"+ i + "_0").val(1);
			}
		}

		//����
		countAll();
	    countInvoiceMoney();
	    countInvoiceNumber();
//		$("#costMoney" + thisNum).val(dlgReturnValue);
	}
}

//¼����Կ���Ϣ
function initCardrecords(thisNum){
	var worklogId = $("#worklogId").val();
	var url = "?model=cardsys_cardrecords_cardrecords&action=toAddInWorklog"
		+ "&worklogId="
		+ worklogId
	;

	//Ϊ�˽��GOOGLE �������BUG������Ҫʹ�����´���
	var prevReturnValue = window.returnValue; // Save the current returnValue
	window.returnValue = undefined;
	var dlgReturnValue = window.showModalDialog(url, '',"dialogWidth:950px;dialogHeight:500px;");
	if (dlgReturnValue == undefined) // We don't know here if undefined is the real result...
	{
	    // So we take no chance, in case this is the Google Chrome bug
	    dlgReturnValue = window.returnValue;
	}
	window.returnValue = prevReturnValue; // Restore the original returnValue

	//��ֵ
	if(dlgReturnValue){
//		$("#costMoney" + thisNum).val(dlgReturnValue);
		setMoney('costMoney' + thisNum,dlgReturnValue);
		var detailTable = $("select[id^='select_" + thisNum + "_']");
		if(detailTable.length == 1){
			setMoney("invoiceMoney_"+ thisNum + "_0",dlgReturnValue);
			$("#invoiceNumber_"+ thisNum + "_0").val(1);

			//����
			countAll();
		    countInvoiceMoney();
		    countInvoiceNumber();
		}
	}
}

//¼����Ƹ��Ա��Ϣ
function initTempPerson(thisNum){
	var worklogId = $("#worklogId").val();
	var url = "?model=engineering_tempperson_personrecords&action=toAddInWorklog"
		+ "&worklogId="
		+ worklogId
	;

	//Ϊ�˽��GOOGLE �������BUG������Ҫʹ�����´���
	var prevReturnValue = window.returnValue; // Save the current returnValue
	window.returnValue = undefined;
	var dlgReturnValue = window.showModalDialog(url, '',"dialogWidth:800px;dialogHeight:400px;");
	if (dlgReturnValue == undefined) // We don't know here if undefined is the real result...
	{
	    // So we take no chance, in case this is the Google Chrome bug
	    dlgReturnValue = window.returnValue;
	}
	window.returnValue = prevReturnValue; // Restore the original returnValue

	//��ֵ
	if(dlgReturnValue){
//		$("#costMoney" + thisNum).val(dlgReturnValue);
		setMoney('costMoney' + thisNum,dlgReturnValue);
		var detailTable = $("select[id^='select_" + thisNum + "_']");
		if(detailTable.length == 1){
			setMoney("invoiceMoney_"+ thisNum + "_0",dlgReturnValue);
			$("#invoiceNumber_"+ thisNum + "_0").val(1);

			//����
			countAll();
		    countInvoiceMoney();
		    countInvoiceNumber();
		}
	}
}

//��ͨ�����������
function detailSet(thisNum){
	var costMobey = $("#costMoney" + thisNum).val()*1;
	if(costMobey){
		var detailTable = $("select[id^='select_" + thisNum + "_']");
		if(detailTable.length == 1){
			setMoney("invoiceMoney_"+ thisNum + "_0",costMobey);

			//��ʼ��һ����Ʊ����
			var invoiceNumberObj = $("#invoiceNumber_"+ thisNum + "_0");
			if(invoiceNumberObj.val() == ""){
				invoiceNumberObj.val(1);
			}
		}

		//��ϸ����
	    countInvoiceMoney();
	    countInvoiceNumber();
	}
}

//���ӷ�Ʊ��Ϣ
function add_lnvoice(id){
	//ʵ��������
	var costMoney , costType , detailMoney ,delTag ,lastMoney;
	//����
	costMoney = $("#costMoney" + id).val();
	//���ƻ���
	costType = $("#costType" + id).val();
	//���³�ʼ�����
	detailAll = 0;
	$("select[id^='select_" + id + "_']").each(function(i,n){
		delTag = $("#isDelTag_"+ id + "_" + i).length;
		if(delTag == 0){
			detailMoney = $("#invoiceMoney_"+ id + "_" + i).val();
			detailAll = accAdd(detailAll,detailMoney,2);
		}
	});
	lastMoney = accSub(costMoney,detailAll,2);
	var invoiceNumber = 1;
	if(lastMoney*1 <= 0){
		lastMoney = "";
		invoiceNumber = "";
	}

	//��ʼ����Ʊ����
	billTypeStr = rtBillTypeStr(bliiTypeArr,id);

	//���ôӱ�
	var tableObj = $("#table_" + id);
	//�ӱ��ж���
	var tableTrObj = $("#table_" + id + " tr");
	//�ӱ�����
	var tableTrLength = tableTrObj.length;
	//����Id��׺
	var countStr = id + "_" + tableTrLength;
	var str = '<tr id="tr_' + countStr + '">' +
			'<td width="30%"><select id="select_' + countStr + '" name="esmcostdetail['+ id + '][invoiceDetail][' + tableTrLength + '][invoiceTypeId]" style="width:90px"><option>��ѡ��Ʊ</option>' + billTypeStr +'</select></td>' +
			'<td width="25%"><input id="invoiceMoney_' + countStr + '" costTypeId="'+ id +'" rowCount="'+ countStr +'"  name="esmcostdetail['+ id + '][invoiceDetail][' + tableTrLength + '][invoiceMoney]" type="text" class="txtshort" value="'+lastMoney+'"/></td>' +
			'<td width="25%"><input id="invoiceNumber_' + countStr + '" costTypeId="'+ id +'" rowCount="'+ countStr +'"  name="esmcostdetail['+ id + '][invoiceDetail][' + tableTrLength + '][invoiceNumber]" type="text" class="txtshort" value="'+invoiceNumber+'"/></td>' +
            '<td width="20%"><img style="cursor:pointer;" src="images/removeline.png" title="�����" onclick="delete_lnvoice(' + id + ',this)"/></td>' +
		'</tr>';
	tableObj.append(str);

    //��ע�߶ȵ���
    var remarkObj = $("#remark" + id);
    remarkObj.animate({height:"+=33"},100);

	//��ʽ�����
    createFormatOnClick('invoiceMoney_'+countStr);

	//��ϸ����
    countInvoiceMoney();
    countInvoiceNumber();
}

//���ӷ�Ʊ��Ϣ
function delete_lnvoice(id,obj){
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="esmcostdetail['+
				id +'][invoiceDetail][' +
				rowNo + '][isDelTag]" id="isDelTag_'+ id +'_'+rowNo +'" value="1"/>');

	    //��ע�߶ȵ���
	    var remarkObj = $("#remark" + id);
	    remarkObj.animate({height:"-=33"},100);

		//��ϸ����
	    countInvoiceMoney();
	    countInvoiceNumber();
	}
}

//�����ݺϼ�
function countAll(){
	//�ӱ��ܽ��
	var tableTrObj = $("#invbody input[id^='costTypeId']");
	var costTypeId , costMoney , countAll;
//	alert(tableTrObj.length)
	$.each(tableTrObj,function(i,n){
		costTypeId = this.value*1;
		delTag = $("#isDelTag_"+ costTypeId).length;

		if(delTag == 0){
			costMoney = $("#costMoney" + costTypeId).val();
			days = $("#days" + costTypeId).val();
			countAll = accAdd(countAll,accMul(costMoney,days),2);
		}
	});
	if(countAll*1 == 0){
		countAll = "";
	}
//	$("#countMoney").html(moneyFormat2(countAll));
	setMoney('countMoney', countAll );
}

//����Ʊ���ϼ�
function countInvoiceMoney(){
	//�ӱ��ܽ��
	var tableTrObj = $("#invbody input[id^='invoiceMoney_']");
	var countAll , delObj , rowCount , mark ,costTypeId , isCount;
//	alert(tableTrObj.length)
	$.each(tableTrObj,function(i,n){
		//��������id
		costTypeId = $(this).attr('costTypeId');
		if(mark != costTypeId){
			mark = costTypeId;
			if($("#isDelTag_" + costTypeId ).length > 0){
				isCount = false;
			}else{
				isCount = true;
			}
		}

		rowCount = $(this).attr('rowCount');
		//�ж��Ƿ���ֵ
		if(this.value != "" && rowCount && isCount){
			if($("#isDelTag_" + rowCount ).length == 0){
				countAll = accAdd(countAll,this.value);
			}
		}
	});
	if(countAll*1 == 0 || !countAll){
		countAll = "";
	}
	setMoney('invoiceMoney', countAll );
}

//����Ʊ�����ϼ�
function countInvoiceNumber(thisObj){
	//������ڴ������������֤�Ƿ�����
	if(thisObj){
		var re = /^[1-9]d*|0$/;

		if (!re.test(thisObj.value)) {
			if (isNaN(thisObj.value)) {
				alert("������Ǹ�����!");
				thisObj.value = "";
				thisObj.focus();
				return false;
			}
		}
	}
	//�ӱ��ܽ��
	var tableTrObj = $("#invbody input[id^='invoiceNumber_']");
	var countAll , delObj , rowCount , mark ,costTypeId , isCount;
//	alert(tableTrObj.length)
	$.each(tableTrObj,function(i,n){
		//��������id
		costTypeId = $(this).attr('costTypeId');
		if(mark != costTypeId){
			mark = costTypeId;
			if($("#isDelTag_" + costTypeId ).length > 0){
				isCount = false;
			}else{
				isCount = true;
			}
		}

		//�ж��Ƿ���ֵ ���ҵ�ǰֵ����ͳ����
		if(this.value != "" && isCount ){
			rowCount = $(this).attr('rowCount');
			if($("#isDelTag_" + rowCount ).length == 0){
				countAll = accAdd(countAll,this.value);
			}
		}
	});
	if(countAll*1 == 0 || !countAll){
		countAll = "";
	}
	$("#invoiceNumber").val(countAll);
}

//����֤
function checkform(){
	var rtVal = true;
	var tableTrObj = $("#invbody input[id^='costTypeId']");
	var costTypeId , costMoney , costType , detailMoney ,delTag;
	$.each(tableTrObj,function(i,n){
		//����id
		costTypeId = this.value*1;
		//����
		costMoney = $("#costMoney" + costTypeId).val();
		if(costMoney != 0){
			//���ƻ���
			costType = $("#costType" + costTypeId).val();
			//���³�ʼ�����
			detailAll = 0;
			$("select[id^='select_" + costTypeId + "_']").each(function(i,n){
				delTag = $("#isDelTag_"+ costTypeId + "_" + i).length;
				if(delTag == 0){
					//��ȡ���
					detailMoney = $("#invoiceMoney_"+ costTypeId + "_" + i).val();

					//��� ��������֤
					if(this.value == "" && (detailMoney *1 != 0 || detailMoney != "")){
						alert( costType + ' ��Ʊ��ϸ�д��������͵��н��ķ�Ʊ��ϸ��');
						rtVal = false;
						return false;
					}
					//���㷢Ʊ���
					detailAll = accAdd(detailAll,detailMoney,2);
				}
			});
			if(rtVal == false){
				return false;
			}

			if(detailAll *1 != costMoney){
				alert( costType + ' �з��ý��' + costMoney + " �����ڷ�Ʊ�ϼƽ�� " + detailAll + ",���޸ĺ��ٽ��б������");
				rtVal = false;
				return false;
			}
		}
	});
	return rtVal;
};