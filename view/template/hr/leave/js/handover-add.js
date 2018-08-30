$(function() {
	num = $('#num').val();
	for(var i=1;i<=num;i++){
	$("#recipientName" + i).yxselect_user({
		hiddenId : 'recipientId'+i,
		isShowButton : false,
		mode:'check'
		});
	}




	//离职清单模板

	$("#schemeName").yxcombogrid_handoverscheme({
		hiddenId : 'schemeId',
		width:600,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data){
						$(".clearClass").remove();
						$.ajax({
						    type: "POST",
						    url: "?model=hr_leave_formwork&action=addItemList",
						    data: {"parentCode" : data.id,"count":num},
						    success: function(data){
						    	$("#appendHtml").after(data);
								for(var i=0;i<=40;i++){
									$("#recipientName" + i).yxselect_user({
										hiddenId : 'recipientId'+i,
										isShowButton : false,
										mode:'check'
									});
								}
						    }
						});
				}
			}
		}
	});
});
//
//	$("#handoverList").yxeditgrid({
//		objName : 'handover[formwork]',
//		url : '?model=hr_leave_formwork&action=addItemJson',
//		isAddAndDel : false,
//		colModel : [{
//			display : '工作及设备交接事项',
//			name : 'items',
//			readonly : "readonly",
//			type : 'txt',
//			tclass : 'readOnlyTxtLong'
//		}, {
//			display : '交接情况',
//			name : 'handoverCondition',
//			type : 'statictext'
//		}, {
//			display : '接收人',
//			name : 'recipientName',
//			readonly : "readonly",
//			process : function($input, rowData) {
//				var rowNum = $input.data("rowNum");
//				var g = $input.data("grid");
//				$input.yxselect_user({
//					hiddenId : 'recipientId',
//					event : {
//						'select' : function(e, row, data) {
//						    g.getCmpByRowAndCol(rowNum,'recipientId').val(row.val);
//						}
//					}
//				});
//			}
//		}, {
//			display : '接收人Id',
//			name : 'recipientId',
////			type : 'txt'
//			type : 'hidden'
//		}, {
//			display : '遗失财务',
//			name : 'lose',
//			type : 'statictext'
//		}, {
//			display : '扣款金额',
//			name : 'deduct',
//			type : 'statictext'
//		}]
//	});
function delItem(obj) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		var mytable = document.getElementById('formtable');
		mytable.deleteRow(rowNo);
	}
}

function dynamic_type_add(packinglist, countNumP) {
	mycount = document.getElementById(countNumP).value * 1 + 1;
	var packinglist = document.getElementById(packinglist);
	i = packinglist.rows.length;
	oTR = packinglist.insertRow([i]);
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = '<img title="删除行" onclick="mydelProduce(this ,\'mytable\')" src="images/closeDiv.gif">';
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = i+1;
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML =  "<input type='text' class='txtshort' value='' id='productNumb"+mycount+"' name='basic[equipment][" + mycount+"][productNumb]' >";
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML ="<input type='text' class='txtmiddle' value='' id='productName"+mycount+"' name='basic[equipment][" + mycount+"][productName]' />"+
						"<input type='hidden' class='productId' value='' id='productId"+mycount+"' name='basic[equipment][" + mycount+"][productId]'/>";
	var oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML ="<input type='text' class='readOnlyTxtShort' value='' id='productTypeName"+mycount+"' name='basic[equipment][" + mycount+"][productTypeName]' />"+
						"<input type='hidden' value='' id='productTypeId"+mycount+"' name='basic[equipment][" + mycount+"][productTypeId]'/>";
	var oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = '<input type="text" class="readOnlyTxtShort" id="pattem'+mycount+'"  value="" name="basic[equipment]['+mycount+'][pattem]" readonly/>';
	var oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = '<input type="text" class="readOnlyTxtShort" id="unitName'+mycount+'"  value="" name="basic[equipment]['+mycount+'][unitName]" readonly/>';
	var oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = '<input type="text" class="readOnlyTxtShort" id="leastPackNum'+mycount+'"  value="" name="basic[equipment]['+mycount+'][leastPackNum]" readonly/>';
	var oTL8 = oTR.insertCell([8]);
	oTL8.innerHTML = '<input type="text" class="readOnlyTxtShort" id="leastOrderNum'+mycount+'"  value="" name="basic[equipment]['+mycount+'][leastOrderNum]" readonly/>';
	var oTL9= oTR.insertCell([9]);
	oTL9.innerHTML =  '<select class="txtshort" name="basic[equipment]['+mycount+'][qualityCode]">'+$("#qualityList").val()+'</select>';
	var oTL10= oTR.insertCell([10]);
	oTL10.innerHTML = '<input type="text" class="amount txtshort" id="amountAll'+mycount+'" onblur="checkNum(this);" value="" name="basic[equipment]['+mycount+'][amountAll]">'+
						'<input type="hidden" value="" name="amountAll">';
	var oTL11 = oTR.insertCell([11]);
	oTL11.innerHTML = '<input type="text" class="readOnlyTxtShort"  value="'+sendTime+'" name="basic[equipment]['+mycount+'][dateIssued]" class="txt">';
	var oTL12 = oTR.insertCell([12]);
	oTL12.innerHTML = '<input type="text" class="txtshort" readonly="" onfocus="WdatePicker()" value="'+dateHope+'" name="basic[equipment]['+mycount+'][dateHope]" class="txt">';
	var oTL13 = oTR.insertCell([13]);
	oTL13.innerHTML = '<input type="text" class="txtshort"  name="basic[equipment]['+mycount+'][remark]"/>'+
						 '<input type="hidden" value="'+purchType+'" name="basic[equipment]['+mycount+'][purchType]">'+
						 '<input id="equObjAssId" type="hidden" value="" name="basic[equipment]['+mycount+'][equObjAssId]">';

	document.getElementById(countNumP).value = document
			.getElementById(countNumP).value
			* 1 + 1;
}
