$(document).ready(function() {
	var provinceUrl = "?model=system_procity_province&action=pageJson"; // ��ȡʡ��URL
	var cityUrl = "?model=system_procity_city&action=pageJson"; // ��ȡ�е�URL
	$('#eprovinceId').append($("<option value=''>").html("��ѡ��ʡ��"));
	$('#ecityId').append($("<option value=''>").html("��ѡ�����"));
	/* ��ȡʡ�ķ��� */
	function getProvinces(data) {
		var o = eval("(" + data + ")");
		var provinceArr = o.collection;
		for (var i = 0, l = provinceArr.length; i < l; i++) {
			var province = provinceArr[i];
			var option = $("<option title='" + province.provinceName + "'>").val(province.id).html(province.provinceName);
			$('#eprovinceId').append(option)
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
			$('#ecityId').append(option);
		}
	}

	// ʡ��ѡ��ı䣬��ȡ��
	$('#eprovinceId').change(function() {
		$('#eprovince').val($(this).find("option:selected").text());
		var provinceId = $(this).val();
		if (provinceId == "") { // �ж��Ƿ�ѡ����ʡ�ݣ����û��ѡ�У���ʡ������Ϊ�� add by suxc
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
		if ($(this).val() == "") { // �ж��Ƿ�ѡ���˳��� add by suxc 2011-08-22
			$('#ecity').val("");
		} else {
			$('#ecity').val($(this).find("option:selected").text());
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
			var eprovinceIdObj = $('#eprovinceId');
			if (eprovinceIdObj.attr('val')) {
				eprovinceIdObj.val(eprovinceIdObj.attr('val'));
				eprovinceIdObj.trigger('change');
			}
			//��ʼ��������Ϣ
			var ecityIdObj = $('#ecityId');
			if (ecityIdObj.attr('val')) {
				ecityIdObj.val(ecityIdObj.attr('val'));
			}
		}
	});

});