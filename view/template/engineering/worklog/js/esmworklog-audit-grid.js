//��������
var activityArr;
//��Ա����
var memberArr;
//�����б�
var objGrid;
//Ա����������
var personTypeArr;

$(function() {
	var projectId = $("#projectId").val();
	//��ȡ��������Ⱦ
	$.ajax({
		type : 'POST',
		url : "?model=engineering_activity_esmactivity&action=listJson",
		data : {
			projectId : projectId,
			isLeaf : 1,
			dir : 'ASC'
		},
	    async: false,
		success : function(data) {
			activityArr = eval("(" + data + ")");
		}
	});

	//�����������
	if(activityArr){
		var optionStr = '';
		var activityId = $("#activityIdHidden").val();
		for(var i = 0;i < activityArr.length ; i++){
			if(activityId == activityArr[i].id){
				optionStr += "<option value='"+ activityArr[i].id  +"' selected='selected'>" + activityArr[i].activityName + "</option>";
			}else
				optionStr += "<option value='"+ activityArr[i].id  +"'>" + activityArr[i].activityName + "</option>";
		}
		$("#activityId").append(optionStr);
	}

	//��ȡ��Ŀ��Ա
	$.ajax({
		type : 'POST',
		url : "?model=engineering_member_esmmember&action=listJson",
		data : {
			projectId : projectId,
			dir : 'ASC'
		},
	    async: false,
		success : function(data) {
			memberArr = eval("(" + data + ")");
		}
	});

	//�����������
	if(memberArr){
		var optionStr = '';
		var memberIdArr = [];
		for(var i = 0;i < memberArr.length ; i++){
			optionStr += "<option value='"+ memberArr[i].memberId  +"'>" + memberArr[i].memberName + "</option>";
			memberIdArr.push(memberArr[i].memberId);
		}
		$("#memberId").append(optionStr);
		$("#memberIds").val(memberIdArr.toString());
	}

	objGrid = $("#esmworklogGrid");

	//��ʼ����־
	initWorklog();
});

//��ʼ���������־
function initWorklog(thisType,thisVal){
	//����ı�Ͳ�ѯ��ʽ���б�
	var auditObj = $("#auditType");
	if(thisType){
		if(thisVal == auditObj.val()){
			return false;
		}else{
			auditObj.val(thisVal);
			objGrid.empty();
		}
	}

	//�����ж�
	var projectId = $("#projectId").val();
	var assessResult = $("#assessResult").val();
	var activityId = $("#activityId").val();
	var memberId = $("#memberId").val();
	var personType = $("#personType").val();
	var memberIds = $("#memberIds").val();
	var pageSize = $("#pageSize").val();
	var paramObj = {
		projectId : projectId,
		activityId : activityId,
		assessResults : assessResult,
		createId : memberId,
		personType : personType,
		memberIdsSearch : memberIds,
		pageSize : pageSize
	};
	if(auditObj.val() == "0"){
		//��ʾ���״̬
		$("#assessResult").show();
		$("#assessResultText").show();
		//�������
		auditNormalList(paramObj);
	}else{
		//�������״̬
		$("#assessResult").hide();
		$("#assessResultText").hide();
		//����Աͳ�����
		auditForPersonList(paramObj);
	}
}

