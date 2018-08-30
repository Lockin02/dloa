var show_page = function(page) {
	$("#materialequGrid").yxgrid("reload");
};
$(function() {
	$("#materialequGrid").yxgrid({
		model : 'purchase_material_materialequ',
       	title : '����Э�����ϸ��',
    	bodyAlign : 'center',
        showcheckbox : false,
        isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		//����Ϣ
				colModel : [{
 					 display : 'id',
 						name : 'id',
 					sortable : true,
 						hide : true
			  },{
    					name : 'parentId',
  					display : '����id',
          			sortable : true,
 						hide : true
              },{
            			name : 'productId',
  					display : '����id',
          			sortable : true,
 						hide : true
              },{
            			name : 'productCode',
  					display : '���ϱ��',
  					sortable : true,
  						width : 120,
					process : function(v,row){
							return "<a href='#' onclick='showOpenWin(\"?model=purchase_material_material&action=toView&id="
									+ row.parentId +"\",1)'>" + v + "</a>";
					}
              },{
    					name : 'productName',
  					display : '��������',
  					sortable : true,
  						width : 300,
					process : function(v,row){
							return "<a href='#' onclick='showThickboxWin(\"?model=stock_productinfo_productinfo&action=View&id="
										+ row.productId +"&placeValuesBefore&TB_iframe=true&modal=false&height=590&width=900\")'>"
										+ v + "</a>";
					}
              },{
    					name : 'lowerNum',
  					display : '��������',
  					sortable : true,
  						width : 70,
  					process : function (e) {
						if(e == 0){
						   return "<span style='color:red'>-</span>";
						}else{
						   return e;
						}
					}
              },{
    					name : 'ceilingNum',
  					display : '��������',
  					sortable : true,
  						width : 70,
  					process : function (e) {
						if(e == 0){
						   return "<span style='color:red'>-</span>";
						}else{
						   return e;
						}
					}
              },{
    					name : 'taxPrice',
  					display : '����',
  					sortable : true,
					process : function(v){
						return moneyFormat2(v ,6);
					}
              },{
    					name : 'startValidDate',
  					display : '��ʼ��Ч��',
  					sortable : true,
  						width : 90
              },{
    					name : 'validDate',
  					display : '������Ч��',
  					sortable : true,
  						width : 90
              },{
    					name : 'suppId',
  					display : '��Ӧ��id',
  					sortable : true,
 						hide : true
              },{
    					name : 'suppName',
  					display : '��Ӧ������',
  					sortable : true,
  						width : 180
              },{
    					name : 'suppCode',
  					display : '��Ӧ�̱���',
  					sortable : true,
 						hide : true
              },{
    					name : 'isEffective',
  					display : '�Ƿ���Ч',
  					sortable : true,
						width : 50,
					process : function (e) {
						if(e == "on"){
						   return "<span style='color:blue'>��</span>";
						}else{
						   return "<span style='color:red'>��</span>";
						}
					}
              },{
    					name : 'giveCondition',
  					display : '��������',
  					sortable : true,
						width : 300,
      					align : 'left'
              },{
    					name : 'remark',
  					display : '��ע',
  					sortable : true,
						width : 300,
      					align : 'left'
              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=purchase_material_NULL&action=pageItemJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'XXX',
						display : '�ӱ��ֶ�'
					}]
		},

		comboEx:[{
			text:'�Ƿ���Ч',
			key:'isEffective',
			data:[{
			   text:'��',
			   value:'on'
			},{
			   text:'��',
			   value:'no'
			}]
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "���ϱ��",
					name : 'productCode'
				},{
					display : "��������",
					name : 'productName'
				},{
					display : "��Ӧ������",
					name : 'suppName'
				}]
 		});
 });