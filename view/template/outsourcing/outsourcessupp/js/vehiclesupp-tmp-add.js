/**
* 设置Excel序列号到界面上
*/
function setExcelValue(data){
	var obj = eval("(" + data + ")");
	//省份
	$('select[name="vehiclesupp[provinceId]"] option').each(function() {
		if( $(this).attr("title") == obj.province){
			$(this).attr("selected","selected'");
		}
	});
	$("#province").trigger('change');
	//城市
	$('select[name="vehiclesupp[cityId]"] option').each(function() {
		if( $(this).attr("title") == obj.city){
			$(this).attr("selected","selected'");
		}
	});
	$("#city").trigger('change');
	//供应商类型
	$('select[name="vehiclesupp[suppCategory]"] option').each(function() {
		if( $(this).attr("title") == obj.suppCategoryName){
			$(this).attr("selected","selected'");
		}
	});
	$("#suppCategory").trigger('change');

	$("#suppName").val(obj.suppName);
	$("#registeredDate").val(obj.registeredDate);
	$("#registeredFunds").val(obj.registeredFunds);

	$("#legalRepre").val(obj.legalRepre);
	$("#carAmount").val(obj.carAmount);
	$("#driverAmount").val(obj.driverAmount);

	//发票属性
	$('select[name="vehiclesupp[invoiceCode]"] option').each(function() {
		if( $(this).attr("title") == obj.invoice){
			$(this).attr("selected","selected'");
		}
	});
	//发票税点
	$("#taxPoint").val(obj.taxPoint);
	//能否配备司机
	$('select[name="vehiclesupp[isEquipDriver]"] option').each(function() {
		var tmp = 0;
		if (obj.isEquipDriver == '能') {
			tmp = 1;
		}
		if( $(this).val() == tmp){
			$(this).attr("selected","selected'");
		}
	});

	//有无路测经验
	$('select[name="vehiclesupp[isDriveTest]"] option').each(function() {
		var tmp = 0;
		if (obj.isDriveTest == '有') {
			tmp = 1;
		}
		if( $(this).val() == tmp ){
			$(this).attr("selected","selected'");
		}
	});
	$("#businessDistributeHidden").val(obj.businessDistribute);
	$("#businessDistribute").val(obj.businessDistribute);
	initBusinessDistribute();
	$("#companyProfile").val(obj.companyProfile);

	$("#tentativeTalk").val(obj.tentativeTalk);

	$("#linkmanName").val(obj.linkmanName);
	$("#linkmanJob").val(obj.linkmanJob);
	$("#linkmanPhone").val(obj.linkmanPhone).trigger('blur');

	$("#linkmanMail").val(obj.linkmanMail);
	$("#postcode").val(obj.postcode);
	$("#address").val(obj.address);


	$("#bankName").val(obj.bankName);
	$("#bankAccount").val(obj.bankAccount);

	$("#suppName").focus();
	//从表数据处理
	if (obj.vehicleNumb > 0) {
		var provinceArr; //缓存的省份数组
		provinceArr = getProvince();

		$("#vehicleListInfo").empty(); //删除原表格
		$("#vehicleListInfo").yxeditgrid({
			objName : 'vehiclesupp[vehicle]',
			data : obj.vehicle,
			colModel : [{
						name : 'area',
					display : '区域名',
						type : 'hidden'
				},{
						name : 'areaId',
					display : '区域',
						type : 'select',
					options : provinceArr,
					process : function ($input ,row) {
						var rowNum = $input.data("rowNum");
						$("#vehicleListInfo_cmp_areaId" + rowNum + " option").each(function() {
							if( $(this).attr("title") == row.area) {
								$(this).attr("selected","selected'");
							}
						});
						$input.change(function() {
							$("#vehicleListInfo_cmp_area" + rowNum).val(
								$("#vehicleListInfo_cmp_areaId" + rowNum).find("option:selected").text());
						});
					}
				},{
							name : 'carAmount',
	  					display : '经济型车辆数量',
	  					validation : {
						required : true,
						custom : ['onlyNumber']
					}
				},{
							name : 'driverAmount',
	  					display : '司机数量',
	  					validation : {
						required : true,
						custom : ['onlyNumber']
					}
				},{
							name : 'rentPrice',
	  					display : '租车费单价',
	  					validation : {
						required : true,
						custom : ['percentageNum']
					}
				}]
		});
	}
}