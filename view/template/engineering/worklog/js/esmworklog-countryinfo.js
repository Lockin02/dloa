//��ʼ������
function initCity() {
	var countryUrl = "?model=system_procity_country&action=pageJson";// ��ȡ���ҵ�URL
	var provinceUrl = "?model=system_procity_province&action=pageJson"; // ��ȡʡ��URL
	var cityUrl = "?model=system_procity_city&action=pageJson"; // ��ȡ�е�URL
	$('#country').append($("<option value=''>").html("��ѡ�����"));
	$('#province').append($("<option value=''>").html("��ѡ��ʡ��"));
	$('#city').append($("<option value=''>").html("��ѡ�����"));

	// ѡ����Ҹı�ʡ
//	$('#country').change(function() {
//		$('#countryName').val($(this).find("option:selected").text());
//		var countryId = $(this).val();
//		if (countryId == "") {
//			$('#provinceName').val("");
//			$('#cityName').val("");
//		} else {
//			$.ajax({
//				type : 'POST',
//				url : provinceUrl,
//				data : {
//					countryId : countryId,
//					pageSize : 999
//				},
//				async : false,
//				success : function(data) {
//					$('#province').children()
//							.remove("option[value!='']");
//					$('#city').children().remove("option[value!='']");
//					getProvinces(data);
//					$('#provinceName').val("");
//					$('#cityName').val("");
//					if ($('#province').attr('val')) {
//						$('#province').val($('#province').attr('val'));
//						$('#province').trigger('change');
//					}
//				}
//			});
//		}
//		if ($(this).val() == 1) {
//			validate({
//				"province" : {
//					required : true
//				},
//				"city" : {
//					required : true
//				}
//			})
//		} else {
//			$('#province').removeClass("validate[required]");
//			$('#city').removeClass("validate[required]");
//		}
//	});

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
				$('#countryName').val($('#country').attr('val'));
			} else {
				$('#country').val(1);
				$('#countryName').val('�й�');
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