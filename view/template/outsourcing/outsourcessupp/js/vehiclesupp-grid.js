var show_page = function(page) {
	$("#vehiclesuppGrid").yxgrid("reload");
};
$(function() {
	//��ͷ��ť����
	var buttonsArr = [];
	var addArr = false;
	var editArr = false;
	var limit = new Array(); //Ȩ������
	var excelInArr = {
		name : 'exportIn',
		text : "����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=outsourcing_outsourcessupp_vehiclesupp&action=toExcelIn"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=600");
		}
	};
	var excelOutCustomArr = {
		name : 'exportOut',
		text : "����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=outsourcing_outsourcessupp_vehiclesupp&action=toExcelOutCustom"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800");
		}
	};
	var viewArr = {
		name : 'view',
		text : "�߼���ѯ",
		icon : 'view',
		action : function() {
			showThickboxWin("?model=outsourcing_outsourcessupp_vehiclesupp&action=toSearch&"
				+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800');
		}
    };
	$.ajax({
		type : 'POST',
		url : '?model=outsourcing_outsourcessupp_vehiclesupp&action=getLimits',
		data : {
			'limitArr' : '����Ȩ��,����Ȩ��,����Ȩ��,�༭Ȩ��,�鿴Ȩ��,ɾ��Ȩ��,������Ȩ��'
		},
		async : false,
		success : function(data) {
			limit = data.replace('"','').split(',');
			if (limit[0] == 1) {
				buttonsArr.push(excelInArr);
			}
			if (limit[1] == 1) {
				buttonsArr.push(excelOutCustomArr);
			}
			if (limit[2] == 1) {
				addArr = true;
			}
			if (limit[3] == 1) {
				editArr = true;
			}
		}
	});

	buttonsArr.push(viewArr);

	$("#vehiclesuppGrid").yxgrid({
		model : 'outsourcing_outsourcessupp_vehiclesupp',
        title : '������Ӧ��',
        bodyAlign : 'center',
        isDelAction : false,
        showcheckbox : false,
        isAddAction : addArr,
        isEditAction : editArr,
		//����Ϣ
		colModel : [{
 					display : 'id',
 						name : 'id',
 					sortable : true,
 						hide : true
			  },{
    					name : 'suppCode',
  					display : '��Ӧ�̱��',
  					sortable : true,
						width : 70,
					process : function(v,row){
							return "<a href='#' onclick='showModalWin(\"?model=outsourcing_outsourcessupp_vehiclesupp&action=toViewTab&id=" + row.id +"\",1)'>" + v + "</a>";
					}
              },{
    					name : 'suppName',
  					display : '��Ӧ������',
  					sortable : true,
					width : 150,
					process : function(v,row){
						if (row.suppLevel == '0') {
							return "<span style='color:red'>" + v + "</span>";
						}else {
							return v;
						}
					}
              },{
    					name : 'province',
  					display : 'ʡ��',
  					sortable : true,
  						width : 40
              },{
    					name : 'city',
  					display : '����',
  					sortable : true,
  						width : 80
              },{
    					name : 'suppCategory',
  					display : '��Ӧ������',
  					sortable : true,
 						hide : true
              },{
    					name : 'suppCategoryName',
  					display : '��Ӧ����������',
  					sortable : true,
 						hide : true
              },{
    					name : 'suppLevel',
  					display : '��Ӧ�̼���',
  					sortable : true,
 						hide : true
              },{
    					name : 'registeredDate',
  					display : '����ʱ��',
  					sortable : true,
  						width : 80
              },{
    					name : 'registeredFunds',
  					display : 'ע���ʽ�(��Ԫ)',
  					sortable : true
              },{
    					name : 'businessDistribute',
  					display : 'ҵ��ֲ�',
  					sortable : true
              },{
    					name : 'businessDistributeId',
  					display : 'ҵ��ֲ�Id',
  					sortable : true,
 						hide : true
              },{
    					name : 'carAmount',
  					display : '��������',
  					sortable : true,
  						width : 50
              },{
    					name : 'driverAmount',
  					display : '˾������',
  					sortable : true,
  						width : 50
              },{
    					name : 'tentativeTalk',
  					display : '������̸����',
  					sortable : true,
  						width : 250,
  						align : 'left'
              },{
    					name : 'invoice',
  					display : '��Ʊ����',
  					sortable : true,
 						hide : true
              },{
    					name : 'invoiceCode',
  					display : '��Ʊ���Ա���',
  					sortable : true,
 						hide : true
              },{
    					name : 'taxPoint',
  					display : '��Ʊ˰��',
  					sortable : true,
 						hide : true
              },{
    					name : 'isEquipDriver',
  					display : '�ܷ��䱸˾��',
  					sortable : true,
 						hide : true
              },{
    					name : 'isDriveTest',
  					display : '����·�⾭��',
  					sortable : true,
 						hide : true
              },{
    					name : 'companyProfile',
  					display : '��˾���',
  					sortable : true,
 						hide : true
              },{
    					name : 'linkmanName',
  					display : '��ϵ������',
  					sortable : true,
  						width : 80
              },{
    					name : 'linkmanJob',
  					display : '��ϵ��ְ��',
  					sortable : true,
  						width : 80
              },{
    					name : 'linkmanPhone',
  					display : '��ϵ�˵绰',
  					sortable : true,
  						width : 80
              },{
    					name : 'linkmanMail',
  					display : '��ϵ������',
  					sortable : true,
  						width : 150
              },{
    					name : 'ExaStatus',
  					display : '����״̬',
  					sortable : true,
 						hide : true
              },{
    					name : 'ExaDT',
  					display : '��������',
  					sortable : true,
 						hide : true
              }],
		lockCol:['suppCode','suppName'],//����������
		// ���ӱ������
		subGridOptions : {
			url : '?model=outsourcing_outsourcessupp_NULL&action=pageItemJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'XXX',
						display : '�ӱ��ֶ�'
					}]
		},

		menusEx : [{
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function() {
				if (limit[5] == 1) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=outsourcing_outsourcessupp_vehiclesupp&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								$("#vehiclesuppGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		},{
			text : '���������',
			icon : 'delete',
			showMenuFn : function(row) {
				if (limit[6] == 1 && row.suppLevel != '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=outsourcing_outsourcessupp_vehiclesupp&action=toBlacklistView&id='
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=800");
			}
		},{
			text : '����������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (limit[6] == 1 && row.suppLevel == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=outsourcing_outsourcessupp_vehiclesupp&action=toUndoBlackView&id='
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=800");
			}
		},{
			name : 'view',
			text : "������־",
			icon : 'view',
			showMenuFn : function() {
				if (limit[4] == 1) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue="
						+ row.id
						+ "&tableName=oa_outsourcessupp_vehiclesupp"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		}],

		buttonsEx : buttonsArr,

		toAddConfig : {
			toAddFn : function(p, g) {
				showModalWin("?model=outsourcing_outsourcessupp_vehiclesupp&action=toAdd",'1');
			}
		},
		toEditConfig : {
			toEditFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_outsourcessupp_vehiclesupp&action=toEditTab&id=" + get[p.keyField],'1');
				}
			}
		},
		toViewConfig : {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_outsourcessupp_vehiclesupp&action=toViewTab&id=" + get[p.keyField],'1');
				}
			}
		},

		searchitems : [{
				display : "��Ӧ�̱��",
				name : 'suppCodeSea'
			},{
				display : "��Ӧ������",
				name : 'suppName'
			},{
				display : "ʡ��",
				name : 'provinceSea'
			},{
				display : "����",
				name : 'citySea'
			},{
				display : "����ʱ��",
				name : 'registeredDateSea'
			},{
				display : "ҵ��ֲ�",
				name : 'businessDistribute'
			},{
				display : "������̸����",
				name : 'tentativeTalk'
			},{
				display : "��ϵ������",
				name : 'linkmanName'
			},{
				display : "��ϵ��ְ��",
				name : 'linkmanJob'
			},{
				display : "��ϵ�˵绰",
				name : 'linkmanPhone'
			},{
				display : "��ϵ������",
				name : 'linkmanMail'
			}],

		sortname : 'suppLevel ASC,id'
 	});
 });