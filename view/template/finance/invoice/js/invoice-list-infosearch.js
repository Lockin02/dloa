function toSupport(){

	var beginYear = $("#beginYear").val();
	var beginMonth = $("#beginMonth").val();
	var endYear = $("#endYear").val();
	var endMonth = $("#endMonth").val();

	var areaName = $("#areaName").val();

	var invoiceUnitId = $("#invoiceUnitId").val();
	var invoiceUnitName = $("#invoiceUnitName").val();
	var invoiceUnitType = $("#invoiceUnitType").val();
	var invoiceUnitProvince = $("#invoiceUnitProvince").val();

	var salesmanId = $("#salesmanId").val();
	var salesman = $("#salesman").val();

	var orderCode = $("#orderCode").val();
	var invoiceNo = $("#invoiceNo").val();

	var signSubjectName = $("#signSubjectName").val();

	//���б�����ȡ
	var listGrid= parent.$("#invoiceGrid").data('yxgrid');

	//����ֵ�Լ������б����
	setVal(listGrid,'beginYear',beginYear);
	setVal(listGrid,'beginMonth',beginMonth);
	setVal(listGrid,'endYear',endYear);
	setVal(listGrid,'endMonth',endMonth);

	setVal(listGrid,'areaName',areaName);

	setVal(listGrid,'salesman',salesman);
	setVal(listGrid,'salesmanId',salesmanId);

	setVal(listGrid,'invoiceUnitId',invoiceUnitId);
	setVal(listGrid,'invoiceUnitName',invoiceUnitName);
	setVal(listGrid,'invoiceUnitType',invoiceUnitType);
	setVal(listGrid,'invoiceUnitProvince',invoiceUnitProvince);

	setVal(listGrid,'invoiceNo',invoiceNo);
	setVal(listGrid,'objCodeSearch',orderCode);


	setVal(listGrid,'signSubjectName',signSubjectName);

	//ˢ���б�
	listGrid.reload();
	closeFun();
}

function setVal(obj,thisKey,thisVal){
//	parent.$("#" + thisKey).val(thisVal);
	return obj.options.extParam[thisKey] = thisVal;
}

$(function(){
	// �ͻ�����
	invoiceUnitTypeArr = getData('KHLX');
	addDataToSelect(invoiceUnitTypeArr, 'invoiceUnitType');

	//������Ⱦ
	$("#areaName").yxcombogrid_area({
		width : 500,
		gridOptions : {
			showcheckbox : false
		}
	});
	//��������Ⱦ
    $("#salesman").yxselect_user({
		hiddenId : 'salesmanId',
		formCode : 'invoice'
	});
	//ʡ����Ⱦ
	$("#invoiceUnitProvince").yxcombogrid_province({
		height : 200,
		width : 400,
		gridOptions : {
			showcheckbox : false
		}
	});

	//�ͻ�
	$("#invoiceUnitName").yxcombogrid_customer({
		hiddenId : 'invoiceUnitId',
		height : 250,
		isShowButton : false,
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false
		}
	});


	//���ݳ�ʼ������
	//���б�����ȡ
	var listGrid= parent.$("#invoiceGrid").data('yxgrid');

	$("#beginYear").val( filterUndefined(listGrid.options.extParam.beginYear) );
	$("#beginMonth").val( filterUndefined(listGrid.options.extParam.beginMonth) );
	$("#endYear").val( filterUndefined(listGrid.options.extParam.endYear) );
	$("#endMonth").val( filterUndefined(listGrid.options.extParam.endMonth,12) );

	$("#areaName").val( filterUndefined(listGrid.options.extParam.areaName) );

	$("#salesmanId").val( filterUndefined(listGrid.options.extParam.salesmanId) );
	$("#salesman").val( filterUndefined(listGrid.options.extParam.salesman) );

	$("#orderCode").val( filterUndefined(listGrid.options.extParam.objCodeSearch) );

	//�ͻ���Ϣ
	$("#invoiceUnitId").val( filterUndefined(listGrid.options.extParam.invoiceUnitId) );
	$("#invoiceUnitName").val( filterUndefined(listGrid.options.extParam.invoiceUnitName) );
	$("#invoiceUnitType").val( filterUndefined(listGrid.options.extParam.invoiceUnitType) );
	$("#invoiceUnitProvince").val( filterUndefined(listGrid.options.extParam.invoiceUnitProvince) );

	$("#invoiceNo").val( filterUndefined(listGrid.options.extParam.invoiceNo) );

	$("#signSubjectName").val( filterUndefined(listGrid.options.extParam.signSubjectName) );
});


//���
function toClear(){
	$(".toClear").val('');
}
