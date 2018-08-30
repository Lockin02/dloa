//启动与结束关闭差验证
function timeCheck($t){
	var startDate = $('#planBeginDate').val();
	var endDate = $('#planEndDate').val();
	if(startDate == "" || endDate == ""){
		return false;
	}
	var s = DateDiff(startDate,endDate) + 1;
	if(s < 0) {
		alert("预计开始不能比预计结束时间晚！");
		$t.value = "";
		return false;
	}
	$("#expectedDuration").val(s);
}

//计算项目总预算
function countBudgetAll(){
	var budgetAll = accAdd($("#budgetField").val(),$("#budgetOutsourcing").val(),2);
	budgetAll = accAdd(budgetAll,$("#budgetPerson").val(),2);
	budgetAll = accAdd(budgetAll,$("#budgetEqu").val(),2);
	budgetAll = accAdd(budgetAll,$("#budgetOther").val(),2);

	setMoney('budgetAll',budgetAll);
}

//计算项目总费用
function countFeeAll(){
	var feeAll = accAdd($("#feeField").val(),$("#feeOther").val(),2);

	setMoney('feeAll',feeAll);
	//计算费用进度
	countProcess();
}

//计算费用进度
function countProcess(){
	//获取预算
	var budgetAll = $("#budgetAll").val();
	if(budgetAll == 0){
		$("#feeAllProcess").val(0);
	}else{
		var feeAllProcess = accMul(accDiv($("#feeAll").val(),budgetAll,4),100,2);
		$("#feeAllProcess").val(feeAllProcess);
	}

	//获取预算
	var budgetField = $("#budgetField").val();
	if(budgetField == 0){
		$("#feeFieldProcess").val(0);
	}else{
		var feeFieldProcess = accMul(accDiv($("#feeField").val(),budgetField,4),100,2);
		$("#feeFieldProcess").val(feeFieldProcess);
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
				$('#province').val(thisProvince).trigger('change');
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

//用于列表进度显示
function formatProgress(value){
    if (value){
        var projectProcess = $("#projectProcess").val();

        // 进度判定 值大于100以100记，小于0以0计
        var present = value > 100 ? 100 : value;
        present = present < 0 ? 0 : present;

        // 负值或者比项目进度低时显示红色
        var red = accSub(projectProcess, value, 2) < 0 || value < 0 ? "" : "";

        // 进度条渲染
        return '<div class="Bar" style="width: 110px;"><div style="width: ' + present + '%;' + red + '">' +
            '<span>' + value + ' %</span>' +
        '</div></div>';
    } else {
        return '';
    }
}

//用于列表进度显示
function formatProgress2(value){
    if (value){
        // 进度判定 值大于100以100记，小于0以0计
        var present = value > 100 ? 100 : value;
        present = present < 0 ? 0 : present;

        // 负值或者比项目进度低时显示红色
        var red = "";

        // 进度条渲染
        return '<div class="Bar2" style="width: 110px;"><div style="width: ' + present + '%;' + red + '">' +
            '<span>' + value + ' %</span>' +
        '</div></div>';
    } else {
        return '';
    }
}

// 是否显示进度
function initShowProcess(key) {
	if ($("#" + key).val() * 1 == 0) {
		$("#" + key + "None").show();
	} else {
		$("#" + key + "Show").show();
	}
}

var updateOfficeInfo = function(elm){
	var exeArea = $(elm).val();// 执行区域
	var officeData = $.ajax({
		type : "POST",
		url : '?model=engineering_officeinfo_officeinfo&action=ajaxGetOfficeInfo',
		data : {
			productLine : exeArea
		},
		async : false
	}).responseText;
	if(officeData != ''){
		officeData = eval("("+officeData+")");
		$("#deptId").val(officeData.feeDeptId);
		$("#deptNameInput").val(officeData.feeDeptName);
		$("#officeId").val(officeData.officeId);
		$("#officeNameInput").val(officeData.officeName);
	}
}

// 检查项目概算占比的值是否在可填范围内
var chkRateIsAvalible = function(obj,type){
	var estimateRate = $(obj).val();
	var estimateOrgRate = $(obj).attr("data-orgval");
	estimateOrgRate = (estimateOrgRate == undefined || estimateOrgRate == '')? 0 : estimateOrgRate;
	if(isNaN(estimateRate) || estimateRate < 0 || estimateRate > 100){
		if(type == "pk"){
			alert("成本占比必须为0到100间的数值!");
		}else{
			alert("概算占比必须为0到100间的数值!");
		}
		$(obj).val(estimateOrgRate);
	}else{
		estimateRate = Number(estimateRate);
		var contractId = $("#contractId").val();
		var paramArr = {
			projectId : $("#id").val(),
			productLine: $("#newProLine").val(),
			rate : estimateRate,
			contratId : contractId,
			contractType : 'GCXMYD-01',
			chkType : type
		};
		$(obj).val(estimateRate);
		var chkResult = $.ajax({
			type : "POST",
			url : '?model=engineering_project_esmproject&action=ajaxChkEstimateRate',
			data : paramArr,
			async : false
		}).responseText;
		if(chkResult != 'ok'){
			alert("同一合同的各个项目占比累计不能超100%,请检查其他项目的占比!");
			$(obj).val(estimateOrgRate);
		}
	}
}

$(function(){
	updateOfficeInfo("#productLine");
})