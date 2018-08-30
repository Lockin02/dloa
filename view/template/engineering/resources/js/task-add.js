$(document).ready(function() {
	//��Ⱦ������
	$("#receiverName").yxselect_user({
		hiddenId : 'receiverId'
	});

	//��ȡʡ�����鲢��ֵ��provinceArr
	provinceArr = getProvince();
	//��ʡ������provinceArr��ֵ��proCode
	addDataToProvince(provinceArr,'proCode');
	//�༭ʱ��ʼ��ʡ��
	var place = $("#place").val();
	$("#proCode").find("option[text='"+place+"']").attr("selected",true);
	
	$("#detailInfo").yxeditgrid({
		url : "?model=engineering_resources_resourceapplydet&action=listJsonForTask",
		param : {
			"mainId" : $("#applyId").val(),
			"isConfirm" : 1,//ȷ���˵�����
			"isNotDetail" : 1//δ������ɵ�����
		},
		objName : 'task[applydet]',
		tableClass : 'form_in_table',
		isAddAndDel : false,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '�豸id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '����id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'resourceTypeName',
			width : 80,
			type : "statictext"
		}, {
			display : '�豸����',
			name : 'resourceName',
			type : "statictext"
		}, {
			display : '��λ',
			name : 'unit',
			width : 50,
			type : "statictext"
		}, {
			display : '��������',
			name : 'number',
			width : 50,
			type : "statictext"
		}, {
			display : '���´�����',
			name : 'exeNumber',
			width : 60,
			type : "statictext"
		}, {
			display : '��������',
			name : 'planBeginDate',
			width : 70,
			type : "statictext"
		}, {
			display : '�黹����',
			name : 'planEndDate',
			width : 70,
			type : "statictext"
		}, {
			display : 'ʹ������',
			name : 'useDays',
			width : 50,
			type : 'hidden'
		}, {
			display : '�豸�ۼ�',
			name : 'price',
			width : 80,
			type : "hidden"
		}, {
			display : 'Ԥ�Ƴɱ�',
			name : 'amount',
			type : 'money',
			width : 80,
			type : "hidden"
		}, {
			display : '�����˱�ע',
			name : 'remark',
			type : "statictext"
		}, {
			display : 'Ԥ�Ƴﱸ��������',
			name : 'feeBack',
			tclass : 'txtshort',
			width : 95
		}, {
			display : '���������',
			name : 'needExeNum',
			width : 60,
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '���η�������',
			name : 'thisExeNum',
			width : 70,
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '�������',
			name : '',
			width : 60,
			type : "statictext",
			process : function(){
				return "<input type='button' class='txt_btn_a' value='�������'/>";
			},
			event : {
				click : function(e){
					var g = e.data.gird;
					var rowNum = e.data.rowNum;
					var needExeNumObj = g.getCmpByRowAndCol(rowNum,'needExeNum');//���������
					var thisExeNumObj = g.getCmpByRowAndCol(rowNum,'thisExeNum');//���η�������
					if(needExeNumObj.val()*1 == thisExeNumObj.val()*1){
						easy_alert('���豸�Ѿ��������');
						return true;
					}
					//�������񷽷�
					dealTask(rowNum);
				}
			}
		}],
		event : {
			'reloadData' : function(e,g,data){
				if(!data){
					alert('û����Ҫ�´﷢�����豸');
					window.close();
				}
			}
		}
	});
	/**
	 * ��֤��Ϣ
	 */
	validate({
		"receiverName" : {
			required : true
		}
	});
});

