/**
 * 公用明细表对象初始化方法
 * 在初始化的同时会除去关联关系
 * @param {} mycount
 */
function initGrid(mycount){
	$("#objId" + mycount ).val("");
	$("#objCode" + mycount ).val("");
	//当类型为销售合同时,初始化销售合同grid
	initGridNoClear(mycount);
}

//初始化grid,不会取消关联关系
function initGridNoClear(mycount){
	var thisObj = $("#objType" + mycount);
	//当类型为销售合同时,初始化销售合同grid
	if(thisObj.val() == 'YFRK-01'){
		$("#objCode"+ mycount).yxcombogrid_purchcontract('remove').yxcombogrid_other('remove').
		yxcombogrid_outsourccontract('remove').yxcombogrid_outaccount('remove').yxcombogrid_purchcontract({
			hiddenId :  'objId'+ mycount,
			gridOptions : {
				showcheckbox : false,
				param : { "csuppId" : $('#supplierId').val()},
				event : {
					'row_dblclick' : function(mycount){
						return function(e, row, data) {
							$("#objCode" + mycount).val(data.hwapplyNumb);
						};
				  	}(mycount)
				}
			}
		});
	}else if(thisObj.val() == 'YFRK-02'){//其他合同
		$("#objCode"+ mycount).yxcombogrid_other('remove').yxcombogrid_purchcontract('remove').
		yxcombogrid_outsourccontract('remove').yxcombogrid_outaccount('remove').yxcombogrid_other({
			hiddenId :  'objId'+ mycount,
			gridOptions : {
				showcheckbox : false,
				param : { "csuppId" : $('#supplierId').val()},
				event : {
					'row_dblclick' : function(mycount){
						return function(e, row, data) {
							$("#objCode" + mycount).val(data.orderCode);
						};
				  	}(mycount)
				}
			}
		});
	}else if(thisObj.val() == 'YFRK-03'){//外包合同
		$("#objCode"+ mycount).yxcombogrid_outsourccontract('remove').yxcombogrid_purchcontract('remove').
		yxcombogrid_other('remove').yxcombogrid_outaccount('remove').yxcombogrid_outsourccontract({
			hiddenId :  'objId'+ mycount,
			gridOptions : {
				showcheckbox : false,
				param : { "csuppId" : $('#supplierId').val()},
				event : {
					'row_dblclick' : function(mycount){
						return function(e, row, data) {
							$("#objCode" + mycount).val(data.orderCode);
						};
				  	}(mycount)
				}
			}
		});
	}else if(thisObj.val() == 'YFRK-05'){
		$("#objCode"+ mycount).yxcombogrid_outaccount('remove').yxcombogrid_purchcontract('remove').yxcombogrid_other('remove').
		yxcombogrid_outsourccontract('remove').yxcombogrid_outaccount({
			hiddenId :  'objId'+ mycount,
			gridOptions : {
				showcheckbox : false,
				param : { "csuppId" : $('#supplierId').val()},
				event : {
					'row_dblclick' : function(mycount){
						return function(e, row, data) {
							$("#objCode" + mycount).val(data.formCode);
						};
				  	}(mycount)
				}
			}
		});
	}
}

//编辑时初始化列表gird
$(function(){
	var invnumber = $('#coutNumb').val();
	for(var i = 1;i <= invnumber;i++){
		initGridNoClear(i);
	}
});