var show_page = function(page) {
		$("#unitsGrid").yxgrid("reload");
};
$(function() {

	// ��ʼ����ͷ��ť����
	buttonsArr = [{
		// ����EXCEL�ļ���ť
		name : 'import',
		text : "����EXCEL",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=carrental_units_units&action=toUploadExcel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500")
		}

	}];

	$("#unitsGrid").yxgrid({
		model : 'carrental_units_units',
		isViewAction : false,
       	title : '�⳵��λ',
		//����Ϣ
					colModel : [{
 								display : 'id',
 								name : 'id',
 								sortable : true,
 								hide : true
					        },{
            					name : 'unitName',
  					display : '��λ����',
  					sortable : true,
  					width : 150
              },{
    					name : 'unitCode',
  					display : '��λ���',
  					sortable : true,
  					hide : true
              },{
    					name : 'address',
  					display : '��λ��ַ',
  					sortable : true,
  					width : 200
              },{
    				name : 'unitNature',
  					display : '��λ����',
  					sortable : true,
  					datacode : "DWXZ"
          },{
					name : 'countryName',
  					display : '����',
  					sortable : true,
  					hide : true
              },{
    					name : 'countryCode',
  					display : '���ұ���',
  					sortable : true,
  					hide : true
              },{
    					name : 'provinceName',
  					display : '����ʡ��',
  					sortable : true
              },{
    					name : 'provinceCode',
  					display : 'ʡ�ݱ���',
  					sortable : true,
  					hide : true
              },{
    					name : 'cityName',
  					display : '����',
  					sortable : true
              },{
    					name : 'cityCode',
  					display : '���б���',
  					sortable : true,
  					hide : true
              },{
    					name : 'linkMan',
  					display : '��ϵ��',
  					sortable : true
              },{
    					name : 'linkPhone',
  					display : '��ϵ�绰',
  					sortable : true,
  					hide : true
              },{
    					name : 'remark',
  					display : '��ע˵��',
  					sortable : true,
  					hide : true
              },{
    					name : 'createId',
  					display : '������Id',
  					sortable : true,
  					hide : true
              },{
    					name : 'createName',
  					display : '¼����',
  					sortable : true
              },{
    					name : 'createTime',
  					display : '¼��ʱ��',
  					sortable : true,
  					width : 150
              },{
    					name : 'updateId',
  					display : '�޸���Id',
  					sortable : true,
  					hide : true
              },{
    					name : 'updateName',
  					display : '�޸�������',
  					sortable : true,
  					hide : true
              },{
    					name : 'updateTime',
  					display : '�޸�ʱ��',
  					sortable : true,
  					hide : true
              }],
			menusEx : [{
					text : '�鿴',
				icon : 'view',
				action : function(row, rows, grid) {
						if (row) {
							showThickboxWin("?model=carrental_units_units&action=viewTab&id="
									+ row.id
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
						}
				}
			},{
			text : '��������',
			icon : 'business',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=carrental_carinfo_carinfo&action=toUnitsAdd&id="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&&width=800");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		buttonsEx : buttonsArr,
		toViewConfig : {
			action : 'toView'
		},
		/**
		 * ��������
		 */
		searchitems : [{
			display : '��λ����',
			name : 'unitName'
		}, {
			display : '��ϵ��',
			name : 'linkMan'
		}]
 		});
 });