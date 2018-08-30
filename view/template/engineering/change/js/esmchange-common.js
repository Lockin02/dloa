var unitArr; //���湤������λ

//�����ܷ��ý���
function countBudgetAll(){
	var newBudgetField = $("#newBudgetField").val();
	var newBudgetOutsourcing = $("#newBudgetOutsourcing").val();
	var newBudgetEqu = $("#newBudgetEqu").val();
	var newBudgetPerson = $("#newBudgetPerson").val();
	var newBudgetOther = $('#newBudgetOther').val();

	var newBudgetAll = accAdd(newBudgetField,newBudgetOther,2);
	newBudgetAll = accAdd(newBudgetAll,newBudgetOutsourcing,2);
	newBudgetAll = accAdd(newBudgetAll,newBudgetEqu,2);
	newBudgetAll = accAdd(newBudgetAll,newBudgetPerson,2);

	setMoney('newBudgetAll',newBudgetAll);
}

//��ʼ����Ա�ȼ�ѡ��
function initPerson(){
	//��ʼ����Ա�ȼ�ѡ��
	$("input[id^='personLevelId']").each(function(i,n){
		var trNo = $(this).attr("trNo");
		var innerTrNo = $(this).attr("innerTrNo");
		var thisI = trNo + "_" + innerTrNo;
		$("#personLevel"+ thisI).yxcombogrid_eperson('remove');
		$("#personLevel"+ thisI).yxcombogrid_eperson({
			hiddenId : 'personLevelId' + thisI,
			nameCol : 'personLevel',
			width : 600,
			gridOptions : {
				showcheckbox : false,
				event : {
					row_dblclick : (function(thisI) {
						return function(e, row, rowData) {
							$("#perCoefficient"+ thisI).val(rowData.coefficient);
							$("#perPrice"+ thisI).val(rowData.price);
							calPersonBatch(thisI);
						}
					})(thisI)
				}
			}
		});
	});
}

//��ʼ������Ԥ��
function initBudget(){
	$("input[id^='budBudgetName']").each(function(i,n){
		var trNo = $(this).attr("trNo");
		var innerTrNo = $(this).attr("innerTrNo");
		var thisI = trNo + "_" + innerTrNo;
		$("#budBudgetName"+ thisI).yxcombogrid_budgetdl('remove');
		$("#budBudgetName"+ thisI).yxcombogrid_budgetdl({
			hiddenId : 'budBudgetId' + thisI,
			searchName : 'budgetNameDLSearch',
			width : 600,
			height : 300,
			gridOptions : {
				showcheckbox : false,
				event : {
					row_dblclick : (function(thisI) {
						return function(e, row, rowData) {
							$("#budParentName"+ thisI).val(rowData.parentName);
							$("#budParentId"+ thisI).val(rowData.parentId);
						}
					})(thisI)
				}
			}
		});
	});
}

//������ĿԤ�ƹ���
function timeCheckProject($t){
	var startDate = $('#planBeginDate').val();
	var endDate = $('#planEndDate').val();
	if(startDate == "" || endDate == ""){
		return false;
	}
	var s = DateDiff(startDate,endDate) + 1;
//	if(s < 0) {
//		alert("Ԥ�ƿ�ʼ���ܱ�Ԥ�ƽ���ʱ����");
//		$t.value = "";
//		return false;
//	}
	$("#expectedDuration").val(s);
}

//������ĿԤ�ƹ���
function timeCheck($t){
	var startDate = $('#actBeginDate').val();
	var endDate = $('#actEndDate').val();
	if(startDate == "" || endDate == ""){
		return false;
	}
	var s = DateDiff(startDate,endDate) + 1;
//	if(s < 0) {
//		alert("Ԥ�ƿ�ʼ���ܱ�Ԥ�ƽ���ʱ����");
//		$t.value = "";
//		return false;
//	}
	$("#actDuration").val(s);
}

//������Ŀ��Χ����
function timeCheckAct($t,$key){
	var startDate = $('#actPlanBeginDate' + $key).val();
	var endDate = $('#actPlanEndDate' + $key).val();
	if(startDate == "" || endDate == ""){
		return false;
	}
	var s = DateDiff(startDate,endDate) + 1;
//	if(s < 0) {
//		alert("Ԥ�ƿ�ʼ���ܱ�Ԥ�ƽ���ʱ����");
//		$t.value = "";
//		return false;
//	}
	$("#actDays" + $key).val(s);

	//��ȡ��ǰ����������λ
	var workloadUnit = $("#actWorkloadUnit" + $key).val();
	//������죬���������Ԥ���������Ĺ�������
	if(workloadUnit == 'GCGZLDW-00'){
		$("#actWorkload" +$key ).val(s);
	}
}

//��������Ԥ������
function timeCheckPerson($t,$key,$k){
	var thisI = $key + "_" + $k;
	var startDate = $('#perPlanBeginDate' + thisI).val();
	var endDate = $('#perPlanEndDate' + thisI).val();
	if(startDate == "" || endDate == ""){
		return false;
	}
	var s = DateDiff(startDate,endDate) + 1;
//	if(s < 0) {
//		alert("Ԥ�ƿ�ʼ���ܱ�Ԥ�ƽ���ʱ����");
//		$t.value = "";
//		return false;
//	}
	$("#perDays" + thisI).val(s);
	calPersonBatch(thisI);
}

