var unitArr; //缓存工作量单位

//计算总费用进度
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

//初始化人员等级选择
function initPerson(){
	//初始化人员等级选择
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

//初始化费用预算
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

//计算项目预计工期
function timeCheckProject($t){
	var startDate = $('#planBeginDate').val();
	var endDate = $('#planEndDate').val();
	if(startDate == "" || endDate == ""){
		return false;
	}
	var s = DateDiff(startDate,endDate) + 1;
//	if(s < 0) {
//		alert("预计开始不能比预计结束时间晚！");
//		$t.value = "";
//		return false;
//	}
	$("#expectedDuration").val(s);
}

//计算项目预计工期
function timeCheck($t){
	var startDate = $('#actBeginDate').val();
	var endDate = $('#actEndDate').val();
	if(startDate == "" || endDate == ""){
		return false;
	}
	var s = DateDiff(startDate,endDate) + 1;
//	if(s < 0) {
//		alert("预计开始不能比预计结束时间晚！");
//		$t.value = "";
//		return false;
//	}
	$("#actDuration").val(s);
}

//计算项目范围工期
function timeCheckAct($t,$key){
	var startDate = $('#actPlanBeginDate' + $key).val();
	var endDate = $('#actPlanEndDate' + $key).val();
	if(startDate == "" || endDate == ""){
		return false;
	}
	var s = DateDiff(startDate,endDate) + 1;
//	if(s < 0) {
//		alert("预计开始不能比预计结束时间晚！");
//		$t.value = "";
//		return false;
//	}
	$("#actDays" + $key).val(s);

	//获取当前任务工作量单位
	var workloadUnit = $("#actWorkloadUnit" + $key).val();
	//如果是天，则根据人力预算更新任务的工作天数
	if(workloadUnit == 'GCGZLDW-00'){
		$("#actWorkload" +$key ).val(s);
	}
}

//计算人力预算日期
function timeCheckPerson($t,$key,$k){
	var thisI = $key + "_" + $k;
	var startDate = $('#perPlanBeginDate' + thisI).val();
	var endDate = $('#perPlanEndDate' + thisI).val();
	if(startDate == "" || endDate == ""){
		return false;
	}
	var s = DateDiff(startDate,endDate) + 1;
//	if(s < 0) {
//		alert("预计开始不能比预计结束时间晚！");
//		$t.value = "";
//		return false;
//	}
	$("#perDays" + thisI).val(s);
	calPersonBatch(thisI);
}

//计算人力预算 - 批量新增中使用
function calPersonBatch(rowNum){
	//获取数量
	var number= $("#perNumber" + rowNum ).val();

	if($("#personLevel"  + rowNum ).val() != "" && number != ""){
		//获取计量系数
		var coefficient = $("#perCoefficient" + rowNum).val();
		//获取单价
		var price = $("#perPrice" + rowNum).val();
		//获取天数
		var days = $("#perDays" + rowNum ).val();
		//计算人工天数
		var personDays = accMul(number,days,2);
		$("#perPersonDays" + rowNum).val(personDays);

		//计算人工天数
		var personCostDays = accMul(coefficient,accMul(number,days,2),2);
		$("#perPersonCostDays" +  rowNum).val(personCostDays);

		//计算人工天数
		var personCost = accMul(price,personDays,2);
		setMoney("perPersonCost" +  rowNum,personCost,2);
	}
	calProjectPerson();
}

//计算项目人力预算
function calProjectPerson(){
	var newBudgetPerson = 0; //人力预算金额
	var newBudgetPeople = 0; //人力预算
	var newBudgetDay = 0; //人力天数
	$("input[id^='personLevelId']").each(function(i,n){
		var trNo = $(this).attr("trNo");
		var innerTrNo = $(this).attr("innerTrNo");
		var thisI = trNo + "_" + innerTrNo;

		//判断删除的不处理
		if($("#isPersonDelTag_" + thisI).length == 0){
			newBudgetPerson = accAdd(newBudgetPerson,$("#perPersonCost" + thisI).val(),2);
			newBudgetPeople = accAdd(newBudgetPeople,$("#perPersonCostDays" + thisI).val(),2);
			newBudgetDay = accAdd(newBudgetDay,$("#perPersonDays" + thisI).val(),2);
		}
	});

	setMoney("newBudgetPerson",newBudgetPerson);
	setMoney("newBudgetPeople",newBudgetPeople);
	setMoney("newBudgetDay",newBudgetDay);

	//更新项目预算
	countBudgetAll();
}

//计算行费用预算
function calBudget($t,$key,$k){
	var thisI = $key + "_" + $k;
	//获取单价
	var budPriceObj = $("#budPrice" + thisI );
	//获取数量
	var budNumberOneObj = $("#budNumberOne" + thisI );
	var budNumberTwoObj = $("#budNumberTwo" + thisI );

	var amountAll = 0;

	if(budPriceObj.val() * 1 ==""){
		return false;
	}

	//如果没有数量的情况默认设1
	if(budNumberOneObj.val()*1 == "" && budNumberTwoObj.val()*1 == ""){
		budNumberOneObj.val(1);
		budNumberTwoObj.val(1);
	}else if(budNumberOneObj.val()*1 == "" && budNumberTwoObj.val()*1 != ""){//如果只有其中一种
		budNumberOneObj.val(1);
	}else if(budNumberOneObj.val()*1 != "" && budNumberTwoObj.val()*1 == ""){//如果只有其中一种
		budNumberTwoObj.val(1);
	}

	//金额设值
	amountAll = accMul(budPriceObj.val(),budNumberOneObj.val(),2);
	amountAll = accMul(amountAll,budNumberTwoObj.val(),2);
	setMoney("budAmount" + thisI,amountAll);

	//计算项目预算金额
	calBudgetField($key);
}

//计算任务预算
function calBudgetField($key){
	var budgetFieldAct = 0; //任务现场预算
	var budgetField = 0; //现场预算
	var mark = "";
	$("input[id^='budBudgetName']").each(function(i,n){
		var trNo = $(this).attr("trNo");
		var innerTrNo = $(this).attr("innerTrNo");
		var thisI = trNo + "_" + innerTrNo;
		//判断删除的不处理
		if($("#isBudgetDelTag_" + thisI).length == 0){
			var thisMoney = $("#budAmount" + thisI ).val();

			budgetField = accAdd(budgetField,thisMoney,2);

			if($key != undefined){
				//如果是变更的任务
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
	//更新项目预算
	countBudgetAll();
}

//任务显示功能
function changeActivity($key){
	$(".trView" + $key).hide();
	$(".trEdit" + $key).show();
	$("#isChange" + $key).val(1);
	//初始化信息

	//初始化人员等级选择
	initPerson();

	//初始化费用预算项
	initBudget();
}

//任务显示功能
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

//初始化城市
function initCity() {
	var countryUrl = "?model=system_procity_country&action=pageJson";// 获取国家的URL
	var provinceUrl = "?model=system_procity_province&action=pageJson"; // 获取省的URL
	var cityUrl = "?model=system_procity_city&action=pageJson"; // 获取市的URL
	$('#country').append($("<option value=''>").html("请选择国家"));
	$('#province').append($("<option value=''>").html("请选择省份"));
	$('#city').append($("<option value=''>").html("请选择城市"));

	// 省的选择改变，获取市
	$('#province').change(function() {
		$('#provinceName').val($(this).find("option:selected").text());
		var provinceId = $(this).val();
		if (provinceId == "") { //判断是否选择了省份，如果没有选中，刚省份名称为空   add by suxc 2011-08-22
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


					//如果存在省份默认值，则赋值省份
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
		if ($(this).val() == "") { //判断是否选择了城市  add by suxc 2011-08-22
			$('#cityName').val("");
		} else {
			$('#cityName').val($(this).find("option:selected").text());
		}
	});

	//获取国家
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
				//$('#countryName').val('中国');
			}
			$('#country').trigger('change');
		}
	});

	//获取省份
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

			//如果存在省份默认值，则赋值省份
			var thisProvince = $('#provinceId').val();
			if (thisProvince > 0) {
				$('#province').val(thisProvince);
				$('#province').trigger('change');
			}
		}
	});
}

