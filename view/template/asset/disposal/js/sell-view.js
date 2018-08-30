$(document).ready(function() {
			$("#purchaseProductTable").yxeditgrid({
				objName:'sell[item]',
    			url:'?model=asset_disposal_sellitem&action=listJson',
    			type:'view',
    			param:{sellID:$("#sellID").val(),
				       assetId:$("#assetId").val()},
       		       colModel : [{
					display:'��Ƭ���',
					name : 'assetCode'
				}, {
					display:'�ʲ�����',
					name : 'assetName'
				},{
					display:'Ӣ������',
					name : 'englishName',
					tclass : 'txtshort'
				}, {
					display:'��������',
					name : 'buyDate',
					type:'date',
					tclass : 'txtshort'
				}, {
					display:'����ͺ�',
					name : 'spec',
					tclass : 'txtshort'
				}, {
					display:'�����豸',
					name : 'equip',
					type:'statictext',
                    process : function(e, data){
                    return '<a href="#" onclick="javascript:window.open(\'?model=asset_assetcard_equip&action=toPage&showBtn=0&assetCode='+data.assetCode+'\')">��ϸ</a>'
				     }
				}
//				, {
//					display:'��������',
//					name : 'estimateDay',
//					tclass : 'txtshort'
//				}
				, {
					display:'�Ѿ�ʹ���ڼ���',
					name : 'alreadyDay',
					tclass : 'txtshort'
				},{
					display:'�۳�����',
					name : 'deptName',
					tclass : 'txtshort'
				}, {
					display:'�۳�ǰ��;',
					name : 'beforeUse',
					tclass : 'txtshort'
				}, {
					display:'���۾ɽ��',
					name : 'depreciation',
					tclass : 'txtshort',
               		 //�б��ʽ��ǧ��λ
               		 process : function(v){
					return moneyFormat2(v);
					}
				},{
					display:'�����ֵ',
					name : 'salvage',
					tclass : 'txtshort',
               		 //�б��ʽ��ǧ��λ
               		 process : function(v){
					return moneyFormat2(v);
					}
				}
//				, {
//					display:'���۾ɶ�',
//					name : 'monthDepr',
//					tclass : 'txtshort',
//               		 //�б��ʽ��ǧ��λ
//               		 process : function(v){
//					return moneyFormat2(v);
//					}
//				}
				, {
					display:'��ע',
					name : 'remark',
					tclass : 'txt'
				}]
		   });

		   //�ύ������鿴����ʱ���عرհ�ť
			if($("#showBtn").val()==1){
				$("#btn").hide();
			}

		});