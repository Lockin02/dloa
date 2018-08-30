var show_page = function(page) {
	$("#arrivalGrid").yxsubgrid("reload");
};
$(function() {
			$("#arrivalGrid").yxsubgrid({
				model : 'purchase_arrival_arrival',
               	title : 'δִ������֪ͨ��',
               	showcheckbox:false,
               	isEditAction:false,
               	isViewAction:false,
               	isDelAction:false,
               	param:{'state':"0"},
						//����Ϣ
				colModel : [{
 								display : 'id',
 								name : 'id',
 								sortable : true,
 								hide : true
					        },{
            					name : 'arrivalCode',
      					display : '���ϵ���',
      					sortable : true,
      					width:180
                  },{
        					name : 'state',
      					display : '����֪ͨ��״̬',
      					sortable : true,
						process : function(v, row) {
							if (row.state == '0') {
								return "δִ��";
							} else {
								return "��ִ��";
							}
						}
                  },{
        					name : 'purchaseId',
      					display : '����id',
      					hide : true
                  },{
        					name : 'purchaseCode',
      					display : '�ɹ��������',
      					sortable : true,
      					width:180
                  },{
        					name : 'arrivalType',
      					display : '��������',
      					sortable : true,
                  		datacode:'ARRIVALTYPE'
                  },{
        					name : 'supplierName',
      					display : '��Ӧ������',
      					sortable : true,
      					width:150
                  },{
        					name : 'supplierId',
      					display : '��Ӧ��id',
      					hide : true
                  },{
        					name : 'purchManId',
      					display : '�ɹ�ԱID',
      					hide : true
                  },{
        					name : 'purchManName',
      					display : '�ɹ�Ա',
      					sortable : true
                  },{
        					name : 'purchMode',
      					display : '�ɹ���ʽ',
      					hide : true,
                  		datacode:'cgfs'
                  },{
        					name : 'stockId',
      					display : '���ϲֿ�Id',
      					hide : true
                  },{
        					name : 'stockName',
      					display : '���ϲֿ�����',
      					sortable : true
                  }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=purchase_arrival_equipment&action=pageJson',
			param : [{
						paramId : 'arrivalId',
						colId : 'id'
					}],
			colModel : [ {
						name : 'sequence',
						display : '���ϱ��'
					}, {
						name : 'productName',
						width : 200,
						display : '��������'
					},{
						name : 'batchNum',
						display : "���κ�"
					},{
						name : 'arrivalDate',
						display : "��������"
					},{
						name : 'month',
						display : "�·�"
					}, {
						name : 'arrivalNum',
						display : "��������"
					},{
						name : 'storageNum',
						display : "���������"
					}]
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
			name : 'view',
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=purchase_arrival_arrival&action=init&perm=view&id="
							+ row.id
							+"&skey="+row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=800");
				} else {
					alert("��ѡ��һ������");
				}
			}
		},{
			name : 'edit',
			text : '�༭',
			icon : 'edit',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=purchase_arrival_arrival&action=init&id="
							+ row.id
							+"&skey="+row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=700&width=900");
				} else {
					alert("��ѡ��һ������");
				}
			}
		},
			{
			    text:'ɾ��',
			    icon:'delete',
			    showMenuFn:function(row){
			    	if(row.state==0){
			    	   return true;
			    	}
			    	return false;
			    },
			    action:function(row,rows,grid){
			    	if(row){
			    		     $.ajax({
			    		         type:"POST",
			    		         url:"?model=purchase_arrival_arrival&action=deletesConfirm",
			    		         data:{
			    		         	id:row.id
			    		         },
			    		         success:function(msg){
			    		            if(msg==0){//�ж��Ƿ��������ʼ�
			    		                alert('�����ϵ��������ʼ����뵥������ɾ��');
			    		                show_page();
			    		            }else{
							    		if(window.confirm("ȷ��Ҫɾ��?")){
							    		     $.ajax({
							    		         type:"POST",
							    		         url:"?model=purchase_arrival_arrival&action=deletesInfo",
							    		         data:{
							    		         	id:row.id
							    		         },
							    		         success:function(msg){
							    		            if(msg==1){
							    		                alert('ɾ���ɹ�!');
							    		                show_page();
							    		            }
							    		         }
							    		     });
							    		}
			    		            }
			    		         }
			    		     });
			    	}
			    }
			}],
                              toAddConfig : {

									/**
									 * ������Ĭ�Ͽ��
									 */
									formWidth :1000,
									/**
									 * ������Ĭ�ϸ߶�
									 */
									formHeight :500
								},
				searchitems : [{
					display : '���ϵ���',
					name : 'arrivalCode'
				}, {
					display : '�ɹ�Ա',
					name : 'purchManName'
				},{
					display : '��Ӧ��',
					name : 'supplierName'
				},{
					display : '��������',
					name : 'productName'
					},{
					display : '���ϱ��',
					name : 'sequence'
				}],
				// Ĭ������˳��
				sortorder : "DESC",
				sortname:"updateTime"
 		});
 });