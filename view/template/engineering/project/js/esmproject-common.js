//����������رղ���֤
function timeCheck($t){
	var startDate = $('#planBeginDate').val();
	var endDate = $('#planEndDate').val();
	if(startDate == "" || endDate == ""){
		return false;
	}
	var s = DateDiff(startDate,endDate) + 1;
	if(s < 0) {
		alert("Ԥ�ƿ�ʼ���ܱ�Ԥ�ƽ���ʱ����");
		$t.value = "";
		return false;
	}
	$("#expectedDuration").val(s);
}

//������Ŀ��Ԥ��
function countBudgetAll(){
	var budgetAll = accAdd($("#budgetField").val(),$("#budgetOutsourcing").val(),2);
	budgetAll = accAdd(budgetAll,$("#budgetPerson").val(),2);
	budgetAll = accAdd(budgetAll,$("#budgetEqu").val(),2);
	budgetAll = accAdd(budgetAll,$("#budgetOther").val(),2);

	setMoney('budgetAll',budgetAll);
}

//������Ŀ�ܷ���
function countFeeAll(){
	var feeAll = accAdd($("#feeField").val(),$("#feeOther").val(),2);

	setMoney('feeAll',feeAll);
	//������ý���
	countProcess();
}

//������ý���
function countProcess(){
	//��ȡԤ��
	var budgetAll = $("#budgetAll").val();
	if(budgetAll == 0){
		$("#feeAllProcess").val(0);
	}else{
		var feeAllProcess = accMul(accDiv($("#feeAll").val(),budgetAll,4),100,2);
		$("#feeAllProcess").val(feeAllProcess);
	}

	//��ȡԤ��
	var budgetField = $("#budgetField").val();
	if(budgetField == 0){
		$("#feeFieldProcess").val(0);
	}else{
		var feeFieldProcess = accMul(accDiv($("#feeField").val(),budgetField,4),100,2);
		$("#feeFieldProcess").val(feeFieldProcess);
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
				$('#province').val(thisProvince).trigger('change');
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

//�����б������ʾ
function formatProgress(value){
    if (value){
        var projectProcess = $("#projectProcess").val();

        // �����ж� ֵ����100��100�ǣ�С��0��0��
        var present = value > 100 ? 100 : value;
        present = present < 0 ? 0 : present;

        // ��ֵ���߱���Ŀ���ȵ�ʱ��ʾ��ɫ
        var red = accSub(projectProcess, value, 2) < 0 || value < 0 ? "" : "";

        // ��������Ⱦ
        return '<div class="Bar" style="width: 110px;"><div style="width: ' + present + '%;' + red + '">' +
            '<span>' + value + ' %</span>' +
        '</div></div>';
    } else {
        return '';
    }
}

//�����б������ʾ
function formatProgress2(value){
    if (value){
        // �����ж� ֵ����100��100�ǣ�С��0��0��
        var present = value > 100 ? 100 : value;
        present = present < 0 ? 0 : present;

        // ��ֵ���߱���Ŀ���ȵ�ʱ��ʾ��ɫ
        var red = "";

        // ��������Ⱦ
        return '<div class="Bar2" style="width: 110px;"><div style="width: ' + present + '%;' + red + '">' +
            '<span>' + value + ' %</span>' +
        '</div></div>';
    } else {
        return '';
    }
}

// �Ƿ���ʾ����
function initShowProcess(key) {
	if ($("#" + key).val() * 1 == 0) {
		$("#" + key + "None").show();
	} else {
		$("#" + key + "Show").show();
	}
}

var updateOfficeInfo = function(elm){
	var exeArea = $(elm).val();// ִ������
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

// �����Ŀ����ռ�ȵ�ֵ�Ƿ��ڿ��Χ��
var chkRateIsAvalible = function(obj,type){
	var estimateRate = $(obj).val();
	var estimateOrgRate = $(obj).attr("data-orgval");
	estimateOrgRate = (estimateOrgRate == undefined || estimateOrgRate == '')? 0 : estimateOrgRate;
	if(isNaN(estimateRate) || estimateRate < 0 || estimateRate > 100){
		if(type == "pk"){
			alert("�ɱ�ռ�ȱ���Ϊ0��100�����ֵ!");
		}else{
			alert("����ռ�ȱ���Ϊ0��100�����ֵ!");
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
			alert("ͬһ��ͬ�ĸ�����Ŀռ���ۼƲ��ܳ�100%,����������Ŀ��ռ��!");
			$(obj).val(estimateOrgRate);
		}
	}
}

$(function(){
	updateOfficeInfo("#productLine");
})