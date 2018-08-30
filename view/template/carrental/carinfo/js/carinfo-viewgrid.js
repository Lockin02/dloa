var show_page = function(page) {
	$("#carinfoGrid").yxgrid("reload");
};
$(function() {
	$("#carinfoGrid").yxgrid({
		model : 'carrental_carinfo_carinfo',
	   	title : '������Ϣ',
       	param : { "unitsId" : $("#unitsId").val() },
	   	isViewAction : false,
	   	isDelAction : false,
	   	isAddAction : false,
	   	isEditAction : false,
		//����Ϣ
		colModel : [
		{
 			display : 'id',
 			name : 'id',
 			sortable : true,
 			hide : true
		},{
        	name : 'unitsName',
  			display : '��λ����',
  			sortable : true
        },{
        	name : 'carType',
  			display : '�����ͺ�',
  			sortable : true
        },{
    		name : 'carNo',
  			display : '���ƺ�',
  			sortable : true
        },{
			name : 'limitedSeating',
  			display : '��������',
  			sortable : true
        },{
    		name : 'status',
  			display : '����״̬',
  			sortable : true,
			process : function(val) {
				if (val == "0") {
				return "��Ч";
				} else if(val == "1"){
					return "ʧЧ";
				} else {
					return "����ʧЧ";
				}
			}
         },{
			name : 'remark',
  			display : '��ע˵��',
  			sortable : true,
  			width : 200
         }],
		menusEx : [
		{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=carrental_carinfo_carinfo&action=viewTab&id=" + row.id + '&skey=' + row.skey_ );
				} else {
					alert("��ѡ��һ������");
				}
			}
		}],
		comboEx : [{
					text : '����״̬',
					key : 'status',
					data : [{
								text : 'ʧЧ',
								value : '1'
							}, {
								text : '��Ч',
								value : '0'
							}]
				}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems :  [{
			display : '��λ����',
			name : 'unitName'
		}, {
			display : '�����ͺ�',
			name : 'carType'
		}]
 		});
 });