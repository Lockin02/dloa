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

	//主列表对象获取
	if(parent.$("#payablesapplyGrid").data('yxsubgrid')){
		//主列表对象获取
		var listGrid= parent.$("#payablesapplyGrid").data('yxsubgrid');
	}else{
		//主列表对象获取
		var listGrid= parent.$("#payablesapplyGrid").data('yxgrid');
	}

	//设置值以及传输列表参数
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

	//刷新列表
	listGrid.reload();
	closeFun();
}

function setVal(obj,thisKey,thisVal){
//	parent.$("#" + thisKey).val(thisVal);
	return obj.options.extParam[thisKey] = thisVal;
}

$(function(){

	//负责人渲染
    $("#salesman").yxselect_user({
		hiddenId : 'salesmanId',
		formCode : 'payablesapply'
	});
	//部门
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});
	//部门
	$("#feeDeptName").yxselect_dept({
		hiddenId : 'feeDeptId',
        unDeptFilter: ($('#unDeptFilter').val() != undefined)? $('#unDeptFilter').val() : ''
	});

	if(parent.$("#payablesapplyGrid").data('yxsubgrid')){
		//主列表对象获取
		var listGrid= parent.$("#payablesapplyGrid").data('yxsubgrid');
	}else{
		//主列表对象获取
		var listGrid= parent.$("#payablesapplyGrid").data('yxgrid');
	}
	//数据初始化部分

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


//清空
function toClear(){
	$(".toClear").val('');
}