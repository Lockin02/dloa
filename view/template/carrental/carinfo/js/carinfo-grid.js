var show_page = function(page) {
	$("#carinfoGrid").yxgrid("reload");
};
$(function() {
	$("#carinfoGrid").yxgrid({
		model : 'carrental_carinfo_carinfo',
	   	title : '������Ϣ',
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
  			sortable : true,
 			hide : true
        },{
    		name : 'carNo',
  			display : '���ƺ�',
  			sortable : true,
  			width : 80,
			process : function(v,row){
				return "<a href='#' onclick='showThickboxWin(\"?model=carrental_carinfo_carinfo&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
			}
        },{
        	name : 'carTypeName',
  			display : '����',
  			sortable : true,
  			width : 80
        },{
        	name : 'brand',
  			display : 'Ʒ��',
  			sortable : true,
  			width : 80
        },{
        	name : 'displacement',
  			display : '����',
  			sortable : true,
  			width : 60
        },{
			name : 'buyDate',
  			display : '��������',
  			sortable : true,
  			width : 80
        },{
			name : 'owners',
  			display : '����',
  			sortable : true,
  			width : 70
        },{
			name : 'driver',
  			display : '˾��',
  			sortable : true,
  			width : 70
        },{
			name : 'linkPhone',
  			display : '��ϵ��ʽ',
  			sortable : true,
  			width : 80
        },{
			name : 'isSign',
  			display : '�Ƿ�ǩ��ͬ',
  			sortable : true,
  			width : 70,
  			process : function(v){
  				if(v == '1'){
  					return "��";
  				}else{
  					return "��";
  				}
  			}
        },{
			name : 'useDays',
  			display : '�ۼ��ó�����',
  			sortable : true,
  			width : 80
        },{
			name : 'evaluate',
  			display : '����',
  			sortable : true
        },{
			name : 'fuelConsumption',
  			display : '�ۺ��ͺ�',
  			sortable : true,
  			width : 60
        },{
			name : 'perFuel',
  			display : 'ƽ���ͺ�',
  			sortable : true,
  			width : 80
        },{
			name : 'lastCheckDate',
  			display : '�������',
  			sortable : true,
  			width : 80
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