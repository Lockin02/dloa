
$(function() {
	// ��Ʒ�嵥
	$("#productInfo").yxeditgrid({
		objName : 'borrowreturnDis[product]',
		url:'?model=projectmanagent_borrowreturn_borrowreturnDisequ&action=listJson',
		tableClass : 'form_in_table',
		isAddAndDel : false,
		//type:'view',
		param:{
        	'disposeId' : $("#id").val()
        },
        event : {
			reloadData : function(event, g) {
				var rowCount = g.getCurRowNum();
				for (var i = 0; i < rowCount; i++) {
					var num = $("#productInfo_cmp_executedNum" + i).val();
					$("#productInfo_cmp_number" + i).val(num);
				}
			}
		},
		colModel : [{
			display : 'id',
			name : 'id',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			type : 'hidden'
		},{
			display : '���ϱ��',
			name : 'productNo',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		},{
			display : '��������',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '����������',
			name : 'disposeNum',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			isSubmit : true
		}, {
			display : '�⳥���',
			name : 'money',
			type : 'txt',
			process : function(v){
            	return '<span style="padding:0px 10px 0px 0px;">'+moneyFormat2(v)+'</span>';
            },
            event : {
            	blur : function (){
            		var itemTableObj = $("#productInfo");
            		//��ȡ����������ֶ�
            	    var moneyArr = itemTableObj.yxeditgrid("getCmpByCol", "money");
            	    var allMoney = 0;
            	    if (moneyArr.length > 0) {
            	        //ѭ��
            	    	moneyArr.each(function() {
            	            //accAdd�ӷ�
            	        	allMoney = accAdd(allMoney, $(this).val(), 2);
            	        });
            	    }
            	    setMoney("money",allMoney);
            	}
            }
		}, {
			name : 'serialName',
			display : '���к�',
			tclass : 'readOnlyTxtNormal',
			readonly : 'readonly'
		}],
		isAddOneRow:false,
		isAdd : false
	});
	/**
	 * ��֤��Ϣ
	 */
	validate({
		"money" : {
			required : true
		},
		"money_v" : {
			required : true
		}

	});
});



