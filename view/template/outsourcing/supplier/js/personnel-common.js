$(document).ready(function() {

  })
//�������֤
     function checkIDCard (obj)
{
	str = $(obj).val();
	if(isIdCardNo(str)){
	}else{
		$(obj).val('');
	}

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

//��ʼ���ó������豸��Ϣ
function initTradeList(){
		var tradeListArr = $('#tradeListHidden').val().split(",");
		var str;
		var tradeListObj = $('#tradeList');
		tradeListObj.combobox({
			url:'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=WBCJSB',
			multiple:true,
			valueField:'text',
            textField:'text',
			editable : false,
	        formatter: function(obj){
	        	//�ж� ���û�г�ʼ�������У���ѡ��
	        	if(tradeListArr.indexOf(obj.text) == -1){
	        		str = "<input type='checkbox' id='tradeList_"+ obj.text +"' value='"+ obj.text +"'/> " + obj.text;
	        	}else{
	        		str = "<input type='checkbox' id='tradeList_"+ obj.text +"' value='"+ obj.text +"' checked='checked'/> " + obj.text;
	        	}
				return str;
	        },
			onSelect : function(obj){
				//checkbox��ֵ
				$("#tradeList_" + obj.text).attr('checked',true);
				//���ö����µ�ѡ����
				mulSelectSet(tradeListObj);
			},
			onUnselect : function(obj){
				//checkbox��ֵ
				$("#tradeList_" + obj.text).attr('checked',false);
				//����������
				mulSelectSet(tradeListObj);
			}
		});

		//�ͻ����ͳ�ʼ����ֵ
		mulSelectInit(tradeListObj);
}

//��ʼ���ó������豸��Ϣ
function initCertifyList(){
		var certifyListArr = $('#certifyListHidden').val().split(",");
		var str;
		var certifyListObj = $('#certifyList');
		certifyListObj.combobox({
			url:'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=WBRYZZ',
			multiple:true,
			valueField:'text',
            textField:'text',
			editable : false,
	        formatter: function(obj){
	        	//�ж� ���û�г�ʼ�������У���ѡ��
	        	if(certifyListArr.indexOf(obj.text) == -1){
	        		str = "<input type='checkbox' id='certifyList_"+ obj.text +"' value='"+ obj.text +"'/> " + obj.text;
	        	}else{
	        		str = "<input type='checkbox' id='certifyList_"+ obj.text +"' value='"+ obj.text +"' checked='checked'/> " + obj.text;
	        	}
				return str;
	        },
			onSelect : function(obj){
				//checkbox��ֵ
				$("#certifyList_" + obj.text).attr('checked',true);
				//���ö����µ�ѡ����
				mulSelectSet(certifyListObj);
			},
			onUnselect : function(obj){
				//checkbox��ֵ
				$("#certifyList_" + obj.text).attr('checked',false);
				//����������
				mulSelectSet(certifyListObj);
			}
		});

		//�ͻ����ͳ�ʼ����ֵ
		mulSelectInit(certifyListObj);
}