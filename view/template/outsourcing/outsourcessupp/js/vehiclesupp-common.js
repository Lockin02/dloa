$(document).ready(function() {

});

function getProvince(){
	var provinceArr;
	//����ʡ����Ϣ
	$.ajax({
		type : 'POST',
		url : "?model=system_procity_province&action=getProvinceForEditGrid",
		data : {
			"countryId" : '1'
		},
	    async: false,
		success : function(data) {
			provinceArr = eval("(" + data + ")");
		}
	});
	return provinceArr;
}

//ҵ��ֲ���Ϣ
function initBusinessDistribute(){
	var businessDistributeArr = $('#businessDistributeHidden').val().split(",");
	var str;
	var businessDistributeObj = $('#businessDistribute');
	businessDistributeObj.combobox({
		url:'index1.php?model=system_procity_province&action=listJson&dir=ASC',
		multiple:true,
		valueField:'provinceName',
        textField:'provinceName',
		editable : false,
        formatter: function(obj){
        	//�ж� ���û�г�ʼ�������У���ѡ��
        	if(businessDistributeArr.indexOf(obj.provinceName) == -1){
        		str = "<input type='checkbox' id='businessDistribute_"+ obj.provinceName +"' value='"+ obj.provinceName +"'/> " + obj.provinceName;
        	}else{
        		str = "<input type='checkbox' id='businessDistribute_"+ obj.provinceName +"' value='"+ obj.provinceName +"' checked='checked'/> " + obj.provinceName;
        	}
			return str;
        },
		onSelect : function(obj){
			//checkbox��ֵ
			$("#businessDistribute_" + obj.provinceName).attr('checked',true);
			//���ö����µ�ѡ����
			mulSelectSet(businessDistributeObj);
		},
		onUnselect : function(obj){
			//checkbox��ֵ
			$("#businessDistribute_" + obj.provinceName).attr('checked',false);
			//����������
			mulSelectSet(businessDistributeObj);
		}
	});
	//��ʼ����ֵ
	mulSelectInit(businessDistributeObj);
}

//������������
function mulSelectSet(thisObj){
	thisObj.next().find("input").each(function(i,n){
		if($(this).attr('class') == 'combo-text validatebox-text'){
			$("#"+ thisObj.attr('id') + "Hidden").val(this.value);
		}
	});
}

//��ֵ��ѡֵ -- ��ʼ����ֵ
function mulSelectInit(thisObj){
	//��ʼ����Ӧ����
	var objVal = $("#"+ thisObj.attr('id') + "Hidden").val();
	if(objVal != "" ){
		thisObj.combobox("setValues",objVal.split(','));
	}
}