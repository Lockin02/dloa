$(function(){
	if( $('#pageAction').val() != 'feedback' ){
		$('tr.Feedback').hide();
	}
	var equIds = $("#equIds").val();
	var contractId = $("#contractId").val();
	var itemTable = $("#itemTable");
	var docType = $("#docType").val();
	itemTable.yxeditgrid({
		objName : 'outplan[productsdetail]',
		url : "?model=contract_contract_equ&action=productJson",
		title : '物料清单',
		isAdd : false,
		param : {
			docType : docType,
			equIds : equIds,
			isDel : 0,
			contractId : contractId
		},

		colModel : [{
					name : 'contEquId',
					display : 'id',
					type : 'hidden'
				},{
					name : 'productNo',
					display : '物料编号',
					sortable : true,
					tclass : 'readOnlyTxtShort',
					readonly : true
				}, {
					name : 'productName',
					display : '物料名称',
					tclass : 'readOnlyTxtShort',
					readonly : true,
					sortable : true
				}, {
					name : 'productId',
					display : '物料ID',
					sortable : true,
					type : 'hidden'
				}, {
					name : 'productModel',
					display : '规格型号',
					sortable : true,
					readonly : true,
					tclass : 'readOnlyTxtMiddle'
				}, {
					name : 'unitName',
					display : '单位',
					tclass : 'readOnlyTxtItem',
					readonly : true
				}, {
					name : 'stockId',
					display : '出货仓库Id',
					tclass : 'readOnlyTxtItem',
					readonly : true,
					type : 'hidden'
				}, {
					name : 'outStockCode',
					display : '出货仓库编码',
					tclass : 'readOnlyTxtItem',
					readonly : true,
					type : 'hidden'
				}, {
					name : 'outStockName',
					display : '出货仓库名称',
					sortable : true,
					tclass : 'txtshort',
					width : 100,
					process: function($input, rowData) {
	                       var rowNum = $input.data("rowNum");
			                var g = $input.data("grid");
			                $input.yxcombogrid_stockinfo({
			                    hiddenId: 'itemTable_cmp_outStockNameId' + rowNum,
			                    height: 250,
								isFocusoutCheck : false,
			                    gridOptions: {
			                        showcheckbox: false,
			                        isTitle: true,
			                        event: {
			                            "row_dblclick": (function(rowNum) {
			                                return function(e, row, rowData) {
			                                    $("#itemTable_cmp_outStockName" + rowNum).val(rowData.stockName);
			                                    $("#itemTable_cmp_outStockCode" + rowNum).val(rowData.stockCode);
			                                    $("#itemTable_cmp_outStockNameId" + rowNum).val(rowData.id);
			                                }
			                            })(rowNum)
			                        }
			                    }
			                });
	                 }
				}, {
					name : 'contractNum',
					display : '合同数量',
					sortable : true,
					width : 60,
					type : 'statictext'
				}, {
					name : 'backNum',
					display : '已退数量',
					sortable : true,
					width : 60,
					type : 'statictext'
				}, {
					name : 'contNum',
					display : '可下达数量',
					sortable : true,
					width : 60,
					type : 'statictext'
				}, {
					name : 'contRemain',
					display : 'contRemain',
					sortable : true,
					type : 'hidden'
				}, {
					name : 'lockNum',
					display : 'lockNum',
					sortable : true,
					type : 'hidden'
				}, {
					name : 'issuedShipNum',
					display : 'issuedShipNum',
					sortable : true,
					type : 'hidden'
				}, {
					name : 'number',
					display : '本次计划发货数量',
					sortable : true,
					event : {
		            	'change' : function(e){
		            		var g= e.data.gird;
			        		var rowNum=e.data.rowNum;
			        		var contNum=g.getCmpByRowAndCol(rowNum, 'contNum'); // 当前数量
			        		var contNum = parseInt(contNum.val());
							var qty=$(this).val();//数量
							if(isNaN(qty)){
								alert('请输入正确数量！');
								g.getCmpByRowAndCol(rowNum, 'number').val("");
								return;
							}
							if(qty<0){
								alert('请输入正确数量！');
								g.getCmpByRowAndCol(rowNum, 'number').val("");
								return;
							}
							if(qty>contNum.val()){
								alert('请输入正确数量！');
								g.getCmpByRowAndCol(rowNum, 'number').val("");
								return;
							}
		                }
            		}
				}, {
					name : 'issuedPurNum',
					display : '即时库存',
					sortable : true,
					type : 'statictext',
					event: {
		                'click': function(e) {
							var rowNum = $(this).data("rowNum");
							// 获取licenseid
							var productObj = $("#itemTable_cmp_productId" + rowNum);
		                 if(productObj.val() == ''){
		                    // 弹窗
							url = "?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList"
								+ "&productId="
								+ $("#itemTable_cmp_productId"+ rowNum).val();
							var returnValue = showModalDialog(url, '',"dialogWidth:1000px;dialogHeight:600px;");
							if(returnValue){
								productObj.val(returnValue);
							}
		                 }else{
		                    // 弹窗
		                	 url = "?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList"
									+ "&productId="
									+ $("#itemTable_cmp_productId"+ rowNum).val();
							var returnValue = showModalDialog(url, '',"dialogWidth:1000px;dialogHeight:600px;");

							if(returnValue){
								productObj.val(returnValue);
							}
		                 }
						}
		            },
		            html : "<span class='blue'>即时库存</span>"
				}]
	});
	validate({
		"shipPlanDate": {
            required: true
        }
    });
	//保修状况
	$("#isWarranty").change(function(){
		$("#isWarrantyName").val($("#isWarranty").find("option:selected").text());
	});
	if($("#isNeedConfirm").val()=='1'){
		$("#overTimeRea").show();
	}else{
		$("#overTimeRea").hide();
	}
});




