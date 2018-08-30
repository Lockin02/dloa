var qualitystandardArr;
var dimensionArr;
var checkTypeArr;

//获取质检标准
function getQualitystandard(){
	var responseText = $.ajax({
		url : 'index1.php?model=produce_quality_standard&action=listJson',
		type : "POST",
		async : false
	}).responseText;
	var dataArr = eval("(" + responseText + ")");
	return dataArr;
}

//初始化质检标准
function initQualitystandard(thisKey,thisVal){
	var optionStr = '';
	qualitystandardArr = getQualitystandard();
	for(var i = 0;i < qualitystandardArr.length ; i++){
		if(thisVal && thisVal == qualitystandardArr[i].id){
			optionStr+= "<option value='" + qualitystandardArr[i].id + "' selected='selected'>" + qualitystandardArr[i].standardName + "</option>";
		}else{
			optionStr+= "<option value='" + qualitystandardArr[i].id + "'>" + qualitystandardArr[i].standardName + "</option>";
		}
	}
	$("#" + thisKey).append(optionStr);
}

//获取质检标准
function getCheckType(){
	var responseText = $.ajax({
		url : 'index1.php?model=produce_quality_checktype&action=listJson',
		type : "POST",
		async : false
	}).responseText;
	var dataArr = eval("(" + responseText + ")");
	var newArr = [];
	if(dataArr.length > 0){
		for(var i = 0;i < dataArr.length ; i++){
			newArr.push({ "name" : dataArr[i].checkType,"value" : dataArr[i].checkType });
		}
	}
	return newArr;
}

//获取质检标准
function getDimension(){
	var responseText = $.ajax({
		url : 'index1.php?model=produce_quality_dimension&action=listJson',
		type : "POST",
		async : false
	}).responseText;
	var dataArr = eval("(" + responseText + ")");
	var newArr = [];
	if(dataArr.length > 0){
		for(var i = 0;i < dataArr.length ; i++){
			newArr.push({ "name" : dataArr[i].dimName,"value" : dataArr[i].dimName });
		}
	}
	return newArr;
}

//修改质检标准
function changeName(thisId,thisName){
	var thisVal = $("#" + thisId).find("option:selected").text();
	$("#" + thisName).val(thisVal);
}

//获取质检结果
function getQualityResult(){
    var dataArr = getData('ZJJDJG');
    var newArr = [];
    if(dataArr.length > 0){
        for(var i = 0;i < dataArr.length ; i++){
            newArr.push({ "name" : dataArr[i].dataName,"value" : dataArr[i].dataCode });
        }
    }
    return newArr;
}

//获取质检标准上传文件
function standardFile(id){
	if(id!=''){
		$.ajax({
			url:'?model=produce_quality_standard&action=getFile&id='+id,
			type:'get',
			dataType:'html',
			success:function(msg){
				if(msg!='')
					msg='<a href="?model=file_uploadfile_management&action=toDownFileById&fileId='+msg+'" taget="_blank" title="点击下载"><img src="images/icon/icon103.gif" /></a>';
				$("#fileImage").html(msg);
			}
		});
	}else{
		$("#fileImage").html('');
	}
}