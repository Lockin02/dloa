var show_page = function(page) {
	$("#vehicleGrid").yxgrid("reload");
};
$(function() {
	$("#vehicleGrid").yxgrid({
		isDelAction : false,
		model : 'outsourcing_outsourcessupp_vehicle',
		title : '������Ӧ��-������Դ��',
        bodyAlign : 'center',
        showcheckbox:false,
		//����Ϣ
			colModel : [{
 					display : 'id',
 						name : 'id',
 					sortable : true,
 						hide : true
			  },{
    					name : 'suppId',
  					display : '��Ӧ��id',
  					sortable : true,
 						hide : true
              },{
    					name : 'suppCode',
  					display : '��Ӧ�̱��',
  					sortable : true,
						width : 70,
					process : function(v,row){
							return "<a href='#' onclick='showModalWin(\"?model=outsourcing_outsourcessupp_vehiclesupp&action=toViewTab&id=" + row.suppId +"\",1)'>" + v + "</a>";
					}
              },{
    					name : 'suppName',
  					display : '��Ӧ������',
						width : 150,
  					sortable : true
              },{
    					name : 'suppCategoryName',
  					display : '��Ӧ����������',
  					sortable : true,
 						hide : true
              },{
    					name : 'suppCategory',
  					display : '��Ӧ������',
  					sortable : true,
 						hide : true,
 						hide : true
              },{
    					name : 'suppLevel',
  					display : '��Ӧ�̼���',
  					sortable : true,
 						hide : true
              },{
    					name : 'place',
  					display : '�ص�',
  					sortable : true
              },{
    					name : 'carNumber',
  					display : '���ƺ�',
  					sortable : true
              },{
    					name : 'carModel',
  					display : '����',
  					sortable : true
              },{
    					name : 'brand',
  					display : 'Ʒ��',
  					sortable : true
              },{
    					name : 'displacement',
  					display : '����',
  					sortable : true
              },{
    					name : 'powerSupply',
  					display : '�����������',
  					sortable : true
              },{
    					name : 'oilWear',
  					display : '�ۺ��ͺ�',
  					sortable : true
              },{
    					name : 'buyDate',
  					display : '��������',
  					sortable : true
              },{
    					name : 'driver',
  					display : '˾��',
  					sortable : true
              },{
    					name : 'phoneNum',
  					display : '��ϵ�绰',
  					sortable : true
              },{
    					name : 'idNumber',
  					display : '���֤��',
  					sortable : true
              },{
    					name : 'drivingLicence',
  					display : '��ʻ֤',
  					sortable : true
              },{
    					name : 'vehicleLicense',
  					display : '��ʻ֤',
  					sortable : true
              },{
    					name : 'insurance',
  					display : '����',
  					sortable : true
              },{
    					name : 'annualExam',
  					display : '����',
  					sortable : true
              },{
    					name : 'rentPrice',
  					display : '�⳵����',
  					sortable : true,
  					process : function (v) {
  						return moneyFormat2(v);
  					}
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

		buttonsEx : [{
			name : 'exportIn',
			text : "����",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=outsourcing_outsourcessupp_vehicle&action=toExcelIn"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=600")
			}
		},{
			name : 'exportOut',
			text : "����",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=outsourcing_outsourcessupp_vehicle&action=toExcelOutCustom"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=800");
			}
		}],
		menusEx : [{
			text : 'ɾ��',
			icon : 'delete',
			action : function(row, rows, grid) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=outsourcing_outsourcessupp_vehicle&action=ajaxdeletes",
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
		}],

		toAddConfig : {
			formWidth : 1000,
			formHeight : 300
		},
		toEditConfig : {
			formWidth : 1000,
			formHeight : 300,
			action : 'toEdit'
		},
		toViewConfig : {
			formWidth : 1000,
			formHeight : 300,
			action : 'toView'
		},
		searchitems : [{
			display : "��Ӧ�̱��",
			name : 'suppCodeSea'
		},{
			display : "��Ӧ������",
			name : 'suppName'
		},{
			display : "�ص�",
			name : 'place'
		},{
			display : "���ƺ�",
			name : 'carNumber'
		},{
			display : "����",
			name : 'carModel'
		},{
			display : "Ʒ��",
			name : 'brand'
		},{
			display : "����",
			name : 'displacement'
		},{
			display : "�����������",
			name : 'powerSupply'
		},{
			display : "�ۺ��ͺ�",
			name : 'oilWear'
		},{
			display : "��������",
			name : 'buyDateSea'
		},{
			display : "˾��",
			name : 'driver'
		},{
			display : "��ϵ�绰",
			name : 'phoneNum'
		},{
			display : "���֤��",
			name : 'idNumber'
		},{
			display : "��ʻ֤",
			name : 'drivingLicence'
		},{
			display : "��ʻ֤",
			name : 'vehicleLicense'
		},{
			display : "����",
			name : 'insurance'
		},{
			display : "����",
			name : 'annualExam'
		}]
	});
});