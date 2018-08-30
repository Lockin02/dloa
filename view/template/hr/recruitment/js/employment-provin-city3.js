$(document).ready(function() {
	var countryUrl = "?model=system_procity_country&action=pageJson";// ��ȡ���ҵ�URL
	var provinceUrl = "?model=system_procity_province&action=pageJson"; // ��ȡʡ��URL
	var cityUrl = "?model=system_procity_city&action=pageJson"; // ��ȡ�е�URL
    $('#country3').append($("<option value=''>").html("��ѡ�����"));
	$('#province3').append($("<option value=''>").html("��ѡ��ʡ��"));
	$('#city3').append($("<option value=''>").html("��ѡ�����"));
	/* ��ȡ���ҵķ��� */
	function getCountrys(data) {
		var o = eval("(" + data + ")");
		var countryArr = o.collection;
		for (var i = 0, l = countryArr.length; i < l; i++) {
			var country = countryArr[i];
			var option = $("<option>").val(country.id)
					.html(country.countryName);
			$('#country3').append(option);
		}
	}
	/* ��ȡʡ�ķ��� */
	function getProvinces(data) {
		var o = eval("(" + data + ")");
		var provinceArr = o.collection;
		for (var i = 0, l = provinceArr.length; i < l; i++) {
			var province = provinceArr[i];
			var option = $("<option title='"+province.provinceName+"'>").val(province.id)
					.text(province.provinceName);
			$('#province3').append(option)
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
			$('#city3').append(option);
		}
	}

	// ʡ��ѡ��ı䣬��ȡ��
	$('#province3').change(function() {
				$('#provinceName3').val($(this).find("option:selected").text());
				var provinceId = $(this).val();
				if(provinceId==""){    //�ж��Ƿ�ѡ����ʡ�ݣ����û��ѡ�У���ʡ������Ϊ��   add by suxc 2011-08-22
					$('#provinceName3').val("");
					$('#city3').children().remove("option[value!='']");
					$('#cityName3').val("");
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
								$('#city3').children().remove("option[value!='']");
								getCitys(data);
								$('#cityName3').val("");
								if ($('#city3').attr('val')) {
//									$('#city').val($('#city').attr('val'));
									$("#city3 option[title='"+$('#city').attr('val')+"']").attr("selected", true);
									$('#city3').trigger('change');
									if(!$('#province4').val()) {
										$("#province4").val($("#province3").val());
										$("#provinceName4").val($("#provinceName3").val());
										$('#province4').change();
									}
									if(!$('#province5').val()) {
										$("#province5").val($("#province3").val());
										$("#provinceName5").val($("#provinceName3").val());
										$('#province5').change();
									}
								}
					    }
					});
				}
			});

	$('#city3').change(function() {
		$('#cityName3').val("");
		if($(this).val()==""){   //�ж��Ƿ�ѡ���˳���  add by suxc 2011-08-22
			$('#cityName3').val("");
		}else{
			$('#cityName3').val($(this).find("option:selected").text());
		}
		if(!$('#city4').val()) {
			$("#city4").val($("#city3").val());
			$("#cityName4").val($("#cityName3").val());
		}
		if(!$('#city5').val()) {
			$("#city5").val($("#city3").val());
			$("#cityName5").val($("#cityName3").val());
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
							$('#province3').children()
									.remove("option[value!='']");
							$('#city3').children().remove("option[value!='']");
							getProvinces(data);
							$('#provinceName3').val("");
							$('#cityName3').val("");
							if ($('#province3').attr('val')) {
//									alert($('#province').attr('val'));
//								$('#province').val($('#province').attr('val'));
								$("#province3 option[title='"+$('#province').attr('val')+"']").attr("selected", true);
								$('#province3').trigger('change');
							}
						}
					});

	$('#nowAddress').change(function() {
		if(!$('#homeAddress').val().trim()) {
			$("#homeAddress").val($("#nowAddress").val());
		}
		if(!$('#appointAddress').val().trim()) {
			$("#appointAddress").val($("#nowAddress").val());
		}
	});

	$('#nowPost').change(function() {
		if(!$('#homePost').val().trim()) {
			$("#homePost").val($("#nowPost").val());
		}
		if(!$('#appointPost').val().trim()) {
			$("#appointPost").val($("#nowPost").val());
		}
	});

});