//��������б�
function auditNormalList(paramObj){
	if(objGrid.children().length != 0){
		//������־
		objGrid.yxeditgrid("setParam",paramObj).yxeditgrid("processData");
	}else{
		//������־
		$("#esmworklogGrid").yxeditgrid({
			url : '?model=engineering_worklog_esmworklog&action=pageJsonWorkLog',
			type : 'view',
			param : paramObj,
			colModel : [{
					display : 'id',
					name : 'id',
					type : 'hidden'
				},{
					display : '��������',
					name : 'activityName',
					width : 120,
					align : 'left'
				},{
					display : 'ִ������',
					name : 'executionDate',
					width : 70,
					process : function(v,row){
						return "<a href='javascript:void(0);' onclick='viewCost("+ row.id +")'>" + v + "</a>";
					}
				}, {
					display : '���',
					name : 'createName',
					width : 80
				}, {
					display : '������',
					name : 'workloadDay',
					width : 60,
					process : function(v,row){
						return v + " " + row.workloadUnitName;
					},
					align : 'right'
				}, {
					display : '��λ',
					name : 'workloadUnitName',
					width : 40,
					type : 'hidden'
				}, {
					display : '�����չ',
					name : 'thisActivityProcess',
					width : 60,
					process : function(v){
						return v + " %";
					},
					align : 'right'
				}, {
					display : '��Ŀ��չ',
					name : 'thisProjectProcess',
					width : 60,
					process : function(v){
						return v + " %";
					},
					align : 'right'
				}, {
					display : '�˹�Ͷ��',
					name : 'inWorkRate',
					width : 70,
					process : function(v){
						return v + " %";
					},
					align : 'right'
				}, {
					display : '����',
					name : 'costMoney',
					width : 60,
					process : function(v,row){
						if(v * 1 == 0 || v == ''){
							return v;
						}else{
							return "<span class='blue'>" + moneyFormat2(v) + "</span>";
						}
					},
					align : 'right'
				}, {
					display : '����״̬',
					width : 50,
					datacode : 'GXRYZT',
					name : 'workStatus'
				}, {
					display : '��������',
					name : 'description',
					align : 'left'
				}, {
					display : '<a href="javascript:void(0);" onclick="auditBatchSet(1);">��</a>',
					name : 'excellent',
					width : 40,
					process : function(v,row){
						return showAudit('1',row);
					},
					event : {
						click : function(){
							//���к�
							var rowNum = $(this).data("rowNum");
							//��˲���
							auditAction(rowNum,'1');
						}
					}
				}, {
					display : '<a href="javascript:void(0);" onclick="auditBatchSet(2);">��</a>',
					name : 'good',
					width : 40,
					process : function(v,row){
						return showAudit('2',row);
					},
					event : {
						click : function(){
							//���к�
							var rowNum = $(this).data("rowNum");
							//��˲���
							auditAction(rowNum,'2');
						}
					}
				}, {
					display : '<a href="javascript:void(0);" onclick="auditBatchSet(3);">��</a>',
					name : 'medium',
					width : 40,
					process : function(v,row){
						return showAudit('3',row);
					},
					event : {
						click : function(){
							//���к�
							var rowNum = $(this).data("rowNum");
							//��˲���
							auditAction(rowNum,'3');
						}
					}
				}, {
					display : '<a href="javascript:void(0);" onclick="auditBatchSet(4);">��</a>',
					name : 'poor',
					width : 40,
					process : function(v,row){
						return showAudit('4',row);
					},
					event : {
						click : function(){
							//���к�
							var rowNum = $(this).data("rowNum");
							//��˲���
							auditAction(rowNum,'4');
						}
					}
				}, {
					display : '<a href="javascript:void(0);" onclick="auditBatchSet(5);">���</a>',
					name : 'back',
					width : 40,
					process : function(v,row){
						return showAudit('5',row);
					},
					event : {
						click : function(){
							//���к�
							var rowNum = $(this).data("rowNum");
							//��˲���
							auditAction(rowNum,'5');
						}
					}
				},{
					display : '��˽���',
					name : 'feedBack',
					align : 'left',
                    width : 150,
					process : function(v,row){
						if(row.assessResultName != ""){
							return v;
						}else{
							return '<input type="text" class="txtmiddle" style="width:98%" id="feedBack'+ row.id +'"/>';
						}
					}
				}, {
					display : 'assessResult',
					name : 'assessResult',
					type : 'hidden'
				},{
					display : 'assessResultName',
					name : 'assessResultName',
					type : 'hidden'
				},{
					display : 'isAudit',
					name : 'isAudit',
					type : 'hidden'
				},{
					display : 'confirmId',
					name : 'confirmId',
					type : 'hidden'
				}
			]
		});
	}
}

