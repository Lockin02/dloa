//��ȡʡ����Ϣ
function getProvince(){
	var provinceArr;
	//����ʡ����Ϣ
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