//�������
function dealTask(rowNum){
	var winObj = $("#dealTaskWin");
	//���ڴ�
	winObj.window({
		title : '�������',
		height : 300,
		width : 400
	});

	//��Ⱦ�豸���
	if(winObj.children().length == 1){
		winObj.yxeditgrid('remove');
	}

	//��ȡ�豸��Ϣ
	var detailObj = $("#detailInfo");
	var resourceId = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"resourceId").val();
	var resourceName = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"resourceName").val();
	var needExeNum = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"needExeNum").val();
	var thisExeNum = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"thisExeNum").val();
	var lastNeedNum = needExeNum*1 - thisExeNum*1;

	//���������һ������������ȡ
	var taskInfoObj = $("#taskInfo");
	var areaArr = [];
	var areaNumArr = {};
	//��ȡ�ѷ�����Ϣ
	if(taskInfoObj.children().length != 0 ){
		var resourceIdArr = taskInfoObj.yxeditgrid('getCmpByCol','resourceId');
		if(resourceIdArr.length != 0){
			resourceIdArr.each(function(){
				if(resourceId == this.value){//���豸
					var taskRowNum = $(this).data('rowNum');
					var areaId = taskInfoObj.yxeditgrid("getCmpByRowAndCol",taskRowNum,"areaId").val();
					if($.inArray(areaId,areaArr) != -1){
						areaNumArr[areaId] += taskInfoObj.yxeditgrid("getCmpByRowAndCol",taskRowNum,"number").val()*1;
					}else{
						areaArr.push(areaId);
						areaNumArr[areaId] = taskInfoObj.yxeditgrid("getCmpByRowAndCol",taskRowNum,"number").val()*1;
					}
				}
			});
		}
	}

	winObj.yxeditgrid({
		url : '?model=engineering_device_esmdevice&action=projectAreaJson',
		param : {
			'isList' : 1,
			'list_id' : resourceId,
			'areaNumArr' : areaNumArr
		},
		tableClass : 'form_in_table',
		height : 400,
		isAddAndDel : false,
		title : '�豸��'+resourceName + ',ʣ�����: ' + lastNeedNum,
		colModel : [{
			display : 'id',//���id������id
			name : 'id',
			type : 'hidden'
		}, {
			display : '����',
			name : 'Name',
			type : 'statictext'
		}, {
			display : '��������',
			name : 'surplus',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '��������',
			name : 'number',
			tclass : 'txtshort',
			event : {
				blur : function(e){
					var g = e.data.gird;
					var rowNum = e.data.rowNum;
					var surplus = g.getCmpByRowAndCol(rowNum,'surplus').val()*1;
					if(surplus < this.value*1){
						var areaName = g.getCmpByRowAndCol(rowNum,'Name').val();
						easy_alert('����'+areaName+'����治��');
						g.setRowColValue(rowNum,'number',0);
					}
					var countNum = 0;
					var numArr = g.getCmpByCol('number');
					numArr.each(function(){
						countNum += this.value*1;
					});
					$("#countNum").val(countNum);
				}
			}
		}],
		event : {
			'reloadData' : function(e,g,data){
				winObj.attr("style", "overflow-x:auto;overflow-y:auto;height:300px;");
				winObj.find('thead tr').eq(0).children().eq(0).append(
					' <input type="button" class="txt_btn_a" onclick="confirmDeal('+rowNum+');" value=" ȷ �� "/>'
				);
				if(!data){
					winObj.find("tbody").append("<tr class='tr_even'><td colspan='4'>-- û����Ӧ��� --</td></tr>");
				}else{
					winObj.find("tbody").append("<tr class='tr_count'><td colspan='3'></td><td><input id='countNum' class='readOnlyTxtShortCount' readonly='readonly'></td></tr>");
				}
			}
		}
	});
}

//ȷ�Ϸ���
function confirmDeal(rowNum){
	//���ڴ�
	var winObj = $("#dealTaskWin");

	//��֤�Ƿ��з���
	var numArr = winObj.yxeditgrid('getCmpByCol','number');
	var isDeal = false;
	var dealNum = 0;//���������
	if(numArr.length > 0){
		numArr.each(function(){
			if(this.value*1 != 0 && this.value != ""){
				isDeal = true;
				dealNum += this.value*1;
			}
		});
	}

	//�����б�ȡ��
	var detailObj = $("#detailInfo");
	var needExeNumObj = detailObj.yxeditgrid('getCmpByRowAndCol',rowNum,'needExeNum');//���������
	var needExeNum = needExeNumObj.val();

	//��֤
	if(isDeal == false){
		easy_alert('û�з�����Ϣ');
	}else if(needExeNum*1 < dealNum){
		easy_alert('��������Ϊ: ' + dealNum + ",�ѳ������������: " + needExeNum + ",�����·��䷢������");
	}else{
		//�������Ⱦ
		var taskInfoObj = $("#taskInfo");
		if(taskInfoObj.children().length == 0){//�����û���б���ʼ��
			initTaskGrid(taskInfoObj);
		}

		//�ⲿ�ֿ�ʼ��ʽ��ֵ
		numArr.each(function(){
			if(this.value*1 != 0 && this.value != ""){
				var taskRowNum = $(this).data('rowNum');//�����¼����

				//��������
				outJson = {
					//���䲿��
					"areaName" : winObj.yxeditgrid('getCmpByRowAndCol',taskRowNum,'Name').val(),
					"areaId" : winObj.yxeditgrid('getCmpByRowAndCol',taskRowNum,'id').val(),
					"number" : winObj.yxeditgrid('getCmpByRowAndCol',taskRowNum,'number').val(),
					//�豸����
					"resourceId" : detailObj.yxeditgrid('getCmpByRowAndCol',rowNum,'resourceId').val(),
					"resourceName" : detailObj.yxeditgrid('getCmpByRowAndCol',rowNum,'resourceName').val(),
					"resourceTypeId" : detailObj.yxeditgrid('getCmpByRowAndCol',rowNum,'resourceTypeId').val(),
					"resourceTypeName" : detailObj.yxeditgrid('getCmpByRowAndCol',rowNum,'resourceTypeName').val(),
					"unit" : detailObj.yxeditgrid('getCmpByRowAndCol',rowNum,'unit').val(),
					"planBeginDate" : detailObj.yxeditgrid('getCmpByRowAndCol',rowNum,'planBeginDate').val(),
					"planEndDate" : detailObj.yxeditgrid('getCmpByRowAndCol',rowNum,'planEndDate').val(),
					"useDays" : detailObj.yxeditgrid('getCmpByRowAndCol',rowNum,'useDays').val(),
					"applyDetailId" : detailObj.yxeditgrid('getCmpByRowAndCol',rowNum,'id').val(),
					"remark" : detailObj.yxeditgrid('getCmpByRowAndCol',rowNum,'remark').val(),
					"applyDetailRowNum" : rowNum
				};
				var taskRowNum = taskInfoObj.yxeditgrid('getAllAddRowNum');
			    taskInfoObj.yxeditgrid('addRow',taskRowNum, outJson);
			}
		});

		var thisExeNumObj = detailObj.yxeditgrid('getCmpByRowAndCol',rowNum,'thisExeNum');//���η�������
		thisExeNumObj.val(thisExeNumObj.val()*1 + dealNum);

		//�رմ���
		winObj.window('close');
	}
}

