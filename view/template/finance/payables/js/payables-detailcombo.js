/**
 * ������ϸ������ʼ������
 * �ڳ�ʼ����ͬʱ���ȥ������ϵ
 * @param {} mycount
 */
function initGrid(mycount){
	$("#objId" + mycount ).val("");
	$("#objCode" + mycount ).val("");
	//������Ϊ���ۺ�ͬʱ,��ʼ�����ۺ�ͬgrid
	initGridNoClear(mycount);
}

//��ʼ��grid,����ȡ��������ϵ
function initGridNoClear(mycount){
	var thisObj = $("#objType" + mycount);
	//������Ϊ���ۺ�ͬʱ,��ʼ�����ۺ�ͬgrid
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
	}else if(thisObj.val() == 'YFRK-02'){//������ͬ
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
	}else if(thisObj.val() == 'YFRK-03'){//�����ͬ
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

//�༭ʱ��ʼ���б�gird
$(function(){
	var invnumber = $('#coutNumb').val();
	for(var i = 1;i <= invnumber;i++){
		initGridNoClear(i);
	}
});