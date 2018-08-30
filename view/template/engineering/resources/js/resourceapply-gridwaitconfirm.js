var show_page = function(page) {
	$("#resourceapplyGrid").yxgrid("reload");
};
$(function() {
	$("#resourceapplyGrid").yxgrid({
		model : 'engineering_resources_resourceapply',
		title : '��ȷ���豸����',
		param : {confirmStatusNotArr : '0'},
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
//			}, {
//				name : 'confirmStatus',
//				display : 'ȷ��',
//				sortable : true,
//				width : 30,
//				align : 'center',
//				process : function(v,row) {
//					switch(v){
//						case '0' : return '';break;
//						case '1' : return '<img src="images/icon/ok3.png" title="����ȷ��"/>';break;
//						case '2' : return '<img src="images/icon/ok2.png" title="��ȷ��,ȷ����[' + row.confirmName + '],ȷ��ʱ��[' + row.confirmTime + ']"/>';break;
//					}
//				}
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
				width : 70
			}, {
				name : 'applyUserId',
				display : '������id',
				sortable : true,
				hide : true
			}, {
				name : 'deptName',
				display : '�����˲���',
				sortable : true,
				width : 70
			}, {
				name : 'applyDate',
				display : '��������',
				sortable : true,
				width : 70
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
				sortable : true,
				width : 70
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
				width : 80
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
				width : 75
			}, {
				name : 'ExaDT',
				display : '��������',
				sortable : true,
				width : 80
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
			},{
				name : 'confirmStatus',
				display : '����״̬',
				sortable : true,
				width : 80,
				process : function(v) {
					switch(v){
						case '1' : return '���ż��';break;
						case '2' : return '������';break;
						case '6' : return '���';break;
						case '3' : return '�ȴ�����';break;
						case '4' : return '������';break;
						case '7' : return '��������';break;
						case '5' : return '���';break;
					}
				}
			}],
		toViewConfig : {
			toViewFn : function(p, g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showOpenWin("?model=engineering_resources_resourceapply&action=toView&id="
						+ row[p.keyField],1,700,1100,row.id);
			}
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : 'ȷ�ϵ���',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.confirmStatus == '1') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
					showOpenWin("?model=engineering_resources_resourceapply&action=toEditCheck&id="
						+ + row.id + "&skey="+ row['skey_'] ,1,700,1100,row.id);
				}
			}
		},{
			text : 'ȷ�����豸',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.confirmStatus == '1') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
					showOpenWin("?model=engineering_resources_resourceapply&action=toConfirmDetail&id="
						+ + row.id + "&skey="+ row['skey_'] ,1,600,1100,row.id);
				}
			}
		},{
			text : '�ύ����',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.confirmStatus == "2" && (row.ExaStatus == '���ύ' || row.ExaStatus == '���')) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					if(row.projectId != "0"){
						var billArea = '';
						$.ajax({
						    type: "POST",
						    url: "?model=engineering_project_esmproject&action=getRangeId",
						    data: {'projectId' : row.projectId },
						    async: false,
						    success: function(data){
						   		billArea = data;
							}
						});
				   		if(billArea != ''){
							showThickboxWin('controller/engineering/resources/ewf_project.php?actTo=ewfSelect&billId='
								+ row.id + "&billArea=" + billArea + "&billDept=" + row.deptId + "&eUserId=" + row.createId
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
						}else{
							showThickboxWin('controller/engineering/resources/ewf_project.php?actTo=ewfSelect&billId='
								+ row.id + "&billDept=" + row.deptId + "&eUserId=" + row.createId
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
						}
					}else{
						var appendDept = "";
						$.ajax({
						    type: "POST",
						    url: "?model=engineering_resources_resourceapply&action=checkIsEsmDept",
						    data: {'deptId' : row.deptId },
						    async: false,
						    success: function(data){
						   		appendDept = data;
							}
						});
						if(appendDept != ""){
							appendDept = row.deptId + "," + appendDept;
						}else{
							appendDept = row.deptId;
						}
                        //����
                        var billArea = '';
                        $.ajax({
                            type: "POST",
                            url: "?model=engineering_officeinfo_range&action=getRangeByProvinceAndDept",
                            data: {'provinceId' : row.placeId ,'deptId' : row.deptId},
                            async: false,
                            success: function(data){
                                billArea = data;
                            }
                        });
                        if(billArea != ''){
                            showThickboxWin('controller/engineering/resources/ewf_person.php?actTo=ewfSelect&billId='
                                + row.id + "&billArea=" + billArea
                                + "&billDept=" + appendDept + "&eUserId=" + row.createId
                                + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
                        }else{
                            showThickboxWin('controller/engineering/resources/ewf_person.php?actTo=ewfSelect&billId='
                                + row.id
                                + "&billDept=" + appendDept + "&eUserId=" + row.createId
                                + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
                        }
					}
				}
			}
		}, {
			text : "���",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.confirmStatus == '1') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫ���?"))) {
					$.ajax({
						type : "POST",
						url : "?model=engineering_resources_resourceapply&action=applyBack",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('��سɹ���');
								show_page(1);
							} else {
								alert("���ʧ��! ");
							}
						}
					});
				}
			}
		}],
		comboEx : [{
			text : '����״̬',
			key : 'confirmStatus',
			value : '1',
			data : [{
				text : '���ż��',
				value : '1'
			},{
				text : '������',
				value : '2'
			}, {
				text : '���',
				value : '6'
			}, {
				text : '�ȴ�����',
				value : '3'
			}, {
				text : '������',
				value : '4'
			}, {
				text : '��������',
				value : '7'
			}, {
				text : '���',
				value : '5'
			}]
		}],
		searchitems : [{
			display : "���뵥��",
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