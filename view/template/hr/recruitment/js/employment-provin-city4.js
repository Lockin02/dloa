$(document).ready(function() {
	var countryUrl = "?model=system_procity_country&action=pageJson";// 获取国家的URL
	var provinceUrl = "?model=system_procity_province&action=pageJson"; // 获取省的URL
	var cityUrl = "?model=system_procity_city&action=pageJson"; // 获取市的URL
    $('#country4').append($("<option value=''>").html("请选择国家"));
	$('#province4').append($("<option value=''>").html("请选择省份"));
	$('#city4').append($("<option value=''>").html("请选择城市"));
	/* 获取国家的方法 */
	function getCountrys(data) {
		var o = eval("(" + data + ")");
		var countryArr = o.collection;
		for (var i = 0, l = countryArr.length; i < l; i++) {
			var country = countryArr[i];
			var option = $("<option>").val(country.id)
					.html(country.countryName);
			$('#country4').append(option);
		}
	}
	/* 获取省的方法 */
	function getProvinces(data) {
		var o = eval("(" + data + ")");
		var provinceArr = o.collection;
		for (var i = 0, l = provinceArr.length; i < l; i++) {
			var province = provinceArr[i];
			var option = $("<option title='"+province.provinceName+"'>").val(province.id)
					.html(province.provinceName);
			$('#province4').append(option)
		}
	}

	/* 获取市的方法 */
	function getCitys(data) {
		// $('#city').html("");
		var o = eval("(" + data + ")");
		var cityArr = o.collection;
		for (var i = 0, l = cityArr.length; i < l; i++) {
			var city = cityArr[i];
			var option = $("<option title='"+city.cityName+"'>").val(city.id).html(city.cityName);
			$('#city4').append(option);
		}
	}

	// 省的选择改变，获取市
	$('#province4').change(function() {
				$('#provinceName4').val($(this).find("option:selected").text());
				var provinceId = $(this).val();
				if(provinceId==""){    //判断是否选择了省份，如果没有选中，刚省份名称为空   add by suxc 4011-08-22
					$('#provinceName4').val("");
					$('#city4').children().remove("option[value!='']");
					$('#cityName4').val("");
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
								$('#city4').children().remove("option[value!='']");
								getCitys(data);
								$('#cityName4').val("");
								if ($('#city4').attr('val')) {
//									$('#city4').val($('#city4').attr('val'));
									$("#city4 option[title='"+$('#city4').attr('val')+"']").attr("selected", true);
									$('#city4').trigger('change');
								}
					    }
					});
				}
			});

	$('#city4').change(function() {
		$('#cityName4').val("");
		if($(this).val()==""){   //判断是否选择了城市  add by suxc 2011-08-22
			$('#cityName4').val("");
		}else{
			$('#cityName4').val($(this).find("option:selected").text());
		}
	});

        //获取省份
                  $.ajax({
					    type : 'POST',
					    url : provinceUrl,
					    data:{
					        countryId : 1,
							pageSize : 999
					    },
					    async: false,
					    success : function(data){
							$('#province4').children()
									.remove("option[value!='']");
							$('#city4').children().remove("option[value!='']");
							getProvinces(data);
							$('#provinceName4').val("");
							$('#cityName4').val("");
							if ($('#province4').attr('val')) {
//								$('#province4').val($('#province4').attr('val'));
								$("#province4 option[title='"+$('#province4').attr('val')+"']").attr("selected", true);
								$('#province4').trigger('change');
							}
						}
					});


});