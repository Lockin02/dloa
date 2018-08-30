var provinceArr; //缓存的省份数组
$(document).ready(function() {
	provinceArr = getProvince();

	$("#vehicleListInfo").yxeditgrid({
		objName : 'vehiclesupp[vehicle]',
		url : '?model=outsourcing_outsourcessupp_vehiclesuppequ&action=listJson',
		param : {
			dir : 'ASC',
			parentId : $("#id").val()
		},
		colModel: [{
			name: 'area',
			display: '区域名',
			type: 'hidden'
		},{
			name: 'areaId',
			display: '区域',
			type: 'select',
			options: provinceArr,
			process: function($input) {
				var rowNum = $input.data("rowNum");
				$("#vehicleListInfo_cmp_area" + rowNum).val(($("#vehicleListInfo_cmp_areaId" + rowNum).find("option:selected").text()));
				$input.change(function() {
					$("#vehicleListInfo_cmp_area" + rowNum).val($("#vehicleListInfo_cmp_areaId" + rowNum).find("option:selected").text());
				});
			}
		},{
			name: 'carAmount',
			display: '经济型车辆数量',
			validation: {
				required: true,
				custom: ['onlyNumber']
			}
		},{
			name: 'driverAmount',
			display: '司机数量',
			validation: {
				required: true,
				custom: ['onlyNumber']
			}
		},{
			name: 'rentPrice',
			display: '租车费单价',
			type: 'money',
			validation: {
				required: true
			}
		}]
	});

	//供应商名字唯一性验证
	$("#suppName").ajaxCheck({
		url : "?model=outsourcing_outsourcessupp_vehiclesupp&action=checkRepeat&id=" + $("#id").val(),
		alertText : "* 该车辆供应商已存在",
		alertTextOk : "* OK"
	});

	// $("#suppCategory").change(function (){
	// 	if ($(this).val() == 'GYSLX-02') {
	// 		$("#suppName").unbind();
	// 	}else {
	// 		$("#suppName").ajaxCheck({
	// 			url : "?model=outsourcing_outsourcessupp_vehiclesupp&action=checkRepeat&id" + $("#id").val(),
	// 			alertText : "* 该车辆供应商已存在",
	// 			alertTextOk : "* OK"
	// 		});
	// 	}
	// });

	// $("#linkmanPhone").blur(function (){
	// 	if ($(this).val() && $("#suppCategory").val() == 'GYSLX-02') {
	// 		$("#suppName").ajaxCheck({
	// 			url : "?model=outsourcing_outsourcessupp_vehiclesupp&action=checkRepeatByPhone&phoneNum=" + $(this).val() + "&id=" + $("#id").val(),
	// 			alertText : "* 该车辆供应商已存在",
	// 			alertTextOk : "* OK"
	// 		});
	// 	}
	// });

	//能否配备司机
	$('select[name="vehiclesupp[isEquipDriver]"] option').each(function() {
		if( $(this).val() == $("#isEquipDriverSelect").val() ){
			$(this).attr("selected","selected'");
		}
	});

	//有无路测经验
	$('select[name="vehiclesupp[isDriveTest]"] option').each(function() {
		if( $(this).val() == $("#isDriveTestSelect").val() ){
			$(this).attr("selected","selected'");
		}
	});

	validate({
		"linkmanPhone" : {
			required : true,
			custom : ['onlyNumber']
		},
		"linkmanMail" : {
			required : false,
			custom : ['email']
		},
		"province" : {
			required : true
		},
		"city" : {
			required : true
		},
		"linkmanName" : {
			required : true
		},
		"linkmanJob" : {
			required : true
		},
		"address" : {
			required : true
		},
		"suppName" : {
			required : true
		},
		"taxPoint" : {
			required : false,
			custom : ['percentageNum']
		}
	});
})