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

//��ʼ�����鲹�䷽ʽ��Ϣ
function initPCC(){
		//��ȡ���鲹�䷽ʽ
		var addModeNameArr = $('#addModeNameHidden').val().split(",");
		var str;
		//���鲹�䷽ʽ��Ⱦ
		var addModeNameObj = $('#addModeName');
		addModeNameObj.combobox({
			url:'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=HRBCFS',
			multiple:true,
			valueField:'text',
            textField:'text',
			editable : false,
	        formatter: function(obj){
	        	//�ж� ���û�г�ʼ�������У���ѡ��
	        	if(addModeNameArr.indexOf(obj.text) == -1){
	        		str = "<input type='checkbox' id='addModeName_"+ obj.text +"' value='"+ obj.text +"'/> " + obj.text;
	        	}else{
	        		str = "<input type='checkbox' id='addModeName_"+ obj.text +"' value='"+ obj.text +"' checked='checked'/> " + obj.text;
	        	}
				return str;
	        },
			onSelect : function(obj){
				//checkbox��ֵ
				$("#addModeName_" + obj.text).attr('checked',true);
				//���ö����µ�ѡ����
				mulSelectSet(addModeNameObj);
			},
			onUnselect : function(obj){
				//checkbox��ֵ
				$("#addModeName_" + obj.text).attr('checked',false);
				//����������
				mulSelectSet(addModeNameObj);
			}
		});

		//�ͻ����ͳ�ʼ����ֵ
		mulSelectInit(addModeNameObj);

}//��ʼ�����鲹�䷽ʽ��Ϣ
function initLevel(data){
		//��ȡ���鲹�䷽ʽ
		var positionLevelArr = $('#positionLevelHidden').val().split(",");
		var str;
		//���鲹�䷽ʽ��Ⱦ
		var positionLevelObj = $('#positionLevel');
		var dataArr=[{"positionLevel":"����"},{"positionLevel":"�м�"},{"positionLevel":"�߼�"}];
		if(data)
			dataArr=data;
		positionLevelObj.combobox({
			data : dataArr,
			multiple:true,
			editable : false,
			valueField:'positionLevel',
            textField:'positionLevel',
	        formatter: function(obj){
	        	//�ж� ���û�г�ʼ�������У���ѡ��
	        	if($.inArray(obj.positionLevel,positionLevelArr) == -1){
	        		str = "<input type='checkbox' id='positionLevel_"+ obj.positionLevel +"' value='"+ obj.positionLevel +"'/> " + obj.positionLevel;
	        	}else{
	        		str = "<input type='checkbox' id='positionLevel_"+ obj.positionLevel +"' value='"+ obj.positionLevel +"' checked='checked'/> " + obj.positionLevel;
	        	}
				return str;
	        },
			onSelect : function(obj){
				//checkbox��ֵ
				$("#positionLevel_" + obj.positionLevel).attr('checked',true);
				//���ö����µ�ѡ����
				mulSelectSet(positionLevelObj);
			},
			onUnselect : function(obj){
				//checkbox��ֵ
				$("#positionLevel_" + obj.positionLevel).attr('checked',false);
				//����������
				mulSelectSet(positionLevelObj);
			}
		});

		//�ͻ����ͳ�ʼ����ֵ
		mulSelectInit(positionLevelObj);
}

//ѡ����������ְλʱ�����������ֵ�����
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
// �ύУ������
function checkData(){

	if($("#addTypeCode").val()=="ZYLXLZ"){
		if($("#leaveManName").val()==""){
			alert("��������ְ/����������");
			return false;
		}
	}else��if($("#positionLevelHidden").val()==""){
			alert("��ѡ�񼶱�");
			return false;

	}else��if($("#addModeNameHidden").val()==""){
			alert("��ѡ���鲹�䷽ʽ");
			return false;
	}else if($("#postType").val()==""){
		alert("��ѡ��ְλ����");
		return false;
	}else if($("#employmentTypeCode").val()==""){
		alert("��ѡ���ù�����");
		return false;
	}else if($("#applyReason").val()==""){
		alert("����������ԭ��");
		return false;
	}
	else{
		return true;
	}
}

		// ��֤�Ƿ���ѡ����Ŀ����
	function checkProjectType(){
		if($("#projectType").val()==""){
			alert("����ѡ����Ŀ����");
		}
	}

$(function(){
	var $postType=$("#postType");

	if('YPZW-WY'==$postType.val()){
		initLevelWY();
	}
	//ְλ���͸ı䴥���¼�
	$postType.change(function(){
		$('#positionLevelHidden').val('');
		$('#positionLevel').val('');
		if($(this).val()=='YPZW-WY'){           //ѡ����������
			initLevelWY();
		}else{
			initLevel();
		}
	});
	//ѧ�������¼�
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
	//ְλѡ��
	$("#positionName").yxcombogrid_position({
		hiddenId : 'positionId',
		width:350,
		gridOptions : {
			param:{deptId:deptId}
		}
	});
	//$("#positionName").yxcombogrid_position("show");
}