//��������Ԥ�� - ����������ʹ��
function calPersonBatch(rowNum){
	//��ȡ����
	var number= $("#perNumber" + rowNum ).val();

	if($("#personLevel"  + rowNum ).val() != "" && number != ""){
		//��ȡ����ϵ��
		var coefficient = $("#perCoefficient" + rowNum).val();
		//��ȡ����
		var price = $("#perPrice" + rowNum).val();
		//��ȡ����
		var days = $("#perDays" + rowNum ).val();
		//�����˹�����
		var personDays = accMul(number,days,2);
		$("#perPersonDays" + rowNum).val(personDays);

		//�����˹�����
		var personCostDays = accMul(coefficient,accMul(number,days,2),2);
		$("#perPersonCostDays" +  rowNum).val(personCostDays);

		//�����˹�����
		var personCost = accMul(price,personDays,2);
		setMoney("perPersonCost" +  rowNum,personCost,2);
	}
	calProjectPerson();
}

//������Ŀ����Ԥ��
function calProjectPerson(){
	var newBudgetPerson = 0; //����Ԥ����
	var newBudgetPeople = 0; //����Ԥ��
	var newBudgetDay = 0; //��������
	$("input[id^='personLevelId']").each(function(i,n){
		var trNo = $(this).attr("trNo");
		var innerTrNo = $(this).attr("innerTrNo");
		var thisI = trNo + "_" + innerTrNo;

		//�ж�ɾ���Ĳ�����
		if($("#isPersonDelTag_" + thisI).length == 0){
			newBudgetPerson = accAdd(newBudgetPerson,$("#perPersonCost" + thisI).val(),2);
			newBudgetPeople = accAdd(newBudgetPeople,$("#perPersonCostDays" + thisI).val(),2);
			newBudgetDay = accAdd(newBudgetDay,$("#perPersonDays" + thisI).val(),2);
		}
	});

	setMoney("newBudgetPerson",newBudgetPerson);
	setMoney("newBudgetPeople",newBudgetPeople);
	setMoney("newBudgetDay",newBudgetDay);

	//������ĿԤ��
	countBudgetAll();
}

//�����з���Ԥ��
function calBudget($t,$key,$k){
	var thisI = $key + "_" + $k;
	//��ȡ����
	var budPriceObj = $("#budPrice" + thisI );
	//��ȡ����
	var budNumberOneObj = $("#budNumberOne" + thisI );
	var budNumberTwoObj = $("#budNumberTwo" + thisI );

	var amountAll = 0;

	if(budPriceObj.val() * 1 ==""){
		return false;
	}

	//���û�����������Ĭ����1
	if(budNumberOneObj.val()*1 == "" && budNumberTwoObj.val()*1 == ""){
		budNumberOneObj.val(1);
		budNumberTwoObj.val(1);
	}else if(budNumberOneObj.val()*1 == "" && budNumberTwoObj.val()*1 != ""){//���ֻ������һ��
		budNumberOneObj.val(1);
	}else if(budNumberOneObj.val()*1 != "" && budNumberTwoObj.val()*1 == ""){//���ֻ������һ��
		budNumberTwoObj.val(1);
	}

	//�����ֵ
	amountAll = accMul(budPriceObj.val(),budNumberOneObj.val(),2);
	amountAll = accMul(amountAll,budNumberTwoObj.val(),2);
	setMoney("budAmount" + thisI,amountAll);

	//������ĿԤ����
	calBudgetField($key);
}

//��������Ԥ��
function calBudgetField($key){
	var budgetFieldAct = 0; //�����ֳ�Ԥ��
	var budgetField = 0; //�ֳ�Ԥ��
	var mark = "";
	$("input[id^='budBudgetName']").each(function(i,n){
		var trNo = $(this).attr("trNo");
		var innerTrNo = $(this).attr("innerTrNo");
		var thisI = trNo + "_" + innerTrNo;
		//�ж�ɾ���Ĳ�����
		if($("#isBudgetDelTag_" + thisI).length == 0){
			var thisMoney = $("#budAmount" + thisI ).val();

			budgetField = accAdd(budgetField,thisMoney,2);

			if($key != undefined){
				//����Ǳ��������
				if( trNo*1 == $key*1 ){
					budgetFieldAct = accAdd(budgetFieldAct,thisMoney,2);
				}
			}
		}
	});

	setMoney("newBudgetField",budgetField);
	if($key != undefined){
		setMoney("budgetAll" + $key,budgetFieldAct);
	}
	//������ĿԤ��
	countBudgetAll();
}

//������ʾ����
function changeActivity($key){
	$(".trView" + $key).hide();
	$(".trEdit" + $key).show();
	$("#isChange" + $key).val(1);
	//��ʼ����Ϣ

	//��ʼ����Ա�ȼ�ѡ��
	initPerson();

	//��ʼ������Ԥ����
	initBudget();
}

//������ʾ����
function showActivity(thisObj,$key){
	if($(thisObj).attr("isHide") == "1"){
		$(".trView" + $key).hide();
		$(".trEdit" + $key).show();
		$(thisObj).attr("isHide",0);
	}else{
		$(".trView" + $key).show();
		$(".trEdit" + $key).hide();
		$(thisObj).attr("isHide",1);
	}

}

