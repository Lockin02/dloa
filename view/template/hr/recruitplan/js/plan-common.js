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

//初始化建议补充方式信息
function initPCC(){
		//获取建议补充方式
		var addModeNameArr = $('#addModeNameHidden').val().split(",");
		var str;
		//建议补充方式渲染
		var addModeNameObj = $('#addModeName');
		addModeNameObj.combobox({
			url:'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=HRBCFS',
			multiple:true,
			valueField:'text',
            textField:'text',
			editable : false,
	        formatter: function(obj){
	        	//判断 如果没有初始化数组中，则选中
	        	if(addModeNameArr.indexOf(obj.text) == -1){
	        		str = "<input type='checkbox' id='addModeName_"+ obj.text +"' value='"+ obj.text +"'/> " + obj.text;
	        	}else{
	        		str = "<input type='checkbox' id='addModeName_"+ obj.text +"' value='"+ obj.text +"' checked='checked'/> " + obj.text;
	        	}
				return str;
	        },
			onSelect : function(obj){
				//checkbox设值
				$("#addModeName_" + obj.text).attr('checked',true);
				//设置对象下的选中项
				mulSelectSet(addModeNameObj);
			},
			onUnselect : function(obj){
				//checkbox设值
				$("#addModeName_" + obj.text).attr('checked',false);
				//设置隐藏域
				mulSelectSet(addModeNameObj);
			}
		});

		//客户类型初始化赋值
		mulSelectInit(addModeNameObj);

}//初始化建议补充方式信息
function initLevel(data){
		//获取建议补充方式
		var positionLevelArr = $('#positionLevelHidden').val().split(",");
		var str;
		//建议补充方式渲染
		var positionLevelObj = $('#positionLevel');
		var dataArr=[{"positionLevel":"初级"},{"positionLevel":"中级"},{"positionLevel":"高级"}];
		if(data)
			dataArr=data;
		positionLevelObj.combobox({
			data : dataArr,
			multiple:true,
			editable : false,
			valueField:'positionLevel',
            textField:'positionLevel',
	        formatter: function(obj){
	        	//判断 如果没有初始化数组中，则选中
	        	if($.inArray(obj.positionLevel,positionLevelArr) == -1){
	        		str = "<input type='checkbox' id='positionLevel_"+ obj.positionLevel +"' value='"+ obj.positionLevel +"'/> " + obj.positionLevel;
	        	}else{
	        		str = "<input type='checkbox' id='positionLevel_"+ obj.positionLevel +"' value='"+ obj.positionLevel +"' checked='checked'/> " + obj.positionLevel;
	        	}
				return str;
	        },
			onSelect : function(obj){
				//checkbox设值
				$("#positionLevel_" + obj.positionLevel).attr('checked',true);
				//设置对象下的选中项
				mulSelectSet(positionLevelObj);
			},
			onUnselect : function(obj){
				//checkbox设值
				$("#positionLevel_" + obj.positionLevel).attr('checked',false);
				//设置隐藏域
				mulSelectSet(positionLevelObj);
			}
		});

		//客户类型初始化赋值
		mulSelectInit(positionLevelObj);
}

//选择网优类型职位时，加载数据字典内容
function initLevelWY(){
	var dataArr=[];
	var data=$.ajax({
					url:'?model=hr_basicinfo_level&action=pageJson&sort=personLevel&dir=ASC&status=0',
					type:'post',
					dataType:'json',
					async:false
				}).responseText;
	data=eval("("+data+")");
	data=data.collection;
	for(i=0;i<data.length;i++){
		dataArr.push({"positionLevel":data[i].personLevel})
	}
	initLevel(dataArr);
}
// 提交校验数据
function checkData(){

	if($("#addTypeCode").val()=="ZYLXLZ"){
		if($("#leaveManName").val()==""){
			alert("请输入离职/换岗人姓名");
			return false;
		}
	}else　if($("#positionLevelHidden").val()==""){
			alert("请选择级别");
			return false;

	}else　if($("#addModeNameHidden").val()==""){
			alert("请选择建议补充方式");
			return false;
	}else if($("#postType").val()==""){
		alert("请选择职位类型");
		return false;
	}else if($("#employmentTypeCode").val()==""){
		alert("请选择用工类型");
		return false;
	}else if($("#applyReason").val()==""){
		alert("请输入需求原因");
		return false;
	}
	else{
		return true;
	}
}

		// 验证是否已选择项目类型
	function checkProjectType(){
		if($("#projectType").val()==""){
			alert("请先选择项目类型");
		}
	}

$(function(){
	var $postType=$("#postType");

	if('YPZW-WY'==$postType.val()){
		initLevelWY();
	}
	//职位类型改变触发事件
	$postType.change(function(){
		$('#positionLevelHidden').val('');
		$('#positionLevel').val('');
		if($(this).val()=='YPZW-WY'){           //选择网优类型
			initLevelWY();
		}else{
			initLevel();
		}
	});
	//学历触发事件
	$("#education").change(function(){
		var edicationName=($(this).find('option:selected').text())
		$("input[name='plan[educationName]']").val(edicationName);
	});
});
function refreshContent(){
	$("#positionName").val("");
	$("#positionId").val("");
	var deptId=$("#deptId").val();
	$("#positionName").yxcombogrid_position("remove");
	//职位选择
	$("#positionName").yxcombogrid_position({
		hiddenId : 'positionId',
		width:350,
		gridOptions : {
			param:{deptId:deptId}
		}
	});
	//$("#positionName").yxcombogrid_position("show");
}