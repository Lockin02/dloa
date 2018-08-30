$(document).ready(function() {
	var provinceUrl = "?model=system_procity_province&action=pageJson"; // ��ȡʡ��URL
	var cityUrl = "?model=system_procity_city&action=pageJson"; // ��ȡ�е�URL
	$('#homeAddressProId').append($("<option value=''>").html("��ѡ��ʡ��"));
	$('#homeAddressCityId').append($("<option value=''>").html("��ѡ�����"));
	/* ��ȡʡ�ķ��� */
	function getProvinces(data) {
		var o = eval("(" + data + ")");
		var provinceArr = o.collection;
		for (var i = 0, l = provinceArr.length; i < l; i++) {
			var province = provinceArr[i];
			var option = $("<option title='" + province.provinceName + "'>").val(province.id).html(province.provinceName);
			$('#homeAddressProId').append(option)
		}
	}

	/* ��ȡ�еķ��� */
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

	// ʡ��ѡ��ı䣬��ȡ��
	$('#homeAddressProId').change(function() {
		$('#homeAddressPro').val($(this).find("option:selected").text());
		var provinceId = $(this).val();
		if (provinceId == "") { // �ж��Ƿ�ѡ����ʡ�ݣ����û��ѡ�У���ʡ������Ϊ�� add by suxc
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
		if ($(this).val() == "") { // �ж��Ƿ�ѡ���˳��� add by suxc 2011-08-22
			$('#homeAddressCity').val("");
		} else {
			$('#homeAddressCity').val($(this).find("option:selected").text());
		}
	});

	// ��ȡʡ��
	$.ajax({
		type : 'POST',
		url : provinceUrl,
		data : {
			countryId : 1,
			pageSize : 999
		},
		async : false,
		success : function(data) {
			//��ʼ��ʡ����Ϣ
			getProvinces(data);
			var homeAddressProIdObj = $('#homeAddressProId');
			if (homeAddressProIdObj.attr('val')) {
				homeAddressProIdObj.val(homeAddressProIdObj.attr('val'));
				$("#homeAddressProId option[title='"+$('#homeAddressProId').attr('val')+"']").attr("selected", true);
				homeAddressProIdObj.trigger('change');
			}
			//��ʼ��������Ϣ
			var homeAddressCityIdObj = $('#homeAddressCityId');
			if (homeAddressCityIdObj.attr('val')) {
				homeAddressCityIdObj.val(homeAddressCityIdObj.attr('val'));
				$("#homeAddressCityId option[title='"+$('#homeAddressCityId').attr('val')+"']").attr("selected", true);
			}
		}
	});

});