//��ʼ������
function initCity() {
	var countryUrl = "?model=system_procity_country&action=pageJson";// ��ȡ���ҵ�URL
	var provinceUrl = "?model=system_procity_province&action=pageJson"; // ��ȡʡ��URL
	var cityUrl = "?model=system_procity_city&action=pageJson"; // ��ȡ�е�URL
	$('#country').append($("<option value=''>").html("��ѡ�����"));
	$('#province').append($("<option value=''>").html("��ѡ��ʡ��"));
	$('#city').append($("<option value=''>").html("��ѡ�����"));

	// ʡ��ѡ��ı䣬��ȡ��
	$('#province').change(function() {
		$('#provinceName').val($(this).find("option:selected").text());
		var provinceId = $(this).val();
		if (provinceId == "") { //�ж��Ƿ�ѡ����ʡ�ݣ����û��ѡ�У���ʡ������Ϊ��   add by suxc 2011-08-22
			$('#provinceName').val("");
			$('#city').children().remove("option[value!='']");
			$('#cityName').val("");
		} else {
			$.ajax({
				type : 'POST',
				url : cityUrl,
				data : {
					provinceId : provinceId,
					pageSize : 999
				},
				async : false,
				success : function(data) {
					$('#city').children().remove("option[value!='']");
					getCitys(data);
					$('#cityName').val("");


					//�������ʡ��Ĭ��ֵ����ֵʡ��
					var thisCity = $('#cityId').val();
					if (thisCity > 0) {
						$('#city').val(thisCity);
						$('#city').trigger('change');
					}
				}
			});
		}
	});

	$('#city').change(function() {
		$('#cityName').val("");
		if ($(this).val() == "") { //�ж��Ƿ�ѡ���˳���  add by suxc 2011-08-22
			$('#cityName').val("");
		} else {
			$('#cityName').val($(this).find("option:selected").text());
		}
	});

	//��ȡ����
	$.ajax({
		type : 'POST',
		url : countryUrl,
		data : {
			pageSize : 999
		},
		async : false,
		success : function(data) {
			$('#contry').children().remove("option[value!='']");
			$('#province').children().remove("option[value!='']");
			$('#city').children().remove("option[value!='']");
			getCountrys(data);
			if ($('#country').attr('val')) {
				$('#country').val($('#country').attr('val'));
			} else {
				$('#country').val(1);
				//$('#countryName').val('�й�');
			}
			$('#country').trigger('change');
		}
	});

	//��ȡʡ��
	$.ajax({
		type : 'POST',
		url : provinceUrl,
		data : {
			countryId : 1,
			pageSize : 999
		},
		async : false,
		success : function(data) {
			$('#province').children().remove("option[value!='']");
			$('#city').children().remove("option[value!='']");
			getProvinces(data);
			$('#provinceName').val("");
			$('#cityName').val("");

			//�������ʡ��Ĭ��ֵ����ֵʡ��
			var thisProvince = $('#provinceId').val();
			if (thisProvince > 0) {
				$('#province').val(thisProvince);
				$('#province').trigger('change');
			}
		}
	});
}

/* ��ȡ���ҵķ��� */
function getCountrys(data) {
	var o = eval("(" + data + ")");
	var countryArr = o.collection;
	for (var i = 0, l = countryArr.length; i < l; i++) {
		var country = countryArr[i];
		var option = $("<option>").val(country.id).html(country.countryName);
		$('#country').append(option);
	}
}
/* ��ȡʡ�ķ��� */
function getProvinces(data) {
	var o = eval("(" + data + ")");
	var provinceArr = o.collection;
	for (var i = 0, l = provinceArr.length; i < l; i++) {
		var province = provinceArr[i];
		var option = $("<option>").val(province.id).html(province.provinceName);
		$('#province').append(option)
	}
}

/* ��ȡ�еķ��� */
function getCitys(data) {
	// $('#city').html("");
	var o = eval("(" + data + ")");
	var cityArr = o.collection;
	for (var i = 0, l = cityArr.length; i < l; i++) {
		var city = cityArr[i];
		var option = $("<option>").val(city.id).html(city.cityName);
		$('#city').append(option);
	}
}

//�ύ����
function audit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=engineering_change_esmchange&action=add&act=audit";
	}else{
		document.getElementById('form1').action="?model=engineering_change_esmchange&action=add";
	}
}

//�༭ʱ�ύ����
function auditEdit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=engineering_change_esmchange&action=edit&act=audit";
	}else{
		document.getElementById('form1').action="?model=engineering_change_esmchange&action=edit";
	}
}