/* 获取国家的方法 */
function getCountrys(data) {
	var o = eval("(" + data + ")");
	var countryArr = o.collection;
	for (var i = 0, l = countryArr.length; i < l; i++) {
		var country = countryArr[i];
		var option = $("<option>").val(country.id).html(country.countryName);
		$('#country').append(option);
	}
}
/* 获取省的方法 */
function getProvinces(data) {
	var o = eval("(" + data + ")");
	var provinceArr = o.collection;
	for (var i = 0, l = provinceArr.length; i < l; i++) {
		var province = provinceArr[i];
		var option = $("<option>").val(province.id).html(province.provinceName);
		$('#province').append(option)
	}
}

/* 获取市的方法 */
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

//提交审批
function audit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=engineering_change_esmchange&action=add&act=audit";
	}else{
		document.getElementById('form1').action="?model=engineering_change_esmchange&action=add";
	}
}

//编辑时提交审批
function auditEdit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=engineering_change_esmchange&action=edit&act=audit";
	}else{
		document.getElementById('form1').action="?model=engineering_change_esmchange&action=edit";
	}
}


//表单验证
function checkForm(){
	if($("#projectName").val() == ""){
		alert('项目名称不能为空');
		return false;
	}

	//日期
//	if($("#planBeginDate").val() == ""){
//		alert('预计开始日期不能为空');
//		return false;
//	}
//	if($("#planEndDate").val() == ""){
//		alert('预计结束日期不能为空');
//		return false;
//	}
//	var expectedDuration = $("#expectedDuration").val();
//	if(expectedDuration == "" || expectedDuration*1 == 0){
//		alert('预计工期不能为0或者空');
//		return false;
//	}
//
//	//外包预算
//	var newBudgetOutsourcing = $("#newBudgetOutsourcing").val();
//	if(expectedDuration == ""){
//		alert('预计工期不能为空');
//		return false;
//	}

//	var actWorkRate = 0;
//	var detailRs = true;
//	//从表循环
//	var activityNameRows = $("input[id^='activityName']");
//	activityNameRows.each(function(i,n){
//		//判断删除的不处理
//		if($("#isActivityDelTag_" + i).length > 0){
//			return;
//		}
//
//		//任务名称
//		if(strTrim(this.value) == ""){
//			alert('任务名称不能为空');
//			detailRs = false;
//			return false;
//		}
//		var activityName = this.value;
//
//		//工作占比
//		var workRate = $("#workRate" + i).val();
//		//计算整个项目的工作占比
//		actWorkRate = accAdd(actWorkRate,workRate,2);
//		if(workRate == ""){
//			alert('任务【' + this.value + '】的工作占比不能为空');
//			detailRs = false;
//			return false;
//		}
//
//		//预计日期
//		var actPlanBeginDate = $("#actPlanBeginDate" + i).val();
//		if(actPlanBeginDate == ""){
//			alert('任务【' + this.value + '】的预计开始日期不能为空');
//			detailRs = false;
//			return false;
//		}
//		var actPlanEndDate = $("#actPlanEndDate" + i).val();
//		if(actPlanEndDate == ""){
//			alert('任务【' + this.value + '】的预计结束日期不能为空');
//			detailRs = false;
//			return false;
//		}
//		var thisDays = DateDiff(actPlanBeginDate,actPlanEndDate) + 1;
//		if(thisDays < 1){
//			alert('任务【' + this.value + '】的预计开始日期不能小于预计结束日期');
//			detailRs = false;
//			return false;
//		}
//
//		//预计工期
//		var actDays = $("#actDays" + i).val();
//		if(actDays == ""){
//			alert('任务【' + this.value + '】的预计工期不能为空');
//			detailRs = false;
//			return false;
//		}
//		var actWorkload = $("#actWorkload" + i).val();
//		if(actWorkload == ""){
//			alert('任务【' + this.value + '】的工作量不能为空');
//			detailRs = false;
//			return false;
//		}
//
//		//人力预算
//		var personRows = $("input[id^='personLevel"+  i +"']");
//		var personRs = true;
//		personRows.each(function(j,m){
//			var trNo = $(this).attr("trNo");
//			var innerTrNo = $(this).attr("innerTrNo");
//			var thisI = trNo + "_" + innerTrNo;
//			//判断删除的不处理
//			if($("#isPersonDelTag_" + thisI).length > 0){
//				return;
//			}
//
//			//费用名称
//			var personLevel = $("#personLevel" + i + "_" + j).val();
//			if(personLevel == ""){
//				alert('任务【' + activityName + '】的人力预算中没有人员等级');
//				personRs = false;
//				return false;
//			}
//			//预计开始日期
//			var perPlanBeginDate = $("#perPlanBeginDate" + i + "_" + j).val();
//			if(perPlanBeginDate == ""){
//				alert('任务【' + activityName + '】的人力预算中没有预计开始日期');
//				personRs = false;
//				return false;
//			}
//			//预计结束日期
//			var perPlanEndDate = $("#perPlanEndDate" + i + "_" + j).val();
//			if(perPlanEndDate == ""){
//				alert('任务【' + activityName + '】的人力预算中没有预计结束日期');
//				personRs = false;
//				return false;
//			}
//			var thisDays = DateDiff(perPlanBeginDate,perPlanEndDate) + 1;
//			if(thisDays < 1){
//				alert('任务【' + activityName + '】的人力预算中预计开始日期不能小于预计结束日期');
//				personRs = false;
//				return false;
//			}
//			//天数
//			var perDays = $("#perDays" + i + "_" + j).val();
//			if(perDays == "" || perDays*1 == 0){
//				alert('任务【' + activityName + '】的人力预算中天数为0或者空');
//				personRs = false;
//				return false;
//			}
//			//数量
//			var perNumber = $("#perNumber" + i + "_" + j).val();
//			if(perNumber == "" || perNumber*1 == 0){
//				alert('任务【' + activityName + '】的人力预算中数量为0或者空');
//				personRs = false;
//				return false;
//			}
//		});
//		if(personRs == false){
//			detailRs = false;
//			return personRs;
//		}
//
//		//费用验证
//		var budBudgetNameRows = $("input[id^='budBudgetName"+  i +"']");
//		var budgetRs = true;
//		budBudgetNameRows.each(function(j,m){
//			var trNo = $(this).attr("trNo");
//			var innerTrNo = $(this).attr("innerTrNo");
//			var thisI = trNo + "_" + innerTrNo;
//			//判断删除的不处理
//			if($("#isBudgetDelTag_" + thisI).length > 0){
//				return;
//			}
//			//费用名称
//			var budBudgetName = $("#budBudgetName" + i + "_" + j).val();
//			if(budBudgetName == ""){
//				alert('任务【' + activityName + '】的费用信息中缺少费用类型');
//				budgetRs = false;
//				return false;
//			}
//			//费用单价
//			var budPrice = $("#budPrice" + i + "_" + j).val();
//			if(budPrice == "" || budPrice*1 == 0){
//				alert('任务【' + activityName + '】的费用信息中费用单价为0或者空');
//				budgetRs = false;
//				return false;
//			}
//			//费用数量1
//			var budNumberOne = $("#budNumberOne" + i + "_" + j).val();
//			if(budNumberOne == "" || budNumberOne*1 == 0){
//				alert('任务【' + activityName + '】的费用信息中费用数量1为0或者空');
//				budgetRs = false;
//				return false;
//			}
//			//费用数量2
//			var budNumberTwo = $("#budNumberTwo" + i + "_" + j).val();
//			if(budNumberTwo == "" || budNumberTwo*1 == 0){
//				alert('任务【' + activityName + '】的费用信息中费用数量2为0或者空');
//				budgetRs = false;
//				return false;
//			}
//			//费用金额
//			var budAmount = $("#budAmount" + i + "_" + j).val();
//			if(budAmount == "" || budAmount*1 == 0){
//				alert('任务【' + activityName + '】的费用信息中费用金额为0或者空');
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
//		alert('项目范围总工作占比不为100,当前工作占比为 ' + actWorkRate);
//		return false;
//	}

	//变更描述
	if($("#changeDescription").val() == ""){
		alert('请填写变更说明');
		return false;
	}

	return true;
}

