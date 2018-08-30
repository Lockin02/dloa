$(function() {
	$("#stockName").yxcombogrid_stockinfo({
		hiddenId : 'stockId',
		nameCol : 'stockName',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#stockCode").val(data.stockCode);
				}
			}
		}
	});

	$("#salesman").yxselect_user({
		hiddenId : 'salesmanId'
	});
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});

	var listSize = $('#invnumber').val();
	for(var i = 1; i <= listSize ; i++ ){
		$("#productNo"+ i).yxcombogrid_product({
			hiddenId : 'productId'+ i,
			gridOptions : {
				event : {
					'row_dblclick' : function(i){
						return function(e, row, data) {
							$("#productNo"+i).val(data.productCode);
							$("#productName"+i).val(data.productName);
							$("#productModel"+i).val(data.pattern);
						};
				  	}(i)
				}
			}
		});
	}
});

/**********************条目列表*************************/
function dynamic_add(packinglist,countNumP){
	mycount = document.getElementById(countNumP).value*1 + 1;
	var packinglist = document.getElementById(packinglist);
	i=packinglist.rows.length;
	oTR =packinglist.insertRow([i]);
	oTL0=oTR.insertCell([0]);
	oTL0.innerHTML=i;
	oTL1=oTR.insertCell([1]);
	oTL1.innerHTML="<input type='text' name='costajust[detail]["+mycount+"][productNo]' id='productNo"+mycount+"' class='txtmiddle'/>";
	oTL2=oTR.insertCell([2]);
	oTL2.innerHTML="<input type='text' name='costajust[detail]["+mycount+"][productName]' id='productName"+mycount+"' class='readOnlyTxtNormal' readonly='readonly'/><input type='hidden' name='costajust[detail]["+mycount+"][productId]' id='productId"+mycount+"'>";
	oTL3=oTR.insertCell([3]);
    oTL3.innerHTML="<input type='text' name='costajust[detail]["+mycount+"][productModel]' id='productModel"+mycount+"' class='readOnlyTxtItem' readonly='readonly'/>";
    oTL4=oTR.insertCell([4]);
    oTL4.innerHTML="<input type='text' name='costajust[detail]["+mycount+"][money]' id='money"+mycount+"' class='txtmiddle'/>";
    oTL5=oTR.insertCell([5]);
    oTL5.innerHTML="<input type='text' name='costajust[detail]["+mycount+"][remark]' id='remark"+mycount+"' class='txtmiddle'/>";
    oTL6=oTR.insertCell([6]);
    oTL6.innerHTML="<img src='images/closeDiv.gif' onclick='mydel(this,\""+packinglist.id+"\")' title='删除行'>";

    document.getElementById(countNumP).value = document.getElementById(countNumP).value*1 + 1 ;

    $("#productNo"+mycount).yxcombogrid_product({
    	hiddenId : 'productId'+ mycount,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
						$("#productNo"+mycount).val(data.productCode);
						$("#productName"+mycount).val(data.productName);
						$("#productModel"+mycount).val(data.pattern);
					};
			  	}(mycount)
			}
		}
    });
}


/**********************删除动态表单*************************/
function mydel(obj,mytable)
{
	if(confirm('确定要删除该行？')){
		var rowNo = obj.parentNode.parentNode.rowIndex*1 - 1;
		var mytable = document.getElementById(mytable);
   		mytable.deleteRow(rowNo);
   		var myrows=mytable.rows.length;
   		for(i=1;i<myrows;i++){
			mytable.rows[i].childNodes[0].innerHTML=i;
		}
	}
	countAll();
}