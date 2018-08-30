$(document).ready(function (){

	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg){
		},
		onsuccess: function(){
			return true;
		}
	});


    $("#dateHope").formValidator({
        onshow: "��ѡ��Ԥ�Ƶ�������",
        onfocus: "��ѡ��Ԥ�Ƶ�������",
        oncorrect: "OK"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "������Ϸ�������,�´�����Ϊ��"
    }).defaultPassed();


    $("#dateFact").formValidator({
        onshow: "��ѡ��ʵ���������",
        onfocus: "��ѡ��ʵ���������",
        oncorrect: "OK"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "��ѡ��Ϸ�������,����Ϊ��"
    }).defaultPassed();

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
    }).defaultPassed();


});