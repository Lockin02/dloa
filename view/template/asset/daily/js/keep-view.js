$(document).ready(function() {
			$("#purchaseProductTable").yxeditgrid({
				objName:'keep[item]',
    			url:'?model=asset_daily_keepitem&action=listJson',
    			type:'view',
    			param:{keepId:$("#keepId").val()},
       		colModel : [
       			{
					display:'��Ƭ���',
					name : 'assetCode'
				}, {
					display:'�ʲ�����',
					name : 'assetName'
				}, {
					display : 'ά�޽��',
					name : 'amount',
					tclass : 'txtmiddle',
               		 //�б��ʽ��ǧ��λ
               		 process : function(v){
					return moneyFormat2(v);
					}
				}, {
					display : 'ʹ����',
					name : 'userName',
					//type : 'date',
					tclass : 'txtshort'
				}, {
					display : '��ע',
					name : 'remark',
					tclass : 'txt'
				}]
		   })

		   // ��select��Ĵ���--����ԭ��
			if ($("#keepType").text() == '1') {
				$("#keepType").text("�ճ�ά��");
			}
			if ($("#keepType").text() == '2') {
				$("#keepType").text("��ͨά��");
			}
			if ($("#keepType").text() == '3') {
				$("#keepType").text("�ش�ά��");
			}
          //�ύ������鿴����ʱ���عرհ�ť
			if($("#showBtn").val()==1){
				$("#btn").hide();
			}


		});