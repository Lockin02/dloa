$(document).ready(function() {
	var provinceUrl = "?model=system_procity_province&action=pageJson"; // ��ȡʡ��URL
	var cityUrl = "?model=system_procity_city&action=pageJson"; // ��ȡ�е�URL
	$('#appointPro').append($("<option value=''>").html("��ѡ��ʡ��"));
	$('#appointCity').append($("<option value=''>").html("��ѡ�����"));
	/* ��ȡʡ�ķ��� */
	function getProvinces(data) {
		var o = eval("(" + data + ")");
		var provinceArr = o.collection;
		for (var i = 0, l = provinceArr.length; i < l; i++) {
			var province = provinceArr[i];
			var option = $("<option title='" + province.provinceName + "'>").val(province.id).html(province.provinceName);
			$('#appointPro').append(option)
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
			$('#appointCity').append(option);
		}
	}

	// ʡ��ѡ��ı䣬��ȡ��
	$('#appointPro').change(function() {
		$('#appointProName').val($(this).find("option:selected").text());
		var provinceId = $(this).val();
		if (provinceId == "") { // �ж��Ƿ�ѡ����ʡ�ݣ����û��ѡ�У���ʡ������Ϊ�� add by suxc
			$('#appointProName').val("");
			$('#appointCity').children().remove("option[value!='']");
			$('#appointCityName').val("");
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
					$('#appointCity').children()
							.remove("option[value!='']");
					getCitys(data);
					$('#appointCityName').val("");
					if ($('#appointCity').attr('val')) {
						// $('#appointCity').val($('#appointCity').attr('val'));
						$("#appointCity option[title='" + $('#appointCity').attr('val') + "']").attr("selected", true);
						$('#appointCity').trigger('change');
					}
				}
			});
		}
	});

	$('#appointCity').change(function() {
		$('#appointCityName').val("");
		if ($(this).val() == "") { // �ж��Ƿ�ѡ���˳��� add by suxc 2011-08-22
			$('#appointCityName').val("");
		} else {
			$('#appointCityName').val($(this).find("option:selected").text());
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
			var appointProObj = $('#appointPro');
			if (appointProObj.attr('val')) {
				appointProObj.val(appointProObj.attr('val'));
				$("#appointPro option[title='"+$('#appointPro').attr('val')+"']").attr("selected", true);
				appointProObj.trigger('change');
			}
			//��ʼ��������Ϣ
			var appointCityObj = $('#appointCity');
			if (appointCityObj.attr('val')) {
				appointCityObj.val(appointCityObj.attr('val'));
				$("#appointCity option[title='"+$('#appointCity').attr('val')+"']").attr("selected", true);
			}
		}
	});

});