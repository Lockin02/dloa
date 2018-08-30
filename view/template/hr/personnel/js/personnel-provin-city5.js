$(document).ready(function() {
	var provinceUrl = "?model=system_procity_province&action=pageJson"; // 获取省的URL
	var cityUrl = "?model=system_procity_city&action=pageJson"; // 获取市的URL
	$('#homeAddressProId').append($("<option value=''>").html("请选择省份"));
	$('#homeAddressCityId').append($("<option value=''>").html("请选择城市"));
	/* 获取省的方法 */
	function getProvinces(data) {
		var o = eval("(" + data + ")");
		var provinceArr = o.collection;
		for (var i = 0, l = provinceArr.length; i < l; i++) {
			var province = provinceArr[i];
			var option = $("<option title='" + province.provinceName + "'>").val(province.id).html(province.provinceName);
			$('#homeAddressProId').append(option)
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
			$('#homeAddressCityId').append(option);
		}
	}

	// 省的选择改变，获取市
	$('#homeAddressProId').change(function() {
		$('#homeAddressPro').val($(this).find("option:selected").text());
		var provinceId = $(this).val();
		if (provinceId == "") { // 判断是否选择了省份，如果没有选中，刚省份名称为空 add by suxc
			$('#homeAddressPro').val("");
			$('#homeAddressCityId').children().remove("option[value!='']");
			$('#homeAddressCity').val("");
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
					$('#homeAddressCityId').children()
							.remove("option[value!='']");
					getCitys(data);
					$('#homeAddressCity').val("");
					if ($('#homeAddressCityId').attr('val')) {
						// $('#homeAddressCityId').val($('#homeAddressCityId').attr('val'));
						$("#homeAddressCityId option[title='" + $('#homeAddressCityId').attr('val') + "']").attr("selected", true);
						$('#homeAddressCityId').trigger('change');
					}
				}
			});
		}
	});

	$('#homeAddressCityId').change(function() {
		$('#homeAddressCity').val("");
		if ($(this).val() == "") { // 判断是否选择了城市 add by suxc 2011-08-22
			$('#homeAddressCity').val("");
		} else {
			$('#homeAddressCity').val($(this).find("option:selected").text());
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
			var homeAddressProIdObj = $('#homeAddressProId');
			if (homeAddressProIdObj.attr('val')) {
				homeAddressProIdObj.val(homeAddressProIdObj.attr('val'));
				$("#homeAddressProId option[title='"+$('#homeAddressProId').attr('val')+"']").attr("selected", true);
				homeAddressProIdObj.trigger('change');
			}
			//初始化城市信息
			var homeAddressCityIdObj = $('#homeAddressCityId');
			if (homeAddressCityIdObj.attr('val')) {
				homeAddressCityIdObj.val(homeAddressCityIdObj.attr('val'));
				$("#homeAddressCityId option[title='"+$('#homeAddressCityId').attr('val')+"']").attr("selected", true);
			}
		}
	});

});