//����Աͳ��
function auditForPersonList(paramObj){
	if(objGrid.children().length == 0){
		//��ȡͳ�Ƶ�����
		var tbStr ='<table class="main_table">' +
				'<thead>' +
					'<tr class="main_tr_header">' +
						'<th width="3%">���</th>' +
						'<th width="3%"></th>' +
						'<th>��ڼ�</th>' +
						'<th width="6%">�����</th>' +
						'<th width="12%">��������</th>' +
						'<th width="7%">���</th>' +
						'<th width="5%">������</th>' +
						'<th width="5%">��λ</th>' +
						'<th width="5%">�����չ</th>' +
						'<th width="5%">��Ŀ��չ</th>' +
						'<th width="5%">����</th>' +
						'<th width="7%">�˹�Ͷ��ռ��</th>' +
						'<th width="4%"><a href="javascript:void(0);" onclick="auditBatchSet(1);">��</a></th>' +
						'<th width="4%"><a href="javascript:void(0);" onclick="auditBatchSet(2);">��</a></th>' +
						'<th width="4%"><a href="javascript:void(0);" onclick="auditBatchSet(3);">��</a></th>' +
						'<th width="4%"><a href="javascript:void(0);" onclick="auditBatchSet(4);">��</a></th>' +
						'<th width="4%"><a href="javascript:void(0);" onclick="auditBatchSet(5);">���</a></th>' +
					'</tr>' +
				'</thead>' +
				'<tbody id="myTbody"></tbody>' +
			'</table>';

		//��ͷ����
		objGrid.append(tbStr);
	}

	//���ݼ���
	reloadPersonData(paramObj);
}

//�����б�����
function reloadPersonData(paramObj){
	//��ȡ��������Ⱦ
	var logArr;
	$.ajax({
		type : 'POST',
		url : "?model=engineering_worklog_esmworklog&action=auditJsonForPerson",
		data : paramObj,
	    async: false,
		success : function(data) {
			logArr = eval("(" + data + ")");
		}
	});

	if(logArr.length > 0){
		var tbodyStr = "";
		var j,trClass;
		for(var i = 0;i< logArr.length ; ++i){
			j = i + 1;
			trClass = i%2 == 0 ? 'tr_odd' : 'tr_even';
			tbodyStr += '<tr class="'+trClass+'" id="tr'+ i +'">' +
					'<td>'+ j +'</td>' +
					'<td><a href="javascript:void(0)" onclick="changeShow(this,' + i + ',\''+ logArr[i].createId +'\',\''+ logArr[i].activityId +'\');">+</a></td>' +
					'<td>' + logArr[i].minDate + '~' + logArr[i].maxDate + '</td>' +
					'<td>' + logArr[i].dataNum + '</td>' +
					'<td>' + logArr[i].activityName + '</td>' +
					'<td>' + logArr[i].createName + '</td>' +
					'<td>' + logArr[i].workloadDay + '</td>' +
					'<td>' + logArr[i].workloadUnitName + '</td>' +
					'<td>' + logArr[i].thisActivityProcess + ' %</td>' +
					'<td>' + logArr[i].thisProjectProcess + ' %</td>' +
					'<td>' + moneyFormat2(logArr[i].costMoney) + ' </td>' +
					'<td>' + logArr[i].inWorkRateProcess + ' %</td>' +
						'<input type="hidden" id="createId'+ i +'" value="'+ logArr[i].createId +'"/>' +
						'<input type="hidden" id="activityId'+ i +'" value="'+ logArr[i].activityId +'"/>' +
						'<input type="hidden" id="isAudit'+ i +'" value="0"/>' +
						'<input type="hidden" id="assessResult'+ i +'" value="0"/>' +
					'</td>' +
					'<td id="excellent'+ i +'" onclick="auditAction('+ i +',1);"> - </td>' +
					'<td id="good'+ i +'" onclick="auditAction('+ i +',2);"> - </td>' +
					'<td id="medium'+ i +'" onclick="auditAction('+ i +',3);"> - </td>' +
					'<td id="poor'+ i +'" onclick="auditAction('+ i +',4);"> - </td>' +
					'<td id="back'+ i +'" onclick="auditAction('+ i +',5);"> - </td>' +
				'</tr>';
		}
		//��������
		$("#myTbody").empty().append(tbodyStr);
	}else{
		//��������
		$("#myTbody").empty();
	}
}

//������ʾ����
function changeShow(thisObj,rowNum,createId,activityId){
	thisObj = $(thisObj);
	if(thisObj.text() == '+'){
		thisObj.text('-');
		loadDetail(rowNum,createId,activityId);
	}else{
		thisObj.text('+');
		$("#tb_" + rowNum).hide();
	}
}

