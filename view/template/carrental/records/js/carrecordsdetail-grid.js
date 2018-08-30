var show_page = function(page) {
	$("#carrecordsdetailGrid").yxgrid("reload");
};
$(function() {
	$("#carrecordsdetailGrid").yxgrid({
		model : 'carrental_records_carrecordsdetail',
		title : '�⳵��ϸ',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'useDate',
				display : 'ʹ������',
				sortable : true,
				width : 80,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=carrental_records_carrecordsdetail&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
				}
			}, {
				name : 'carNo',
				display : '����',
				sortable : true,
				width : 80,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=carrental_carinfo_carinfo&action=toView&id=" + row.carId + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
				}
			}, {
				name : 'rentalTypeName',
				display : '�⳵����',
				sortable : true
			}, {
				name : 'mileage',
				display : '�����',
				sortable : true,
				width : 60
			}, {
				name : 'useHours',
				display : 'ʹ��ʱ��',
				sortable : true,
				width : 80
			}, {
				name : 'travelFee',
				display : '�˳���',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'fuelFee',
				display : '�ͷ�',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'roadFee',
				display : '·�ŷ�',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'parkingFee',
				display : 'ͣ����',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'effectiveLog',
				display : '��ЧLOG',
				sortable : true,
				width : 60
			}, {
				name : 'useReson',
				display : '��;',
				sortable : true
			}, {
				name : 'createId',
				display : '������Id',
				sortable : true,
				hide : true
			}, {
				name : 'createName',
				display : '������',
				sortable : true
			}, {
				name : 'createTime',
				display : '����ʱ��',
				sortable : true
			}, {
				name : 'updateId',
				display : '�޸���Id',
				sortable : true,
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
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "����",
			name : 'carNoSearch'
		}]
	});
});