//����֤
function checkForm(){
	if($("#projectName").val() == ""){
		alert('��Ŀ���Ʋ���Ϊ��');
		return false;
	}

	//����
//	if($("#planBeginDate").val() == ""){
//		alert('Ԥ�ƿ�ʼ���ڲ���Ϊ��');
//		return false;
//	}
//	if($("#planEndDate").val() == ""){
//		alert('Ԥ�ƽ������ڲ���Ϊ��');
//		return false;
//	}
//	var expectedDuration = $("#expectedDuration").val();
//	if(expectedDuration == "" || expectedDuration*1 == 0){
//		alert('Ԥ�ƹ��ڲ���Ϊ0���߿�');
//		return false;
//	}
//
//	//���Ԥ��
//	var newBudgetOutsourcing = $("#newBudgetOutsourcing").val();
//	if(expectedDuration == ""){
//		alert('Ԥ�ƹ��ڲ���Ϊ��');
//		return false;
//	}

//	var actWorkRate = 0;
//	var detailRs = true;
//	//�ӱ�ѭ��
//	var activityNameRows = $("input[id^='activityName']");
//	activityNameRows.each(function(i,n){
//		//�ж�ɾ���Ĳ�����
//		if($("#isActivityDelTag_" + i).length > 0){
//			return;
//		}
//
//		//��������
//		if(strTrim(this.value) == ""){
//			alert('�������Ʋ���Ϊ��');
//			detailRs = false;
//			return false;
//		}
//		var activityName = this.value;
//
//		//����ռ��
//		var workRate = $("#workRate" + i).val();
//		//����������Ŀ�Ĺ���ռ��
//		actWorkRate = accAdd(actWorkRate,workRate,2);
//		if(workRate == ""){
//			alert('����' + this.value + '���Ĺ���ռ�Ȳ���Ϊ��');
//			detailRs = false;
//			return false;
//		}
//
//		//Ԥ������
//		var actPlanBeginDate = $("#actPlanBeginDate" + i).val();
//		if(actPlanBeginDate == ""){
//			alert('����' + this.value + '����Ԥ�ƿ�ʼ���ڲ���Ϊ��');
//			detailRs = false;
//			return false;
//		}
//		var actPlanEndDate = $("#actPlanEndDate" + i).val();
//		if(actPlanEndDate == ""){
//			alert('����' + this.value + '����Ԥ�ƽ������ڲ���Ϊ��');
//			detailRs = false;
//			return false;
//		}
//		var thisDays = DateDiff(actPlanBeginDate,actPlanEndDate) + 1;
//		if(thisDays < 1){
//			alert('����' + this.value + '����Ԥ�ƿ�ʼ���ڲ���С��Ԥ�ƽ�������');
//			detailRs = false;
//			return false;
//		}
//
//		//Ԥ�ƹ���
//		var actDays = $("#actDays" + i).val();
//		if(actDays == ""){
//			alert('����' + this.value + '����Ԥ�ƹ��ڲ���Ϊ��');
//			detailRs = false;
//			return false;
//		}
//		var actWorkload = $("#actWorkload" + i).val();
//		if(actWorkload == ""){
//			alert('����' + this.value + '���Ĺ���������Ϊ��');
//			detailRs = false;
//			return false;
//		}
//
//		//����Ԥ��
//		var personRows = $("input[id^='personLevel"+  i +"']");
//		var personRs = true;
//		personRows.each(function(j,m){
//			var trNo = $(this).attr("trNo");
//			var innerTrNo = $(this).attr("innerTrNo");
//			var thisI = trNo + "_" + innerTrNo;
//			//�ж�ɾ���Ĳ�����
//			if($("#isPersonDelTag_" + thisI).length > 0){
//				return;
//			}
//
//			//��������
//			var personLevel = $("#personLevel" + i + "_" + j).val();
//			if(personLevel == ""){
//				alert('����' + activityName + '��������Ԥ����û����Ա�ȼ�');
//				personRs = false;
//				return false;
//			}
//			//Ԥ�ƿ�ʼ����
//			var perPlanBeginDate = $("#perPlanBeginDate" + i + "_" + j).val();
//			if(perPlanBeginDate == ""){
//				alert('����' + activityName + '��������Ԥ����û��Ԥ�ƿ�ʼ����');
//				personRs = false;
//				return false;
//			}
//			//Ԥ�ƽ�������
//			var perPlanEndDate = $("#perPlanEndDate" + i + "_" + j).val();
//			if(perPlanEndDate == ""){
//				alert('����' + activityName + '��������Ԥ����û��Ԥ�ƽ�������');
//				personRs = false;
//				return false;
//			}
//			var thisDays = DateDiff(perPlanBeginDate,perPlanEndDate) + 1;
//			if(thisDays < 1){
//				alert('����' + activityName + '��������Ԥ����Ԥ�ƿ�ʼ���ڲ���С��Ԥ�ƽ�������');
//				personRs = false;
//				return false;
//			}
//			//����
//			var perDays = $("#perDays" + i + "_" + j).val();
//			if(perDays == "" || perDays*1 == 0){
//				alert('����' + activityName + '��������Ԥ��������Ϊ0���߿�');
//				personRs = false;
//				return false;
//			}
//			//����
//			var perNumber = $("#perNumber" + i + "_" + j).val();
//			if(perNumber == "" || perNumber*1 == 0){
//				alert('����' + activityName + '��������Ԥ��������Ϊ0���߿�');
//				personRs = false;
//				return false;
//			}
//		});
//		if(personRs == false){
//			detailRs = false;
//			return personRs;
//		}
//
//		//������֤
//		var budBudgetNameRows = $("input[id^='budBudgetName"+  i +"']");
//		var budgetRs = true;
//		budBudgetNameRows.each(function(j,m){
//			var trNo = $(this).attr("trNo");
//			var innerTrNo = $(this).attr("innerTrNo");
//			var thisI = trNo + "_" + innerTrNo;
//			//�ж�ɾ���Ĳ�����
//			if($("#isBudgetDelTag_" + thisI).length > 0){
//				return;
//			}
//			//��������
//			var budBudgetName = $("#budBudgetName" + i + "_" + j).val();
//			if(budBudgetName == ""){
//				alert('����' + activityName + '���ķ�����Ϣ��ȱ�ٷ�������');
//				budgetRs = false;
//				return false;
//			}
//			//���õ���
//			var budPrice = $("#budPrice" + i + "_" + j).val();
//			if(budPrice == "" || budPrice*1 == 0){
//				alert('����' + activityName + '���ķ�����Ϣ�з��õ���Ϊ0���߿�');
//				budgetRs = false;
//				return false;
//			}
//			//��������1
//			var budNumberOne = $("#budNumberOne" + i + "_" + j).val();
//			if(budNumberOne == "" || budNumberOne*1 == 0){
//				alert('����' + activityName + '���ķ�����Ϣ�з�������1Ϊ0���߿�');
//				budgetRs = false;
//				return false;
//			}
//			//��������2
//			var budNumberTwo = $("#budNumberTwo" + i + "_" + j).val();
//			if(budNumberTwo == "" || budNumberTwo*1 == 0){
//				alert('����' + activityName + '���ķ�����Ϣ�з�������2Ϊ0���߿�');
//				budgetRs = false;
//				return false;
//			}
//			//���ý��
//			var budAmount = $("#budAmount" + i + "_" + j).val();
//			if(budAmount == "" || budAmount*1 == 0){
//				alert('����' + activityName + '���ķ�����Ϣ�з��ý��Ϊ0���߿�');
//				budgetRs = false;
//				return false;
//			}
//		});
//		if(budgetRs == false){
//			detailRs = false;
//			return budgetRs;
//		}
//	});
//	if(detailRs == false){
//		return detailRs;
//	}
//
//	if(actWorkRate != 100){
//		alert('��Ŀ��Χ�ܹ���ռ�Ȳ�Ϊ100,��ǰ����ռ��Ϊ ' + actWorkRate);
//		return false;
//	}

	//�������
	if($("#changeDescription").val() == ""){
		alert('����д���˵��');
		return false;
	}

	return true;
}