//��ʾ��Ϣ
function easy_alert(msg){
	$.messager.alert('��ʾ',msg);
}

//�б��ʼ��
function initTaskGrid(taskInfoObj){
	taskInfoObj.yxeditgrid({
		objName : 'task[taskdetail]',
		tableClass : 'form_in_table',
		isAdd : false,
		isAddOneRow : false,
		colModel : [{
			display : '�豸id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '����id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'areaName',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : 'areaId',
			name : 'areaId',
			type : 'hidden'
		}, {
			display : 'applyDetailId',
			name : 'applyDetailId',
			type : 'hidden'
		}, {
			display : 'applyDetailRowNum',
			name : 'applyDetailRowNum',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'resourceTypeName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : '�豸����',
			name : 'resourceName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '��λ',
			name : 'unit',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '����',
			name : 'number',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '��������',
			name : 'planBeginDate',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '�黹����',
			name : 'planEndDate',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : 'ʹ������',
			name : 'useDays',
			width : 50,
			type : 'hidden'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txtmiddle'
		}],
		event : {
			'removeRow' : function(e, rowNum, row){
				//ɾ���е�ʱ��ȡ�����������
				var thisExeNumObj = $("#detailInfo").yxeditgrid('getCmpByRowAndCol',row.applyDetailRowNum,'thisExeNum');//���η�������
				thisExeNumObj.val(thisExeNumObj.val()*1 - row.number*1);

				//�����ɾ����,������б�
				if(taskInfoObj.yxeditgrid('getCmpByCol','number').length == 0){
					taskInfoObj.yxeditgrid('remove');
				}
			}
		}
	});
}

//����֤
function checkForm(){
	//�������Ⱦ
	var taskInfoObj = $("#taskInfo");
	if(taskInfoObj.children().length == 0){//�����û���б���ʼ��
		easy_alert('û�������������');
		return false;
	}else if(taskInfoObj.yxeditgrid('getCmpByCol','number').length == 0){
		easy_alert('û�������������');
		return false;
	}

	//������������ TODO
}

//��ȡʡ��
function getProvince() {
	var responseText = $.ajax({
		url : 'index1.php?model=system_procity_province&action=getProvinceNameArr',
		type : "POST",
		async : false
	}).responseText;
	var o = eval("(" + responseText + ")");
	return o;
}

/**
 * ���ʡ��������ӵ�������
 */
function addDataToProvince(data, selectId) {
	$("#" + selectId).append("<option ></option>");
	for (var i = 0, l = data.length; i < l; i++) {
		$("#" + selectId).append("<option title='" + data[i].text
				+ "' value='" + data[i].value + "'>" + data[i].text
				+ "</option>");
	}
}
/**
* ��ʡ�ݸı�ʱ�ԣ���esmproject[proCode]��title��ֵ��ֵ��esmproject[proName]
*/
function setProName(){
	$('#place').val($("#proCode").find("option:selected").attr("title"));
}