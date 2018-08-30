$(document).ready(function (){

	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg){
		},
		onsuccess: function(){
//			return true;
		}
	});


    $("#sendName").formValidator({
        onshow: "��ѡ�������",
        onfocus: "��ѡ�������",
        oncorrect: "OK"
    }).inputValidator({
        min: 1,
        onerror: "��ѡ�������"
    });


    $("#supplierName").formValidator({
        onshow: "��ѡ��Ӧ��",
        onfocus: "��ѡ��Ӧ��",
        oncorrect: "OK"
    }).inputValidator({
        min: 1,
        onerror: "��ѡ��Ӧ��"
    });

    $("#dateHope").formValidator({
        onshow: "��ѡ��Ԥ�Ƶ�������",
        onfocus: "��ѡ��Ԥ�Ƶ�������",
        oncorrect: "OK"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "��ѡ��Ϸ�������,����Ϊ��"
    });


    $("#dateFact").formValidator({
        onshow: "��ѡ�������������",
        onfocus: "��ѡ�������������",
        oncorrect: "OK"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "��ѡ��Ϸ�������,����Ϊ��"
    });

    //�Ժ�ͬ�Ž���Ψһ����֤
    $("#hwapplyNumb").formValidator({
        onshow: "������ɹ��������",
        onfocus: "������ɹ��������",
        oncorrect: "OK"
    }).inputValidator({
        min: 1,
        max: 100,
        empty:{
        	leftempty:false,
        	rightempty:false,
        	emptyerror:"��ͬ�����߲����пշ���"
        }
    }).ajaxValidator({
    	type:"get",
    	url:"index1.php",
    	data : "model=purchase_contract_purchasecontract&action=ajaxContractNumb",
    	datatype : "json",
    	success : function(data){
    		if(data == "1"){
    			return true;
    		}else{
    			return false;
    		}
    	},
    	buttons : $("#saveButton"),
    	error : function(){
    		alert("������û�з������ݣ����ܷ�����æ��������");
    	},
    	onerror : "�����Ʋ����ã������",
    	onwait : "���ڶԺ�ͬ��Ž���Ψһ����֤�����Ժ�..."
    });


});