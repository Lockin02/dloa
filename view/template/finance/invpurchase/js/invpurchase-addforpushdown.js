

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
	//������˾
	$("#businessBelongName").yxcombogrid_branch({
		hiddenId : 'businessBelong',
		height : 250,
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
					//��ʼ�����ṹ
					initTree();
					//�������η�Χ
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
		//��������ı���
		$("#supplierNameNeed").attr("class","red").html("[*]");
		//�⿪�����ı�������
		$("#supplierName").attr("class","txt").attr("readonly",false);
	}


	changeTaxRateClear('invType');
	countInit();
});


/**********************ɾ����̬��*************************/
function mydel(obj,mytable)
{
	if(confirm('ȷ��Ҫɾ�����У�')){
		var rowNo = obj.parentNode.parentNode.rowIndex*1 - 1;
		var mytable = document.getElementById(mytable);
   		mytable.deleteRow(rowNo);
   		var myrows=mytable.rows.length;
   		for(i=1;i<myrows;i++){
			mytable.rows[i].childNodes[0].innerHTML=i;
		}
	}
}