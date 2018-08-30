$(document).ready(function() {
	var countryUrl = "?model=system_procity_country&action=pageJson";// 获取国家的URL
	var provinceUrl = "?model=system_procity_province&action=pageJson&sort=sequence"; // 获取省的URL
	var cityUrl = "?model=system_procity_city&action=pageJson"; // 获取市的URL
	$('#country').append($("<option value=''>").html("请选择国家"));
	$('#companyProvinceCode').append($("<option value=''>").html("请选择省份"));
	$('#companyCityCode').append($("<option value=''>").html("请选择城市"));
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
			var option = $("<option title='"+province.id+"'>").val(province.provinceCode)
					.text(province.provinceName);
			$('#companyProvinceCode').append(option)
		}
	}

	/* 获取市的方法 */
	function getCitys(data) {
		// $('#city').html("");
		var o = eval("(" + data + ")");
		var cityArr = o.collection;
		for (var i = 0, l = cityArr.length; i < l; i++) {
			var city = cityArr[i];
			var option = $("<option title='"+city.id+"'>").val(city.cityCode).html(city.cityName);
			$('#companyCityCode').append(option);
		}
	}

	// 省的选择改变，获取市
	$('#companyProvinceCode').change(function() {
		$('#companyProvince').val($(this).find("option:selected").text());
		var provinceCode = $(this).val();
		if(provinceCode==""){    //判断是否选择了省份，如果没有选中，刚省份名称为空
			$('#companyProvince').val("");
			$('#companyCityCode').children().remove("option[value!='']");
			$('#companyCity').val("");
		}else{
			$.ajax({
			    type : 'POST',
			    url : cityUrl,
			    data:{
			       provinceId : $(this).find("option:selected").attr('title'),
				   pageSize : 999
			    },
			    async: false,
			    success : function(data){
					$('#companyCityCode').children().remove("option[value!='']");
					getCitys(data);
					$('#companyCity').val("");
					if ($('#companyCityCode').attr('val')) {
						$("#companyCityCode option[title='"+$('#companyCityCode').attr('val')+"']").attr("selected", true);
						$('#companyCityCode').trigger('change');
					}
			    }
			});
		}
	});

	$('#companyCityCode').change(function() {
		$('#companyCity').val("");
		if($(this).val()==""){   //判断是否选择了城市  add by suxc 2011-08-22
			$('#companyCity').val("");
		}else{
			$('#companyCity').val($(this).find("option:selected").text());
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
			$('#companyProvinceCode').children()
					.remove("option[value!='']");
			$('#companyCityCode').children().remove("option[value!='']");
			getProvinces(data);
			$('#companyProvince').val("");
			$('#companyCity').val("");
			if ($('#companyProvinceCode').attr('val')) {
				$("#province option[title='"+$('#companyProvinceCode').attr('val')+"']").attr("selected", true);
				$('#companyProvinceCode').trigger('change');
			}
		}
	});


});