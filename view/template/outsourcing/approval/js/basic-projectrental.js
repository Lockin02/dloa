//var pageAttr;//��ǰҳ�湦������ add/edit/view
var datadictArr = [];//���������ֵ�
var parentDatadictArr = [];//�����ϼ�����

//��ʼ����������
function initProjectRental(){
	//��ѡ��Ӧ��
//	$("#suppName").yxcombogrid_outsupplier({
//		hiddenId : 'suppId',
//		gridOptions : {
//			showcheckbox : false,
//			event : {
//				'row_dblclick' : function(e,row,data) {
//                        $("#suppCode").val(data.suppCode);
//				}
//			}
//		}
//	});
	if(checkCanInit() == false){
		return false;
	}

	if(pageAttr == 'add'){//����ʱ
		initProjectRentalAdd();
	}else if(pageAttr == "edit"){
		initProjectRentalEdit();
	}else{
		initProjectRentalView();
	}
	//��ѡ��Ӧ��
	$("#supplier2").yxcombogrid_outsupplier({
		hiddenId : 'supplierId2',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
                        $("#supplierCode2").val(data.suppCode);
                        $("#supp2").val(data.suppName);
                        checkSupp(2);
				}
			}
		}
	});
	//��ѡ��Ӧ��
	$("#supplier3").yxcombogrid_outsupplier({
		hiddenId : 'supplierId3',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
                        $("#supplierCode3").val(data.suppCode);
                        $("#supp3").val(data.suppName);
                        checkSupp(3);
				}
			}
		}
	});
	//��ѡ��Ӧ��
	$("#supplier4").yxcombogrid_outsupplier({
		hiddenId : 'supplierId4',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
                        $("#supplierCode4").val(data.suppCode);
                        $("#supp4").val(data.suppName);
                        checkSupp(4);
				}
			}
		}
	});
}

//��֤�Ƿ�ɳ�ʼ��
function checkCanInit(){
	//��ʼ��ʱ��֤�����Ƿ����
	try{
		pageAttr;
	}catch(e){
		alert('�޷���ʼ������/�ְ���Ϣ�����ȶ�����룡');
		return false;
	}
	return true;
}

//����
function initProjectRentalAdd(){
	var projectRentalTbObj = $("#projectRentalTb");
	if(projectRentalTbObj.children().length == 0){
		//ajax��ȡ���۸�����
		var responseText = $.ajax({
			url:"?model=outsourcing_approval_projectRental&action=getAddPage",
			data : {projectId : $("#projectId").val()},
			type : "POST",
			async : false
		}).responseText;
		projectRentalTbObj.html(responseText);

		//ǧ��λ��Ⱦ
		formatProjectRentalMoney();
	}
}

//�༭
function initProjectRentalEdit(){
	var projectRentalTbObj = $("#projectRentalTb");
	if(projectRentalTbObj.children().length == 0){
		//ajax��ȡ���۸�����
		var responseText = $.ajax({
			url:"?model=outsourcing_approval_projectRental&action=getEditPage",
			data : {mainId : $("#id").val()},
			type : "POST",
			async : false
		}).responseText;
		projectRentalTbObj.html(responseText);

		//ǧ��λ��Ⱦ
		formatProjectRentalMoney();
	}
}

//�鿴
function initProjectRentalView(){
	var projectRentalTbObj = $("#projectRentalTb");
	if(projectRentalTbObj.children().length == 0){
		//ajax��ȡ���۸�����
		var responseText = $.ajax({
			url:"?model=outsourcing_approval_projectRental&action=getViewPage",
			data : {mainId : $("#id").val()},
			type : "POST",
			async : false
		}).responseText;
		projectRentalTbObj.html(responseText);

		//ǧ��λ��Ⱦ
		formatProjectRentalMoney();
	}
}

//ɾ�� - ��
function delProjectRentalRow(rowNum){
	var supplierObj = $("#supplier4_id" + rowNum);
	if(supplierObj.length > 0){
		supplierObj.after('<input type="hidden" name="basic[projectRental]['+rowNum+'][isDelTag]" id="isDel'+rowNum+'" value="1"/>');
		$("#tr"+rowNum).hide();
	}else{
		$("#tr"+rowNum).remove();
	}
	countProjectCost(1);
	countProjectCost(2);
	countProjectCost(3);
	countProjectCost(4);
}

