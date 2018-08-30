//������Ŀ�鿴ҳ��
function showProject(projectId){
	var skey = "";
    $.ajax({
	    type: "POST",
	    url: "?model=engineering_project_esmproject&action=md5RowAjax",
	    data: { "id" : projectId },
	    async: false,
	    success: function(data){
	   	   skey = data;
		}
	});
	showModalWin("?model=engineering_project_esmproject&action=viewTab&id=" + projectId +"&skey=" + skey ,1);
}

//�鿴������Ϣ
function showActivity(activityId){
	var skey = "";
    $.ajax({
	    type: "POST",
	    url: "?model=engineering_activity_esmactivity&action=md5RowAjax",
	    data: { "id" : activityId },
	    async: false,
	    success: function(data){
	   	   skey = data;
		}
	});
	showOpenWin("?model=engineering_activity_esmactivity&action=toView&id=" + activityId +"&skey=" + skey ,'��Ŀ����');
}

//ѡ��������λʱ��֤
function changeWorkloadUnit(){
	//������������������޸Ĺ�������λ
	var activityId = $("#activityId").val();
	if(activityId != "" && activityId != 0){
		alert('�漰���������־�����������������λ');
		$("#workloadUnitView").val($("#workloadUnit").val());
		return false;
	}else{
		$("#workloadUnit").val($("#workloadUnitView").val());
	}
}

//���������Ϣ - ������־��д��
function clearActivity(){
	//�����������
	$("#actitityId").val("");
	$("#activityName").val("");
	$("#workProcess").val("");
	$("#workloadDay").val("");
	$("#thisActivityProcess").val("");
	$("#thisProjectProcess").val("");
}

//���������Ϣ - ������־��д��
function changeActivity(){
	//�����������
	$("#workProcess").val("");
	$("#workloadDay").val("");
	$("#thisActivityProcess").val("");
	$("#thisProjectProcess").val("");
	$("#activityEndDate").val("");
}

//���������Ϣ - �����־��д��
function clearActivityBatch(rowNum){
	var beforeStr = "importTable_cmp_";
	//�����������
	$("#"+ beforeStr + "actitityId" + rowNum).val("");
	$("#"+ beforeStr + "activityName" + rowNum).val("");
	$("#"+ beforeStr + "workProcess" + rowNum).val("");
	$("#"+ beforeStr + "workloadDay" + rowNum).val("");
	$("#"+ beforeStr + "thisActivityProcess" + rowNum).val("");
	$("#"+ beforeStr + "thisProjectProcess" + rowNum).val("");
	$("#"+ beforeStr + "activityEndDate" + rowNum).val("");
}

//��� - ѡ������ʱ
function changeActivityBatch(rowNum){
	var beforeStr = "importTable_cmp_";
	//�����������
	$("#"+ beforeStr + "workProcess" + rowNum).val("");
	$("#"+ beforeStr + "workloadDay" + rowNum).val("");
	$("#"+ beforeStr + "thisActivityProcess" + rowNum).val("");
	$("#"+ beforeStr + "thisProjectProcess" + rowNum).val("");
	$("#" + beforeStr + "activityEndDate" + rowNum ).val("");
}