//������Ŀ��Χ
function addActivity(){
	if(!unitArr){
		unitArr = getData('GCGZLDW');
	}

	//�ӱ�ѭ��
	var activityNameRows = $("input[id^='activityName']");
	var $key = activityNameRows.length;
	var $trClass = $key %2 == 0 ? 'tr_odd' : 'tr_even';
	var $thisI = $key + 1;
	var str = '<tr class="'+$trClass+' trEdit' + $key +'" id="trActivity' + $key +'">' +
			'<td valign="top">' +
				'<img style="cursor:pointer;" src="images/removeline.png" title="ɾ������" onclick="delActivity(this,' + $key +')"/>' +
			'</td>' +
			'<td valign="top">' + $thisI +'</td>' +
			'<td align="left" valign="top">' +
				'<input type="text" class="txt" name="esmchange[esmactivity][' + $key +'][activityName]" id="activityName' + $key +'"/>' +
				'<input type="hidden" name="esmchange[esmactivity][' + $key +'][activityId]" id="activityId' + $key +'"/>' +
				'<input type="hidden" name="esmchange[esmactivity][' + $key +'][projectId]" value="'+ $("#projectId").val() +'"/>' +
				'<input type="hidden" name="esmchange[esmactivity][' + $key +'][projectCode]" value="'+ $("#projectCode").val() +'"/>' +
				'<input type="hidden" name="esmchange[esmactivity][' + $key +'][projectName]" value="'+ $("#projectName").val() +'"/>' +
				'<input type="hidden" id="isChange' + $key +'" name="esmchange[esmactivity][' + $key +'][isChange]" value="1"/>' +
			'</td>' +
			'<td valign="top">' +
				'<input type="text" class="txtshort" name="esmchange[esmactivity][' + $key +'][workRate]" id="workRate' + $key +'"/>' +
			'</td>' +
			'<td valign="top">' +
				'<input type="text" class="txtshort Wdate" style="width:90px" id="actPlanBeginDate' + $key +'" name="esmchange[esmactivity][' + $key +'][planBeginDate]" onblur="timeCheckAct(this,' + $key +');" onfocus="WdatePicker();"/>' +
			'</td>' +
			'<td valign="top">' +
				'<input type="text" class="txtshort Wdate" style="width:90px" id="actPlanEndDate' + $key +'" name="esmchange[esmactivity][' + $key +'][planEndDate]" onblur="timeCheckAct(this,' + $key +');" onfocus="WdatePicker();"/>' +
			'</td>' +
			'<td valign="top">' +
				'<input type="text" class="txtmin" id="actDays' + $key +'" name="esmchange[esmactivity][' + $key +'][days]" style="width:50px"/>' +
			'</td>' +
			'<td valign="top">' +
				'<input type="text" class="txtmin" id="actWorkload' + $key +'" name="esmchange[esmactivity][' + $key +'][workload]" style="width:50px"/>' +
			'</td>' +
			'<td valign="top">' +
				'<select id="actWorkloadUnit' + $key +'" name="esmchange[esmactivity][' + $key +'][workloadUnit]" style="width:50px"></select>' +
			'</td>' +
			'<td valign="top">' +
				'<input type="text" class="readOnlyTxtShort formatMoney" id="budgetAll' + $key +'" name="esmchange[esmactivity][' + $key +'][budgetAll]" value="0" readonly="readonly"/>' +
			'</td>' +
			'<td align="left" valign="top">' +
				'<input type="text" class="txt" name="esmchange[esmactivity][' + $key +'][workContent]" style="width:300px"/>' +
				'<input type="hidden" name="esmchange[esmactivity][' + $key +'][remark]"/>' +
			'</td>' +
		'</tr>' +
		'<tr class="'+$trClass+' trEdit' + $key +'" id="outTrPerson' + $key +'">' +
			'<td valign="top" colspan="2">����Ԥ��</td>' +
			'<td valign="top" colspan="9">' +
				'<table class="form_in_table">' +
					'<tr class="main_tr_header">' +
						'<th style="width:40px">' +
							'<img src="images/add_item.png" onclick="addPerson(' + $key +')" title="�����"/>' +
						'</th>' +
						'<th>��Ա�ȼ�</th>' +
						'<th>Ԥ�ƿ�ʼ</th>' +
						'<th>Ԥ�ƽ���</th>' +
						'<th>����</th>' +
						'<th>����</th>' +
						'<th>��������</th>' +
						'<th>�����ɱ�</th>' +
						'<th>�����ɱ����</th>' +
						'<th>��ע��Ϣ</th>' +
					'</tr>' +
					'</thead>' +
					'<tbody id="tblPerson' + $key +'"></tbody>' +
				'</table>' +
			'</td>' +
		'</tr>' +
		'<tr class="'+$trClass+' trEdit' + $key +'" id="outTrBudget' + $key +'">' +
			'<td valign="top" colspan="2">����Ԥ��</td>' +
			'<td valign="top" colspan="9">' +
				'<table class="form_in_table">' +
					'<thead>' +
						'<tr class="main_tr_header">' +
							'<th style="width:40px">' +
								'<img src="images/add_item.png" onclick="addBudget(' + $key +')" title="�����"/>' +
							'</th>' +
							'<th>���÷���</th>' +
							'<th>Ԥ������</th>' +
							'<th>����</th>' +
							'<th>����1</th>' +
							'<th>����2</th>' +
							'<th>Ԥ����</th>' +
							'<th>��ע��Ϣ</th>' +
						'</tr>' +
					'</thead>' +
					'<tbody id="tblBudget' + $key +'"></tbody>' +
				'</table>' +
			'</td>' +
		'</tr>';
	$("#tblActivity").append(str);
	addDataToSelect(unitArr,"actWorkloadUnit" + $key);
	formateMoney();
}

