function toSupport(){

	var formDateBegin = $("#formDateBegin").val();
	var formDateEnd = $("#formDateEnd").val();

	var supplierName = $("#supplierName").val();

	var salesmanId = $("#salesmanId").val();
	var salesman = $("#salesman").val();

	var deptName = $("#deptName").val();
	var deptId = $("#deptId").val();

	var feeDeptName = $("#feeDeptName").val();
	var feeDeptId = $("#feeDeptId").val();

	var sourceType = $("#sourceType").val();
//	var payFor = $("#payFor").val();

	//���б�����ȡ
	if(parent.$("#payablesapplyGrid").data('yxsubgrid')){
		//���б�����ȡ
		var listGrid= parent.$("#payablesapplyGrid").data('yxsubgrid');
	}else{
		//���б�����ȡ
		var listGrid= parent.$("#payablesapplyGrid").data('yxgrid');
	}

	//����ֵ�Լ������б����
	setVal(listGrid,'formDateBegin',formDateBegin);
	setVal(listGrid,'formDateEnd',formDateEnd);

	setVal(listGrid,'supplierName',supplierName);

	setVal(listGrid,'salesman',salesman);
	setVal(listGrid,'salesmanId',salesmanId);
	setVal(listGrid,'deptName',deptName);
	setVal(listGrid,'deptId',deptId);
	setVal(listGrid,'feeDeptName',feeDeptName);
	setVal(listGrid,'feeDeptId',feeDeptId);

	setVal(listGrid,'sourceType',sourceType);

	//ˢ���б�
	listGrid.reload();
	closeFun();
}

function setVal(obj,thisKey,thisVal){
//	parent.$("#" + thisKey).val(thisVal);
	return obj.options.extParam[thisKey] = thisVal;
}

$(function(){

	//��������Ⱦ
    $("#salesman").yxselect_user({
		hiddenId : 'salesmanId',
		formCode : 'payablesapply'
	});
	//����
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});
	//����
	$("#feeDeptName").yxselect_dept({
		hiddenId : 'feeDeptId',
        unDeptFilter: ($('#unDeptFilter').val() != undefined)? $('#unDeptFilter').val() : ''
	});

	if(parent.$("#payablesapplyGrid").data('yxsubgrid')){
		//���б�����ȡ
		var listGrid= parent.$("#payablesapplyGrid").data('yxsubgrid');
	}else{
		//���б�����ȡ
		var listGrid= parent.$("#payablesapplyGrid").data('yxgrid');
	}
	//���ݳ�ʼ������

	$("#supplierName").val( filterUndefined(listGrid.options.extParam.supplierName) );

	$("#formDateBegin").val( filterUndefined(listGrid.options.extParam.formDateBegin) );
	$("#formDateEnd").val( filterUndefined(listGrid.options.extParam.formDateEnd) );

	$("#salesman").val( filterUndefined(listGrid.options.extParam.salesman) );
	$("#salesmanId").val( filterUndefined(listGrid.options.extParam.salesmanId) );

	$("#deptName").val( filterUndefined(listGrid.options.extParam.deptName) );
	$("#deptId").val( filterUndefined(listGrid.options.extParam.deptId) );

	$("#feeDeptName").val( filterUndefined(listGrid.options.extParam.feeDeptName) );
	$("#feeDeptId").val( filterUndefined(listGrid.options.extParam.feeDeptId) );

	$("#sourceType").val( filterUndefined(listGrid.options.extParam.sourceType) );
//	$("#payFor").val( filterUndefined(listGrid.options.extParam.payFor) );


});


//���
function toClear(){
	$(".toClear").val('');
}