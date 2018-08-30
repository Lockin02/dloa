var show_page = function(page) {
	$("#vehicleGrid").yxgrid("reload");
};
$(function() {
	$("#vehicleGrid").yxgrid({
		model : 'outsourcing_outsourcessupp_vehicle',
        title : '������Ӧ��-������Դ��',
        isAddAction:false,
		isEditAction:false,
		isDelAction:false,
		param:{'suppId' : $("#suppId").val()},
		bodyAlign:'center',
		showcheckbox:false,
		//����Ϣ
			colModel : [{
 					display : 'id',
 						name : 'id',
 					sortable : true,
 						hide : true
			  },{
            			name : 'formBelong',
  					display : '���ݹ�����˾',
  					sortable : true,
 						hide : true
              },{
    					name : 'formBelongName',
  					display : '���ݹ�����˾CN',
  					sortable : true,
 						hide : true
              },{
    					name : 'businessBelong',
  					display : 'ҵ�������˾',
  					sortable : true,
 						hide : true
              },{
    					name : 'businessBelongName',
  					display : 'ҵ�������˾CN',
  					sortable : true,
 						hide : true
              },{
    					name : 'suppCode',
  					display : '��Ӧ�̱��',
  					sortable : true,
 						hide : true
              },{
    					name : 'suppName',
  					display : '��Ӧ������',
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
			display : "���ƺ�",
			name : 'carNumber'
		},{
			display : "˾��",
			name : 'driver'
		},{
			display : "���֤��",
			name : 'idNumber'
		}]
 	});
 });