$(document).ready(function() {
	var countryUrl = "?model=system_procity_country&action=pageJson";// ��ȡ���ҵ�URL
	var provinceUrl = "?model=system_procity_province&action=pageJson&sort=sequence"; // ��ȡʡ��URL
	var cityUrl = "?model=system_procity_city&action=pageJson"; // ��ȡ�е�URL
	$('#country').append($("<option value=''>").html("��ѡ�����"));
	$('#province').append($("<option value=''>").html("��ѡ��ʡ��"));
	$('#city').append($("<option value=''>").html("��ѡ�����"));

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
			var option = $("<option title='" + province.provinceName + "'>").val(province.id).text(province.provinceName);
			$('#province').append(option)
		}
	}

	/* ��ȡ�еķ��� */
	function getCitys(data) {
		var o = eval("(" + data + ")");
		var cityArr = o.collection;
		for (var i = 0, l = cityArr.length; i < l; i++) {
			var city = cityArr[i];
			var option = $("<option title='" + city.cityName + "'>").val(city.id).html(city.cityName);
			$('#city').append(option);
		}
	}

	// ʡ��ѡ��ı䣬��ȡ��
	$('#province').change(function() {
		$('#provinceName').val($(this).find("option:selected").text());
		var provinceId = $(this).val();
		if(provinceId == "") { //�ж��Ƿ�ѡ����ʡ�ݣ����û��ѡ�У���ʡ������Ϊ��
			$('#provinceName').val("");
			$('#city').children().remove("option[value!='']");
			$('#cityName').val("");
			//CDMA�Ŷ�
//			$("#usePlace").parent().hide().prev().hide();
		} else {
			$.ajax({
			    type : 'POST',
			    url : cityUrl,
			    data:{
			       provinceId : provinceId,
				   pageSize : 999
			    },
			    async: false,
			    success : function(data){
					$('#city').children().remove("option[value!='']");
					getCitys(data);
					$('#cityName').val("");
					if ($('#city').attr('val')) {
						$("#city option[title='" + $('#city').attr('val')+"']").attr("selected" ,true);
						$('#city').trigger('change');
					}
			    }
			});
			//CDMA�Ŷ�
//			if (provinceId == 43) {
//				$("#usePlace").trigger('blur').parent().show().prev().show();
//			} else {
//				$("#usePlace").parent().hide().prev().hide();
//			}
		}
	});

	$('#city').change(function() {
		$('#cityName').val("");
		if($(this).val() == "") {
			$('#cityName').val("");
		} else {
			$('#cityName').val($(this).find("option:selected").text());
		}
	});

	//��ȡʡ��
	$.ajax({
		type : 'POST',
		url : provinceUrl,
		data:{
			countryId : 1,
			pageSize : 999
		},
		async: false,
		success : function(data){
			$('#province').children().remove("option[value!='']");
			$('#city').children().remove("option[value!='']");
			getProvinces(data);
			$('#provinceName').val("");
			$('#cityName').val("");
			if ($('#province').attr('val')) {
				$("#province option[title='" + $('#province').attr('val')+"']").attr("selected", true);
				$('#province').trigger('change');
			}
		}
	});
});