function toSupport(){

	var docDateBegin = $("#docDateBegin").val();
	var docDateEnd = $("#docDateEnd").val();

	//���б�����ȡ
	if(parent.$("#pickingGrid").data('yxsubgrid')){
		//���б�����ȡ
		var listGrid= parent.$("#pickingGrid").data('yxsubgrid');
	}else{
		//���б�����ȡ
		var listGrid= parent.$("#pickingGrid").data('yxgrid');
	}

	//����ֵ�Լ������б����
	setVal(listGrid,'docDateBegin',docDateBegin);
	setVal(listGrid,'docDateEnd',docDateEnd);

	//ˢ���б�
	listGrid.reload();
	closeFun();
}

function setVal(obj,thisKey,thisVal){
	return obj.options.extParam[thisKey] = thisVal;
}

//���
function toClear(){
	$(".toClear").val('');
}