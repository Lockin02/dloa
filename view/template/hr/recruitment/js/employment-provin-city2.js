$(document).ready(function() {
	var countryUrl = "?model=system_procity_country&action=pageJson";// 获取国家的URL
	var provinceUrl = "?model=system_procity_province&action=pageJson"; // 获取省的URL
	var cityUrl = "?model=system_procity_city&action=pageJson"; // 获取市的URL
    $('#country2').append($("<option value=''>").html("请选择国家"));
	$('#province2').append($("<option value=''>").html("请选择省份"));
	$('#city2').append($("<option value=''>").html("请选择城市"));
	/* 获取国家的方法 */
	function getCountrys(data) {
		var o = eval("(" + data + ")");
		var countryArr = o.collection;
		for (var i = 0, l = countryArr.length; i < l; i++) {
			var country = countryArr[i];
			var option = $("<option>").val(country.id)
					.html(country.countryName);
			$('#country2').append(option);
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
			$('#province2').append(option)
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
			$('#city2').append(option);
		}
	}

	// 省的选择改变，获取市
	$('#province2').change(function() {
				$('#provinceName2').val($(this).find("option:selected").text());
				var provinceId = $(this).val();
				if(provinceId==""){    //判断是否选择了省份，如果没有选中，刚省份名称为空   add by suxc 2011-08-22
					$('#provinceName2').val("");
					$('#city2').children().remove("option[value!='']");
					$('#cityName2').val("");
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
								$('#city2').children().remove("option[value!='']");
								getCitys(data);
								$('#cityName2').val("");
								if ($('#city2').attr('val')) {
//									$('#city2').val($('#city2').attr('val'));
									$("#city2 option[title='"+$('#city2').attr('val')+"']").attr("selected", true);
									$('#city2').trigger('change');
								}
					    }
					});
				}
			});

	$('#city2').change(function() {
		$('#cityName2').val("");
		if($(this).val()==""){   //判断是否选择了城市  add by suxc 2011-08-22
			$('#cityName2').val("");
		}else{
			$('#cityName2').val($(this).find("option:selected").text());
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
							$('#province2').children()
									.remove("option[value!='']");
							$('#city2').children().remove("option[value!='']");
							getProvinces(data);
							$('#provinceName2').val("");
							$('#cityName2').val("");
							if ($('#province2').attr('val')) {
//								$('#province2').val($('#province2').attr('val'));
								$("#province2 option[title='"+$('#province2').attr('val')+"']").attr("selected", true);
								$('#province2').trigger('change');
							}
						}
					});


});