//���� - ��
function countDetail(rowNum,supplierNum,isCopy){
	var priceObj = $("#supplier"+supplierNum+"_price" + rowNum);
	var numberObj = $("#supplier"+supplierNum+"_number" + rowNum);
	var periodObj = $("#supplier"+supplierNum+"_period" + rowNum);

	//�Ƿ��ǰ�渴�� ����������
	if(isCopy == 1){
		numberObj.val($("#supplier1_number" + rowNum).val());
		periodObj.val($("#supplier1_period" + rowNum).val());
	}

	if(priceObj.val()!= "" || numberObj.val()!= "" || periodObj.val()!= ""){//�����ߴ���һ����Ϊ��ʱ,���м���
		var price = number = period = 0;
		if(priceObj.val()*1 != "") price = priceObj.val()*1;
		if(numberObj.val()*1 != "") number = numberObj.val()*1;
		if(periodObj.val()*1 != "") period = periodObj.val()*1;

		var amount = accMul(accMul(price,number,2),period,2);
		setMoney("supplier"+supplierNum+"_amount" + rowNum,amount);
	}
	countProjectCost(supplierNum);//������Ŀ�ɱ�
}

//���� - ��
function countProjectRental(){
	//��δʵ��
}

//�����ܳɱ�
function countProjectCost(num){
	var projectRentalRowNum=$("#projectRentalRowNum").val()*1+1;
	var justCost=0;//��Ŀ���ɱ�
	for(i=0;i<projectRentalRowNum;i++){
		var supplierObj = $("#supplier4_id" + i);
		if(supplierObj.length > 0){
			if($('#isDel'+i).val()!=1&&$("#supplier"+num+"_amount"+i).length>0){
				justCost=accAdd(justCost,$("#supplier"+num+"_amount"+i).val()*1,2);
			}
		}else if($("#supplier"+num+"_amount"+i).length>0){
				justCost=accAdd(justCost,$("#supplier"+num+"_amount"+i).val()*1,2);
		}
	}
	//��������
	var otherfee=$("#otherfee"+num).val()*1;
	if(otherfee>0){
		justCost=accAdd(justCost,otherfee,2);
	}
	//�������
	var mangerfee=$("#mangerfee"+num).val()*1;
	if(mangerfee>0&&num==1){
		justCost=accAdd(justCost,mangerfee,2);
	}
	if(justCost>0||justCost==0){
		$("#isAllCost"+num).val(justCost);
		$("#isAllCost"+num+"_v").val(moneyFormat2(justCost));
	}
	//��Ŀë����
	countProjectProfit(num);
	//��Ŀ������
	countNetProfit(num);
	//˰��
	countDLTax(num);
	//ѡ��Ӧ��
     checkSupp(num);
}

//��Ŀë����
function countProjectProfit(num){
	//��Ŀ�ܳɱ�
	var isAllCost=$("#isAllCost"+num).val()*1;
	//��ͬ���
	var orderMoney=$("#orderMoney").val()*1;
	if(isAllCost>0&&orderMoney>0){
		var grossMargin=accDiv(orderMoney-isAllCost,orderMoney,4);
		$("#isProfit"+num).val(accMul(grossMargin,100,2) + '%');
	}
}

//��Ŀ������
function countNetProfit(num){
	//��Ŀ�ܳɱ�
	var isAllCost=$("#isAllCost"+num).val()*1;
	//˰��
	var isTax=$("#isTax"+num).val()*1;
	//��ͬ���
	var orderMoney=$("#orderMoney").val()*1;
	if(isAllCost>0&&orderMoney>0&&isTax>0){
		var grossMargin=accDiv(orderMoney-isAllCost-isTax,orderMoney,4);
		$("#isNetProfit"+num).val(accMul(grossMargin,100,2) + '%');
	}
}

