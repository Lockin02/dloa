$(document).ready(function() {
	var provinceUrl = "?model=system_procity_province&action=pageJson"; // ��ȡʡ��URL
	var cityUrl = "?model=system_procity_city&action=pageJson"; // ��ȡ�е�URL
	$('#nowPlaceProId').append($("<option value=''>").html("��ѡ��ʡ��"));
	$('#nowPlaceCityId').append($("<option value=''>").html("��ѡ�����"));
	/* ��ȡʡ�ķ��� */
	function getProvinces(data) {
		var o = eval("(" + data + ")");
		var provinceArr = o.collection;
		for (var i = 0, l = provinceArr.length; i < l; i++) {
			var province = provinceArr[i];
			var option = $("<option title='" + province.provinceName + "'>").val(province.id).html(province.provinceName);
			$('#nowPlaceProId').append(option)
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
			$('#nowPlaceCityId').append(option);
		}
	}

	// ʡ��ѡ��ı䣬��ȡ��
	$('#nowPlaceProId').change(function() {
		$('#nowPlacePro').val($(this).find("option:selected").text());
		var provinceId = $(this).val();
		if (provinceId == "") { // �ж��Ƿ�ѡ����ʡ�ݣ����û��ѡ�У���ʡ������Ϊ�� add by suxc
			$('#nowPlacePro').val("");
			$('#nowPlaceCityId').children().remove("option[value!='']");
			$('#nowPlaceCity').val("");
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
					$('#nowPlaceCityId').children()
							.remove("option[value!='']");
					getCitys(data);
					$('#nowPlaceCity').val("");
					if ($('#nowPlaceCityId').attr('val')) {
						// $('#nowPlaceCityId').val($('#nowPlaceCityId').attr('val'));
						$("#nowPlaceCityId option[title='" + $('#nowPlaceCityId').attr('val') + "']").attr("selected", true);
						$('#nowPlaceCityId').trigger('change');
					}
				}
			});
		}
	});

	$('#nowPlaceCityId').change(function() {
		$('#nowPlaceCity').val("");
		if ($(this).val() == "") { // �ж��Ƿ�ѡ���˳��� add by suxc 2011-08-22
			$('#nowPlaceCity').val("");
		} else {
			$('#nowPlaceCity').val($(this).find("option:selected").text());
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
			var nowPlaceProIdObj = $('#nowPlaceProId');
			if (nowPlaceProIdObj.attr('val')) {
				nowPlaceProIdObj.val(nowPlaceProIdObj.attr('val'));
				$("#nowPlaceProId option[title='"+$('#nowPlaceProId').attr('val')+"']").attr("selected", true);
				nowPlaceProIdObj.trigger('change');
			}
			//��ʼ��������Ϣ
			var nowPlaceCityIdObj = $('#nowPlaceCityId');
			if (nowPlaceCityIdObj.attr('val')) {
				nowPlaceCityIdObj.val(nowPlaceCityIdObj.attr('val'));
				$("#nowPlaceCityId option[title='"+$('#nowPlaceCityId').attr('val')+"']").attr("selected", true);
			}
		}
	});

});