//��ȡ��ϸ��
function loadDetail(rowNum,createId,activityId){
	var tbObj = $("#tb_" + rowNum);
	if(tbObj.length == 1){
		tbObj.show();
		return false;
	}
	//�����ж�
	var projectId = $("#projectId").val();
	var assessResult = $("#assessResult").val();
	var personType = $("#personType").val();
	var memberIds = $("#memberIds").val();
	var paramObj = {
		projectId : projectId,
		activityId : activityId,
		assessResults : assessResult,
		createId : createId,
		personType : personType,
		memberIdsSearch : memberIds
	};

	//��ȡ��������Ⱦ
	var logArr;
	$.ajax({
		type : 'POST',
		url : "?model=engineering_worklog_esmworklog&action=listJson",
		data : paramObj,
	    async: false,
		success : function(data) {
			logArr = eval("(" + data + ")");
		}
	});

	if(logArr.length > 0){
		//��ȡͳ�Ƶ�����
		var tbStr ='<tr id="tb_'+ rowNum +'"><td></td><td colspan="99"><table class="main_table">' +
				'<thead>' +
					'<tr class="main_tr_header">' +
						'<th width="3%">���</th>' +
						'<th width="12%">�����</th>' +
						'<th width="8%">����״̬</th>' +
						'<th width="8%">������</th>' +
						'<th width="8%">��λ</th>' +
						'<th width="8%">�����չ</th>' +
						'<th width="8%">��Ŀ��չ</th>' +
						'<th width="8%">����</th>' +
						'<th width="8%">�˹�Ͷ��ռ��</th>' +
						'<th>��������</th>' +
					'</tr>' +
				'</thead>' +
				'<tbody>';
		var j,trClass;
		for(var i = 0;i< logArr.length ; ++i){
			j = i + 1;
			trClass = i%2 == 0 ? 'tr_odd' : 'tr_even';
			tbStr += '<tr class="'+trClass+'">' +
					'<td>'+ j +'</td>' +
					'<td><a href="javascript:void(0);" onclick="viewCost(' + logArr[i].id + ')">' + logArr[i].executionDate + '</a></td>' +
					'<td>' + logArr[i].workStatus + ' </td>' +
					'<td>' + logArr[i].workloadDay + '</td>' +
					'<td>' + logArr[i].workloadUnitName + '</td>' +
					'<td>' + logArr[i].thisActivityProcess + ' %</td>' +
					'<td>' + logArr[i].thisProjectProcess + ' %</td>' +
					'<td>' + moneyFormat2(logArr[i].costMoney) + ' </td>' +
					'<td>' + logArr[i].inWorkRate + ' %</td>' +
					'<td style="text-align:left;">' + logArr[i].description + '</td>' +
				'</tr>';
		}
		tbStr += '</tbody></table></td></tr>';
		//��ͷ����
		$("#tr" + rowNum).after(tbStr);
	}
}

/**
 * ˢ���б�
 */
function show_page(){
	//����ı�Ͳ�ѯ��ʽ���б�
	var auditObj = $("#auditType");
	if(auditObj.val() == "0"){
		objGrid.yxeditgrid('processData');
	}else{
		//�����ж�
		var projectId = $("#projectId").val();
		var assessResult = $("#assessResult").val();
		var activityId = $("#activityId").val();
		var memberId = $("#memberId").val();
		var personType = $("#personType").val();
		var memberIds = $("#memberIds").val();
		var pageSize = $("#pageSize").val();
		var paramObj = {
			projectId : projectId,
			activityId : activityId,
			assessResults : assessResult,
			createId : memberId,
			personType : personType,
			memberIdsSearch : memberIds,
			pageSize :pageSize
		};
		//���ݼ���
		reloadPersonData(paramObj);
	}
}

//�����־
function auditLog(id,assessResult){
	var feedBack = $("#feedBack" + id).val();
	var action = assessResult*1 == 5 ? 'backLog' : 'auditLog';
	//��ȡ��������Ⱦ
	$.ajax({
		type : 'POST',
		url : "?model=engineering_worklog_esmworklog&action=" + action,
		data : {
			id : id,
			assessResult : assessResult,
			feedBack : feedBack
		},
	    async: false,
		success : function(data) {
			if(data == '1'){

			}else{
				alert('���ʧ��');
			}
		}
	});
}

//��ʾ��˽��
function showAudit(assessResult,row){
	if(row.assessResultName != ""){
		if(row.assessResult == assessResult){
			return "<span class='blue'>��</span>";
		}
	}else{
		return '-';
	}
}

//�����־�¼�
function auditAction(rowNum,assessResult){
	var assessResultNow = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'assessResult').val();//��ǰϵͳѡ��
	if(assessResultNow != assessResult){
		//��������ųⷽ��
		auditExclude(rowNum,assessResult);
	}else{
		var confirmId = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'confirmId').val();//��ǰϵͳѡ��
		if(confirmId == ''){
			//ȡ��ѡ��
			cancelAction(rowNum,assessResult);
		}
	}
}

