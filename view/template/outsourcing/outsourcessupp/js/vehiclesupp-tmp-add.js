/**
* ����Excel���кŵ�������
*/
function setExcelValue(data){
	var obj = eval("(" + data + ")");
	//ʡ��
	$('select[name="vehiclesupp[provinceId]"] option').each(function() {
		if( $(this).attr("title") == obj.province){
			$(this).attr("selected","selected'");
		}
	});
	$("#province").trigger('change');
	//����
	$('select[name="vehiclesupp[cityId]"] option').each(function() {
		if( $(this).attr("title") == obj.city){
			$(this).attr("selected","selected'");
		}
	});
	$("#city").trigger('change');
	//��Ӧ������
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

	//��Ʊ����
	$('select[name="vehiclesupp[invoiceCode]"] option').each(function() {
		if( $(this).attr("title") == obj.invoice){
			$(this).attr("selected","selected'");
		}
	});
	//��Ʊ˰��
	$("#taxPoint").val(obj.taxPoint);
	//�ܷ��䱸˾��
	$('select[name="vehiclesupp[isEquipDriver]"] option').each(function() {
		var tmp = 0;
		if (obj.isEquipDriver == '��') {
			tmp = 1;
		}
		if( $(this).val() == tmp){
			$(this).attr("selected","selected'");
		}
	});

	//����·�⾭��
	$('select[name="vehiclesupp[isDriveTest]"] option').each(function() {
		var tmp = 0;
		if (obj.isDriveTest == '��') {
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
	//�ӱ����ݴ���
	if (obj.vehicleNumb > 0) {
		var provinceArr; //�����ʡ������
		provinceArr = getProvince();

		$("#vehicleListInfo").empty(); //ɾ��ԭ���
		$("#vehicleListInfo").yxeditgrid({
			objName : 'vehiclesupp[vehicle]',
			data : obj.vehicle,
			colModel : [{
						name : 'area',
					display : '������',
						type : 'hidden'
				},{
						name : 'areaId',
					display : '����',
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
	  					display : '�����ͳ�������',
	  					validation : {
						required : true,
						custom : ['onlyNumber']
					}
				},{
							name : 'driverAmount',
	  					display : '˾������',
	  					validation : {
						required : true,
						custom : ['onlyNumber']
					}
				},{
							name : 'rentPrice',
	  					display : '�⳵�ѵ���',
	  					validation : {
						required : true,
						custom : ['percentageNum']
					}
				}]
		});
	}
}