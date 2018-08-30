

$(document).ready(function() {

	var provinceUrl = "index1.php?model=system_procity_province&action=pageJson"; // ��ȡʡ��URL
	var cityUrl = "index1.php?model=system_procity_city&action=pageJson"; // ��ȡ�е�URL

	/* ��ȡʡ�ķ��� */
	function getProvinces(data) {
		var o = eval("(" + data + ")");
		var provinceArr = o.collection;
		var option = "";
		for (var i = 0, l = provinceArr.length; i < l; i++) {
			var province = provinceArr[i];
			option += "<option value='" + province.id + "'>"
					+ province.provinceName + "</option>";
			// option +=
			// $("<option>").val(province.id).html(province.provinceName);
		}
		$('#province').append(option);
	}

	/* ��ȡ�еķ��� */
	function getCitys(data) {
		// $('#city').html("");
		var o = eval("(" + data + ")");
		var cityArr = o.collection;
		var option = "";
		$('#cityName').val(cityArr[0].cityName);
		for (var i = 0, l = cityArr.length; i < l; i++) {
			var city = cityArr[i];
			option += "<option value='" + city.id + "'>" + city.cityName
					+ "</option>";
			// var option = $("<option>").val(city.id).html(city.cityName);
		}
		$('#city').append(option);
	}

	// ��ȡʡ
	// $.post(provinceUrl, {
	// pageSize : 999
	// }, function(data) {
	// getProvinces(data);
	// });
	$.ajax({
				type : "POST",
				url : provinceUrl,
				data : {
					pageSize : 999
				},
				success : function(data) {
					getProvinces(data);
				}
			});

	// ʡ��ѡ��ı䣬��ȡ��
	$('#province').change(function() {
		var provinceId = $(this);
		var provinceName = $('#provinceName');
		$.post(cityUrl, {
					provinceId : provinceId.val()
				}, function(data) {
					if (provinceId.val() == "") {
						$('#city').html("<option value=''>��</option>");
						provinceName.val(provinceId.find("option:selected")
								.text());
					} else {
						$('#city').html("");
						getCitys(data);
						provinceName.val(provinceId.find("option:selected")
								.text());
					}
				});
	});

	$('#city').change(function() {
				var cityId = $(this);
				var cityName = $('#cityName');
				cityName.val(cityId.find("option:selected").text());
			});

});