//新增项目范围
function addActivity(){
	if(!unitArr){
		unitArr = getData('GCGZLDW');
	}

	//从表循环
	var activityNameRows = $("input[id^='activityName']");
	var $key = activityNameRows.length;
	var $trClass = $key %2 == 0 ? 'tr_odd' : 'tr_even';
	var $thisI = $key + 1;
	var str = '<tr class="'+$trClass+' trEdit' + $key +'" id="trActivity' + $key +'">' +
			'<td valign="top">' +
				'<img style="cursor:pointer;" src="images/removeline.png" title="删除任务" onclick="delActivity(this,' + $key +')"/>' +
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
			'<td valign="top" colspan="2">人力预算</td>' +
			'<td valign="top" colspan="9">' +
				'<table class="form_in_table">' +
					'<tr class="main_tr_header">' +
						'<th style="width:40px">' +
							'<img src="images/add_item.png" onclick="addPerson(' + $key +')" title="添加行"/>' +
						'</th>' +
						'<th>人员等级</th>' +
						'<th>预计开始</th>' +
						'<th>预计结束</th>' +
						'<th>天数</th>' +
						'<th>数量</th>' +
						'<th>人力天数</th>' +
						'<th>人力成本</th>' +
						'<th>人力成本金额</th>' +
						'<th>备注信息</th>' +
					'</tr>' +
					'</thead>' +
					'<tbody id="tblPerson' + $key +'"></tbody>' +
				'</table>' +
			'</td>' +
		'</tr>' +
		'<tr class="'+$trClass+' trEdit' + $key +'" id="outTrBudget' + $key +'">' +
			'<td valign="top" colspan="2">费用预算</td>' +
			'<td valign="top" colspan="9">' +
				'<table class="form_in_table">' +
					'<thead>' +
						'<tr class="main_tr_header">' +
							'<th style="width:40px">' +
								'<img src="images/add_item.png" onclick="addBudget(' + $key +')" title="添加行"/>' +
							'</th>' +
							'<th>费用分类</th>' +
							'<th>预算名称</th>' +
							'<th>单价</th>' +
							'<th>数量1</th>' +
							'<th>数量2</th>' +
							'<th>预算金额</th>' +
							'<th>备注信息</th>' +
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

//删除项目范围
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
				alert('当前项目范围已经存在日志信息，不能进行删除');
				canDel = false;
			}
		}
	});
	if(canDel == false){
		return canDel;
	}

	var actObj = $(obj);
	if(confirm('确定要删除此项吗？')){
		actObj.parent().parent().hide();
		actObj.parent().append('<input type="hidden" id="isActivityDelTag_'+ $key + '" value="1"/><input type="hidden" name="esmchange[esmactivity]['+
				$key +'][isDel]" value="1"/>');
		//人力预算
		$("#isChange" + $key).val(1);

		//人力预算
		$("#outTrPerson" + $key).hide();
		var personRows = $("input[id^='personLevelId"+  $key +"']");
		personRows.each(function(i,n){
			var trNo = $(this).attr("trNo");
			var innerTrNo = $(this).attr("innerTrNo");
			var thisI = trNo + "_" + innerTrNo;
			//判断删除的不处理
			if($("#isPersonDelTag_" + thisI).length > 0){
				return false;
			}else{
				$(this).after('<input type="hidden" id="isPersonDelTag_'+ trNo +'_'+innerTrNo +'" value="1"/><input type="hidden" name="esmchange[esmactivity]['+
				trNo +'][esmperson][' +
				innerTrNo + '][isDel]" value="1"/>');
			}
		});

		//费用预算
		$("#outTrBudget" + $key).hide();
		var budBudgetNameRows = $("input[id^='budBudgetName"+  $key +"']");
		budBudgetNameRows.each(function(i,n){
			var trNo = $(this).attr("trNo");
			var innerTrNo = $(this).attr("innerTrNo");
			var thisI = trNo + "_" + innerTrNo;
			//判断删除的不处理
			if($("#isBudgetDelTag_" + thisI).length > 0){
				return false;
			}else{
				$(this).after('<input type="hidden" id="isBudgetDelTag_'+ trNo +'_'+innerTrNo +'" value="1"/><input type="hidden" name="esmchange[esmactivity]['+
				trNo +'][esmbudget][' +
				innerTrNo + '][isDel]" value="1"/>');
			}
		});

		//重新计算人力预算
		calProjectPerson();
		//重新计算费用
		calBudgetField($key);
	}
}