//ȡ����˶���
function cancelAction(rowNum,assessResult){
	objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,keyValue(assessResult)).html("-");
	objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'assessResult').val(0);
	objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'isAudit').val('');
}

//����ų���÷���
function auditExclude(rowNum,assessResult){
	//����ı�Ͳ�ѯ��ʽ���б�
	var auditObj = $("#auditType");
	if(auditObj.val() == "0"){
		for(var i = 1;i <= 5 ; i++){
			if(i == assessResult*1){
				objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,keyValue(i)).html("<span class='blue'>��</span>");
				objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'assessResult').val(assessResult);
				objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'isAudit').val(1);
			}else{
				objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,keyValue(i)).html('-');
			}
		}
	}else{
		for(var i = 1;i <= 5 ; i++){
			if(i == assessResult*1){
				$("#isAudit" + rowNum).val(1);
				$("#assessResult" + rowNum).val(assessResult);
				$("#" + keyValue(i) + rowNum).html("<span class='blue'>��</span>");
			}else{
				$("#" + keyValue(i) + rowNum).html('-');
			}
		}
	}
}

//�������
function auditBatch(){
	//����ı�Ͳ�ѯ��ʽ���б�
	var auditObj = $("#auditType");
	var isNull = true;

	//�ж���˷�ʽ
	if(auditObj.val() == "0"){
		var objArr = objGrid.yxeditgrid("getCmpByCol", "isAudit");

		if(objArr.length != 0){
			if(!confirm('ȷ��Ҫ�����ѡ�е���־��')){
				return false;
			}
			objArr.each(function(i,n){
				if(this.value == "1"){
					isNull = false;

					//����������ȡ��Ӧֵ
					var rowNum = $(this).data('rowNum');
					var id =  objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'id').val();
					var assessResult =  objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,'assessResult').val();
					//���
					auditLog(id,assessResult);
				}
			});
		}
	}else{
		var objArr = $("input[id^='isAudit']");
		if(objArr.length != 0){
			if(!confirm('ȷ��Ҫ�����ѡ�е���־��')){
				return false;
			}
			objArr.each(function(i,n){
				if(this.value == "1"){
					isNull = false;

					//����������ȡ��Ӧֵ
					var assessResult = $("#assessResult" + i).val();
					var createId = $("#createId" + i).val();
					var activityId = $("#activityId" + i).val();
					//���
					auditLogForPerson(createId,activityId,assessResult);
				}
			});
		}
	}

	if(isNull == true){
		alert('û����Ҫȷ����˵���־');
	}else{
		//���¼����б�
		show_page();
	}
}

//����������˽��
function auditBatchSet(assessResult){
	//����ı�Ͳ�ѯ��ʽ���б�
	var auditObj = $("#auditType");
	if(auditObj.val() == "0"){
		var rows = objGrid.yxeditgrid("getCmpByCol","assessResultName");
		if(rows.length > 0){
			rows.each(function(){
				if(this.value == ""){
					//��˲���
					auditAction($(this).data('rowNum'),assessResult);
				}
			});
		}
	}else{
		var rows = $("tr[id^='tr']");
		if(rows.length){
			rows.each(function(i,n){
				//��˲���
				auditAction(i,assessResult);
			});
		}
	}
}

//�����־
function auditLogForPerson(createId,activityId,assessResult){
	//�����ж�
	var projectId = $("#projectId").val();
	var paramObj = {
		projectId : projectId,
		activityId : activityId,
		assessResults : assessResult,
		createId : createId,
		confirmStatus : 0
	};
	var action = assessResult*1 == 5 ? 'backLogForPerson' : 'auditLogForPerson';
	//��ȡ��������Ⱦ
	$.ajax({
		type : 'POST',
		url : "?model=engineering_worklog_esmworklog&action=" + action,
		data : paramObj,
	    async: false,
		success : function(data) {
			if(data == '1'){

			}else{
				alert('���ʧ��');
			}
		}
	});
}

//����鿴����ҳ��
function viewCost(worklogId){
	var url = "?model=engineering_worklog_esmworklog&action=toView&id=" + worklogId;
	var height = 800;
	var width = 1150;
	window.open(url, "�鿴��־��Ϣ",
	'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width='
			+ width + ',height=' + height);
}