var show_page = function() {
	$("#myresourceapplyGrid").yxgrid("reload");
};

$(function() {
	//��鵱ǰԱ���Ƿ�����豸����������¼
	var lockLimit = false;
	$.ajax({
		type: "POST",
		url: "?model=engineering_resources_lock&action=checkLock",
		async: false,
		success: function(data){
			if(data == 1){
				lockLimit = true;
			}
		}
	});
	$("#myresourceapplyGrid").yxgrid({
		model : 'engineering_resources_resourceapply',
		action : 'myJson',
		title : '�ҵ��豸����',
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
//		}, {
//			name : 'confirmStatus',
//			display : 'ȷ��',
//			sortable : true,
//			width : 30,
//			align : 'center',
//			hide : true,
//			process : function(v,row) {
//				switch(v){
//					case '0' : return '';break;
//					case '1' : return '<img src="images/icon/ok3.png" title="����ȷ��"/>';break;
//					case '2' : return '<img src="images/icon/ok2.png" title="��ȷ��,ȷ����[' + row.confirmName + '],ȷ��ʱ��[' + row.confirmTime + ']"/>';break;
//				}
//			}
//		}, {
//			name : 'status',
//			display : '����',
//			sortable : true,
//			width : 30,
//			align : 'center',
//			hide : true,
//			process : function(v) {
//				switch(v){
//					case '0' : return '';break;
//					case '1' : return '<img src="images/icon/cicle_blue.png" title="������"/>';break;
//					case '2' : return '<img src="images/icon/cicle_green.png" title="�Ѵ���"/>';break;
//				}
//			}
		}, {
			name : 'formNo',
			display : '���뵥���',
			sortable : true,
			width : 120,
			process : function(v, row) {
				return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_resources_resourceapply&action=toView&id="
						+ row.id + '&skey=' + row.skey_ + "\",1,700,1100,"+row.id+")'>" + v + "</a>";
			}
		}, {
			name : 'applyUser',
			display : '������',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'applyUserId',
			display : '������id',
			sortable : true,
			hide : true
		}, {
			name : 'applyDate',
			display : '��������',
			sortable : true,
			width : 75
		}, {
			name : 'applyTypeName',
			display : '��������',
			sortable : true,
			width : 70
		}, {
			name : 'getTypeName',
			display : '���÷�ʽ',
			sortable : true,
			width : 70
		}, {
			name : 'place',
			display : '�豸ʹ�õ�',
			sortable : true
		}, {
			name : 'deptName',
			display : '��������',
			sortable : true,
			hide : true
		}, {
			name : 'projectCode',
			display : '��Ŀ���',
			sortable : true,
			width : 120
		}, {
			name : 'projectName',
			display : '��Ŀ����',
			sortable : true,
			width : 120
		}, {
			name : 'managerName',
			display : '��Ŀ����',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'managerId',
			display : '��Ŀ����id',
			sortable : true,
			hide : true
		}, {
			name : 'remark',
			display : '��ע��Ϣ',
			sortable : true,
			width : 130,
			hide : true
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 70
		}, {
			name : 'ExaDT',
			display : '��������',
			sortable : true,
			width : 75
		}, {
			name : 'createName',
			display : '������',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '����ʱ��',
			sortable : true,
			hide : true
		}, {
			name : 'updateName',
			display : '�޸���',
			sortable : true,
			hide : true
		}, {
			name : 'updateTime',
			display : '�޸�ʱ��',
			sortable : true,
			hide : true
		}, {
			name : 'confirmStatus',
			display : '����״̬',
			sortable : true,
			width : 80,
			process : function(v) {
				switch(v){
					case '0' : return '����';break;
					case '1' : return '���ż��';break;
					case '2' : return '������';break;
					case '6' : return '���';break;
					case '3' : return '�ȴ�����';break;
					case '4' : return '������';break;
					case '7' : return '��������';break;
					case '5' : return '���';break;
				}
			}
		},{
			name : 'status',
			display : '�´�״̬',
			sortable : true,
			width : 80,
			process : function(v) {
				switch(v){
					case '0' : return 'δ�´�';break;
					case '1' : return '�����´�';break;
					case '2' : return '���´�';break;
				}
			}
		}],
		toAddConfig : {
			toAddFn : function() {
				alert("���ã���OA�����ߣ��뵽��OA�ύ�������롣лл��");
				return false;
				//�豸����������֤
				if(lockLimit){
					alert('�����豸��������Ȩ����ʱ����������������豸���С��黹�������衿��ת�衿��������������ϵ�豸����Ա');
					return false;
				}
				showOpenWin("?model=engineering_resources_resourceapply&action=toAdd",1,700,1100,'toRAdd');
			}
		},
		toEditConfig : {
			showMenuFn : function(row) {
				if ((row.confirmStatus == "0" || row.confirmStatus == "6") && (row.ExaStatus == '���ύ' || row.ExaStatus == '���')) {
					return true;
				}
				return false;
			},
			toEditFn : function(p, g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showOpenWin("?model=engineering_resources_resourceapply&action=toEdit&id="
					+ row.id + "&skey=" + row['skey_'],1,700,1100,row.id);
			}
		},
		toViewConfig : {
			toViewFn : function(p, g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showOpenWin("?model=engineering_resources_resourceapply&action=toView&id="
						+ row[p.keyField],1,700,1100,row.id);
			}
		},
		menusEx : [{
			text : '�ύȷ��',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.confirmStatus == "0" || row.confirmStatus == "6") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				//�豸����������֤
				if(lockLimit){
					alert('�����豸��������Ȩ����ʱ������������ϵ�豸����Ա');
					return false;
				}
				if (confirm('ȷ���������ύȷ����')) {
					$.ajax({
					    type: "POST",
					    url: "?model=engineering_resources_resourceapply&action=ajaxConfirmStatus",
					    data: {
					    	'id' : row.id,
					    	'confirmStatus' : '1'
					    },
					    async: false,
					    success: function(data){
					   		if(data == '1'){
								alert('�ύ�ɹ�');
								show_page();
							}else{
								alert('�ύʧ��');
							}
						}
					});
				}
			}
		}, {
		// 	text : '�ύ����',
		// 	icon : 'add',
		// 	showMenuFn : function(row) {
		// 		if (row.confirmStatus == "2" && (row.ExaStatus == '���ύ' || row.ExaStatus == '���')) {
		// 			return true;
		// 		}
		// 		return false;
		// 	},
		// 	action : function(row, rows, grid) {
		// 		if (row) {
		// 			if(row.projectId != "0"){
		// 				var billArea = '';
		// 				$.ajax({
		// 				    type: "POST",
		// 				    url: "?model=engineering_project_esmproject&action=getRangeId",
		// 				    data: {'projectId' : row.projectId },
		// 				    async: false,
		// 				    success: function(data){
		// 				   		billArea = data;
		// 					}
		// 				});
		// 		   		if(billArea != ''){
		// 					showThickboxWin('controller/engineering/resources/ewf_project.php?actTo=ewfSelect&billId='
		// 						+ row.id + "&billArea=" + billArea + "&billDept=" + row.deptId
		// 						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
		// 				}else{
		// 					showThickboxWin('controller/engineering/resources/ewf_project.php?actTo=ewfSelect&billId='
		// 						+ row.id + "&billDept=" + row.deptId
		// 						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
		// 				}
		// 			}else{
		// 				var appendDept = "";
		// 				$.ajax({
		// 				    type: "POST",
		// 				    url: "?model=engineering_resources_resourceapply&action=checkIsEsmDept",
		// 				    data: {'deptId' : row.deptId },
		// 				    async: false,
		// 				    success: function(data){
		// 				   		appendDept = data;
		// 					}
		// 				});
		// 				if(appendDept != ""){
		// 					appendDept = row.deptId + "," + appendDept;
		// 				}else{
		// 					appendDept = row.deptId;
		// 				}
  //                       //����
  //                       var billArea = '';
  //                       $.ajax({
  //                           type: "POST",
  //                           url: "?model=engineering_officeinfo_range&action=getRangeByProvinceAndDept",
  //                           data: {'provinceId' : row.placeId ,'deptId' : row.deptId},
  //                           async: false,
  //                           success: function(data){
  //                               billArea = data;
  //                           }
  //                       });
  //                       if(billArea != ''){
  //                           showThickboxWin('controller/engineering/resources/ewf_person.php?actTo=ewfSelect&billId='
  //                               + row.id + "&billArea=" + billArea
  //                               + "&billDept=" + appendDept
  //                               + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
  //                       }else{
  //                           showThickboxWin('controller/engineering/resources/ewf_person.php?actTo=ewfSelect&billId='
  //                               + row.id
  //                               + "&billDept=" + appendDept
  //                               + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
  //                       }
		// 			}
		// 		}
		// 	}
		// }, {
			text : "ɾ��",
			icon : 'delete',
			showMenuFn : function(row) {
				if ((row.confirmStatus == "0" || row.confirmStatus == "6") && (row.ExaStatus == '���ύ' || row.ExaStatus == '���')) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=engineering_resources_resourceapply&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								show_page(1);
							} else {
								alert("ɾ��ʧ��! ");
							}
						}
					});
				}
			}