//ɾ����Ŀ��Χ
function delActivity(obj,$key){
	var activityId = $("#activityId" + $key).val();
	var canDel = true;
	$.ajax({
		type : 'POST',
		url : "?model=engineering_worklog_esmworklog&action=checkActLog",
		data : {
			'activityId' : activityId
		},
		async : false,
		success : function(data) {
			if(data == "1"){
				alert('��ǰ��Ŀ��Χ�Ѿ�������־��Ϣ�����ܽ���ɾ��');
				canDel = false;
			}
		}
	});
	if(canDel == false){
		return canDel;
	}

	var actObj = $(obj);
	if(confirm('ȷ��Ҫɾ��������')){
		actObj.parent().parent().hide();
		actObj.parent().append('<input type="hidden" id="isActivityDelTag_'+ $key + '" value="1"/><input type="hidden" name="esmchange[esmactivity]['+
				$key +'][isDel]" value="1"/>');
		//����Ԥ��
		$("#isChange" + $key).val(1);

		//����Ԥ��
		$("#outTrPerson" + $key).hide();
		var personRows = $("input[id^='personLevelId"+  $key +"']");
		personRows.each(function(i,n){
			var trNo = $(this).attr("trNo");
			var innerTrNo = $(this).attr("innerTrNo");
			var thisI = trNo + "_" + innerTrNo;
			//�ж�ɾ���Ĳ�����
			if($("#isPersonDelTag_" + thisI).length > 0){
				return false;
			}else{
				$(this).after('<input type="hidden" id="isPersonDelTag_'+ trNo +'_'+innerTrNo +'" value="1"/><input type="hidden" name="esmchange[esmactivity]['+
				trNo +'][esmperson][' +
				innerTrNo + '][isDel]" value="1"/>');
			}
		});

		//����Ԥ��
		$("#outTrBudget" + $key).hide();
		var budBudgetNameRows = $("input[id^='budBudgetName"+  $key +"']");
		budBudgetNameRows.each(function(i,n){
			var trNo = $(this).attr("trNo");
			var innerTrNo = $(this).attr("innerTrNo");
			var thisI = trNo + "_" + innerTrNo;
			//�ж�ɾ���Ĳ�����
			if($("#isBudgetDelTag_" + thisI).length > 0){
				return false;
			}else{
				$(this).after('<input type="hidden" id="isBudgetDelTag_'+ trNo +'_'+innerTrNo +'" value="1"/><input type="hidden" name="esmchange[esmactivity]['+
				trNo +'][esmbudget][' +
				innerTrNo + '][isDel]" value="1"/>');
			}
		});

		//���¼�������Ԥ��
		calProjectPerson();
		//���¼������
		calBudgetField($key);
	}
}

//�������Ԥ��
function addPerson($key){
	var $trClass = $key %2 == 0 ? 'tr_odd' : 'tr_even';
	var $k = $("input[id^='personLevel"+  $key +"']").length;
	var $thisI = $key + "_" + $k;
	var activityName = $("#activityName" + $key).val();
	var str = '<tr class="'+ $trClass +' trEdit'+ $key +'" id="trPerson'+ $thisI +'">' +
			'<td valign="top">' +
				'<img style="cursor:pointer;" src="images/removeline.png" title="ɾ������Ԥ��" onclick="delPerson(this,'+ $key +','+ $k +')"/>' +
			'</td>' +
			'<td align="left" valign="top">' +
				'<input type="text" class="txtmiddle" id="personLevel'+ $thisI +'" name="esmchange[esmactivity]['+ $key +'][esmperson]['+ $k +'][personLevel]" readonly="readonly"/>' +
				'<input type="hidden" id="personLevelId'+ $thisI +'" name="esmchange[esmactivity]['+ $key +'][esmperson]['+ $k +'][personLevelId]" trNo="'+ $key +'" innerTrNo="'+ $k +'"/>' +
				'<input type="hidden" name="esmchange[esmactivity]['+ $key +'][esmperson]['+ $k +'][projectId]" value="'+ $("#projectId").val() +'"/>' +
				'<input type="hidden" name="esmchange[esmactivity]['+ $key +'][esmperson]['+ $k +'][projectCode]" value="'+ $("#projectCode").val() +'"/>' +
				'<input type="hidden" name="esmchange[esmactivity]['+ $key +'][esmperson]['+ $k +'][projectName]" value="'+ $("#projectName").val() +'"/>' +
				'<input type="hidden" name="esmchange[esmactivity]['+ $key +'][esmperson]['+ $k +'][activityName]" value="'+ activityName +'"/>' +
				'<input type="hidden" name="esmchange[esmactivity]['+ $key +'][esmperson]['+ $k +'][orgId]"/>' +
			'</td>' +
			'<td valign="top">' +
				'<input type="text" class="txtmiddle Wdate" style="width:90px" id="perPlanBeginDate'+ $thisI +'" name="esmchange[esmactivity]['+ $key +'][esmperson]['+ $k +'][planBeginDate]" onblur="timeCheckPerson(this,'+ $key +','+ $k +');" onfocus="WdatePicker();"/>' +
			'</td>' +
			'<td valign="top">' +
				'<input type="text" class="txtmiddle Wdate" style="width:90px" id="perPlanEndDate'+ $thisI +'" name="esmchange[esmactivity]['+ $key +'][esmperson]['+ $k +'][planEndDate]" onblur="timeCheckPerson(this,'+ $key +','+ $k +');" onfocus="WdatePicker();"/>' +
			'</td>' +
			'<td valign="top">' +
				'<input type="text" class="txtmin" id="perDays'+ $thisI +'" name="esmchange[esmactivity]['+ $key +'][esmperson]['+ $k +'][days]" style="width:50px" onblur="calPersonBatch(\''+ $thisI +'\')"/>' +
				'<input type="hidden" id="perPrice'+ $thisI +'" name="esmchange[esmactivity]['+ $key +'][esmperson]['+ $k +'][price]"/>' +
				'<input type="hidden" id="perCoefficient'+ $thisI +'" name="esmchange[esmactivity]['+ $key +'][esmperson]['+ $k +'][coefficient]"/>' +
			'</td>' +
			'<td valign="top">' +
				'<input type="text" class="txtmin" id="perNumber'+ $thisI +'" name="esmchange[esmactivity]['+ $key +'][esmperson]['+ $k +'][number]" style="width:50px" onblur="calPersonBatch(\''+ $thisI +'\')"/>' +
			'</td>' +
			'<td valign="top">' +
				'<input type="text" class="readOnlyTxtMiddle" id="perPersonDays'+ $thisI +'" style="width:90px" name="esmchange[esmactivity]['+ $key +'][esmperson]['+ $k +'][personDays]" readonly="readonly"/>' +
			'</td>' +
			'<td valign="top">' +
				'<input type="text" class="readOnlyTxtMiddle" id="perPersonCostDays'+ $thisI +'" style="width:90px" name="esmchange[esmactivity]['+ $key +'][esmperson]['+ $k +'][personCostDays]" readonly="readonly"/>' +
			'</td>' +
			'<td valign="top">' +
				'<input type="text" class="readOnlyTxtMiddle formatMoney" id="perPersonCost'+ $thisI +'" style="width:90px" name="esmchange[esmactivity]['+ $key +'][esmperson]['+ $k +'][personCost]" readonly="readonly"/>' +
			'</td>' +
			'<td align="left" valign="top">' +
				'<input type="text" class="txt" name="esmchange[esmactivity]['+ $key +'][esmperson]['+ $k +'][remark]"/>' +
			'</td>' +
		'</tr>';
	$("#tblPerson" + $key).append(str);
	$("#personLevel"+ $thisI).yxcombogrid_eperson({
		hiddenId : 'personLevelId' + $thisI,
		nameCol : 'personLevel',
		width : 600,
		gridOptions : {
			showcheckbox : false,
			event : {
				row_dblclick : (function($thisI) {
					return function(e, row, rowData) {
						$("#perCoefficient"+ $thisI).val(rowData.coefficient);
						$("#perPrice"+ $thisI).val(rowData.price);
						calPersonBatch($thisI);
					}
				})($thisI)
			}
		}
	});
	formateMoney();
}

