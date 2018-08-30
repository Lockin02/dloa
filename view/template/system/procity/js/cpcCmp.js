$(document).ready(function() {
	var countryUrl = "?model=system_procity_country&action=pageJson";// ��ȡ���ҵ�URL
	var provinceUrl = "?model=system_procity_province&action=pageJson"; // ��ȡʡ��URL
	var cityUrl = "?model=system_procity_city&action=pageJson"; // ��ȡ�е�URL

	/* ��ȡ���ҵķ��� */
	function getCountrys(data) {
		var o = eval("(" + data + ")");
		var countryArr = o.collection;
		for (var i = 0, l = countryArr.length; i < l; i++) {
			var country = countryArr[i];
			var option = $("<option>").val(country.id)
					.html(country.countryName);
			$('#country').append(option);
		}
	}
	/* ��ȡʡ�ķ��� */
	function getProvinces(data) {
		var o = eval("(" + data + ")");
		var provinceArr = o.collection;
		for (var i = 0, l = provinceArr.length; i < l; i++) {
			var province = provinceArr[i];
			var option = $("<option>").val(province.id)
					.html(province.provinceName);
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

	// ��ȡʡ
	$.post(countryUrl, {
				pageSize : 999
			}, function(data) {
				getCountrys(data);
				if ($('#country').attr('val')) {
					$('#country').val($('#country').attr('val'));
				}else{
					$('#country').val(1);
					//$('#countryName').val('�й�');
				}
				$('#country').trigger('change');
			});

	$('#country').change(function() {
				$('#countryName').val($(this).find("option:selected").text());
				var countryId = $(this).val();
				if(countryId==""){
					$('#provinceName').val("");
					$('#cityName').val("");
				}
				$.post(provinceUrl, {
							countryId : countryId,
							pageSize : 999
						}, function(data) {
							$('#province').children()
									.remove("option[value!='']");
							$('#city').children().remove("option[value!='']");
							getProvinces(data);
							$('#provinceName').val("");
							$('#cityName').val("");
							if ($('#province').attr('val')) {
								$('#province').val($('#province').attr('val'));
								$('#province').trigger('change');
							}
						});
			});

	// ʡ��ѡ��ı䣬��ȡ��
	$('#province').change(function() {
				$('#provinceName').val($(this).find("option:selected").text());
				var provinceId = $(this).val();
				if(provinceId==""){    //�ж��Ƿ�ѡ����ʡ�ݣ����û��ѡ�У���ʡ������Ϊ��   add by suxc 2011-08-22
					$('#provinceName').val("");
					$('#city').children().remove("option[value!='']");
					$('#cityName').val("");
				}else{
					$.post(cityUrl, {
								provinceId : provinceId,
								pageSize : 999
							}, function(data) {
								$('#city').children().remove("option[value!='']");
								getCitys(data);
								$('#cityName').val("");
								if ($('#city').attr('val')) {
									$('#city').val($('#city').attr('val'));
									$('#city').trigger('change');
								}
							});
				}
			});

	$('#city').change(function() {
//		alert($(this).find("option:selected").text())
		$('#cityName').val("");
		if($(this).val()==""){   //�ж��Ƿ�ѡ���˳���  add by suxc 2011-08-22
			$('#cityName').val("");
		}else{
			$('#cityName').val($(this).find("option:selected").text());
		}
			});
	$('#country').append($("<option value=''>").html("��ѡ�����"));
	$('#province').append($("<option value=''>").html("��ѡ��ʡ��"));
	$('#city').append($("<option value=''>").html("��ѡ�����"));

});