//����˰��
function countDLTax(num){
	//��ͬ���
	var orderMoney=$("#orderMoney").val()*1;
	//��Ŀ�ܳɱ�
	var allCost=$("#isAllCost"+num).val()*1;
	if(orderMoney>0){
		var tax=0;
		if(num==1){
			tax=accMul(orderMoney,0.06,2);
		}else if(num!=1&&$("#isPoint"+num).val()!=''&&allCost>0){
			dlTax=accMul(orderMoney,0.06,2);
			wbTax=accMul(allCost,$("#isPoint"+num).val()*0.01,2);
			tax=accSub(dlTax,wbTax,2);
		}
		if(allCost>0){
			$("#isTax"+num).val(tax);
			$("#isTax"+num+"_v").val(moneyFormat2(tax));
		}
	}
	//��Ŀ������
	countNetProfit(num);
	//ѡ����ֵ˰ר�÷�Ʊ˰��
	checkTaxPointCode(num);

}


//ǧ��λ��Ⱦ
function formatProjectRentalMoney(){
	// ��Ⱦ ǧ��λ���
	$.each($("#projectRentalTbody input.formatMoney"), function(i, n) {
		var idStr = "" + $(this).attr('id');
		if ($(this).get(0).tagName == 'INPUT'
				&& idStr.indexOf("_v") <= 1) {
			var strHidden = $("<input type='hidden' name='" + n.name
					+ "' id='" + n.id + "' value='" + n.value + "' />");
			$(this).attr('name', '');
			$(this).attr('id', n.id + '_v');
			$(this).val(moneyFormat2(n.value));
			$(this).bind("blur", function() {
				moneyFormat1(this, 2);
				if (n.onblur)
					n.onblur();
			});
			$(this).after(strHidden);
		} else {
			returnMoney = moneyFormat2($(this).text(), 2);
			if (returnMoney != "")
				$(this).text(returnMoney);
		}
	});

	//��Ⱦǧ��λ ҳ����
	$.each($("#projectRentalTbody td.formatMoney"),function(){
		returnMoney = moneyFormat2($(this).text(), 2);
		if (returnMoney != "")
			$(this).text(returnMoney);
	});
}

//��ȡ�����ֵ�
function getDatadictArr(code){
	if(!datadictArr[code]){//����Ѵ��ڸû���,��ֱ�ӷ��ػ���
		var dataArr = getData(code);
		datadictArr[code] = dataArr;
	}
	return datadictArr[code];
}

//��ȡ�����ֵ����
function getParentDatadictArr(code){
	if(parentDatadictArr.length == 0){
		parentArr = getDatadictArr('WBHTFYX');
		var num = parentArr.length;
		for(var i = 0;i<num;i++){
			parentDatadictArr[parentArr[i].dataCode] = parentArr[i];
		}
	}
	return parentDatadictArr[code];
}

//�����ֵ���Ⱦ��option
function getOptionStr(data){
	var str = "";
	var num = data.length;
	for(var i=0;i<num;i++){
		str +="<option value='"+ data[i].dataCode +"'>"+ data[i].dataName +"</option>";
	}
	return str;
}



