
$(function() {
	//从表仓库渲染
	temp = $('#rowNum').val();
	for(var i=1;i<=temp;i++){
	$("#stockName"+ i).yxcombogrid_stockinfo({
				hiddenId : 'stockId'+i,
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(i){
						return function(e, row, data) {
							$('#stockId' + i).val(data.id);
							$('#stockCode' + i).val(data.stockCode);
							$('#stockName' + i).val(data.stockName);
						}
					}(i)
				}
			}
		});
	}

});
$(function(){
	//主表仓库渲染
	$("#stockName").yxcombogrid_stockinfo({
		hiddenId : 'stockId',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$('#stockId').val(data.id);
					$('#stockCode').val(data.stockCode);
					$('#stockName').val(data.stockName);
					temp = $('#rowNum').val();
					for(var i=1;i<=temp;i++){
						if($('#stockName' + i).val() == ""){
							$('#stockId' + i).val(data.id);
							$('#stockCode' + i).val(data.stockCode);
							$('#stockName' + i).val(data.stockName);
						}
					}
				}
			}
		}
	});
});

//客户与客户联系人联动
$(function() {
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		gridOptions : {
			showcheckbox : false,
			// param :{"contid":$('#contractId').val()},
			event : {
				'row_dblclick' : function(e, row, data) {
//					alert(i);
					var getGrid = function() {
						return $("#linkman")
								.yxcombogrid_linkman("getGrid");
					}
					var getGridOptions = function() {
						return $("#linkman")
								.yxcombogrid_linkman("getGridOptions");
					}
					if (getGrid().reload) {
						getGridOptions().param = {
							customerId : data.id
						};
						getGrid().reload();
					} else {
						getGridOptions().param = {
							customerId : data.id
						}
					}
					$("#customerId").val(data.id);
					$("#linkman").val("");
					$("#linkmanId").val("");
				}
			}
		}
	});
	$("#linkman").yxcombogrid_linkman({
		hiddenId : 'linkmanId',
		isFocusoutCheck : false,
		gridOptions : {
			reload : true,
			showcheckbox : false,
			param : {'customerId' : $('#customerId').val()},
			// param : param,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#customerName").val(data.customerName);
					$("#customerId").val(data.customerId);
					$("#mobil").val(data.phone);
				}
			}
		}
	});
	$("#companyName").yxcombogrid_logistics({
		hiddenId : 'companyId',
		gridOptions : {
			reload : true,
			showcheckbox : false,
			// param : param,
			event : {
				'row_dblclick' : function(e, row, data) {
				}
			}
		}
	});
});
$(function(){
	$("#signDate").val(formatDate(new Date()));
});
//$(function(){
//	var docType = $("#docType").val();
//	$.each($(":checkbox[@name=shipType]"),function(){
//		if($(this).val() == docType ){
//			$(this).attr("checked",true);
//		}
//	})
//	$("#shipDate").val(formatDate(new Date()));
//});


$(function(){
	var docType = $("#docType").val();
	if( docType=='oa_sale_lease' ){
		$("#lease").attr("checked","checked");
	}else if( docType=='oa_borrow_borrow' ){
		$("#borrow").attr("checked","checked");
	}else{
		$("#order").attr("checked","checked");
	}
	$("#shipDate").val(formatDate(new Date()));
});
/** ********************删除动态表单************************ */
function mydel(obj, mytable) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 1;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;
		for (i = 1; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i;
		}
	}
}

/** *****************隐藏计划******************************* */
function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}

function checkThis( numObj,shipNumObj ){
	number = document.getElementById(numObj).value;
	shipNum = document.getElementById(shipNumObj).value;
	if(shipNum-number<0){
		alert('设备数量超过发货计划数量，该数值讲被置回发货计划数量！')
		document.getElementById(numObj).value = shipNum;
	}
}

function mailFun(){
	document.getElementById('form1').action="?model=stock_outplan_ship&action=add&msg=mail";
}
//借用发货单客户信息不需要填
$(function(){
	if($('#hasCus').val()==1){
		$("#customerName").yxcombogrid_customer('remove');
		$("#linkman").yxcombogrid_linkman("remove");
		$('.customerCheck').remove();
		document.getElementById('customerName').className='readOnlyTxtNormal';
		$('#customerName').attr("readonly","readonly")
	}
})