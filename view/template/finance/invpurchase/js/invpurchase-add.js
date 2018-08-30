
/**
 * 渲染产品列表
 */

$(function() {
	var listSize = $('#invnumber').val();
	var countSize = 0;
	//归属公司
	$("#businessBelongName").yxcombogrid_branch({
		hiddenId : 'businessBelong',
		height : 250,
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
					//初始化树结构
					initTree();
					//重置责任范围
					reloadManager();
				}
			}
		}
	});
	
	$("#supplierName").yxcombogrid_supplier({
		hiddenId : 'supplierId',
		height : 300,
		width : 600,
		gridOptions : {
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#address").val(data.address);
				}
			}
		}
	});
	for(var i = 1; i <= listSize ; i++ ){
		$("#productNo"+ i).yxcombogrid_product({
			hiddenId : 'productId'+ i,
			gridOptions : {
				event : {
					'row_dblclick' : function(i){
						return function(e, row, data) {
							$("#productName"+i).val(data.productName);
							$("#productModel"+i).val(data.pattern);
							$("#unit"+i).val(data.unitName);
						};
				  	}(i)
				}
			}
		});
	}


	for(var i = 1; i <= listSize ; i++ ){
		$("#productName"+ i).yxcombogrid_product({
			hiddenId : 'productId'+ i,
			nameCol : 'productName',
			gridOptions : {
				event : {
					'row_dblclick' : function(i){
						return function(e, row, data) {
							$("#productNo"+i).val(data.productCode);
							$("#productModel"+i).val(data.pattern);
							$("#unit"+i).val(data.unitName);
						};
				  	}(i)
				}
			}
		});
	}

	$("#head").yxselect_user({
		hiddenId : 'headId',
		formCode : 'invpurchaseHead'
	});
	$("#acount").yxselect_user({
		hiddenId : 'acountId',
		formCode : 'invpurchaseAcount'
	});
	$("#salesman").yxselect_user({
		hiddenId : 'salesmanId',
		formCode : 'invpurchase'
	});
	$("#departments").yxselect_dept({
		hiddenId : 'departmentsId'
	});

	if($("#id").length == 1){
		changeTaxRate('invType');
	}else{
		changeTaxRateWithOutCount('invType');
	}
});

function changeTaxRateWithOutCount(thisVal){
	taxRateObj = $("#taxRate");
	var taxRate = 0;
	if($("#" + thisVal).find("option:selected").attr("e1") != ""){
		taxRate = $("#" + thisVal).find("option:selected").attr("e1");
	}
	taxRateObj.val(taxRate);
}

function changeTaxRate(thisVal){
	taxRateObj = $("#taxRate");
	var taxRate = 0;
	if($("#" + thisVal).find("option:selected").attr("e1") != ""){
		taxRate = $("#" + thisVal).find("option:selected").attr("e1");
	}
	taxRateObj.val(taxRate);
	countAll();
}