//ɾ������Ԥ��
function delPerson(obj,$key,$k){
	if(confirm('ȷ��Ҫɾ��������')){
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" id="isPersonDelTag_'+ $key +'_'+$k +'" value="1"/><input type="hidden" name="esmchange[esmactivity]['+
				$key +'][esmperson][' +
				$k + '][isDel]" value="1"/>');

		//���¼�������Ԥ��
		calProjectPerson();
	}
}

//���Ԥ��
function addBudget($key){
	var $trClass = $key %2 == 0 ? 'tr_odd' : 'tr_even';
	var $k = $("input[id^='budBudgetName"+  $key +"']").length;
	var $thisI = $key + "_" + $k;
	var activityName = $("#activityName" + $key).val();
	var str = '<tr class="'+ $trClass +' trEdit'+ $key +'" id="trBudget'+ $thisI +'">' +
			'<td valign="top">' +
				'<img style="cursor:pointer;" src="images/removeline.png" title="ɾ������Ԥ��" onclick="delBudget(this,'+ $key +','+ $k +')"/>' +
			'</td>' +
			'<td valign="top">' +
				'<input type="text" class="readOnlyTxtMiddle" id="budParentName'+ $thisI +'" name="esmchange[esmactivity]['+ $key +'][esmbudget]['+ $k +'][parentName]"/>' +
				'<input type="hidden" id="budParentId'+ $thisI +'" name="esmchange[esmactivity]['+ $key +'][esmbudget]['+ $k +'][parentId]"/>' +
				'<input type="hidden" name="esmchange[esmactivity]['+ $key +'][esmbudget]['+ $k +'][projectId]" value="'+ $("#projectId").val() +'"/>' +
				'<input type="hidden" name="esmchange[esmactivity]['+ $key +'][esmbudget]['+ $k +'][projectCode]" value="'+ $("#projectCode").val() +'"/>' +
				'<input type="hidden" name="esmchange[esmactivity]['+ $key +'][esmbudget]['+ $k +'][projectName]" value="'+ $("#projectName").val() +'"/>' +
				'<input type="hidden" name="esmchange[esmactivity]['+ $key +'][esmbudget]['+ $k +'][activityName]" value="'+ activityName +'"/>' +
				'<input type="hidden" name="esmchange[esmactivity]['+ $key +'][esmbudget]['+ $k +'][orgId]"/>' +
			'</td>' +
			'<td valign="top">' +
				'<input type="text" class="txtmiddle" id="budBudgetName'+ $thisI +'" name="esmchange[esmactivity]['+ $key +'][esmbudget]['+ $k +'][budgetName]" readonly="readonly" trNo="'+ $key +'" innerTrNo="'+ $k +'"/>' +
				'<input type="hidden" id="budBudgetId'+ $thisI +'" name="esmchange[esmactivity]['+ $key +'][esmbudget]['+ $k +'][budgetId]"/>' +
			'</td>' +
			'<td valign="top">' +
				'<input type="text" id="budPrice'+ $thisI +'" class="txtshort formatMoney" name="esmchange[esmactivity]['+ $key +'][esmbudget]['+ $k +'][price]" onblur="calBudget(this,'+ $key +','+ $k +');"/>' +
			'</td>' +
			'<td valign="top">' +
				'<input type="text" id="budNumberOne'+ $thisI +'" class="txtshort" name="esmchange[esmactivity]['+ $key +'][esmbudget]['+ $k +'][numberOne]" onblur="calBudget(this,'+ $key +','+ $k +');"/>' +
			'</td>' +
			'<td valign="top">' +
				'<input type="text" id="budNumberTwo'+ $thisI +'" class="txtshort" name="esmchange[esmactivity]['+ $key +'][esmbudget]['+ $k +'][numberTwo]" onblur="calBudget(this,'+ $key +','+ $k +');"/>' +
			'</td>' +
			'<td valign="top">' +
				'<input type="text" id="budAmount'+ $thisI +'" class="txtshort formatMoney" name="esmchange[esmactivity]['+ $key +'][esmbudget]['+ $k +'][amount]"/>' +
			'</td>' +
			'<td valign="top">' +
				'<input type="text" class="txtlong" name="esmchange[esmactivity]['+ $key +'][esmbudget]['+ $k +'][remark]"/>' +
			'</td>' +
		'</tr>';
	$("#tblBudget" + $key).append(str);
	$("#budBudgetName"+ $thisI).yxcombogrid_budgetdl({
		hiddenId : 'budBudgetId' + $thisI,
		searchName : 'budgetNameDLSearch',
		width : 600,
		height : 300,
		gridOptions : {
			showcheckbox : false,
			event : {
				row_dblclick : (function($thisI) {
					return function(e, row, rowData) {
						$("#budParentName"+ $thisI).val(rowData.parentName);
						$("#budParentId"+ $thisI).val(rowData.parentId);
					}
				})($thisI)
			}
		}
	});
	formateMoney();
}

