var show_page = function(page) {
	$("#applyitemGrid").yxgrid("reload");
	};
$(function() {
	$("#applyitemGrid").yxgrid({
		model : 'service_repair_applyitem',
               	title : 'ά������(����)�嵥',

						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							  },{
                    					name : 'productType',
										display : '���Ϸ���',
										hide : true,
                  					sortable : true
                              },{
                    					name : 'productCode',
                  					display : '���ϱ��',
                  					sortable : true
                              },{
                    					name : 'productName',
                  					display : '��������',
                  					sortable : true,
                  					width : 250
                              },{
                    					name : 'pattern',
                  					display : '����ͺ�',
                  					sortable : true
                              },{
                    					name : 'unitName',
                  					display : '��λ',
                  					sortable : true
                              },{
                    					name : 'serilnoName',
                  					display : '���к�',
                  					sortable : true
                              },{
                    					name : 'fittings',
                  					display : '�����Ϣ',
                  					sortable : true
                              },{
                    					name : 'place',
                  					display : '���ڵص�',
                  					sortable : true
                              },{
                    					name : 'process',
                  					display : 'Ԥ������',
                  					sortable : true
                              },{
                    					name : 'troubleInfo',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'checkInfo',
                  					display : '��⴦����',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '��ע',
                  					sortable : true
                              },{
                    					name : 'isGurantee',
                  					display : '�Ƿ����',
                  					sortable : true,
                  					 process : function(val) {
									  if (val == "0") {
										return "��";
									   }else {
										return "��";
									   }
								       }
                              },{
                    					name : 'repairType',
                  					display : '��������',
                  					sortable : true,
                  					 process : function(val) {
									  if (val == "0") {
										return "�շ�ά��";
									   }
									   if(val="1") {
										return "����ά��";
									   }else{
									   return "�ڲ�ά��";
									   }
								       }

                              },{
                    					name : 'repairCost',
                  					display : 'ά�޷���',
                  					sortable : true
                              },{
                    					name : 'cost',
                  					display : '��ȡ����',
                  					sortable : true
                              },{
                    					name : 'isDetect',
                  					display : '�Ƿ����´���ά��',
                  					sortable : true
                              },{
                    					name : 'delivery',
                  					display : '�Ƿ��ѷ���',
                  					sortable : true
                              }],

		toEditConfig : {
			toEditFn : function(p) {
					action : showThickboxWin("?model=service_repair_applyitem&action=toEdit&id="
								+ row.id
								+ "&skey="
								+ row['skey_'])
			}
		},
		toViewConfig : {
			toViewFn : function(p) {
				action : showThickboxWin("?model=service_repair_applyitem&action=toView&id="
							+ row.id
							+ "&skey="
							+ row['skey_'])
			}
		},
		searchitems : {
					display : "�����ֶ�",
					name : 'XXX'
				}
 		});
 });