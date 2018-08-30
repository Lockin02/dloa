$(function(){

	$("#salesman").yxselect_user({
		hiddenId : 'salesmanId'
	});

	$("#exaMan").yxselect_user({
		hiddenId : 'exaManId'
	});

	$("#supplierName").yxcombogrid_supplier({
		hiddenId : 'supplierId',
		isShowButton : false,
		gridOptions : {
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#address").val(data.address);
				}
			}
		}
	});

	//���ϱ��
	$("#productNo").yxcombogrid_product({
    	hiddenId : 'productId',
		gridOptions : {
			showcheckbox : false
		}
    });

	//���ݳ�ʼ������
	//���б�����ȡ
	var listGrid= parent.$("#invpurchaseGrid").data('yxsubgrid');

	$("#supplierName").val( filterUndefined(listGrid.options.extParam.supplierName) );
	$("#supplierId").val( filterUndefined(listGrid.options.extParam.supplierId) );

	$("#formDateBegin").val( filterUndefined(listGrid.options.extParam.formDateBegin) );
	$("#formDateEnd").val( filterUndefined(listGrid.options.extParam.formDateEnd) );

	$("#productNo").val( filterUndefined(listGrid.options.extParam.productNo) );

	$("#salesman").val( filterUndefined(listGrid.options.extParam.salesman) );
	$("#salesmanId").val( filterUndefined(listGrid.options.extParam.salesmanId) );

	$("#exaMan").val( filterUndefined(listGrid.options.extParam.exaMan) );
	$("#exaManId").val( filterUndefined(listGrid.options.extParam.exaManId) );

	$("#formType").val( filterUndefined(listGrid.options.extParam.formType) );
	$("#ExaStatus").val( filterUndefined(listGrid.options.extParam.ExaStatus) );
	$("#status").val( filterUndefined(listGrid.options.extParam.status) );

	$("#invType").val( filterUndefined(listGrid.options.extParam.invType) );
	$("#objNo").val( filterUndefined(listGrid.options.extParam.objNo) );

});
function confirm(){
	var listGrid= parent.$("#invpurchaseGrid").data('yxsubgrid');

	var formDateBegin=$("#formDateBegin").val();		//��ʼʱ���
	var formDateEnd=$("#formDateEnd").val();			//����ʱ��
	var supplierId=$("#supplierId").val();				//��Ӧ��id
	var supplierName=$("#supplierName").val();			//��Ӧ��
	var exaMan=$("#exaMan").val();					//�����
	var exaManId=$("#exaManId").val();					//�����

	var salesmanId=$("#salesmanId").val();				//ҵ��Ա
	var salesman=$("#salesman").val();				//ҵ��Ա

	var status=$("#status").val();						//����״̬
	var formType=$("#formType").val();					//������
	var ExaStatus=$("#ExaStatus").val();				//���״̬
	var invType=$("#invType").val();					//��Ʊ����
	var productNo=$("#productNo").val();				//���ϱ��
	var objNo=$("#objNo").val();				//���ϱ��

	listGrid.options.extParam['supplierId'] = supplierId;
	listGrid.options.extParam['supplierName'] = supplierName;
	listGrid.options.extParam['formDateBegin'] = formDateBegin;
	listGrid.options.extParam['formDateEnd'] = formDateEnd;
	listGrid.options.extParam['exaManId'] = exaManId;
	listGrid.options.extParam['exaMan'] = exaMan;

	listGrid.options.extParam['salesman'] = salesman;
	listGrid.options.extParam['salesmanId'] = salesmanId;

	listGrid.options.extParam['status'] = status;
	listGrid.options.extParam['formType'] = formType;
	listGrid.options.extParam['ExaStatus'] = ExaStatus;
	listGrid.options.extParam['invType'] = invType;
	listGrid.options.extParam['objNo'] = objNo;
	listGrid.options.extParam['productNo'] = productNo;

	listGrid.reload();
	self.parent.tb_remove();
}

function refresh(){
	var listGrid= parent.$("#invpurchaseGrid").data('yxsubgrid');

	listGrid.options.extParam['supplierId'] = "";
	listGrid.options.extParam['supplierName'] = "";

	listGrid.options.extParam['formDateBegin'] = "";
	listGrid.options.extParam['formDateEnd'] = "";

	listGrid.options.extParam['exaManId'] = "";
	listGrid.options.extParam['exaMan'] = "";

	listGrid.options.extParam['salesmanId'] = "";
	listGrid.options.extParam['salesman'] = "";

	listGrid.options.extParam['status'] = "";
	listGrid.options.extParam['formType'] = "";
	listGrid.options.extParam['ExaStatus'] = "";
	listGrid.options.extParam['invType'] = "";
	listGrid.options.extParam['productNo'] = "";
	listGrid.options.extParam['objNo'] = "";
	listGrid.reload();
	self.parent.tb_remove();

}