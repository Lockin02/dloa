function toSupport(){

	var docDateBegin = $("#docDateBegin").val();
	var docDateEnd = $("#docDateEnd").val();

	//主列表对象获取
	if(parent.$("#pickingGrid").data('yxsubgrid')){
		//主列表对象获取
		var listGrid= parent.$("#pickingGrid").data('yxsubgrid');
	}else{
		//主列表对象获取
		var listGrid= parent.$("#pickingGrid").data('yxgrid');
	}

	//设置值以及传输列表参数
	setVal(listGrid,'docDateBegin',docDateBegin);
	setVal(listGrid,'docDateEnd',docDateEnd);

	//刷新列表
	listGrid.reload();
	closeFun();
}

function setVal(obj,thisKey,thisVal){
	return obj.options.extParam[thisKey] = thisVal;
}

//清空
function toClear(){
	$(".toClear").val('');
}