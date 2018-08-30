function checkThis(obj){
	if( $('#remainNum' + obj).val()*1 < $('#amountAll' + obj).val()*1 ){
		alert( '此次采购数量超过合同剩余采购数量！请重新输入！' );
		$('#amountAll' + obj).val('')
		$('#amountAll' + obj+ '_v').val('')
	}
}
function dateHopeAllFun(){
	temp = $('#rowNum').val();
	for(var i=1;i<=temp;i++){
		if($('#dateHope' + i).val()==''){
			$('#dateHope' + i).val( $('#dateHopeAll').val() );
		}
	}
}

$(function(){


var rowNum = $('#rowNum').val();
for(var mycount=1;mycount<=rowNum;mycount++){
	$("#productNumb"+mycount).yxcombogrid_product({
    	hiddenId : 'productId'+ mycount,
		gridOptions : {
			showcheckbox : false,
			param:{'notStatTypeArr':'TJCP,TJBCP'},
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
//						$("#productNumb"+mycount).val(data.productCode);
						$("#unitName"+mycount).val(data.unitName);
						$("#pattem"+mycount).val(data.pattern);
						$("#pattem"+mycount).val(data.pattem);
						$("#amountAll"+mycount).val('');
						$("#unitName"+mycount).val(data.unitName);
						$("#productId"+mycount).val(data.productId);
						$("#productNumb"+mycount).val(data.productCode);
						$("#productName"+mycount).val(data.productName);
						$("#contNum"+mycount).val('无');
						$("#issuedPurNum"+mycount).val('无');
						$.ajax({
							type : "POST",
							url : "?model=stock_inventoryinfo_inventoryinfo&action=getExeNum",
							data : {
								id : data.id
							},
							success : function(msg) {
								msg=msg*1;
								$('#exeNum'+mycount).html(msg);
							}
						});
					};
			  	}(mycount)
			}
		}
    });

	$("#productName"+mycount).yxcombogrid_product({
    	hiddenId : 'productId'+ mycount,
    	nameCol:'productName',
		gridOptions : {
			showcheckbox : false,
			param:{'notStatTypeArr':'TJCP,TJBCP'},
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
//						alert($('#exeNum'+mycount).html('无'));
//						$("#productNumb"+mycount).val(data.productCode);
						$("#unitName"+mycount).val(data.unitName);
						$("#pattem"+mycount).val(data.pattern);
						$("#pattem"+mycount).val(data.pattem);
						$("#amountAll"+mycount).val('');
						$("#unitName"+mycount).val(data.unitName);
						$("#productId"+mycount).val(data.productId);
						$("#productNumb"+mycount).val(data.productCode);
						$("#productName"+mycount).val(data.productName);
						$("#contNum"+mycount).val('无');
						$("#issuedPurNum"+mycount).val('无');
						$.ajax({
							type : "POST",
							url : "?model=stock_inventoryinfo_inventoryinfo&action=getExeNum",
							data : {
								id : data.id
							},
							success : function(msg) {
								msg=msg*1;
								$('#exeNum'+mycount).html(msg);
							}
						});
					};
			  	}(mycount)
			}
		}
    });
}
});

function checkAllData(){
	var dateHopeAll = $("#dateHopeAll").val();
	var sendTime = $("#sendTime").val();
	if(dateHopeAll!=""){
		if(dateHopeAll < sendTime ){
			alert("期望完成日期不能小于申请日期");
			return false;
		}else{
			return true;
		}
	}else{
		alert("期望完成日期不能为空");
		return false;
	}

}