///** ********************条目列表************************ */
//function dynamic_add(packinglist, countNumP) {
//	mycount = document.getElementById(countNumP).value * 1 + 1;
//	var packinglist = document.getElementById(packinglist);
//	i = packinglist.rows.length;
//	oTR = packinglist.insertRow([i]);
//
//
//
//	var oTL0 = oTR.insertCell([0]);
//	oTL0.innerHTML = i;
//	var oTL1 = oTR.insertCell([1]);
//	oTL1.innerHTML = "<input type='text' id='productNo" + mycount
//			+ "' class='productNo txtmiddle' name='outplan[productsdetail][" + mycount
//			+ "][productNo]' readonly />";
//	var oTL2 = oTR.insertCell([2]);
//	oTL2.innerHTML = "<input type='hidden' id='productId" + mycount + "'  name='outplan[productsdetail][" + mycount + "][productId]'/>" +
//			"<input id='productName" + mycount + "' type='text' class='readOnlyTxtNormal' readonly='readonly' name='outplan[productsdetail][" + mycount + "][productName]' readonly/>";
//
//
//
//	// 单选产品
//	$("#productNo" + mycount).yxcombogrid_product({
//		hiddenId : 'productId' + mycount,
//		gridOptions : {
//			showcheckbox : false,
//			event : {
//				'row_dblclick' : function(mycount){
//						return function(e, row, data) {
//						$("#productNo"+mycount).val(data.sequence);
//						$("#productName"+mycount).val(data.productName);
//						$("#productModel"+mycount).val(data.pattern);
//					};
//			  	}(mycount)
//			}
//		}
//	});
//
//
//
//	var oTL3 = oTR.insertCell([3]);
//	oTL3.innerHTML = "<input id='productModel" + mycount
//			+ "' type='text' class='readOnlyTxtNormal' readonly='readonly' name='outplan[productsdetail]["
//			+ mycount + "][productModel]'>";
//	var oTL4 = oTR.insertCell([4]);
//	oTL4.innerHTML = "<input type='hidden' id='dstockId" + mycount + "' name='outplan[productsdetail][" + mycount + "][stockId]'/>"
//			+ "<input type='hidden' id='dstockCode" + mycount + "' name='outplan[productsdetail][" + mycount + "][stockCode]'/>"
//			+ "<input type='text' id='dstockName" + mycount
//			+ "' class='stockName txtmiddle' name='outplan[productsdetail][" + mycount
//			+ "][stockName]' readonly/>";
//	// 仓库下拉
//	$("#dstockName" + mycount).yxcombogrid_stockinfo({
//		hiddenId : 'dstockId'+ mycount,
//		gridOptions :{
//			showcheckbox : false,
//			event : {
//				'row_dblclick' : function(mycount){
//					return function(e, row, data) {
//						$('#dstockId' + mycount).val(data.id);
//						$('#dstockCode' + mycount).val(data.stockCode);
//						$('#dstockName' + mycount).val(data.stockName);
//					}
//				}(mycount)
//			}
//		}
//	});
//
//	$("#dstockId" + mycount).val($("#stockId").val());
//	$("#dstockCode" + mycount).val($("#stockCode").val());
//	$("#dstockName" + mycount).val($("#stockName").val());
//
//	var oTL5 = oTR.insertCell([5]);
//	oTL5.innerHTML = "<input id='applyNum" + mycount
//			+ "' type='text' class='txtshort' name='outplan[productsdetail]["
//			+ mycount + "][applyNum]'>";
//	var oTL6 = oTR.insertCell([6]);
//	oTL6.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
//			+ packinglist.id + "\")' title='删除行'>";
//
//	document.getElementById(countNumP).value = document
//			.getElementById(countNumP).value
//			* 1 + 1;
//
//}
//
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
	if($('#borrowbody').get(0)&&$('#borrowbody').get(0).rows.length==1){
		$('#borrowTr').hide();
	}
}

