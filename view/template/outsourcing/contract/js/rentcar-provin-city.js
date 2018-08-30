$(document).ready(function() {
	var countryUrl = "?model=system_procity_country&action=pageJson";// ��ȡ���ҵ�URL
	var provinceUrl = "?model=system_procity_province&action=pageJson&sort=sequence"; // ��ȡʡ��URL
	var cityUrl = "?model=system_procity_city&action=pageJson"; // ��ȡ�е�URL
	$('#country').append($("<option value=''>").html("��ѡ�����"));
	$('#companyProvinceCode').append($("<option value=''>").html("��ѡ��ʡ��"));
	$('#companyCityCode').append($("<option value=''>").html("��ѡ�����"));
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
			var option = $("<option title='"+province.id+"'>").val(province.provinceCode)
					.text(province.provinceName);
			$('#companyProvinceCode').append(option)
		}
	}

	/* ��ȡ�еķ��� */
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

	// ʡ��ѡ��ı䣬��ȡ��
	$('#companyProvinceCode').change(function() {
		$('#companyProvince').val($(this).find("option:selected").text());
		var provinceCode = $(this).val();
		if(provinceCode==""){    //�ж��Ƿ�ѡ����ʡ�ݣ����û��ѡ�У���ʡ������Ϊ��
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
		if($(this).val()==""){   //�ж��Ƿ�ѡ���˳���  add by suxc 2011-08-22
			$('#companyCity').val("");
		}else{
			$('#companyCity').val($(this).find("option:selected").text());
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