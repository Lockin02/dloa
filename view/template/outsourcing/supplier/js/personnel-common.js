$(document).ready(function() {

  })
//检验身份证
     function checkIDCard (obj)
{
	str = $(obj).val();
	if(isIdCardNo(str)){
	}else{
		$(obj).val('');
	}

}

//隐藏区域设置
function mulSelectSet(thisObj){
	thisObj.next().find("input").each(function(i,n){
		if($(this).attr('class') == 'combo-text validatebox-text'){
			$("#"+ thisObj.attr('id') + "Hidden").val(this.value);
		}
	});
}

//设值多选值 -- 初始化赋值
function mulSelectInit(thisObj){
	//初始化对应内容
	var objVal = $("#"+ thisObj.attr('id') + "Hidden").val();
	if(objVal != "" ){
		thisObj.combobox("setValues",objVal.split(','));
	}
}

//初始化擅长厂家设备信息
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
	        	//判断 如果没有初始化数组中，则选中
	        	if(tradeListArr.indexOf(obj.text) == -1){
	        		str = "<input type='checkbox' id='tradeList_"+ obj.text +"' value='"+ obj.text +"'/> " + obj.text;
	        	}else{
	        		str = "<input type='checkbox' id='tradeList_"+ obj.text +"' value='"+ obj.text +"' checked='checked'/> " + obj.text;
	        	}
				return str;
	        },
			onSelect : function(obj){
				//checkbox设值
				$("#tradeList_" + obj.text).attr('checked',true);
				//设置对象下的选中项
				mulSelectSet(tradeListObj);
			},
			onUnselect : function(obj){
				//checkbox设值
				$("#tradeList_" + obj.text).attr('checked',false);
				//设置隐藏域
				mulSelectSet(tradeListObj);
			}
		});

		//客户类型初始化赋值
		mulSelectInit(tradeListObj);
}

//初始化擅长厂家设备信息
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
	        	//判断 如果没有初始化数组中，则选中
	        	if(certifyListArr.indexOf(obj.text) == -1){
	        		str = "<input type='checkbox' id='certifyList_"+ obj.text +"' value='"+ obj.text +"'/> " + obj.text;
	        	}else{
	        		str = "<input type='checkbox' id='certifyList_"+ obj.text +"' value='"+ obj.text +"' checked='checked'/> " + obj.text;
	        	}
				return str;
	        },
			onSelect : function(obj){
				//checkbox设值
				$("#certifyList_" + obj.text).attr('checked',true);
				//设置对象下的选中项
				mulSelectSet(certifyListObj);
			},
			onUnselect : function(obj){
				//checkbox设值
				$("#certifyList_" + obj.text).attr('checked',false);
				//设置隐藏域
				mulSelectSet(certifyListObj);
			}
		});

		//客户类型初始化赋值
		mulSelectInit(certifyListObj);
}