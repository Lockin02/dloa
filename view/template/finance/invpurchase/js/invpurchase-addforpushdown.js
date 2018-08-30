

$(function() {

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

	if($("#isRed").length != 0){
		if($("#isRed").val() == 1){
			$("input[name='invpurchase[formType]'][value='red']").attr('checked',true);
			changeTitle('red');
		}
	}

	if($("#supplierName").val() == ""){
		$("#supplierName").yxcombogrid_supplier({
			hiddenId : 'supplierId',
			isShowButton : false,
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
		//处理必填文本框
		$("#supplierNameNeed").attr("class","red").html("[*]");
		//解开输入文本框限制
		$("#supplierName").attr("class","txt").attr("readonly",false);
	}


	changeTaxRateClear('invType');
	countInit();
});


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
}