$(document).ready(function() {
	var countryUrl = "?model=system_procity_country&action=pageJson";// 获取国家的URL
	var provinceUrl = "?model=system_procity_province&action=pageJson"; // 获取省的URL
	var cityUrl = "?model=system_procity_city&action=pageJson"; // 获取市的URL
	$('#country').append($("<option value=''>").html("请选择国家"));
	$('#province').append($("<option value=''>").html("请选择省份"));
	$('#city').append($("<option value=''>").html("请选择城市"));
	/* 获取国家的方法 */
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
	/* 获取省的方法 */
	function getProvinces(data) {
		var o = eval("(" + data + ")");
		var provinceArr = o.collection;
		for (var i = 0, l = provinceArr.length; i < l; i++) {
			var province = provinceArr[i];
			var option = $("<option title='"+province.provinceName+"'>").val(province.id)
					.text(province.provinceName);
			$('#province').append(option)
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
			$('#city').append(option);
		}
	}

	// 省的选择改变，获取市
	$('#province').change(function() {
		$('#provinceName').val($(this).find("option:selected").text());
		var provinceId = $(this).val();
		if(provinceId==""){    //判断是否选择了省份，如果没有选中，刚省份名称为空   add by suxc 2011-08-22
			$('#provinceName').val("");
			$('#city').children().remove("option[value!='']");
			$('#cityName').val("");
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
					$('#city').children().remove("option[value!='']");
					getCitys(data);
					$('#cityName').val("");
					if ($('#city').attr('val')) {
//						$('#city').val($('#city').attr('val'));
						$("#city option[title='"+$('#city').attr('val')+"']").attr("selected", true);
						$('#city').trigger('change');
					}
			    }
			});
		}
	});

	$('#city').change(function() {
		$('#cityName').val("");
		if($(this).val()==""){   //判断是否选择了城市  add by suxc 2011-08-22
			$('#cityName').val("");
		}else{
			$('#cityName').val($(this).find("option:selected").text());
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
			$('#province').children()
					.remove("option[value!='']");
			$('#city').children().remove("option[value!='']");
			getProvinces(data);
			$('#provinceName').val("");
			$('#cityName').val("");
			if ($('#province').attr('val')) {
//				$('#province').val($('#province').attr('val'));
				$("#province option[title='"+$('#province').attr('val')+"']").attr("selected", true);
				$('#province').trigger('change');
			}
		}
	});


});