//添加人力预算
function addPerson($key){
	var $trClass = $key %2 == 0 ? 'tr_odd' : 'tr_even';
	var $k = $("input[id^='personLevel"+  $key +"']").length;
	var $thisI = $key + "_" + $k;
	var activityName = $("#activityName" + $key).val();
	var str = '<tr class="'+ $trClass +' trEdit'+ $key +'" id="trPerson'+ $thisI +'">' +
			'<td valign="top">' +
				'<img style="cursor:pointer;" src="images/removeline.png" title="删除人力预算" onclick="delPerson(this,'+ $key +','+ $k +')"/>' +
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

//删除人力预算
function delPerson(obj,$key,$k){
	if(confirm('确定要删除此项吗？')){
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" id="isPersonDelTag_'+ $key +'_'+$k +'" value="1"/><input type="hidden" name="esmchange[esmactivity]['+
				$key +'][esmperson][' +
				$k + '][isDel]" value="1"/>');

		//重新计算人力预算
		calProjectPerson();
	}
}

//添加预算
function addBudget($key){
	var $trClass = $key %2 == 0 ? 'tr_odd' : 'tr_even';
	var $k = $("input[id^='budBudgetName"+  $key +"']").length;
	var $thisI = $key + "_" + $k;
	var activityName = $("#activityName" + $key).val();
	var str = '<tr class="'+ $trClass +' trEdit'+ $key +'" id="trBudget'+ $thisI +'">' +
			'<td valign="top">' +
				'<img style="cursor:pointer;" src="images/removeline.png" title="删除费用预算" onclick="delBudget(this,'+ $key +','+ $k +')"/>' +
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

//删除预算
function delBudget(obj,$key,$k){
	if(confirm('确定要删除此项吗？')){
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" id="isBudgetDelTag_'+ $key +'_'+$k +'" value="1"/><input type="hidden" name="esmchange[esmactivity]['+
				$key +'][esmbudget][' +
				$k + '][isDel]" value="1"/>');

		//重新计算费用
		calBudgetField($key);
	}
}

//项目预算渲染
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
			display : '费用大类'
		}, {
			name : 'budgetName',
			display : '费用小类',
			process : function(v,row){
				if(row.isChanging == "0"){
					return v;
				}else{
					if(row.changeAction == 'add'){
						return "<img src='images/new.gif' title='新增'/><span class='red'>"+v+"</span>";
					}else if(row.changeAction == 'edit'){
						return "<img src='images/changeedit.gif' title='变更'/><span class='red'>"+v+"</span>";
					}else if(row.changeAction == 'delete'){
						return "<span class='red' style='text-decoration:line-through;' title='删除'>"+v+"</span>";
					}
				}
			}
		}, {
			name : 'price',
			display : '单价',
			process : function(v,row){
                if(row.customPrice == "1"){
                    return "<span class='blue' title='自定义价格'>" + moneyFormat2(v) + "</span>";
                }else{
                    return moneyFormat2(v);
                }
			}
		}, {
			name : 'numberOne',
			display : '数量1'
		}, {
			name : 'numberTwo',
			display : '数量2'
		}, {
			name : 'amount',
			display : '金额',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			name : 'budgetType',
			display : '费用属性',
			process : function(v){
				switch(v){
					case 'budgetField' : return '<span class="blue">现场预算</span>';break;
					case 'budgetPerson' : return '<span class="green">人力预算</span>';break;
					case 'budgetOutsourcing' : return '<span style="color:gray">外包预算</span>';break;
					case 'budgetOther' : return '其他预算';break;
				}
			}
		}, {
			name : 'remark',
			display : '备注'
		}]
	});
}

//项目预算渲染
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
			display : '设备类型'
		}, {
			name : 'resourceName',
			display : '设备名称',
			process : function(v,row){
				if(row.isChanging == "0"){
					return v;
				}else{
					if(row.changeAction == 'add'){
						return "<img src='images/new.gif' title='新增'/><span class='red'>"+v+"</span>";
					}else if(row.changeAction == 'edit'){
						return "<img src='images/changeedit.gif' title='变更'/><span class='red'>"+v+"</span>";
					}else if(row.changeAction == 'delete'){
						return "<span class='red' style='text-decoration:line-through;' title='删除'>"+v+"</span>";
					}
				}
			},
			width : 200
		}, {
			name : 'number',
			display : '数量'
		}, {
			name : 'planBeginDate',
			display : '预计借出日期'
		}, {
			name : 'planEndDate',
			display : '预计归还日期'
		}, {
			name : 'useDays',
			display : '使用天数'
		}, {
			name : 'unit',
			display : '单位'
		}, {
			name : 'price',
			display : '单设备折价',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			name : 'amount',
			display : '金额',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			name : 'remark',
			display : '备注'
		}]
	});
}