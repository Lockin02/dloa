function toSupport(){

	var beginYear = $("#beginYear").val();
	var beginMonth = $("#beginMonth").val();
	var endYear = $("#endYear").val();
	var endMonth = $("#endMonth").val();
	var createId = $("#createId").val();
	var createName = $("#createName").val();

	//���б�����ȡ
	var listGrid= parent.$("#logoperationGrid").data('yxgrid');

	//����ֵ�Լ������б����
	setVal(listGrid,'beginYear',beginYear);
	setVal(listGrid,'beginMonth',beginMonth);
	setVal(listGrid,'endYear',endYear);
	setVal(listGrid,'endMonth',endMonth);
	setVal(listGrid,'createId',createId);
	setVal(listGrid,'createName',createName);

	if(beginMonth >= 10){
		var beginYearMonth = beginYear + "" + beginMonth;
	}else{
		var beginYearMonth = beginYear + "0" + beginMonth;
	}
	setVal(listGrid,'beginYearMonth',beginYearMonth);


	if(endMonth >= 10){
		var endYearMonth = endYear + "" + endMonth;
	}else{
		var endYearMonth = endYear + "0" + endMonth;
	}
	setVal(listGrid,'endYearMonth',endYearMonth);

	//ˢ���б�
	listGrid.reload();
	closeFun();
}

function setVal(obj,thisKey,thisVal){
//	parent.$("#" + thisKey).val(thisVal);
	return obj.options.extParam[thisKey] = thisVal;
}

$(function(){

	$("#createName").yxselect_user({
		hiddenId : 'createId'
	});

	//���ݳ�ʼ������
	//���б�����ȡ
	var listGrid= parent.$("#logoperationGrid").data('yxgrid');

	$("#beginYear").val( filterUndefined(listGrid.options.extParam.beginYear) );
	$("#beginMonth").val( filterUndefined(listGrid.options.extParam.beginMonth) );
	$("#endYear").val( filterUndefined(listGrid.options.extParam.endYear) );
	$("#endMonth").val( filterUndefined(listGrid.options.extParam.endMonth) );
	$("#createId").val( filterUndefined(listGrid.options.extParam.createId) );
	$("#createName").val( filterUndefined(listGrid.options.extParam.createName) );
});


//���
function toClear(){
	$(".toClear").val('');
}