//ɾ��Ԥ��
function delBudget(obj,$key,$k){
	if(confirm('ȷ��Ҫɾ��������')){
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" id="isBudgetDelTag_'+ $key +'_'+$k +'" value="1"/><input type="hidden" name="esmchange[esmactivity]['+
				$key +'][esmbudget][' +
				$k + '][isDel]" value="1"/>');

		//���¼������
		calBudgetField($key);
	}
}

//��ĿԤ����Ⱦ
function initEsmBudget(){
	$("#esmbudget").yxeditgrid({
		url : '?model=engineering_change_esmchangebud&action=listJson',
		type : 'view',
		param : {
			'changeId' : $("#id").val()
		},
		tableClass : 'form_in_table',
		colModel : [{
			name : 'parentName',
			display : '���ô���'
		}, {
			name : 'budgetName',
			display : '����С��',
			process : function(v,row){
				if(row.isChanging == "0"){
					return v;
				}else{
					if(row.changeAction == 'add'){
						return "<img src='images/new.gif' title='����'/><span class='red'>"+v+"</span>";
					}else if(row.changeAction == 'edit'){
						return "<img src='images/changeedit.gif' title='���'/><span class='red'>"+v+"</span>";
					}else if(row.changeAction == 'delete'){
						return "<span class='red' style='text-decoration:line-through;' title='ɾ��'>"+v+"</span>";
					}
				}
			}
		}, {
			name : 'price',
			display : '����',
			process : function(v,row){
                if(row.customPrice == "1"){
                    return "<span class='blue' title='�Զ���۸�'>" + moneyFormat2(v) + "</span>";
                }else{
                    return moneyFormat2(v);
                }
			}
		}, {
			name : 'numberOne',
			display : '����1'
		}, {
			name : 'numberTwo',
			display : '����2'
		}, {
			name : 'amount',
			display : '���',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			name : 'budgetType',
			display : '��������',
			process : function(v){
				switch(v){
					case 'budgetField' : return '<span class="blue">�ֳ�Ԥ��</span>';break;
					case 'budgetPerson' : return '<span class="green">����Ԥ��</span>';break;
					case 'budgetOutsourcing' : return '<span style="color:gray">���Ԥ��</span>';break;
					case 'budgetOther' : return '����Ԥ��';break;
				}
			}
		}, {
			name : 'remark',
			display : '��ע'
		}]
	});
}

//��ĿԤ����Ⱦ
function initEsmEqu(){
	$("#esmequ").yxeditgrid({
		url : '?model=engineering_change_esmchangeres&action=listJson',
		type : 'view',
		param : {
			'changeId' : $("#id").val()
		},
		tableClass : 'form_in_table',
		colModel : [{
			name : 'resourceTypeName',
			display : '�豸����'
		}, {
			name : 'resourceName',
			display : '�豸����',
			process : function(v,row){
				if(row.isChanging == "0"){
					return v;
				}else{
					if(row.changeAction == 'add'){
						return "<img src='images/new.gif' title='����'/><span class='red'>"+v+"</span>";
					}else if(row.changeAction == 'edit'){
						return "<img src='images/changeedit.gif' title='���'/><span class='red'>"+v+"</span>";
					}else if(row.changeAction == 'delete'){
						return "<span class='red' style='text-decoration:line-through;' title='ɾ��'>"+v+"</span>";
					}
				}
			},
			width : 200
		}, {
			name : 'number',
			display : '����'
		}, {
			name : 'planBeginDate',
			display : 'Ԥ�ƽ������'
		}, {
			name : 'planEndDate',
			display : 'Ԥ�ƹ黹����'
		}, {
			name : 'useDays',
			display : 'ʹ������'
		}, {
			name : 'unit',
			display : '��λ'
		}, {
			name : 'price',
			display : '���豸�ۼ�',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			name : 'amount',
			display : '���',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			name : 'remark',
			display : '��ע'
		}]
	});
}