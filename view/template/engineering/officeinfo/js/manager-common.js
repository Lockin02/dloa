//获取省份信息
function initProvince(selectId){
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

//验证是否重复
function checkRepeat(){
	//判断是否重复
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

	//如果确实重复,弹出信息
	if(isRepeat == true){
		alert('已经存在相同省份和公司的服务经理配置,不能重复录入');
		return false;
	}else{
		return true;
	}
}