function toSupport(){

	var userNoSearch = $.trim($("#userNoSearch").val());
	var userNameSearch = $.trim($("#userNameSearch").val());
	var deptNameSearch = $.trim($("#deptNameSearch").val());

	var projectNameSearch = $.trim($("#projectNameSearch").val());
	var projectContent= $.trim($("#projectContent").val());
	var beginDateSearch= $.trim($("#beginDateSearch").val());
	var closeDateSearch= $.trim($("#closeDateSearch").val());
	var projectManagerSearch= $.trim($("#projectManagerSearch").val());
	var projectPlace= $.trim($("#projectPlace").val());
	//���б�����ȡ
	var listGrid= parent.$("#projectGrid").data('yxgrid');
	//����ֵ�Լ������б����
	setVal(listGrid,'userNoSearch',userNoSearch);
	setVal(listGrid,'userNameSearch',userNameSearch);
	setVal(listGrid,'deptNameSearch',deptNameSearch);

	setVal(listGrid,'projectNameSearch',projectNameSearch);
	setVal(listGrid,'projectContent',projectContent);
	setVal(listGrid,'beginDateSearch',beginDateSearch);
	setVal(listGrid,'closeDateSearch',closeDateSearch);
	setVal(listGrid,'projectManagerSearch',projectManagerSearch);
	setVal(listGrid,'projectPlace',projectPlace);

	//ˢ���б�
	listGrid.reload();
	closeFun();
}

function setVal(obj,thisKey,thisVal){
//	parent.$("#" + thisKey).val(thisVal);
	return obj.options.extParam[thisKey] = thisVal;
}

$(function(){
	//ֱ������
	$("#deptNameSearch").yxselect_dept({
		hiddenId : 'deptId'
	});
		//ְλѡ��
		$("#jobNameSearch").yxcombogrid_position({
			hiddenId : 'jobId',
			isShowButton : false,
			width:350
		})


});


//���
function toClear(){
	$(".toClear").val('');
}