//��֤�Ƿ�ѡ��ѡ
function verification(){
	var outsourcing = $("#outsourcing").val();
	var rowNum = $("#projectRentalRowNum").val();
	var supplier2 = $('#supplier2').val();
	var supplier3 = $('#supplier3').val();
	var supplier4 = $('#supplier4').val();
	var checkVal = $('input[name="basic[projectRental][supplier][checkSupplier]"]:checked').val();
	if(outsourcing == "HTWBFS-01"||outsourcing == "HTWBFS-03"){
		$("#orderMoney").addClass('validate[required]');
		$("#beginDate").addClass('validate[required]');
		$("#endDate").addClass('validate[required]');
		$("#mangerfee1").addClass('validate[required]');
		$("#mangerfee1_v").addClass('validate[required]');
		$("#suppName").addClass('validate[required]');
		for(var i = 0;i<=rowNum;i++){
			if( $("#tr"+i).css("display") != "none" ){
				$("#supplier1_price"+i+"_v").addClass('validate[required]');
				$("#supplier1_number"+i).addClass('validate[required]');
				$("#supplier1_period"+i).addClass('validate[required]');
				$("#supplier1_amount"+i+"_v").addClass('validate[required]');
			}else{
				$("#supplier1_price"+i+"_v").removeClass('validate[required]');
				$("#supplier1_number"+i).removeClass('validate[required]');
				$("#supplier1_period"+i).removeClass('validate[required]');
				$("#supplier1_amount"+i+"_v").removeClass('validate[required]');
			}
		}
		if(supplier2 || supplier3 || supplier4.length>0){
			if(checkVal !=null){
				if($('#supplier'+checkVal).val()!=''){
					for(var i = 0;i<=rowNum;i++){
						if( $("#tr"+i).css("display") != "none" ){
							$("#supplier"+checkVal+"_price"+i+"_v").addClass('validate[required]');
							$("#supplier"+checkVal+"_number"+i).addClass('validate[required]');
							$("#supplier"+checkVal+"_period"+i).addClass('validate[required]');
							$("#supplier"+checkVal+"_amount"+i+"_v").addClass('validate[required]');
							$("#isPoint"+checkVal).addClass('validate[required]');
						}else{
							$("#supplier"+checkVal+"_price"+i+"_v").removeClass('validate[required]');
							$("#supplier"+checkVal+"_number"+i).removeClass('validate[required]');
							$("#supplier"+checkVal+"_period"+i).removeClass('validate[required]');
							$("#supplier"+checkVal+"_amount"+i+"_v").removeClass('validate[required]');
						}
					}

				}else{
					alert('��ѡ��Ϊ�յĹ�Ӧ��');
				}

			}else{
				alert('��ѡ��һ����Ӧ��');
			}
		}else{
				alert('����д�����Ӧ����Ϣ');
			}

	}
	else{
		$("#orderMoney").removeClass('validate[required]');
		$("#beginDate").removeClass('validate[required]');
		$("#endDate").removeClass('validate[required]');
		$("#mangerfee1").removeClass('validate[required]');
		$("#mangerfee1_v").removeClass('validate[required]');
		$("#suppName").removeClass('validate[required]');
		for(var i = 0;i<rowNum;i++){
			$("#supplier1_price"+i+"_v").removeClass('validate[required]');
			$("#supplier1_number"+i).removeClass('validate[required]');
			$("#supplier1_period"+i).removeClass('validate[required]');
			$("#supplier1_amount"+i+"_v").removeClass('validate[required]');
		}
	}
}
//ѡ��Ӧ��
function checkSupp(num){
	var checkVal = $('input[name="basic[projectRental][supplier][checkSupplier]"]:checked').val();
	if(checkVal==4||checkVal==2||checkVal==3){
		var supplier = $('#supplier'+checkVal).val();
		if(supplier!=""){
			var supplierId = $('#supplierId'+checkVal).val();
			var supplierCode = $('#supplierCode'+checkVal).val();
			$('#suppCode').val(supplierCode);
			$('#suppId').val(supplierId);
			$('#suppName').val(supplier);
			//��Ŀ�ܳɱ�
			var allCost=$("#isAllCost"+checkVal).val()*1;
			if(allCost>0){
				$("#outSuppMoney").val(allCost);
				$("#outSuppMoney_v").val(moneyFormat2(allCost));
			}
			var isProfit=$("#isProfit"+checkVal).val();
			if(isProfit!=''){
				$("#grossProfit").val(isProfit);
			}
			checkTaxPointCode(checkVal);
		}else{
			$('#suppCode').val("");
			$('#suppId').val("");
			$('#suppName').val("");
			$("#outSuppMoney").val("");
			$("#grossProfit").val("");
		}
	}

}

//��ֵ˰ר�÷�Ʊ˰��
function checkTaxPointCode(num){
	var checkVal = $('input[name="basic[projectRental][supplier][checkSupplier]"]:checked').val();
	if(checkVal==4||checkVal==2||checkVal==3){
		$("#taxPointCode").val($("#isPoint"+checkVal).val());
	}

}
