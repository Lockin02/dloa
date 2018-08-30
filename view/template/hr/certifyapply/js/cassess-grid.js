var show_page = function(page) {
	$("#cassessGrid").yxgrid("reload");
};
$(function() {
	$("#cassessGrid").yxgrid({
		model : 'hr_certifyapply_cassess',
		title : '��ְ�ʸ����۱�',
		isAddAction : false,
		isDelAction : false,
		isOpButton : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'modelName',
				display : 'ƥ��ģ������',
				sortable : true,
				hide : true
			}, {
				name : 'userNo',
				display : 'Ա�����',
				sortable : true,
				hide : true
			}, {
				name : 'userAccount',
				display : 'Ա���˺�',
				sortable : true,
				hide : true
			}, {
				name : 'userName',
				display : 'Ա������',
				sortable : true,
				width : 80
			}, {
				name : 'deptName',
				display : '��������',
				sortable : true,
				width : 80
			}, {
				name : 'jobName',
				display : 'ְλ����',
				sortable : true,
				width : 80
			}, {
				name : 'nowDirectionName',
				display : '��ǰͨ��',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'nowLevelName',
				display : '��ǰ����',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'nowGradeName',
				display : '��ǰ����',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'careerDirectionName',
				display : '����ͨ��',
				sortable : true,
				width : 80
			}, {
				name : 'baseLevelName',
				display : '���뼶��',
				sortable : true,
				width : 70
			}, {
				name : 'baseGradeName',
				display : '���뼶��',
				sortable : true,
				width : 70
			}, {
				name : 'managerName',
				display : '������ί',
				sortable : true,
				width : 80
			}, {
				name : 'memberName',
				display : '������ί',
				sortable : true
			}, {
				name : 'status',
				display : '״̬',
				sortable : true,
				width : 80,
				process : function(v){
					switch(v){
						case '0' : return '����';break;
						case '1' : return '��֤׼����';break;
						case '2' : return '������';break;
						case '3' : return '��ɴ�����';break;
						case '4' : return '���������';break;
						case '5' : return 'ȷ�������';break;
						case '6' : return '��������';break;
						case '7' : return '��֤ʧ��';break;
						default : return v;
					}
				}
			}, {
				name : 'ExaStatus',
				display : '����״̬',
				sortable : true,
				width : 60
			}, {
				name : 'ExaDT',
				display : '��������',
				sortable : true,
				width : 80
			}, {
				name : 'scoreAll',
				display : '����',
				sortable : true,
				width : 60
			}, {
				name : 'createName',
				display : '������',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'createTime',
				display : '����ʱ��',
				sortable : true,
				width : 120,
				hide : true
			}, {
				name : 'updateName',
				display : '�޸�������',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '�޸�ʱ��',
				sortable : true,
				hide : true
			}],
		toEditConfig : {
			action : 'toEdit',
			showMenuFn : function(row) {
				if (row.status == '0') {
					return true;
				}
				return false;
			},
			toEditFn : function(p, g) {
				var c = p.toEditConfig;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					//�ж�
					showModalWin("?model=" + p.model + "&action=" + c.action + c.plusUrl + "&id=" + rowData[p.keyField] + keyUrl);
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			}
		},
		toViewConfig : {
			action : 'toView',
			showMenuFn : function(row) {
				return true;
			},
			toViewFn : function(p, g) {
				var c = p.toViewConfig;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					//�ж�
					showModalWin("?model=" + p.model + "&action=" + c.action + c.plusUrl + "&id=" + rowData[p.keyField] + keyUrl);
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			}
		},
		buttonsEx :[{
			text: "ָ����ί",
			icon: 'edit',
			action: function(row,rows,idArr ) {
					var idArr=$("#cassessGrid").yxgrid("getCheckedRowIds");  //��ȡѡ�е�id
				if (row) {
					//�ж������Ƿ��������
					for (var i = 0; i < rows.length; i++) {
						if(rows[i].status != '3'){
							alert('��¼ ['+ rows[i].id +']״̬����ȷ ��ֻ��״̬Ϊ [��ɴ�����] �ļ�¼����ָ����ί');
							return false;
						}

						if(rows[i].scoreAll*1 > 0){
							alert('��¼ ['+ rows[i].id +']���ڽ������֣���������ָ����ί');
							return false;
						}
					}
					//�ύ��֤���
					idStr = idArr.toString();

					showThickboxWin("?model=hr_certifyapply_cassess&action=toSetManager&id="
						+ idStr
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=500"
					);

				}else{
					alert('��ѡ��һ�м�¼��');
				}
			}
		},{
			text: "��֤���",
			icon: 'edit',
			items : [{
				text: "�ύ��֤���",
				icon: 'edit',
				action: function(row,rows,idArr ) {
					var idArr=$("#cassessGrid").yxgrid("getCheckedRowIds");  //��ȡѡ�е�id
					if (row) {
						//�ж������Ƿ��������
						for (var i = 0; i < rows.length; i++) {
							if(rows[i].status != '4'){
								alert('��¼ ['+ rows[i].id +']״̬����ȷ ��ֻ��״̬Ϊ [���������] �ļ�¼�����ύ��֤����');
								return false;
							}
						}
						//�ύ��֤���
						idStr = idArr.toString();
						//�ж�
						showModalWin("?model=hr_certifyapply_certifyresult&action=toAdd&assessIds=" + idStr );
					}else{
						alert('��ѡ��һ�м�¼��');
					}
				}
			},{
				text: "��֤ʧ��",
				icon: 'edit',
				action: function(row,rows,idArr ) {
					var idArr=$("#cassessGrid").yxgrid("getCheckedRowIds");  //��ȡѡ�е�id
					if (row) {
						//�ж������Ƿ��������
						for (var i = 0; i < rows.length; i++) {
							if(rows[i].status != '4'){
								alert('��¼ ['+ rows[i].id +']״̬����ȷ ��ֻ��״̬Ϊ [���������] �ļ�¼���ܽ��д˲���');
								return false;
							}
						}

						if(confirm('ȷ������ѡ��¼���¸���֤ʧ����')){
							//�ύ��֤���
							idStr = idArr.toString();
							$.ajax({
							    type: "POST",
							    url: "?model=hr_certifyapply_cassess&action=assessFailure",
							    data: {"ids" : idStr},
							    async: false,
							    success: function(data){
							   	    if(data == 1){
										alert('���ݸ��³ɹ�');
										show_page();
									}else{
										alert('���ݸ���ʧ��');
									}
								}
							});
						}
					}else{
						alert('��ѡ��һ�м�¼��');
					}
				}
			}]
		}],
		menusEx : [{
			name : 'edit',
			text : "�ύ��֤׼��",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.status == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if(confirm('ȷ���ύ��֤׼����')){
					$.ajax({
					    type: "POST",
					    url: "?model=hr_certifyapply_cassess&action=handUp",
					    data: {"id" : row.id},
					    async: false,
					    success: function(data){
					   	    if(data == 1){
								alert('�ύ�ɹ�');
								show_page();
							}else{
								alert('�ύʧ��');
							}
						}
					});
				}
			}
		},{
			name : 'setManagerInfo',
			text : "ָ����ί",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '3') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if(row.scoreAll*1 > 0){
					alert('���ڽ������֣���������ָ����ί');
					return false;
				}
				//�ж�
				showThickboxWin("?model=hr_certifyapply_cassess&action=toSetManager&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=500"
				);
			}
		},{
			name : 'edit',
			text : "¼�����",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.status == '3' || row.status == '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if(row.managerId == "" || row.memberId == ""){
					alert('��ѡ��������ί�Ͳ�����ί');
					return false;
				}
				//�ж�
				showModalWin("?model=hr_certifyapply_cassess&action=toInScore&id=" + row.id + "&skey=" + row.skey);
			}
		},{
			name : 'view',
			text : "�鿴����",
			icon : 'view',
			showMenuFn : function(row) {
				if (row.scoreAll*1  != 0) {
					return true;
				}
				return false;
			},
			action : function(row) {
				//�ж�
				showModalWin("?model=hr_certifyapply_cassess&action=toViewScore&id=" + row.id + "&skey=" + row.skey);
			}
		}],

        //��������
		comboEx:[{
		     text:'��֤ͨ��',
		     key:'careerDirection',
		     datacode : 'HRZYFZ'
		   },{
		     text:'״̬',
		     key:'status',
		     data : [{
					text : ' ����',
					value : '0'
				}, {
					text : '��֤׼����',
					value : '1'
				}, {
					text : '������',
					value : '2'
				}, {
					text : '��ɴ�����',
					value : '3'
				}, {
					text : '���������',
					value : '4'
				}, {
					text : 'ȷ�������',
					value : '5'
				}, {
					text : '��������',
					value : '6'
				}, {
					text : '��֤ʧ��',
					value : '7'
				}
			]
		}],

		searchitems : [{
			display : "Ա������",
			name : 'userNameSearch'
		}]
	});
});