//��ȡʡ����Ϣ
function getProvince(){
	//����ʡ����Ϣ
    var provinceArr;
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

//���������ȡ
function getCity(provinceId){
	var cityArr;
	//������ڻ���
	if(cityCache != undefined && cityCache[provinceId]){
		cityArr = cityCache[provinceId];
	}else{
		//����ʡ����Ϣ
		$.ajax({
			type : 'POST',
			url : "?model=system_procity_city&action=getCityForEditGrid",
			data : {
				"provinceId" : provinceId
			},
		    async: false,
			success : function(data) {
				cityArr = eval("(" + data + ")");

				//���������Ϣ
				cityCache[provinceId] = cityArr;
			}
		});
	}
	return cityArr;
}

//�����־�ĳ�����Ϣ
function changeCity(rowNum,provinceId,cityId){
	//���ó�������
	var cityArr = getCity(provinceId);
	var beforeStr = "importTable_cmp_";

	//����ѡ������
	var cityObj = $("#" + beforeStr + "cityId" + rowNum );
	var cityStr;
	//���ѡ��
	cityObj.empty();
	var city;//Ĭ�ϳ���
	var selected = '';
	for (var i = 0, l = cityArr.length; i < l; i++) {
		selected = '';
		//����Ĭ�ϳ���
		if(i == 0){
			city = cityArr[i].name;
		}
		//Ĭ��ѡ��
		if(cityId == cityArr[i].value){
			selected = "selected='selected'";
			city = cityArr[i].name;
		}
		cityStr += "<option "+ selected + " value='" + cityArr[i].value + "' title='" + cityArr[i].name + "'>" + cityArr[i].name
			+ "</option>";
	}
	cityObj.append(cityStr);
	$("#" + beforeStr + "city" + rowNum ).val(city);
}

//����������Ϣ
function initActivityBatch(projectId,rowNum){
	var beforeStr = "importTable_cmp_";
	var activityObj = $("#" + beforeStr + "activityName" + rowNum );

	//������Ŀ��Ⱦ
	activityObj.yxcombogrid_esmactivity("remove").yxcombogrid_esmactivity({
		hiddenId : beforeStr + 'activityId' + rowNum,
		nameCol : 'activityName',
		isShowButton : false,
		height : 250,
		width : 500,
		gridOptions : {
			param : {"projectId":projectId , 'isLeaf' : 1 , 'isTrial' : 0 },
			action : 'selectActForLog',
			isTitle : true,
			showcheckbox : false,
			// ��
			colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '��������',
					name : 'activityNameView',
                    sortable : false
				}, {
					display : '��������',
					name : 'activityName',
					sortable : true,
					hide : true
				}, {
					display : 'Ԥ�ƿ�ʼ',
					name : 'planBeginDate',
					sortable : true,
					width : 80
				}, {
					display : 'Ԥ�ƽ���',
					name : 'planEndDate',
					sortable : true,
					width : 80
				}, {
					display : 'Ԥ�ƹ���',
					name : 'days',
					sortable : true,
					width : 70,
                    process : function(v,row){
                        return v;
                    }
				}, {
					display : '������',
					name : 'workload',
					sortable : true,
					process : function(v,row){
						return v + " " + row.workloadUnitName;
					},
					width : 80
				}, {
					display : '�������',
					name : 'process',
					sortable : true,
					process : function(v,row){
						return v + " %";
					},
					width : 80
				}, {
					display : 'ʵ�ʿ�ʼ',
					name : 'actBeginDate',
					sortable : true,
					width : 80,
					hide : true
				}, {
					display : 'ʵ�ʽ���',
					name : 'actEndDate',
					sortable : true,
					width : 80,
					hide : true
				}, {
		            name : 'remark',
		            display : '��ע',
		            sortable : true,
					width : 150,
					hide : true
		        }],
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#" + beforeStr + "workloadUnit" + rowNum ).val(data.workloadUnit);
					$("#" + beforeStr + "workloadUnitView" + rowNum ).val(data.workloadUnitName);

					//���
					changeActivityBatch(rowNum);

					$("#" + beforeStr + "activityEndDate" + rowNum ).val(data.planEndDate);
				}
			}
		},
		event : {
			'clear' : function(){
				changeActivityBatch(rowNum);
			}
		}
	});
}

