$(document).ready(function() {
	var countryUrl = "?model=system_procity_country&action=pageJson";// ��ȡ���ҵ�URL
	var provinceUrl = "?model=system_procity_province&action=pageJson"; // ��ȡʡ��URL
	var cityUrl = "?model=system_procity_city&action=pageJson"; // ��ȡ�е�URL
    $('#country5').append($("<option value=''>").html("��ѡ�����"));
	$('#province5').append($("<option value=''>").html("��ѡ��ʡ��"));
	$('#city5').append($("<option value=''>").html("��ѡ�����"));
	/* ��ȡ���ҵķ��� */
	function getCountrys(data) {
		var o = eval("(" + data + ")");
		var countryArr = o.collection;
		for (var i = 0, l = countryArr.length; i < l; i++) {
			var country = countryArr[i];
			var option = $("<option>").val(country.id)
					.html(country.countryName);
			$('#country5').append(option);
		}
	}
	/* ��ȡʡ�ķ��� */
	function getProvinces(data) {
		var o = eval("(" + data + ")");
		var provinceArr = o.collection;
		for (var i = 0, l = provinceArr.length; i < l; i++) {
			var province = provinceArr[i];
			var option = $("<option title='"+province.provinceName+"'>").val(province.id)
					.html(province.provinceName);
			$('#province5').append(option)
		}
	}

	/* ��ȡ�еķ��� */
	function getCitys(data) {
		// $('#city').html("");
		var o = eval("(" + data + ")");
		var cityArr = o.collection;
		for (var i = 0, l = cityArr.length; i < l; i++) {
			var city = cityArr[i];
			var option = $("<option title='"+city.cityName+"'>").val(city.id).html(city.cityName);
			$('#city5').append(option);
		}
	}

	// ʡ��ѡ��ı䣬��ȡ��
	$('#province5').change(function() {
				$('#provinceName5').val($(this).find("option:selected").text());
				var provinceId = $(this).val();
				if(provinceId==""){    //�ж��Ƿ�ѡ����ʡ�ݣ����û��ѡ�У���ʡ������Ϊ��   add by suxc 4011-08-22
					$('#provinceName5').val("");
					$('#city5').children().remove("option[value!='']");
					$('#cityName5').val("");
				}else{
					$.ajax({
					    type : 'POST',
					    url : cityUrl,
					    data:{
					       provinceId : provinceId,
						   pageSize : 999
					    },
					    async: false,
					    success : function(data){
								$('#city5').children().remove("option[value!='']");
								getCitys(data);
								$('#cityName5').val("");
								if ($('#city5').attr('val')) {
//									$('#city5').val($('#city5').attr('val'));
									$("#city5 option[title='"+$('#city5').attr('val')+"']").attr("selected", true);
									$('#city5').trigger('change');
								}
					    }
					});
				}
			});

	$('#city5').change(function() {
		$('#cityName5').val("");
		if($(this).val()==""){   //�ж��Ƿ�ѡ���˳���  add by suxc 2011-08-22
			$('#cityName5').val("");
		}else{
			$('#cityName5').val($(this).find("option:selected").text());
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
							$('#province5').children()
									.remove("option[value!='']");
							$('#city5').children().remove("option[value!='']");
							getProvinces(data);
							$('#provinceName5').val("");
							$('#cityName5').val("");
							if ($('#province5').attr('val')) {
//								$('#province5').val($('#province5').attr('val'));
								$("#province5 option[title='"+$('#province5').attr('val')+"']").attr("selected", true);
								$('#province5').trigger('change');
							}
						}
					});


});