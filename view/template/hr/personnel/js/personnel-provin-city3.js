$(document).ready(function() {
	var provinceUrl = "?model=system_procity_province&action=pageJson"; // 获取省的URL
	var cityUrl = "?model=system_procity_city&action=pageJson"; // 获取市的URL
	$('#eprovinceId').append($("<option value=''>").html("请选择省份"));
	$('#ecityId').append($("<option value=''>").html("请选择城市"));
	/* 获取省的方法 */
	function getProvinces(data) {
		var o = eval("(" + data + ")");
		var provinceArr = o.collection;
		for (var i = 0, l = provinceArr.length; i < l; i++) {
			var province = provinceArr[i];
			var option = $("<option title='" + province.provinceName + "'>").val(province.id).html(province.provinceName);
			$('#eprovinceId').append(option)
		}
	}

	/* 获取市的方法 */
	function getCitys(data) {
		// $('#city').html("");
		var o = eval("(" + data + ")");
		var cityArr = o.collection;
		for (var i = 0, l = cityArr.length; i < l; i++) {
			var city = cityArr[i];
			var option = $("<option title='" + city.cityName + "'>").val(city.id).html(city.cityName);
			$('#ecityId').append(option);
		}
	}

	// 省的选择改变，获取市
	$('#eprovinceId').change(function() {
		$('#eprovince').val($(this).find("option:selected").text());
		var provinceId = $(this).val();
		if (provinceId == "") { // 判断是否选择了省份，如果没有选中，刚省份名称为空 add by suxc
			$('#eprovince').val("");
			$('#ecityId').children().remove("option[value!='']");
			$('#ecity').val("");
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
					$('#ecityId').children()
							.remove("option[value!='']");
					getCitys(data);
					$('#ecity').val("");
					if ($('#ecityId').attr('val')) {
						$('#ecityId').val($('#ecityId').attr('val'));
						$("#ecityId option[title='" + $('#ecityId').attr('val') + "']").attr("selected", true);
						$('#ecityId').trigger('change');
					}
				}
			});
		}
	});

	$('#ecityId').change(function() {
		$('#ecity').val("");
		if ($(this).val() == "") { // 判断是否选择了城市 add by suxc 2011-08-22
			$('#ecity').val("");
		} else {
			$('#ecity').val($(this).find("option:selected").text());
		}
	});

	// 获取省份
	$.ajax({
		type : 'POST',
		url : provinceUrl,
		data : {
			countryId : 1,
			pageSize : 999
		},
		async : false,
		success : function(data) {
			//初始化省份信息
			getProvinces(data);
			var eprovinceIdObj = $('#eprovinceId');
			if (eprovinceIdObj.attr('val')) {
				eprovinceIdObj.val(eprovinceIdObj.attr('val'));
				eprovinceIdObj.trigger('change');
			}
			//初始化城市信息
			var ecityIdObj = $('#ecityId');
			if (ecityIdObj.attr('val')) {
				ecityIdObj.val(ecityIdObj.attr('val'));
			}
		}
	});

});