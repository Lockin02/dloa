//获取省份信息
function getProvince(){
	var provinceArr;
	//缓存省份信息
	$.ajax({
		type : 'POST',
		url : "?model=system_procity_province&action=getProvinceForEditGrid",
		data : {
			"countryId" : '1'
		},
	    async: false,
		success : function(data) {
			provinceArr = eval("(" + data + ")");
		}
	});
	return provinceArr;
}