$(document).ready(function() {
			$("#purchaseProductTable").yxeditgrid({
				objName:'lose[item]',
    			url:'?model=asset_daily_loseitem&action=listJson',
    			type:'view',
    			param:{loseId:$("#loseId").val(),
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
				}, {
					display : '��������',//orgName
					name : 'orgName',
					tclass : 'txt',
					readonly:true
				}, {
					display : 'ʹ�ò���',//useOrgName
					name : 'useOrgName',
					tclass : 'txtshort',
					readonly:true
				}, {
					display:'�����豸',
					name : 'equip',
					type:'statictext',
                    process : function(e, data){
                    return '<a href="#" onclick="javascript:window.open(\'?model=asset_assetcard_equip&action=toPage&showBtn=0&assetCode='+data.assetCode+'\')">��ϸ</a>'
				     }
				}, {
					display : '����ԭֵ',
					name : 'origina',
					tclass : 'txt',
					readonly:true
				}, {
					display : '�Ѿ�ʹ���ڼ���',
					name : 'alreadyDay',
					tclass : 'txtshort',
					readonly:true
				}, {
					display : '�ۼ��۾ɽ��',
					name : 'depreciation',
					tclass : 'txtmiddle',
					readonly:true,
               		 //�б��ʽ��ǧ��λ
               		 process : function(v){
					return moneyFormat2(v);
					}
				}, {
					display : '��ֵ',
					name : 'salvage',
					tclass : 'txtmiddle',
					readonly:true,
               		 //�б��ʽ��ǧ��λ
               		 process : function(v){
					return moneyFormat2(v);
					}
				}, {
					display : '��ע',
					name : 'remark',
					tclass : 'txt'
				}]
		   })


          //�ύ������鿴����ʱ���عرհ�ť
			if($("#showBtn").val()==1){
				$("#btn").hide();
			}

		});