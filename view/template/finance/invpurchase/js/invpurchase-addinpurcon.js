

$(function() {

	$("#head").yxselect_user({
		hiddenId : 'headId'
	});
	$("#acount").yxselect_user({
		hiddenId : 'acountId'
	});
	$("#salesman").yxselect_user({
		hiddenId : 'salesmanId'
	});
	$("#departments").yxselect_dept({
		hiddenId : 'departmentsId'
	});

	if($("#isRed").length != 0){
		if($("#isRed").val() == 1){
			$("input[name='invpurchase[formType]'][value='red']").attr('checked',true);
		}
	}


	changeTaxRateClear('invType');
	countAll('taxPrice');
});


/**********************ɾ����̬��*************************/
function mydel(obj,mytable)
{
	if(confirm('ȷ��Ҫɾ�����У�')){
		var rowNo = obj.parentNode.parentNode.rowIndex*1 - 1;
		var mytable = document.getElementById(mytable);
   		mytable.deleteRow(rowNo);
   		var myrows=mytable.rows.length;
   		for(i=1;i<myrows;i++){
			mytable.rows[i].childNodes[0].innerHTML=i;
		}
	}
}