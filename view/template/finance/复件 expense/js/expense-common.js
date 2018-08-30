//����������
var billTypeArr = [];
var billTypeStr;

//ʵ�����ڼ���
function actTimeCheck(){
	var startDate = $('#CostDateBegin').val();
	var endDate = $('#CostDateEnd').val();
	if(startDate == "" || endDate == ""){
		return false;
	}
	var s = DateDiff(startDate,endDate);
	if(s < 0) {
		alert("��ʼ���ڲ��ܱȽ���������");
		return false;
	}
	var actDays = s + 1;
	$("#days").val(actDays);
	$("#periodDays").val(actDays);

	var thisCostTypeId,showDays ;
	$("#invbody input[id^='costTypeId']").each(function(i,n){
		thisCostTypeId = this.value;
		//�Ƿ���ʾ����
		showDays = $("#showDays"+ thisCostTypeId).val();
		//���޸�����
		if(showDays == '1'){
			$("#days" + thisCostTypeId).val(actDays);
			detailSet(thisCostTypeId);
			countAll();
		}
	});
}

//��ʼ���������
function initDays(){
	var startDate = $('#CostDateBegin').val();
	var endDate = $('#CostDateEnd').val();
	if(startDate == "" || endDate == ""){
		return false;
	}
	var s = DateDiff(startDate,endDate) + 1;
	$("#periodDays").val(s);
}

