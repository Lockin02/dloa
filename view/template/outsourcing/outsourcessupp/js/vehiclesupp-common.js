$(document).ready(function() {

});

function getProvince(){
	var provinceArr;
	//缓存省份信息
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

//业务分布信息
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
        	//判断 如果没有初始化数组中，则选中
        	if(businessDistributeArr.indexOf(obj.provinceName) == -1){
        		str = "<input type='checkbox' id='businessDistribute_"+ obj.provinceName +"' value='"+ obj.provinceName +"'/> " + obj.provinceName;
        	}else{
        		str = "<input type='checkbox' id='businessDistribute_"+ obj.provinceName +"' value='"+ obj.provinceName +"' checked='checked'/> " + obj.provinceName;
        	}
			return str;
        },
		onSelect : function(obj){
			//checkbox设值
			$("#businessDistribute_" + obj.provinceName).attr('checked',true);
			//设置对象下的选中项
			mulSelectSet(businessDistributeObj);
		},
		onUnselect : function(obj){
			//checkbox设值
			$("#businessDistribute_" + obj.provinceName).attr('checked',false);
			//设置隐藏域
			mulSelectSet(businessDistributeObj);
		}
	});
	//初始化赋值
	mulSelectInit(businessDistributeObj);
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