// ��̨����������ɽ���
function calTaskProcessBatch(rowNum,workload){
	var beforeStr = "importTable_cmp_";
	if(workload == ""){
		$("#" + beforeStr + "workProcess" + rowNum).val('');
		return false;
	}
	var activityId = $("#" + beforeStr + "activityId" + rowNum).val();
	if(activityId == "" || activityId*1 == 0){
		return false;
	}
	$.ajax({
		type : "POST",
		url : "?model=engineering_activity_esmactivity&action=calTaskProcess",
		data : {
			"workload" : workload,
			"id" : activityId
		},
		success : function(msg) {
			if(msg != -1){
				var processObj = eval("(" + msg + ")");
				$("#" + beforeStr + "workProcess" + rowNum).val(processObj.process);
				$("#" + beforeStr + "thisActivityProcess" + rowNum).val(processObj.thisActivityProcess);
				$("#" + beforeStr + "thisProjectProcess" + rowNum).val(processObj.thisProjectProcess);
			}else{
				alert('��ȡ���ȴ���');
			}
		}
	});
}

//��ȡ��Ӧ���������������
function getMaxWorkRate(){
	//��ȡ������Ͷ�빤������
	var executionDate = $("#executionDate").val();
	$.ajax({
		type : "POST",
		url : "?model=engineering_worklog_esmworklog&action=getMaxInWorkRate",
		data : {
			"executionDate" : executionDate
		},
		success : function(msg) {
			$("#maxInWorkRateShow").html(msg);

			//���ֵ����
			var maxInWorkRate = $("#maxInWorkRate");
			if(maxInWorkRate.length != 0){
				maxInWorkRate.val(msg);
			}
		}
	});
	$("#executionDateShow").html(executionDate);
}

