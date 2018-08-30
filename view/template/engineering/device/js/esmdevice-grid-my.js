var show_page = function() {
	$("#esmdeviceGrid").yxsubgrid("reload");
};

$(function() {
	$.ajax({
	    type: "POST",
	    url: "?model=engineering_device_esmdevice&action=getFormType",
	    data: {'myself' : 1},
	    async: false,
	    success: function(data){
	   		formTypeArr = eval( "(" + data + ")" );
		}
	});
	$("#esmdeviceGrid").yxsubgrid({
		model : 'engineering_device_esmdevice',
		action : 'myJson',
		title : '�ҵ��豸',
		showcheckbox : false,
		isDelAction : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isOpButton : false,
		// ����Ϣ
		colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        }, {
            name : 'deptName',
            display : '�豸��������',
            sortable : true
        }, {
            name : 'deviceType',
            display : '�豸����',
            sortable : true,
            width : 100
        }, {
            name : 'device_name',
            display : '�豸����',
            sortable : true,
            width : 200
        }, {
            name : 'unit',
            display : '��λ',
            sortable : true,
            width : 80
        }, {
            name : 'num',
            display : '����',
            sortable : true,
            width : 80
        }, {
            name : 'notse',
            display : '��ע',
            sortable : true,
            hide : true
        }, {
            name : 'description',
            display : '������Ϣ',
            sortable : true,
            width : 480
        }],
		subGridOptions : {
			subgridcheck : true,
			url : '?model=engineering_device_esmdevice&action=equJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				paramId : 'cid',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������
			}],
			// ��ʾ����
			afterProcess : function(data, rowDate, $tr) {
				if (data.number <= data.executedNum) {
					$tr.find("td").css("background-color", "#A1A1A1");
				}
			},
			colModel : [{
				name : 'isLockFlag',
				display : '����',
				sortable : false,
				width : '25',
				align : 'center',
				process : function(v, row) {
					if (row.borrowDays > 8) {
						return '<img src="images/icon/cicle_yellow.png" title="������" />';
					}else{
						return '';
					}
				}
			}, {
				name : 'device_name',
				width : 150,
				display : '�豸����'
			}, {
				name : 'coding',
				display : '������',
				width : 100
			}, {
				name : 'dpcoding',
				display : '���ű���',
				width : 100
			}, {
				name : 'amount',
				display : '����',
				width : 50
			}, {
				name : 'price',
				display : '����',
				width : 50
			}, {
				name : 'projectCode',
				display : '��Ŀ���',
				width : 120		
			}, {
				name : 'projectName',
				display : '��Ŀ����',
				width : 120		
			}, {
				name : 'date',
				display : '��������',
				width : 70
			}, {
				name : 'targetdate',
				display : 'Ԥ�ƹ黹����',
				width : 80
			}, {
                name : 'formNo',
                display : '��ǰ����ҵ��',
                width : 110
            }]
		},
		searchitems : [{
			display : '�豸����',
			name : 'device_nameSearch'
		}, {
			display : '������',
			name : 'bCoding'
		}, {
			display : '������Ϣ',
			name : 'descriptionSearch'
		}],
		// ����״̬���ݹ���
		comboEx : [{
			text : "�豸����",
			key : 'typeid',
			data : formTypeArr
		},{
			text : "��ʾ����",
			key : 'isLock',
			data : [{
                text : '��',
                value : '1'
            },{
                text : '��',
                value : '0'
            }]
		}],
 		buttonsEx : [{
 			name : 'addReturn',
 			text : "���ɹ黹��",
 			icon : 'add',
 			action : function(row, rows, rowIds, g) {
				alert("���ã���OA�����ߣ��뵽��OA������лл��");
				return false;
				if (row) {
					//������
					var allRows = g.getAllSubSelectRowDatas();
                    var rowIdArr = [];
                    var projectIdArr = [];
                    var projectCodeArr = [];
                    var projectNameArr = [];
                    var managerIdArr = [];
                    var managerNameArr = [];
                    var deptId = '';
					for(var i=0; i< allRows.length ; i++){
                        if(allRows[i].formNo != ''){
                            alert('�豸��'+ allRows[i].device_name +'���Ѿ������ڵ��ݡ�'+ allRows[i].formNo +'����������ѡ��');
                            return false;
                        }
						rowIdArr.push(allRows[i].borrowItemId);
                        if(deptId == ''){
                            deptId = allRows[i].deptId;
                        }else{
                            if(deptId != allRows[i].deptId){
                                alert('��ͬ�������ŵ��豸���ܺϲ�����һ�ŵ��ݣ�������ѡ��');
                                return false;
                            }
                        }
                        if(allRows[i].projectId != '' && $.inArray(allRows[i].projectId,projectIdArr) == -1){
                        	projectIdArr.push(allRows[i].projectId);
                        }
                        if(allRows[i].projectCode != '' && $.inArray(allRows[i].projectCode,projectCodeArr) == -1){
                        	projectCodeArr.push(allRows[i].projectCode);
                        }
                        if(allRows[i].projectName != '' && $.inArray(allRows[i].projectName,projectNameArr) == -1){
                        	projectNameArr.push(allRows[i].projectName);
                        }
                        if(allRows[i].managerId != '' && $.inArray(allRows[i].managerId,managerIdArr) == -1){
                        	managerIdArr.push(allRows[i].managerId);
                        }
                        if(allRows[i].managerName != '' && $.inArray(allRows[i].managerName,managerNameArr) == -1){
                        	managerNameArr.push(allRows[i].managerName);
                        }
					}
					//�黹������ҳ��
					if(rowIdArr.length > 0){
						showOpenWin("?model=engineering_resources_ereturn&action=toAdd&rowsId="+rowIdArr.toString()
                            +"&projectId="+projectIdArr.toString()+"&projectCode="+projectCodeArr.toString()
                            +"&projectName="+projectNameArr.toString()+"&managerId="+managerIdArr.toString()
                            +"&managerName="+managerNameArr.toString()+"&deviceDeptId="+deptId,1,700,1100);
					}
					else
						alert('����ѡ���¼');
				} else {
					alert('����ѡ���¼');
				}
			}
 		},{
 			name : 'addRenew',
 			text : "�������赥",
 			icon : 'add',
 			action : function(row, rows, rowIds, g) {
				alert("���ã���OA�����ߣ��뵽��OA������лл��");
				return false;
				if (row) {
					//������
					var allRows = g.getAllSubSelectRowDatas();
                    var rowIdArr = [];
                    var projectId = '';
                    var deptId = '';
					for(var i=0; i< allRows.length ; i++){
                        if(allRows[i].formNo != ''){
                            alert('�豸��'+ allRows[i].device_name +'���Ѿ������ڵ��ݡ�'+ allRows[i].formNo +'����������ѡ��');
                            return false;
                        }
                        rowIdArr.push(allRows[i].borrowItemId);
                        if(deptId == ''){
                            deptId = allRows[i].deptId;
                        }else{
                            if(deptId != allRows[i].deptId){
                                alert('��ͬ�������ŵ��豸���ܺϲ�����һ�ŵ��ݣ�������ѡ��');
                                return false;
                            }
                        }
                        if(projectId == ''){
                            projectId = allRows[i].projectId;
                        }else{
                            if(projectId != allRows[i].projectId){
                                alert("��ͬ��Ŀ���豸���ܺϲ�����һ�ŵ��ݣ�������ѡ��");
                                return false;
                            }
                        }
					}
					//���赥����ҳ��
					if(rowIdArr.length > 0){
						showOpenWin("?model=engineering_resources_erenew&action=toAdd&rowsId="+rowIdArr.toString()
                            +"&projectId="+allRows[0].projectId+"&projectCode="+allRows[0].projectCode
                            +"&projectName="+allRows[0].projectName+"&managerId="+allRows[0].managerId
                            +"&managerName="+allRows[0].managerName+"&flag="+allRows[0].flag+"&deviceDeptId="+deptId,1,700,1100);
					}
					else
						alert('����ѡ���¼');
				} else {
					alert('����ѡ���¼');
				}
			}
 		},{
 			name : 'addReturn',
 			text : "����ת�赥",
 			icon : 'add',
 			action : function(row, rows, rowIds, g) {
				alert("���ã���OA�����ߣ��뵽��OA������лл��");
				return false;
				if (row) {
                    //������
                    var allRows = g.getAllSubSelectRowDatas();
                    var rowIdArr = [];
                    var projectIdArr = [];
                    var projectCodeArr = [];
                    var projectNameArr = [];
                    var managerIdArr = [];
                    var managerNameArr = [];
                    var deptId = '';
                    for(var i=0; i< allRows.length ; i++){
                        if(allRows[i].formNo != ''){
                            alert('�豸��'+ allRows[i].device_name +'���Ѿ������ڵ��ݡ�'+ allRows[i].formNo +'����������ѡ��');
                            return false;
                        }
                        rowIdArr.push(allRows[i].borrowItemId);
                        if(deptId == ''){
                            deptId = allRows[i].deptId;
                        }else{
                            if(deptId != allRows[i].deptId){
                                alert('��ͬ�������ŵ��豸���ܺϲ�����һ�ŵ��ݣ�������ѡ��');
                                return false;
                            }
                        }
                        if(allRows[i].projectId != '' && $.inArray(allRows[i].projectId,projectIdArr) == -1){
                        	projectIdArr.push(allRows[i].projectId);
                        }
                        if(allRows[i].projectCode != '' && $.inArray(allRows[i].projectCode,projectCodeArr) == -1){
                        	projectCodeArr.push(allRows[i].projectCode);
                        }
                        if(allRows[i].projectName != '' && $.inArray(allRows[i].projectName,projectNameArr) == -1){
                        	projectNameArr.push(allRows[i].projectName);
                        }
                        if(allRows[i].managerId != '' && $.inArray(allRows[i].managerId,managerIdArr) == -1){
                        	managerIdArr.push(allRows[i].managerId);
                        }
                        if(allRows[i].managerName != '' && $.inArray(allRows[i].managerName,managerNameArr) == -1){
                        	managerNameArr.push(allRows[i].managerName);
                        }
                    }
					//ת�赥����ҳ��
					if(rowIdArr.length > 0){
						showOpenWin("?model=engineering_resources_elent&action=toAdd&rowsId="+rowIdArr.toString()
							+"&projectId="+projectIdArr.toString()+"&projectCode="+projectCodeArr.toString()
	                        +"&projectName="+projectNameArr.toString()+"&managerId="+managerIdArr.toString()
	                        +"&managerName="+managerNameArr.toString()+"&deviceDeptId="+deptId,1,700,1100);
					}
					else
						alert('����ѡ���¼');
				} else {
					alert('����ѡ���¼');
				}
			}
 		},{
 	        name: 'addBatch',
 	        text: "��������",
 	        icon: 'add',
 	        action: function () {
				alert("���ã���OA�����ߣ��뵽��OA������лл��");
				return false;
 	        	showModalWin("?model=engineering_device_esmdevice&action=toBatch");
 	        }
 	    }],
		sortname : 'g.typename,c.device_name',
		sortorder : 'ASC'
	});
});