//		}, {
//			text : "���ؼ��",
//			icon : 'delete',
//			showMenuFn : function(row) {
//				if (row.confirmStatus == "1" ) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				if (window.confirm(("ȷ��Ҫ����?"))) {
//					$.ajax({
//						type : "POST",
//						url : "?model=engineering_resources_resourceapply&action=checkBack",
//						data : {
//							id : row.id
//						},
//						success : function(msg) {
//							if (msg == 1) {
//								alert('���سɹ���');
//								show_page(1);
//							} else {
//								alert("����ʧ��! ");
//							}
//						}
//					});
//				}
//			}
		}],
        //��������
		comboEx:[{
			text : '����״̬',
			key : 'confirmStatus',
			data : [{
					text : '����',
					value : '0'
				}, {
					text : '���ż��',
					value : '1'
				}, {
					text : '������',
					value : '2'
				},{
					text : '���',
					value : '6'
				}, {
					text : '�ȴ�����',
					value : '3'
				},{
					text : '������',
					value : '4'
				},{
					text : '��������',
					value : '7'
				},{
					text : '���',
					value : '5'
				}]
			},{
		     text:'���״̬',
		     key:'ExaStatus',
		     type : 'workFlow'
		}],
		searchitems : [{
			display : "���뵥���",
			name : 'formNoSch'
		},{
			display : "��Ŀ���",
			name : 'projectCodeSch'
		},{
			display : "��Ŀ����",
			name : 'projectNameSch'
		}]
	});
});