//����������־���� ����֤
function checkBatchAdd(){
	var executionDate = $("#executionDate").val();
	
	//�ж���־�����Ƿ�������ݼټ�¼
    var workStatus = $('#workStatus').val();
	if(workStatus == 'GXRYZT-01' || workStatus == 'GXRYZT-02'){
		var isInHols = false;
		$.ajax({
			type : "POST",
			url : "?model=engineering_worklog_esmworklog&action=isInHols",
			data : {
				"executionDate" : executionDate
			},
			async: false,
			success : function(msg) {
				if(msg == '1'){
					alert("��" + executionDate + "���������ݼټ�¼������״̬����Ϊ���������");
					isInHols = true;
				}
			}
		});
		if(isInHols){
			return false;
		}
	}
	
	//�������ݱ�
	var importTableObj = $("#importTable");
	var beforeStr = "importTable_cmp_";

	//���ȼ������ڲ�
	var thisDate = $("#thisDate").val();
	var dayDiff = DateDiff(executionDate,thisDate);

	//���ȼ��������Ƿ��ظ���д��־
	var cmps = importTableObj.yxeditgrid("getCmpByCol", "inWorkRate");
	var activityCache = [];
    var projectCache = [];
	var hasSame = false; //������ͬ����
	var noActivity = false; //û������
	var noProject = false; //û������
	var activityId;
	var projectId;
	var inWorkRate = 0;
	var hasWorkloadDay = true; //���������δ��
	var maxLogDay;//��������
	var hasBiggerDate = false;
	cmps.each(function(i, n){
		if($("#esmworklog[detail]_" + i +"_isDelTag").length == 0){
			//������ȡ
			var rowNum = $(this).data("rowNum");

			//��־������֤
			activityId = $("#" + beforeStr + "activityId" + rowNum).val();
			projectId = $("#" + beforeStr + "projectId" + rowNum).val();
			if(projectId != "" && projectId*1 != 0){
                // ������ĿID
                if($.inArray(projectId, projectCache) == -1){
                    projectCache.push(projectId);
                }

				if($.inArray(activityId,activityCache) != -1){
					hasSame = true;
					return false;
				}else{
					activityCache.push(activityId);
				}

				//û������
				if(activityId == "" || activityId * 1 == 0){
					noActivity = true;
					return false;
				}

				//û�������
				var workloadDay = $("#" + beforeStr + "workloadDay" + rowNum).val();
				if(workloadDay == ""){
					hasWorkloadDay = false;
					return false;
				}

				//��������
				maxLogDay = $("#" + beforeStr + "maxLogDay" + rowNum).val();
				if(maxLogDay*1 != 0 && maxLogDay < dayDiff){
					var activityName = $("#" + beforeStr + "activityName" + rowNum).val();
					alert('����'+ activityName +'������־������Ŀ��������');
					hasBiggerDate = true;
					return false;
				}
			}else if(activityId != "" && activityId * 1 != 0){
				//û������
				if(projectId == "" || projectId * 1 == 0){
					noProject = true;
					return false;
				}
			}

			//Ͷ�빤������
			inWorkRate = accAdd(inWorkRate,this.value,2);
		}
        return true;
	});

	//�����ظ�
	if(hasSame == true){
		alert('ͬһ�������첻����д���ݻ�����������־');
		return false;
	}

	//û������
	if(noActivity == true){
		alert('��������Ŀ����û��������У�');
		return false;
	}

	//û����Ŀ
	if(noProject == true){
		alert('������������û����Ŀ���У�');
		return false;
	}

	//û�������
	if(hasWorkloadDay == false){
		alert('��ѡ���������־�������������');
		return false;
	}

	//��Ͷ�빤������
	var maxInWorkRate = $("#maxInWorkRateShow").html();
	if(maxInWorkRate*1 < inWorkRate*1){
		alert('�Ͷ�빤��������' + inWorkRate + "���Ѵ��ڵ�������Ͷ�빤��������" +maxInWorkRate + "����" );
		return false;
	}

	//������������
	if(hasBiggerDate == true){
		return false;
	}

    // ������Ŀ������֤�Ƿ񻹿���
    if (projectCache.length > 0) {
        //��֤�����ڶ�Ӧ��־���Ƿ�����
        var projectWithoutDeadline = true;
        $.ajax({
            type : "POST",
            url : "?model=engineering_worklog_esmworklog&action=checkProjectWithoutDeadline",
            data : {
                "projectIds" : projectCache.toString(),
                "executionDate" : executionDate
            },
            async: false,
            success : function(msg) {
                if(msg != "1"){
                    projectWithoutDeadline = false;
                }
            }
        });

        //û�������
        if(!projectWithoutDeadline){
            alert('������Ŀ������ֹ�����');
            return false;
        }
    }

	//�����������
	if(activityCache.toString() != ""){
		//��֤�����ڶ�Ӧ��־���Ƿ�����
		var logIsExist = false;
		$.ajax({
			type : "POST",
			url : "?model=engineering_worklog_esmworklog&action=checkActivityLog",
			data : {
				"activityId" : activityCache.toString(),
				"executionDate" : executionDate,
				"searchType" : "mul"
			},
		    async: false,
			success : function(msg) {
				if(msg != "0" && msg != ""){
					alert(msg);
					logIsExist = true;
				}
			}
		});

        //û�������
        if(logIsExist == true){
            return false;
        }
	}

	return true;
}

//��˽������
function auditResult(assessResult){
	assessResult = assessResult + "";
	switch(assessResult){
		case '0' : return '';break;
		case '1' : return '��';break;
		case '2' : return '��';break;
		case '3' : return '��';break;
		case '4' : return '��';break;
		case '5' : return '���';break;
		default : return '';
	}
}

//��˽������
function keyValue(assessResult){
	assessResult = assessResult + "";
	switch(assessResult){
		case '1' : return 'excellent';break;
		case '2' : return 'good';break;
		case '3' : return 'medium';break;
		case '4' : return 'poor';break;
		case '5' : return 'back';break;
        default : return assessResult;
	}
}

//��֤Ͷ�빤������
function checkWorkRate(){
    var inWorkRateObj = $("#inWorkRate");
	var inWorkRateVal = inWorkRateObj.val();
	if(isNaN(inWorkRateVal) || (parseInt(inWorkRateVal) > 100 || parseInt(inWorkRateVal) < 0)){
		alert('������ 0 �� 100 ���ڵ�����');
		inWorkRateObj.val('');
	}
}

//��ʾloading
function showLoading(){
	$("#loading").show();
}

//����
function hideLoading(){
	$("#loading").hide();
}