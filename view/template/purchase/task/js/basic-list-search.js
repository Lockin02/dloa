$(function() {
		$("#productNumb1").yxcombogrid_product({
    	hiddenId : 'productId1',
		gridOptions : {
			showcheckbox : false,
			event : {
						'row_dblclick' : function(e, row, data) {
							$("#productName1").val(data.productName);
						}
			}
		}
    });

	$("#productName1").yxcombogrid_product({
    	hiddenId : 'productId1',
    	nameCol:'productName',
		gridOptions : {
			showcheckbox : false,
			event : {
						'row_dblclick' : function(e, row, data) {
							$("#productNumb1").val(data.productCode);
						}
			}
		}
    });
});

/**********************ɾ����̬��*************************/
function mydel(obj, mytable) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;
		for (i = 2; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i-1;
		}
	}
}

/**********************��Ŀ�б�*************************/
function dynamic_add(packinglist, countNumP) {

	var purchType=$("#purchType").val();
	var sendTime=$("#sendTime").val();
	var dateHope=$("#dateHope").val();
//	if(purchType==""){
//		alert("����ѡ��ɹ��ƻ����ͣ�");
//		return false;
//	}
	mycount = document.getElementById(countNumP).value * 1 + 1;
	var packinglist = document.getElementById(packinglist);
	i = packinglist.rows.length;
	oTR = packinglist.insertRow([i]);
	var oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i+1;
	var oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML =  "<input type='text' class='txt' value='' id='productNumb"+mycount+"' name='basic[" + mycount+"][productNumb]' >";
	var oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML ="<input type='text' class='txtMiddlelong' value='' id='productName"+mycount+"' name='basic[" + mycount+"][productName]' />"+
						"<input type='hidden' class='productId' value='' id='productId"+mycount+"' name='basic[" + mycount+"][productId]'/>";
	var oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = '<img title="ɾ����" onclick="mydel(this , \'mytable\')" src="images/closeDiv.gif">';

	document.getElementById(countNumP).value = document
			.getElementById(countNumP).value
			* 1 + 1;

	$("#productNumb"+mycount).yxcombogrid_product({
    	hiddenId : 'productId'+ mycount,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
//						$("#productNumb"+mycount).val(data.productCode);
						$("#unitName"+mycount).val(data.unitName);
						$("#pattem"+mycount).val(data.pattern);
						$("#productName"+mycount).val(data.productName);
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
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
						$("#productNumb"+mycount).val(data.productCode);
						$("#unitName"+mycount).val(data.unitName);
						$("#pattem"+mycount).val(data.pattern);
					};
			  	}(mycount)
			}
		}
    });
}