var qualitystandardArr;
var dimensionArr;
var checkTypeArr;

//��ȡ�ʼ��׼
function getQualitystandard(){
	var responseText = $.ajax({
		url : 'index1.php?model=produce_quality_standard&action=listJson',
		type : "POST",
		async : false
	}).responseText;
	var dataArr = eval("(" + responseText + ")");
	return dataArr;
}

//��ʼ���ʼ��׼
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

//��ȡ�ʼ��׼
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

//��ȡ�ʼ��׼
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

//�޸��ʼ��׼
function changeName(thisId,thisName){
	var thisVal = $("#" + thisId).find("option:selected").text();
	$("#" + thisName).val(thisVal);
}

//��ȡ�ʼ���
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

//��ȡ�ʼ��׼�ϴ��ļ�
function standardFile(id){
	if(id!=''){
		$.ajax({
			url:'?model=produce_quality_standard&action=getFile&id='+id,
			type:'get',
			dataType:'html',
			success:function(msg){
				if(msg!='')
					msg='<a href="?model=file_uploadfile_management&action=toDownFileById&fileId='+msg+'" taget="_blank" title="�������"><img src="images/icon/icon103.gif" /></a>';
				$("#fileImage").html(msg);
			}
		});
	}else{
		$("#fileImage").html('');
	}
}