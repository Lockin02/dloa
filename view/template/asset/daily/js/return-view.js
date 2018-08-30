$(document).ready(function() {
			$("#purchaseProductTable").yxeditgrid({
				objName:'return[item]',
    			url:'?model=asset_daily_returnitem&action=listJson',
    			type:'view',
    			param:{allocateID :$("#allocateID").val(),
				       assetId:$("#assetId").val()},
       		       colModel : [{
					display:'��Ƭ���',
					name : 'assetCode'
				}, {
					display:'�ʲ�����',
					name : 'assetName'
				}, {
					display : '����ͺ�',
					name : 'spec',
					tclass : 'txtshort',
					readonly:true
				}, {
					display : '��������',
					name : 'buyDate',
					//type : 'date',
					tclass : 'txtshort',
					readonly:true
				}, {
					display : 'Ԥ��ʹ���ڼ���',
					name : 'estimateDay',
					tclass : 'txtshort',
					readonly:true
				}, {
					display : '�Ѿ�ʹ���ڼ���',
					name : 'alreadyDay',
					tclass : 'txtshort',
					readonly:true
				}
				, {
					display : 'ʣ��ʹ���ڼ���',
					name : 'residueYears',
					tclass : 'txtshort',
					readonly:true
				}
				, {
					display : '��ע',
					name : 'remark',
					tclass : 'txt'
				}]
		   });

		     // ��select��Ĵ���--�黹���ͣ��Ѿ�������Ӧ�ı�
		   switch($('#returnType').text()){
		   	case 'other' :
		   		$("#returnType").text("����");
				$("#otherNo").hide();
				$("#chargeNo").hide();
				$("#borrowNo").hide();
		   		break;
		   	case 'oa_asset_borrow' :
		   		$("#returnType").text("���ù黹");
				$("#chargeNo").hide();
		   		break;
		   	case 'oa_asset_charge' :
		   		$("#returnType").text("���ù黹");
				$("#borrowNo").hide();
		   		break;
		   	case 'asset' :
		   		$("#returnType").text("�����黹");
				$("#borrowNo").hide();
				$("#chargeNo").hide();
		   		break;
		   }

		   //�ύ������鿴����ʱ���عرհ�ť
			if($("#showBtn").val()==1){
				$("#btn").hide();
			}

		});