$(document).ready(function() {
			$("#purchaseProductTable").yxeditgrid({
				objName:'charge[item]',
    			url:'?model=asset_daily_chargeitem&action=listJson',
    			type:'view',
    			param:{allocateID:$("#allocateID").val(),
    			       assetId:$("#assetId").val()},
       		colModel : [
//       			{
//				display:'���',
//				name : 'sequence'
//				},
				{
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
				}

				, {
					display : 'Ԥ��ʹ���ڼ���',
					name : 'estimateDay',
					tclass : 'txtshort',
					readonly:true
				}, {
					display : '�Ѿ�ʹ���ڼ���',
					name : 'alreadyDay',
					tclass : 'txtshort',
					readonly:true
				}, {
					display : 'ʣ��ʹ���ڼ���',//���ڿ�Ƭ��Ԥ��ʹ���ڼ�����ȥ��ʹ���ڼ���
					name : 'residueYears',
					tclass : 'txtshort',
					readonly:true
				}, {
					display : '��ע',
					name : 'remark',
					tclass : 'txt',
					readonly:true
				}]
		   })


          //�ύ������鿴����ʱ���عرհ�ť
			if($("#showBtn").val()==1){
				$("#btn").hide();
			}

		});