/** ********************删除动态表单************************ */
function mydelBorrow(obj, mytable) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 1;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;
		for (i = 1; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i;
		}
	}
	if($('#borrowbody').get(0)&&$('#borrowbody').get(0).rows.length==1){
		$('#borrowTr').hide();
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

function changeDate(){
	//计算周次
	$.ajax({
		type : 'POST',
		url : '?model=stock_outplan_outplan&action=week',
		data : {
			date : $('#shipPlanDate').val()
		},
		success : function(data) {
			$('#week').val(data)
		}
	});
	var docType = $("#docType").val();
	// 暂时去掉  超期发货需要销售确认的 代码
//	if(docType == 'oa_contract_contract'){
//	  //判断是否超期发货
//		$.ajax({
//			type : "POST",
//			url : "?model=stock_outplan_outplan&action=checkShipPlanDate",
//			async: false,
//			data : {
//				shipPlanDate :  $("#shipPlanDate").val(),
//				contractId : $("#docId").val()
//			},
//			success : function(msg) {
//				if (msg == 1) {
//					$("#isNeedConfirm").val('1');
//					$("#overTimeRea").show();
//				}else {
//					$("#isNeedConfirm").val('0');
//					$("#overTimeReason").val('');
//					$("#overTimeRea").hide();
//				}
//			}
//		});
//	}
}

function issuedFun(){
	document.getElementById('form1').action="?model=stock_outplan_outplan&action=add&issued=true&msg=下达成功";
}

//检测计划发货日期
function checkOutplan(){
		var shipPlanDate = $("#shipPlanDate").val();
		var arr1="19000101";
		var arr2="21000101";
		var date1=new Date(parseInt(arr1.substr(0,4)),parseInt(arr1.substr(4,2))-1,parseInt(arr1.substr(6,2)),0,0,0);
		var date2=new Date(parseInt(arr2.substr(0,4)),parseInt(arr2.substr(4,2))-1,parseInt(arr2.substr(6,2)),0,0,0);
		if(Number(shipPlanDate)<Number(date1) || Number(shipPlanDate)>Number(date2)){
			alert("日期必须在1900-01-01和2100-01-01之间");
		}
	   // onerror: "日期必须在\"1900-01-01\"和\"2100-01-01\"之间"
}

//检查计划发货日期是否大于希望发货日期
function checkDate(){
	var result;
	if($("#isNeedConfirm").val()=='1'){
		if(confirm("计划发货日期大于希望发货日期，需要销售人员确认，是否确认下达发货计划?")){
			if($("#overTimeReason").val()==''){
				alert("超期原因不能为空！");
				result =  false;
			}else{
				result = true;
			}
		}else{
				result =  false;
		}
	}else{
		result =  true;
	}
	return result;
}