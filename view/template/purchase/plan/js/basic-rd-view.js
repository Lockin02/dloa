$(function() {

			if($('#readType').val()!="" ){
				$('#actType').hide();
			}
	$("#RDProductTable").yxeditgrid({
		objName : 'basic[equipment]',
		 delTagName : 'isDelTag',
		type : 'view',
		url : '?model=purchase_plan_equipment&action=listJson',
		param : {
			basicId : $("#id").val()
		},
		colModel : [{
					display : 'id',
					name : 'id',
					type : 'hidden'
				}, {
					display : '�Ƿ�����̶��ʲ�',
					name : 'isAsset',
					tclass : 'txtmin',
					process : function(v, row) {
						if(v=="on"){
							return "��";
						}else{
							return "��";
						}
					}
				}, {
					display : 'productId',
					name : 'productId',
					type : 'hidden'
				}, {
					display : '�豸����',
					name : 'productNumb'
				}, {
					display : '�豸����',
					name : 'productName',
					tclass : 'txt'
				}, {
					display : '����ͺ�',
					name : 'pattem'
					// validation : {
				// required : true
				// }
			}	, {
					display : '��λ',
					name : 'unitName',
					tclass : 'txtshort'
				}, {
					display : '����',
					name : 'amountAll',
					tclass : 'txtshort'
				}, {
					display : '��Ӧ��',
					name : 'surpplierName'
				}, {
					display : 'ϣ����������',
					name : 'dateHope',
					tclass : 'txtshort'
				}, {
					display : '�豸ʹ������',
					name : 'equUseYear',
					tclass : 'txtshort',
					process : function(v, row) {
						if(v==0){
							return "һ������";
						}else{
							return "һ������";
						}
					}
				}, {
					display : 'Ԥ�ƹ��뵥��',
					name : 'planPrice',
					tclass : 'txtmiddle',
					process : function(v, row) {
						if(v==0){
							return "500Ԫ����";
						}else{
							return "500Ԫ����";
						}
					}

				}, {
					display : '��ע',
					name : 'remark',
					tclass : 'txt'
				}]
	})

});