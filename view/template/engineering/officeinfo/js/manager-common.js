//��ȡʡ����Ϣ
function initProvince(selectId){
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

	var str;
	for(var i = 0;i < provinceArr.length;i++){
		if(selectId == provinceArr[i].value){
			str += "<option value='" + provinceArr[i].value + "' selected='selected'>" + provinceArr[i].name + "</option>";
		}else{
			str += "<option value='" + provinceArr[i].value + "'>" + provinceArr[i].name + "</option>";
		}
	}
	if(!selectId){
		$("#province").val(provinceArr[0].name);
	}
	$("#provinceId").append(str).change(function(){
		var province = $(this).find("option:selected").text();
		$("#province").val(province);
	});
}

//��֤�Ƿ��ظ�
function checkRepeat(){
	//�ж��Ƿ��ظ�
	var isRepeat = false;
	var id = '';
	if($("#id").length > 0){
		if($("#productLine").val() == $("#oldProductLine").val() && $("#provinceId").val() == $("#provinceIdHidden").val() && $("#businessBelong").val() == $("#oldBelong").val()){
			return true;
		}else{
			id = $("#id").val();
		}
	}
	$.ajax({
		type : 'POST',
		url : "?model=engineering_officeinfo_manager&action=checkRepeat",
		data : {
			"provinceId" : $("#provinceId").val(),
			"businessBelong" : $("#businessBelong").val(),
			"productLine" : $("#productLine").val(),
			"noId" : id
		},
	    async: false,
		success : function(data) {
			if(data == "1"){
				isRepeat = true;
			}
		}
	});

	//���ȷʵ�ظ�,������Ϣ
	if(isRepeat == true){
		alert('�Ѿ�������ͬʡ�ݺ͹�˾�ķ���������,�����ظ�¼��');
		return false;
	}else{
		return true;
	}
}