//��������
function periodDaysCheck(){
	var days = $("#days").val();
	var newDays = days;
	var periodDays = $("#periodDays").val();
	if(periodDays < days){
		alert('�����������ܴ��ڷ����ڼ�������');
		$("#days").val(periodDays);
		newDays = periodDays;
	}

	var thisCostTypeId,showDays ;
	$("#invbody input[id^='costTypeId']").each(function(i,n){
		thisCostTypeId = this.value;
		//�Ƿ���ʾ����
		showDays = $("#showDays"+ thisCostTypeId).val();
		//���޸�����
		if(showDays == '1'){
			$("#days" + thisCostTypeId).val(newDays);
			detailSet(thisCostTypeId);
			countAll();
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
	var isSubsidy = $("#isSubsidy"+ costTypeId).val();
	if(isReplace == 1){
        var title =  '�˷���������Ʊ';
	}else{
        var title =  '�˷��ò�������Ʊ';
	}
	var str ;
	for (var i = 0, l = data.length; i < l; i++) {
		if(defaultVal == data[i].id && isSubsidy == "0"){
    		str +='<option value="'+ data[i].id +'" selected="selected" title="'+title+'">'+ data[i].name+'</option>';
        }else{
        	if(isReplace == '1'){
           		str +='<option value="'+ data[i].id +'" title="'+title+'">'+ data[i].name+'</option>';
        	}
        }
	}
	return str;
}

//��ͨ�����������
function detailSet(thisNum){
	//ȡ��ʾֵ��Ȼ�����+�ż���
	var costMoney = $("#costMoney" + thisNum + "_v").val();

	if(costMoney){
		costMoney = autoAdd(costMoney);
		setMoney("costMoney" + thisNum,costMoney);

		//��ȡѡ�����
		var isSubsidy = $("#isSubsidy" + thisNum);
		if(isSubsidy.val() == "1"){
			var detailTable = $("#table_"+ thisNum +" select[id^='select_" + thisNum + "_']");
			if(detailTable.length == 0){
				var days = $("#days" + thisNum).val();
				costMoney = accMul(costMoney,days,2);
				setMoney("invoiceMoney_"+ thisNum + "_0",costMoney);

				//��ʼ��һ����Ʊ����
				$("#invoiceNumber_"+ thisNum + "_0").val(0);
			}
		}else{
			var detailTable = $("#table_"+ thisNum +" select[id^='select_" + thisNum + "_']");
			if(detailTable.length == 1){
				var days = $("#days" + thisNum).val();
				costMoney = accMul(costMoney,days,2);
				setMoney("invoiceMoney_"+ thisNum + "_0",costMoney);

				//��ʼ��һ����Ʊ����
				var invoiceNumberObj = $("#invoiceNumber_"+ thisNum + "_0");
				if(invoiceNumberObj.val() == ""){
					invoiceNumberObj.val(1);
				}
			}
		}

		//��ϸ����
	    countInvoiceMoney();
	    countInvoiceNumber();
	}
}

//��Ʊ������������
function invMoneySet(thisNum){
	//ȡ��ʾֵ��Ȼ�����+�ż���
	var invoiceMoney = $("#invoiceMoney_" + thisNum + "_v").val();
	if(invoiceMoney){
		invoiceMoney = autoAdd(invoiceMoney);
		setMoney("invoiceMoney_" + thisNum,invoiceMoney);
	}
}

//�ӷ�����
function autoAdd(thisVal){
	var thisValArr = thisVal.split("+");
	var rtMoney = 0;
	for(var i = 0;i< thisValArr.length;i++){
		rtMoney = accAdd(rtMoney,thisValArr[i],2);
	}
	return rtMoney;
}

//���ӷ�Ʊ��Ϣ
function add_lnvoice(id){
	//ʵ��������
	var costMoney , costType , detailMoney ,delTag ,lastMoney,days;
	//����
	costMoney = $("#costMoney" + id).val();
	days = $("#days" + id).val();
	costMoney = accMul(costMoney,days,2);
	//���ƻ���
	costType = $("#costType" + id).val();
	//���³�ʼ�����
	detailAll = 0;

	var isSubsidy = $("#isSubsidy" + id).val();
	if(isSubsidy == "1"){
		//������ǲ����࣬��ֱ�Ӵ���
		detailMoney = $("#invoiceMoney_"+ id + "_0").val();
		detailAll = accAdd(detailAll,detailMoney,2);

		//���⴦����
		var k = 1;
		//������ǲ����࣬��ֱ�Ӵ���
		$("select[id^='select_" + id + "_']").each(function(i,n){
			delTag = $("#isDelTag_"+ id + "_" + k).length;
			if(delTag == 0){
				detailMoney = $("#invoiceMoney_"+ id + "_" + k).val();
				detailAll = accAdd(detailAll,detailMoney,2);
			}
			k++;
		});
	}else{
		//������ǲ����࣬��ֱ�Ӵ���
		$("select[id^='select_" + id + "_']").each(function(i,n){
			delTag = $("#isDelTag_"+ id + "_" + i).length;
			if(delTag == 0){
				detailMoney = $("#invoiceMoney_"+ id + "_" + i).val();
				detailAll = accAdd(detailAll,detailMoney,2);
			}
		});
	}
	lastMoney = accSub(costMoney,detailAll,2);
	var invoiceNumber = 1;
	if(lastMoney*1 <= 0){
		lastMoney = "";
		invoiceNumber = "";
	}

	//��ʼ����Ʊ����
	billTypeStr = rtBillTypeStr(billTypeArr,id);

	//���ôӱ�
	var tableObj = $("#table_" + id);
	//�ӱ��ж���
	var tableTrObj = $("#table_" + id + " tr");
	//�ӱ�����
	var tableTrLength = tableTrObj.length;
	//����Id��׺
	var countStr = id + "_" + tableTrLength;
	var str = '<tr id="tr_' + countStr + '">' +
			'<td width="30%"><select id="select_' + countStr + '" name="expense[expensedetail]['+ id + '][expenseinv][' + tableTrLength + '][BillTypeID]" style="width:90px"><option value="">��ѡ��Ʊ</option>' + billTypeStr +'</select></td>' +
			'<td width="25%"><input id="invoiceMoney_' + countStr + '" costTypeId="'+ id +'" rowCount="'+ countStr +'" name="expense[expensedetail]['+ id + '][expenseinv][' + tableTrLength + '][Amount]" type="text" class="txtshort" value="'+lastMoney+'" onblur="invMoneySet(\''+ countStr +'\');countInvoiceMoney();countAll();"/></td>' +
			'<td width="25%"><input id="invoiceNumber_' + countStr + '" costTypeId="'+ id +'" rowCount="'+ countStr +'"  name="expense[expensedetail]['+ id + '][expenseinv][' + tableTrLength + '][invoiceNumber]" type="text" class="txtshort" value="'+ invoiceNumber +'" onblur="countInvoiceNumber(this);"/>' +
			'<input type="hidden" id="invIsSubsidy_' + countStr + '" name="expense[expensedetail]['+ countStr +'][expenseinv][0][isSubsidy]" value="0"/></td>' +
            '<td width="20%"><img style="cursor:pointer;" src="images/removeline.png" title="ɾ�����з�Ʊ" onclick="delete_lnvoice(' + id + ',this)"/></td>' +
		'</tr>';
	tableObj.append(str);
	//��ʽ�����
    createFormatOnClick('invoiceMoney_'+countStr);

    //��ע�߶ȵ���
    var remarkObj = $("#remark" + id);
    remarkObj.animate({height:"+=33"},100);

	//��ϸ����
    countAll();
    countInvoiceMoney();
    countInvoiceNumber();
}

//ɾ����Ʊ��Ϣ
function delete_lnvoice(id,obj){
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="expense[expensedetail]['+
				id +'][expenseinv][' +
				rowNo + '][isDelTag]" id="isDelTag_'+ id +'_'+rowNo +'" value="1"/>');

	    //��ע�߶ȵ���
	    var remarkObj = $("#remark" + id);
	    remarkObj.animate({height:"-=33"},100);


		//��ϸ����
	    countInvoiceNumber();
		countInvoiceMoney();
	    countAll();
	}
}

//���÷��ý���title
function initAmountTitle(feeRegular,feeSubsidy){
	$("#feeRegularView").html(moneyFormat2(feeRegular));
	$("#feeSubsidyView").html(moneyFormat2(feeSubsidy));
}

//�����ݺϼ�
function countAll(){
	//�ӱ��ܽ��
	var tableTrObj = $("#invbody input[id^='costTypeId']");
	var costTypeId , costMoney , countAll , days ,thisCostMoney , isSubsidy , detailInvMoney;
	var feeRegular = feeSubsidy  = 0;
//	alert(tableTrObj.length)
	$.each(tableTrObj,function(i,n){
		costTypeId = this.value*1;
		delTag = $("#isDelTag_"+ costTypeId).length;

		if(delTag == 0){
			costMoney = $("#costMoney" + costTypeId).val();
			days = $("#days" + costTypeId).val();
			thisCostMoney = accMul(costMoney,days,2);

			//��ȡ�Ƿ���Ҫ��Ʊѡ��
			isSubsidy = $("#isSubsidy" + costTypeId).val();
			if(isSubsidy == 1){
				//������Ĳ�������
//				feeSubsidy = accAdd(feeSubsidy,thisCostMoney,2);
				//�����Ʊ�ǲ���������ݽ����㲹������
				$("input[id^='invIsSubsidy_"+ costTypeId +"_']").each(function(i,n){
					var invIsDelTag = $("#isDelTag_" + costTypeId + "_" + i).length;
					if(invIsDelTag == 0){
						detailInvMoney = $("#invoiceMoney_" + costTypeId + "_" + i).val();
						if(this.value == "1"){
							feeSubsidy = accAdd(feeSubsidy,detailInvMoney,2);
						}else{
							feeRegular = accAdd(feeRegular,detailInvMoney,2);
						}
					}
				});
			}else{
				//������ĳ������
				feeRegular = accAdd(feeRegular,thisCostMoney,2);
			}
			//��������ܽ��
			countAll = accAdd(countAll,thisCostMoney,2);
		}
	});
	if(countAll*1 == 0 || countAll == undefined){
		countAll = "";
	}
	setMoney('countMoney', countAll );
	//����title
	initAmountTitle(feeRegular,feeSubsidy);
	//�������
	$("#feeRegular").val(feeRegular);
	$("#feeSubsidy").val(feeSubsidy);
}

//����Ʊ���ϼ�
function countInvoiceMoney(){
	//�ӱ��ܽ��
	var tableTrObj = $("#invbody input[id^='invoiceMoney_']");
	var countAll , delObj , rowCount , mark ,costTypeId , isCount,isSubsidy;
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

		rowCount = $(this).attr('rowCount');//��ϸid��
		if(rowCount){
			invIsSubsidy = $("#invIsSubsidy_" + rowCount).val()*1;
		}
		//�ж��Ƿ���ֵ
		if(this.value != "" && rowCount && isCount && invIsSubsidy == "0"){
			if($("#isDelTag_" + rowCount ).length == 0){
				countAll = accAdd(countAll,this.value);
			}
		}
	});
	if(countAll*1 == 0 || !countAll){
		countAll = "";
	}
	setMoney('invoiceMoney', countAll );
	//�������úϼ�
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
function checkForm(){
	//����
	var detailType = $("input[name='expense[DetailType]']:checked").val();
	if(strTrim(detailType) == "undefined"){
		alert('����ѡ��һ�ַ������ͣ�');
		return false;
	}

	//�����ڼ����֤
	var CostDateBegin = $("#CostDateBegin").val();
	var CostDateEnd = $("#CostDateEnd").val();
	var days = $("#days").val();
	if(CostDateBegin == "" || CostDateEnd == "" || days == ""){
		alert('����д�����ķ����ڼ�');
		return false;
	}
	var s = DateDiff(CostDateBegin,CostDateEnd);
	if(s < 0) {
		alert("��ʼ���ڲ��ܱȽ���������");
		$("#CostDateBegin").focus();
		return false;
	}
	var actDays = s + 1;
	if(actDays*1 < days*1){
		alert('�����ڼ��������ܴ��ڷ����ڼ���������');
		$("#days").focus();
		return false;
	}

	//����
	var PurposeObj = $("#Purpose");
	if(strTrim(PurposeObj.val()) == ""){
		alert('����д��������');
		PurposeObj.focus();
		return false;
	}

	//������
	var CostManName = $("#CostManName").val();
	if(strTrim(CostManName) == ""){
		alert('��ѡ������Ա');
		return false;
	}

	//ͬ������
	var memberNumber = $("#memberNumber");
	if(memberNumber.val()*1 != memberNumber.val() || memberNumber.val()*1 < 0){
		alert('ͬ������Ϊ������');
		memberNumber.val('').focus();
		return false;
	}

	//���� ��Ӧ������֤
	switch(detailType){
		case '1' :
			var costBelongCom = $("#costBelongCom").val();
			if(costBelongCom == ""){
				alert("û����д���ù�����˾");
				return false;
			}
			var costBelongDeptName = $("#costBelongDeptName").val();
			if(costBelongDeptName == ""){
				alert("û����д���ù�������");
				return false;
			}
			if($("#deptIsNeedProvince").val() == "1"){
				var province = $("#province").combobox('getValue');
				if(province == ""){
					alert("��ѡ������ʡ��");
					return false;
				}
			}
			var projectCode = $("#projectCode").val();
			if(projectCode == ""){
				alert("��ѡ��ñʷ������ڹ�����");
				return false;
			}
			break;
		case '2' :
			var projectCode = $("#projectCode").val();
			if(projectCode == ""){
				alert("��ѡ��ñʷ������ڹ�����Ŀ");
				return false;
			}
			break;
		case '3' :
			var projectCode = $("#projectCode").val();
			if(projectCode == ""){
				alert("��ѡ��ñʷ��������з���Ŀ");
				return false;
			}
			break;
		case '4' :
			var province = $("#province").combobox('getValue');
			if(province == ""){
				alert("��ѡ��ͻ�����ʡ��");
				return false;
			}
			var city = $("#city").combobox('getValues');
			if(city == ""){
				alert("��ѡ��ͻ����ڳ���");
				return false;
			}
			var customerType = $("#customerType").combobox('getValues');
			if(customerType == ""){
				alert("��ѡ��ͻ�����");
				return false;
			}
			var costBelongerId = $("#costBelongerId").val();
			if(costBelongerId == ""){
				alert("��¼�����۸����ˣ����۸����˿����̻����ͻ������Զ�����������ͨ���ͻ�ʡ�ݡ����С�������ϵͳƥ��");
				return false;
			}
			var costBelongDeptId = $("#costBelongDeptId").val();
			var costBelongDeptName = $("#costBelongDeptName").combobox('getValue');
			if(costBelongDeptId == "" || costBelongDeptName ==""){
				alert("��ѡ����ù�������");
				return false;
			}
			break;
		case '5' :
			var contractCode = $("#contractCode").val();
			if(contractCode == ""){
				alert("��ѡ��ñʷ��ù�����ͬ");
				return false;
			}
			var costBelongDeptId = $("#costBelongDeptId").val();
			var costBelongDeptName = $("#costBelongDeptName").combobox('getValue');
			if(costBelongDeptId == "" || costBelongDeptName ==""){
				alert("��ѡ����ù�������");
				return false;
			}
			break;
		default : break;
	}

	//�����ܽ���ж�
	if($("#countMoney").val()*1 == 0){
		alert('���ݽ��Ϊ�ջ�0�����ܽ��б���');
		return false;
	}

	var rtVal = true;
	var tableTrObj = $("#invbody input[id^='costTypeId']");
	var costTypeId , costMoney , costType , detailMoney ,delTag ,isSubsidy;
	$.each(tableTrObj,function(i,n){
		//����id
		costTypeId = this.value*1;
		delTag = $("#isDelTag_"+ costTypeId).length;
		if(delTag == 0){
			//��ȡ�Ƿ���Ҫ��Ʊѡ��
			isSubsidy = $("#isSubsidy" + costTypeId).val();

			//����
			costMoney = $("#costMoney" + costTypeId).val();
			days = $("#days" + costTypeId).val();
			costMoney = accMul(costMoney,days,2);

			//���ƻ���
			costType = $("#costType" + costTypeId).val();

			//�������Ҫ¼�뷢Ʊ����������֤
			if(isSubsidy == '0'){
				if(costMoney != 0){
					//���³�ʼ�����
					detailAll = 0;
					$("#table_"+ costTypeId +" select[id^='select_" + costTypeId + "_']").each(function(i,n){
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

							//��Ʊ����������֤
							detailNumber = $("#invoiceNumber_"+ costTypeId + "_" + i).val();
							if(detailNumber*1 == 0 || strTrim( detailNumber ) == ""){
								alert( costType + ' ��Ʊ��ϸ�д��ڷ�Ʊ����Ϊ0��յ���');
								rtVal = false;
							}
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
			}else{
				if(costMoney != 0){
					//���³�ʼ�����
					detailAll = 0;

					//���㷢Ʊ���
					detailMoney = $("#invoiceMoney_"+ costTypeId + "_0").val();
					detailAll = accAdd(detailAll,detailMoney,2);

					var k = 1;
					$("#table_"+ costTypeId +" select[id^='select_" + costTypeId + "_']").each(function(i,n){
						delTag = $("#isDelTag_"+ costTypeId + "_" + k).length;
						if(delTag == 0){
							//��ȡ���
							detailMoney = $("#invoiceMoney_"+ costTypeId + "_" + k).val();
							//��� ��������֤
							if(this.value == "" && (detailMoney *1 != 0 || detailMoney != "")){
								alert( costType + ' ��Ʊ��ϸ�д��������͵��н��ķ�Ʊ��ϸ��');
								rtVal = false;
								return false;
							}
							//���㷢Ʊ���
							detailAll = accAdd(detailAll,detailMoney,2);

							//��Ʊ����������֤
							detailNumber = $("#invoiceNumber_"+ costTypeId + "_" + k).val();
							alert(detailNumber);
							if(detailNumber*1 == 0 || strTrim( detailNumber ) == ""){
								alert( costType + ' ��Ʊ��ϸ�д��ڷ�Ʊ����Ϊ0��յ���');
								rtVal = false;
							}
						}
						k++;
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
			}

			//��ע
			if(costMoney*1 > 0 && strTrim($("#remark" + costTypeId).val()) == ""){
				alert('����д ' + costType + ' �ķ���˵��');
				rtVal = false;
				return false;
			}
		}
	});

	if(rtVal == true){
		//��ֹ�ظ��ύ
		$("input[type='submit']").attr('disabled',true);
	}

	return rtVal;
}

//����֤ - ������Ŀ���ñ���
function checkEsm(){

	//�����ڼ����֤
	var CostDateBegin = $("#CostDateBegin").val();
	var CostDateEnd = $("#CostDateEnd").val();
	var days = $("#days").val();
	if(CostDateBegin == "" || CostDateEnd == "" || days == ""){
		alert('����д�����ķ����ڼ�');
		return false;
	}

	//����
	var Purpose = $("#Purpose").val();
	if(strTrim(Purpose) == ""){
		alert('����д��������');
		return false;
	}

	//�����Ԥ����Ŀ��ģ���Ҫ��֤���ù�������
	var DetailType = $("input:radio[name=expense[DetailType]]:radio:checked").val();
	if(strTrim(DetailType) == "4"){
		if($("#CostBelongDeptNameS").combobox("getValue") == ""){
			alert('��ѡ����ù������ţ�');
			return false;
		}
	}
	return true;
}

//�ύ�����ı�����ֵ
function audit(thisType){
	$("#thisAuditType").val(thisType);
}

//�Զ������ѡ����
function selectCostType(){
	//�Զ��������
	var costAreaObj = $("#costArea");

	//��ȡ��ǰ�еķ�������
	var costTypeArr = $("#invbody input[id^='costTypeId']");
	//��ǰ���ڷ�����������
	var costTypeIdArr = [];
	//��ǰ���ڷ��������ַ���
	var costTypeIds = '';

	if(costTypeArr.length > 0){
		//���浱ǰ���ڷ�������
		costTypeArr.each(function(i,n){
			//�ж��Ƿ���ɾ��
			if($("#isDelTag_" + this.value).length == 0){
				costTypeIdArr.push(this.value);
			}
		});
		//������ǰ���ڷ�������id��
		costTypeIds = costTypeIdArr.toString();
	}

	//�����������ظ�ֵ
	$("#costTypeIds").val(costTypeIds);

	//��һ�μ���
	var isFirst = false;

	if($("#costTypeInner").html() == ""){
		isFirst = true;
		$.ajax({
		    type: "POST",
		    url: "?model=finance_expense_expense&action=getCostType",
		    async: false,
		    success: function(data){
		   		if(data != ""){
					$("#imgLoading2").hide();
					$("#costTypeInner").html(data)
					if(costTypeIds != ""){
						//��ֵ
						for(var i = 0; i < costTypeIdArr.length;i++){
							$("#chk" + costTypeIdArr[i]).attr('checked',true);
							$("#view" + costTypeIdArr[i]).attr('class','blue');
						}
					}
		   	    }else{
					alert('û���ҵ��Զ���ķ�������');
		   	    }
			}
		});
	}

	//��ʾ����
	if(costAreaObj.is(":hidden")){
		costAreaObj.show();
		if(isFirst == true){
			initMasonry();
		}
	}else{
		costAreaObj.hide();
	}
}

//�Զ������ѡ���� - ����ѡ��
function selectCostType2(){

	//��ȡ��ǰ�еķ�������
	var costTypeArr = $("#invbody input[id^='costTypeId']");
	//��ǰ���ڷ�����������
	var costTypeIdArr = [];
	//��ǰ���ڷ��������ַ���
	var costTypeIds = '';

	if(costTypeArr.length > 0){
		//���浱ǰ���ڷ�������
		costTypeArr.each(function(i,n){
			//�ж��Ƿ���ɾ��
			if($("#isDelTag_" + this.value).length == 0){
				costTypeIdArr.push(this.value);
			}
		});
		//������ǰ���ڷ�������id��
		costTypeIds = costTypeIdArr.toString();
	}

	//�����������ظ�ֵ
	$("#costTypeIds").val(costTypeIds);

	//��һ�μ���
	var isFirst = false;

	if($("#costTypeInner").html() == ""){
		isFirst = true;
		$.ajax({
		    type: "POST",
		    url: "?model=finance_expense_expense&action=getCostType",
		    async: false,
		    success: function(data){
		   		if(data != ""){
					$("#costTypeInner").html("<div id='costTypeInner2'>" + data + "</div>")
					if(costTypeIds != ""){
						//��ֵ
						for(var i = 0; i < costTypeIdArr.length;i++){
							$("#chk" + costTypeIdArr[i]).attr('checked',true);
							$("#view" + costTypeIdArr[i]).attr('class','blue');
						}
					}
					//��ʱ�������򷽷�
					setTimeout(function(){
						initMasonry();
						if(checkExplorer() == 1){
							$("#costTypeInner2").height(560).css("overflow-y","scroll");
						}
					},200);
		   	    }else{
					alert('û���ҵ��Զ���ķ�������');
		   	    }

			}
		});
	}
	$("#costTypeInner").dialog({
		title : '������������',
		height : 600,
		width : 1000
	});
}

//�ж������
function checkExplorer(){
	var Sys = {};
    var ua = navigator.userAgent.toLowerCase();
    window.ActiveXObject ? Sys.ie = ua.match(/msie ([\d.]+)/)[1] :
    document.getBoxObjectFor ? Sys.firefox = ua.match(/firefox\/([\d.]+)/)[1] :
    window.MessageEvent && !document.getBoxObjectFor ? Sys.chrome = ua.match(/chrome\/([\d.]+)/)[1] :
    window.opera ? Sys.opera = ua.match(/opera.([\d.]+)/)[1] :
    window.openDatabase ? Sys.safari = ua.match(/version\/([\d.]+)/)[1] : 0;

    if(Sys.ie){
		return 1;
    }
}

//�ٲ�������
function initMasonry(){
	$('#costTypeInner2').masonry({
		itemSelector: '.box'
	});
}

//���¼���Ⱦ
function CostTypeShowAndHide(thisCostType){
	//���������
	var tblObj = $("table .ct_"+thisCostType + "[isView='1']");
	//������ǰ������״̬������ʾ
	if(tblObj.is(":hidden")){
		tblObj.show();
		$("#" + thisCostType).attr("src","images/menu/tree_minus.gif");
	}else{
		tblObj.hide();
		$("#" + thisCostType).attr("src","images/menu/tree_plus.gif");
	}
	initMasonry();
}

//����������Ŀ�鿴
function CostType2View(thisCostType){
	//���������
	var tblObj = $("table .ct_"+thisCostType);
	//������ǰ������״̬������ʾ
	if(tblObj.is(":hidden")){
		tblObj.show();
		tblObj.attr('isView',1);
		$("#" + thisCostType).attr("src","images/menu/tree_minus.gif");
	}else{
		tblObj.hide();
		tblObj.attr('isView',0);
		$("#" + thisCostType).attr("src","images/menu/tree_plus.gif");
	}
	initMasonry();
}

//ѡ���Զ����������
function setCustomCostType(thisCostType,thisObj){
	if($(thisObj).attr('checked') == true){
		$("#view" + thisCostType).attr('class','blue');
	}else{
		$("#view" + thisCostType).attr('class','');
	}
	//�ж������Ƿ���ڣ�������ɵ�������������
	var trObj = $("#tr" + thisCostType);
	if(trObj.length == 1 && $("#isDelTag_" + thisCostType).length == 0){
		//ɾ�������¼�����
		deleteRow(thisCostType);
	}else{
		if(trObj.length > 0){
			//����Ѿ����ڶ��󣬷�ɾ��
			unDeleteRow(thisCostType);
		}else{
			//����ѡ����
			var chkObj = $("#chk" + thisCostType);
			var chkName = chkObj.attr('name');  //��������
			var chkParentName = chkObj.attr('parentName'); //���ø���������
			var chkParentId = chkObj.attr('parentId'); //���ø�����id
			var chkShowDays = chkObj.attr('showDays'); //�Ƿ���ʾ����
			var chkIsPeplace = chkObj.attr('isReplace');//����Ʊ
			var chkIsEqu = chkObj.attr('isEqu');//¼���豸��Ϣ
			var chkInvoiceType = chkObj.attr('invoiceType');//Ĭ�Ϸ�Ʊ����
			var chkInvoiceTypeName = chkObj.attr('invoiceTypeName');//Ĭ�Ϸ�Ʊ����
			var chkIsSubsidy = chkObj.attr('isSubsidy');//�Ƿ���
			//������
			var invbodyObj = $("#invbody");
			//��ʽ��Ⱦ
			var tr_class = $("#invbody").children().length % 2 == 0 ? 'tr_odd' : 'tr_even';

			var thisI = thisCostType + "_0";
			var str = '<tr class="'+tr_class+'" id="tr' + thisCostType + '">' +
				'<td valign="top">' +
				'<img style="cursor:pointer;" src="images/removeline.png" title="ɾ������" onclick="deleteCostType('+ thisCostType +')"/>' +
				'</td>' +
				'<td valign="top" class="form_text_right">' +
				chkParentName +
				'<input type="hidden" name="expense[expensedetail]['+ thisCostType +'][MainType]" value="'+ chkParentName +'"/>' +
				'<input type="hidden" name="expense[expensedetail]['+ thisCostType +'][MainTypeId]" value="'+ chkParentId +'"/>' +
				'<input type="hidden" id="showDays'+ thisCostType +'" value="'+ chkShowDays +'"/>' +
				'<input type="hidden" id="defaultInvoice'+ thisCostType +'" value="'+ chkInvoiceType +'"/>' +
				'<input type="hidden" id="defaultInvoiceName'+ thisCostType +'" value="'+ chkInvoiceTypeName +'"/>' +
				'<input type="hidden" id="isReplace'+ thisCostType +'" value="'+ chkIsPeplace +'"/>' +
				'<input type="hidden" id="isEqu'+ thisCostType +'" value="'+ chkIsEqu +'"/>' +
				'<input type="hidden" id="isSubsidy'+ thisCostType +'" value="'+ chkIsSubsidy +'"/>' +
				'</td>' +
				'<td valign="top" class="form_text_right">' +
				chkName +
				'<input type="hidden" name="expense[expensedetail]['+ thisCostType +'][costType]" id="costType'+ thisCostType +'" value="'+ chkName + '"/>' +
				'<input type="hidden" name="expense[expensedetail]['+ thisCostType +'][CostTypeID]" id="costTypeId'+ thisCostType +'" value="' + thisCostType + '"/>' +
				'</td>' +
				'<td valign="top" class="form_text_right">';

			if(chkShowDays == 0){
				str +=
					'<input type="text" name="expense[expensedetail]['+ thisCostType +'][CostMoney]" id="costMoney'+ thisCostType +'" style="width:146px" class="txtmiddle formatMoney" onblur="detailSet('+ thisCostType +');countAll();"/>' +
					'<input type="hidden" name="expense[expensedetail]['+ thisCostType +'][days]" id="days'+ thisCostType +'" value="1"/>';
			}else{
				//��ȡ�ڼ�����
				var days = $("#days").val();
				str +=
					'<span>' +
					'<input type="text" name="expense[expensedetail]['+ thisCostType +'][CostMoney]" id="costMoney'+ thisCostType +'" style="width:60px" class="txtshort formatMoney" onblur="detailSet('+ thisCostType +');countAll();"/>' +
					' X ' +
					' ���� ' +
					'<input type="text" name="expense[expensedetail]['+ thisCostType +'][days]" class="txtmin" id="days'+ thisCostType +'" value="'+ days +'" onblur="daysCheck(this);detailSet('+ thisCostType +');countAll();"/>' +
					'</span>';
			}

			//����ǲ���������÷�Ʊ����
			if(chkIsSubsidy == "1"){
				str +='</td>' +
					'<td valign="top" colspan="4" class="innerTd">' +
					'<table class="form_in_table" id="table_'+ thisCostType +'">' +
					'<tr id="tr_' + thisI + '">' +
					'<td width="30%">' +
					'<input type="text" id="select_' + thisI + '" style="width:90px" class="readOnlyTxtShort" readonly="readonly" value="'+ chkInvoiceTypeName +'"/>' +
					'<input type="hidden" name="expense[expensedetail]['+ thisCostType +'][expenseinv][0][BillTypeID]" value="'+ chkInvoiceType +'"/>' +
					'</td>' +
					'<td width="25%">' +
					'<input  type="text" id="invoiceMoney_' + thisI + '" name="expense[expensedetail]['+ thisCostType +'][expenseinv][0][Amount]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" style="color:gray" title="�����෢Ʊ�����뵽���ݷ�Ʊ�����,ֻ���ڴ���ʾ" class="txtshort formatMoney" onblur="invMoneySet(\''+ thisI +'\');countAll();"/>' +
					'</td>' +
					'<td width="25%">' +
					'<input type="text" id="invoiceNumber_' + thisI + '" name="expense[expensedetail]['+ thisCostType +'][expenseinv][0][invoiceNumber]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" class="readOnlyTxtShort" style="color:gray" readonly="readonly"/>' +
					'<input type="hidden" id="invIsSubsidy_' + thisI + '" name="expense[expensedetail]['+ thisCostType +'][expenseinv][0][isSubsidy]" value="1"/>' +
					'</td>' +
					'<td width="20%">' +
					'<img style="cursor:pointer;" src="images/add_item.png" title="�����" onclick="add_lnvoice('+ thisCostType +')"/>' +
					'</td>' +
					'</tr>' +
					'</table>' +
					'</td>' +
					'<td valign="top">' +
	            	'<input name="expense[expensedetail]['+ thisCostType +'][specialApplyNo]" id="specialApplyNo' + thisCostType + '" class="txtshort" title="�����ر�����" onclick="showSpecialApply(' + thisCostType + ')" readonly="readonly"/>' +
					'</td>' +
					'<td valign="top">' +
	            	'<textarea name="expense[expensedetail]['+ thisCostType +'][Remark]" id="remark' + thisCostType + '" class="txt"></textarea>' +
					'</td>' +
					'</tr>';
			}else{
				str +='</td>' +
					'<td valign="top" colspan="4" class="innerTd">' +
					'<table class="form_in_table" id="table_'+ thisCostType +'">' +
					'<tr id="tr_' + thisI + '">' +
					'<td width="30%">' +
					'<select id="select_' + thisI + '" name="expense[expensedetail]['+ thisCostType +'][expenseinv][0][BillTypeID]"><option value="">��ѡ��Ʊ</option></select>' +
					'</td>' +
					'<td width="25%">' +
					'<input  type="text" id="invoiceMoney_' + thisI + '" name="expense[expensedetail]['+ thisCostType +'][expenseinv][0][Amount]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" class="txtshort formatMoney" onblur="invMoneySet(\''+ thisI +'\');countInvoiceMoney();countAll();"/>' +
					'</td>' +
					'<td width="25%">' +
					'<input type="text" id="invoiceNumber_' + thisI + '" name="expense[expensedetail]['+ thisCostType +'][expenseinv][0][invoiceNumber]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" class="txtshort" onblur="countInvoiceNumber(this);"/>' +
					'<input type="hidden" id="invIsSubsidy_' + thisI + '" name="expense[expensedetail]['+ thisCostType +'][expenseinv][0][isSubsidy]" value="0"/>' +
					'</td>' +
					'<td width="20%">' +
					'<img style="cursor:pointer;" src="images/add_item.png" title="�����" onclick="add_lnvoice('+ thisCostType +')"/>' +
					'</td>' +
					'</tr>' +
					'</table>' +
					'</td>' +
					'<td valign="top">' +
	            	'<input name="expense[expensedetail]['+ thisCostType +'][specialApplyNo]" id="specialApplyNo' + thisCostType + '" class="txtshort" title="�����ر�����" onclick="showSpecialApply(' + thisCostType + ')" readonly="readonly"/>' +
					'</td>' +
					'<td valign="top">' +
	            	'<textarea name="expense[expensedetail]['+ thisCostType +'][Remark]" id="remark' + thisCostType + '" class="txt"></textarea>' +
					'</td>' +
					'</tr>';
			}
			//������
			invbodyObj.append(str);
			formateMoney();
			//��ʼ����Ʊ����TODO
			if(chkIsSubsidy == "0"){
				billTypeStr = rtBillTypeStr(billTypeArr,thisCostType);
				$("#select_" + thisI).append(billTypeStr);
			}
		}
	}
	countAll();
	//��ϸ����
    countInvoiceNumber();
	countInvoiceMoney();
}

//ɾ��������
function deleteRow(thisCostType){
	//�ж���
	var trObj = $("#tr" + thisCostType);
	trObj.hide();
	trObj.append('<input type="hidden" name="expense[expensedetail]['+
			thisCostType +'][isDelTag]" id="isDelTag_'+ thisCostType +'" value="1"/>');
}

//��ɾ��������
function unDeleteRow(thisCostType){
	//�ж���
	var trObj = $("#tr" + thisCostType);
	trObj.show();
	$("#isDelTag_" + thisCostType).remove();
}

//������֤
function daysCheck(obj){
	var days = $("#days").val();
	if(strTrim(obj.value) == "" || isNaN(obj.value)){
		alert('��������ֵ����');
		obj.value = days;
		return false;
	}

	if(days*1 < obj.value*1){
		alert('������ϸ�е��������ܴ��ڷ����ڼ������!');
		obj.value = days;
		return false;
	}
}

//ɾ������
function deleteCostType(costType){
	if(confirm('ȷ��ɾ�����������')){
		//ȡ��ѡ��
		var��chkObj = $("#chk" + costType);
		if(chkObj.length == 1){
			chkObj.attr('checked',false);
		}
		//ɾ��
		deleteRow(costType);

		//���¼�����Ľ���
		countAll();
	    countInvoiceNumber();
		countInvoiceMoney();
	}
}

//���¼����Զ������ѡ����
function resetCustomCostType(){
	//�Զ��������
	var costTypeInnerObj = $("#costTypeInner");
	if(costTypeInnerObj.html() != ""){
		//��ȡ��ѡ��
		$("#costTypeInner input[id^='chk']").attr('checked',false);
		//��ȡ��ǰ�еķ�������
		var costTypeArr = $("#invbody input[id^='costTypeId']");
		//��ǰ���ڷ�����������
		var costTypeIdArr = [];
		//��ǰ���ڷ��������ַ���
		var costTypeIds = '';

		if(costTypeArr.length > 0){
			//���浱ǰ���ڷ�������
			costTypeArr.each(function(i,n){
				//�ж��Ƿ���ɾ��
				if($("#isDelTag_" + this.value).length == 0){
					costTypeIdArr.push(this.value);
					$("#chk" + this.value).attr('checked',true);
				}
			});
			//������ǰ���ڷ�������id��
			costTypeIds = costTypeIdArr.toString();
		}

		//�����������ظ�ֵ
		$("#costTypeIds").val(costTypeIds);
	}
}

//��ȡ�����ĵ�
function getFile(){
	if($("#fileId").attr("href") == '#'){
		alert('û���ϴ�����˵���ĵ�');
		return false;
	}
}

//�򿪱������
function openSavePage(){
	//��ȡ��ǰ�еķ�������
	var costTypeArr = $("#invbody input[id^='costTypeId']");
	var contentIdArr = [];
	//���浱ǰ���ڷ�������
	costTypeArr.each(function(i,n){
		//�ж��Ƿ���ɾ��
		if($("#isDelTag_" + this.value).length == 0){
			contentIdArr.push(this.value);
		}
	});

	if(contentIdArr.length == "0"){
		alert('������ѡ��һ���������');
	}else{
		$("#templateName").val($("#modelTypeName").val());
		$('#templateInfo').dialog({
		    title: '����ģ��',
		    width: 400,
		    height: 200,
   			modal: false,
   			closable : true
		}).dialog('open');
	}
}

//����ģ��
function saveTemplate(){
	//��ȡ��ǰ�еķ�������
	var costTypeArr = $("#invbody input[id^='costTypeId']");
	var contentArr = [];
	var contentIdArr = [];
	//���浱ǰ���ڷ�������
	costTypeArr.each(function(i,n){
		//�ж��Ƿ���ɾ��
		if($("#isDelTag_" + this.value).length == 0){
			contentArr.push($("#costType" + this.value).val());
			contentIdArr.push(this.value);
		}
	});

	if(contentIdArr.length == 0){
		alert('û������ѡ��ֵ��������ѡ��һ���������');
	}else{
		var templateName= $("#templateName").val();
		var content = contentArr.toString();
		var contentId = contentIdArr.toString();
	    if(templateName){
			$.ajax({
			    type: "POST",
			    url: "?model=finance_expense_customtemplate&action=ajaxSave",
			    data : {"templateName" : templateName , "content" : content , "contentId" : contentId },
			    async: false,
			    success: function(data){
			   		if(data != ""){
			   			alert('ģ�屣��ɹ�');
						$("#modelTypeName").val(templateName).yxcombogrid_expensemodel('reload');
						$("#modelType").val(data);
						$('#templateInfo').dialog('close');
			   	    }else{
						alert('ģ�屣��ʧ��');
						$('#templateInfo').dialog('close');
			   	    }
				}
			});
	    }else{
	    	if(strTrim(templateName) == ""){
				alert('�����뱨��ģ������');
	    	}
	    }
	}
}

//�����û�id��ȡ�û���Ϣ
function getUserInfo(userId){
	var dataArr;
	$.ajax({
	    type: "POST",
	    url: "?model=deptuser_user_user&action=ajaxGetUserInfo",
	    data : {"userId" : userId },
	    async: false,
	    success: function(data){
	   		if(data != ""){
				dataArr = eval("(" + data + ")");
	   	    }else{
				alert('û���ҵ���Ӧ���û�');
	   	    }
		}
	});
	return dataArr;
}

//�ж϶����combobox�Ƿ��Ѵ���
function isCombobox(objCode){
	if($("#" + objCode).attr("comboname")){
		return 1;
	}else{
		return 0;
	}
}

//��ʼ���ύ��ť
function initSubButton(){
	if($("#needExpenseCheck").val() == "1"){
		$("#btnCheck").show();
	}else{
		$("#btnAudit").show();
	}
}

//���鹹��
function deptArrBuild(deptId1,deptName1,deptId2,deptName2){
	var deptArr = [];
	deptArr.push({"text" : deptName1,"value" : deptId1 });
	if(deptId2 != undefined && deptId2 != '' && deptId1 != deptId2){
		deptArr.push({"text" : deptName2,"value" : deptId2 });
	}
	return deptArr;
}

//���鹹�� - ��ѡ
function deptArrBuildMul(deptArr,deptId,deptName){
	var newDeptArr = [{"text" : deptName,"value" : deptId }];
	var tempArr = [];
	tempArr.push(deptId);

	//���ԭ���鳤�ȴ���0���ж��ظ��Լ����벿��
	if(deptArr.length > 0){
		for(var i = 0 ; i < deptArr.length ; i++ ){
			//���û�й����棬������
			if(tempArr.indexOf(deptArr[i].deptId) == -1){
				//���뷵������
				newDeptArr.push({"text" : deptArr[i].deptName,"value" : deptArr[i].deptId });
				//���뻺��
				tempArr.push(deptArr[i].deptId);
			}
		}
	}
	return newDeptArr;
}

//AJAX��ص���
function ajaxBack(){
	if(confirm('ȷ�ϴ�ص�����')){
		$.ajax({
			type : "POST",
			url : "?model=finance_expense_expense&action=ajaxBack",
			data : {
				id : $("#id").val()
			},
			success : function(msg) {
				if (msg == '1') {
					alert('��سɹ���');
					window.close();
					opener.show_page(1);
				}else{
					alert("���ʧ��! ");
				}
			}
		});
	}
}

//��ȡ�ر���������
function showSpecialApply(costType){
	//����
	var DetailType = $("input:radio[name=expense[DetailType]]:radio:checked").val();
	if(strTrim(DetailType) == "undefined"){
		alert('����ѡ��һ�ַ������ͣ�');
		return false;
	}

	var CostMan = $("#CostMan").val();//������
	var winObj = $("#specialApplyWindow");
    if(CostMan != ""){
    	$(".costTypeSpeCls").hide();//����ȫ�����ر���������
    	//���ڴ�
		winObj.window({
			title : '�����ر���������',
			height : 400,
			width : 800
		});
		//�ر���������Ķ���
		var costSpeWin = $("#costTypeSpeTb" + costType);
		//�ر����������
    	if(costSpeWin.length == 0){
    		winObj.append("<div id='costTypeSpeTb"+ costType +"' class='costTypeSpeCls'></div>");
    		costSpeWin = $("#costTypeSpeTb" + costType);
    		costSpeWin.yxeditgrid({
				url : '?model=general_special_specialapply&action=listJsonForSelect',
				param : {
					'charger' : CostMan ,
					'isCanUse' : 1 ,
					'nSpecialApplyNos' : $("#specialApplyNos").val(),
					'ExaStatus' : '���',
					'needValidDate' : '1',
					'sdetailType' : DetailType,
					'dcostTypeId' : $("#costTypeId" + costType).val()
				},
				tableClass : 'form_in_table',
				height : 400,
				isAddAndDel : false,
				title : ' ',
				colModel : [{
					display : '',
					name : 'id',
					type : 'statictext',
					width : 30,
					process : function(v,row){
						return "<input type='radio' name='spa' value='"+row.formNo+"'/>";
					}
				}, {
					display : '��������',
					name : 'detailType',
					type : 'statictext',
					process : function(v){
						switch(v){
							case '1' : return '���ŷ���';break;
							case '2' : return '��ͬ��Ŀ����';break;
							case '3' : return '�з�����';break;
							case '4' : return '��ǰ����';break;
							case '5' : return '�ۺ����';break;
						}
					},
					width : 70
				}, {
					display : '���뵥��',
					name : 'formNo',
					type : 'statictext'
				}, {
					display : '��������',
					name : 'applyDate',
					type : 'statictext',
					width : 70
				}, {
					display : '��Ч��ֹ����',
					name : 'validDate',
					type : 'statictext',
					width : 70
				}, {
					display : '������Ա',
					name : 'useRangeName',
					type : 'statictext'
				}, {
					display : '��������',
					name : 'costType',
					type : 'statictext',
					align : 'left'
				}, {
					display : '����ԭ��',
					name : 'applyReson',
					type : 'statictext',
					align : 'left'
				}],
				event : {
					'reloadData' : function(){
						costSpeWin.attr("style", "overflow-x:auto;overflow-y:auto;height:350px;");
						costSpeWin.find('thead tr').eq(0).children().eq(0).append(
							'<button class="txt_btn_a" onclick="checkSpecialApply();">ȷ��ѡ��</button> '+
							'<button class="txt_btn_a" onclick="cancelSpecialApply();">ȡ��ѡ��</button>'+
							'<input type="hidden" id="hiddenCostType"/>'
						);
						//����ѡ��
						reloadCheckbox(costType);
					}
				}
    		});
    	}else{
    		//��ʾѡ���
    		costSpeWin.show();
    		//����ѡ��
    		reloadCheckbox(costType);
    	}
    }else{
		alert('��ѡ������Ա');
    }
}

//ѡ��
function checkSpecialApply(){
	//��ǰ��������
	var costType = $("#hiddenCostType").val();
	//ѡ�е���
	var formNoArr = $("input[name='spa']:checked");
	var formNoValArr = [];
	formNoArr.each(function(){
		formNoValArr.push(this.value);
	});
	$("#specialApplyNo" + costType).val(formNoValArr.toString());
	$("#specialApplyWindow").window('close');
}

//ȡ��ѡ��
function cancelSpecialApply(){
	var costType = $("#hiddenCostType").val();
	$("#specialApplyNo" + costType).val('');
	$("#specialApplyWindow").window('close');
}

//����ѡ��
function reloadCheckbox(costType){
	var specialApplyNoVal = $("#specialApplyNo" + costType).val();
	var formNoValArr = specialApplyNoVal.split(",");
	$("input[name='spa']").each(function(){
		if($.inArray(this.value,formNoValArr) != -1){
			$(this).attr('checked',true);
		}else{
			$(this).attr('checked',false);
		}
	});
	$("#hiddenCostType").val(costType);
}