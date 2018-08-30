$(document).ready(function() {
    //�����ʼ����ݱ�
    var itemTableObj = $("#itemTable");

    itemTableObj.yxeditgrid({
        objName: 'batch[items]',
        url: "?model=flights_balance_batchitem&action=listJson&sort=auditDate&dir=ASC",
        param: {
            "mainId": $("#id").val()
        },
        event: {
            'reloadData': function(e) {
                //��ȡ����������ֶ�
                var itemArr = itemTableObj.yxeditgrid("getCmpByCol", "costPay");

                //���ڹ��񳤶�ʱ
                if(itemArr.length > 15){
					itemTableObj.attr("style", "overflow-x:auto;overflow-y:auto;height:480px;");
                }
			}
        },
        type: 'view',
//        title: '��Ʊ��Ϣ',
        colModel: [{
            name: 'msgType',
            display: '��������',
            type: 'statictext',
            process : function(v){
				if(v == "0"){
					return '<span class="green">����</span>';
				}else if(v == '1'){
					return '<span class="blue">��ǩ</span>';
				}else{
					return '<span class="red">��Ʊ</span>';
				}
            },
            width : 70
        },{
            name: 'auditDate',
            display: '��������'
        },{
            name: 'airName',
            display: '�˻���'
        },{
            name: 'airId',
            display: 'airId',
            type : 'hidden'
        },{
            name: 'airline',
            display: '���չ�˾',
            align :'left',
            process : function(v){
            	return '<span style="padding:0px 0px 0px 10px;">'+v+'</span>';
            }
        },{
            name: 'airlineId',
            display: 'airlineId',
            type : 'hidden'
        },{
            name: 'flightNumber',
            display: '����/�����',
            align :'left',
            process : function(v){
            	return '<span style="padding:0px 0px 0px 10px;">'+v+'</span>';
            }
        },{
            name: 'flightTime',
            display: '�˻�ʱ��'
        },{
            name: 'arrivalTime',
            display: '����ʱ��'
        },{
            name: 'departPlace',
            display: '�����ص�'
        },{
            name: 'arrivalPlace',
            display: '����ص�'
        },{
            name: 'costPay',
            display: 'ʵ�ʸ�����',
            align :'right',
            process : function(v){
            	return '<span style="padding:0px 10px 0px 0px;">'+moneyFormat2(v)+'</span>';
            }
        }]
    });
});