var show_page = function(page) {
	$("#signinGrid").yxgrid("reload");
};
$(function() {
	$("#signinGrid").yxgrid({
		model: 'projectmanagent_order_order',
		action : 'SignInJson',
		param : {'signIn': '0' , 'ExaStatusArr' : '���,���������' ,'states' :'2,3,4'},
//		'orderstate' : '���ύ' ,
		title: 'δǩ���б�',
			isViewAction : false,
			isEditAction : false,
			isDelAction : false,
			showcheckbox : false,
			isAddAction : false,
			customCode : 'signin',
		// ��չ�Ҽ��˵� duplicate

		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action: function(row){
				  if(row.tablename == 'oa_sale_order'){
				     showOpenWin('?model=projectmanagent_order_order&action=toViewTab&id='+ row.orgid + "&skey="+row['skey_']);
				  } else if (row.tablename == 'oa_sale_service'){
				     showOpenWin("?model=engineering_serviceContract_serviceContract&action=toViewTab&id=" + row.orgid+ "&skey="+row['skey_'])
                  } else if (row.tablename == 'oa_sale_lease'){
                     showOpenWin("?model=contract_rental_rentalcontract&action=toViewTab&id=" + row.orgid+ "&skey="+row['skey_'])
                  } else if (row.tablename == 'oa_sale_rdproject') {
                     showOpenWin("?model=rdproject_yxrdproject_rdproject&action=toViewTab&id=" + row.orgid+ "&skey="+row['skey_'])
                  }

			}
		   },{
			text : 'ǩ�պ�ͬ',
			icon : 'add',
			action: function(row){
				if(row.tablename == 'oa_sale_order'){
				     showOpenWin('?model=projectmanagent_order_order&action=toSign&id='
						+ row.orgid
						+ "&skey="+row['skey_']
                        + '&perm=signIn');
				  } else if (row.tablename == 'oa_sale_service'){
				     showOpenWin('?model=engineering_serviceContract_serviceContract&action=toSign&id='
						+ row.orgid
						+ "&skey="+row['skey_']
                        + '&perm=signIn');
                  } else if (row.tablename == 'oa_sale_lease'){
                      showOpenWin('?model=contract_rental_rentalcontract&action=toSign&id='
						+ row.orgid
						+ "&skey="+row['skey_']
                        + '&perm=signIn');
                  } else if (row.tablename == 'oa_sale_rdproject') {
                     showOpenWin('?model=rdproject_yxrdproject_rdproject&action=toSign&id='
						+ row.orgid
						+ "&skey="+row['skey_']
                        + '&perm=signIn');
                  }

			}
		   },{
			text : '����',
			icon : 'add',
			action: function(row){
				     window.open ('?model=contract_common_allcontract&action=importCont&id='
				                      + row.orgid
				                      +'&type='
				                      +row.tablename
				                      + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		   },{
			text : '�����ϴ�',
			icon : 'add',
			action: function(row){
				     showThickboxWin ('?model=projectmanagent_order_order&action=toUploadFile&id='
				                      + row.orgid
				                      +'&type='
				                      +row.tablename
				                      + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
			}
		   }],

		//����Ϣ
		colModel :
			[
			  {
 					display : 'id',
 					name : 'id',
 					sortable : true,
 					hide : true
			  },{
			        name : 'tablename',
			        display : '��ͬ����',
			        sortable : true,
			        process : function(v){
  						if( v == 'oa_sale_order'){
  							return "���ۺ�ͬ";
  						}else if(v == 'oa_sale_service'){
  							return "�����ͬ";
  						}else if(v == 'oa_sale_lease'){
  							return "���޺�ͬ";
  						}else if(v == 'oa_sale_rdproject'){
  							return "�з���ͬ";
  						}else if (v == ''){
						    return "���ϼ�";
						}
  					},
  					width : 80
			  },{
			        name : 'signinType',
			        display : 'ǩ������',
			        sortable : true,
			        process : function(v){
  						if( v == 'order'){
  							return "������";
  						}else if(v == 'service'){
  							return "������";
  						}else if(v == 'lease'){
  							return "������";
  						}else if(v == 'rdproject'){
  							return "�з���";
  						}else if (v == ''){
						    return "���ϼ�";
						}
  					},
  					width : 80
			  },{
					name : 'createTime',
  					display : '����ʱ��',
  					sortable : true,
  					hide : true,
  					width : 200
              },{
					name : 'orderCode',
  					display : '������ͬ��',
  					sortable : true,
  					width : 200
              },{
					name : 'orderTempCode',
  					display : '��ʱ��ͬ��',
  					sortable : true,
  					width : 200
              },{
					name : 'state',
  					display : '��ͬ״̬',
  					sortable : true,
  					process : function(v){
  						if( v == '0'){
  							return "δ�ύ";
  						}else if(v == '1'){
  							return "������";
  						}else if(v == '2'){
  							return "ִ����";
  						}else if(v == '3'){
  							return "�ѹر�";
  						}else if(v == '4'){
  						    return "�����";
  						}
  					},
  					width : 80
              },{
					name : 'ExaStatus',
  					display : '����״̬',
  					sortable : true,
  					width : 80
              },{
                    name : 'signIn',
                    display : 'ǩ��״̬',
                    sortalbe : true,
                    process : function(v){
  						if( v == '0'){
  							return "δǩ��";
  						}else if(v == '1'){
  							return "��ǩ��";
  						}
  					},
  					width : 80
              },{
    				name : 'sign',
  					display : '�Ƿ�ǩԼ',
  					sortable : true,
  					width : 70
              },{
    				name : 'orderstate',
  					display : 'ֽ�ʺ�ͬ״̬',
  					sortable : true,
  					width : 80
              },{
					name : 'orderTempMoney',
					display : 'Ԥ�ƺ�ͬ���',
					sortable : true,
					width : 80,
					process : function(v,row) {
						if(row.orderMoney == '' || row.orderMoney == 0.00 || row.id== 'allMoney'){
                           return moneyFormat2(v);
						}else{
						   return "<font color = '#B2AB9B'>"
								+ moneyFormat2(v) + "</font>";
						}

					}
				}, {
					name : 'orderMoney',
					display : 'ǩԼ��ͬ���',
					sortable : true,
					width : 80,
					process : function(v) {
						return moneyFormat2(v);
					}
				},{
    				name : 'invoiceMoney',
  					display : '��Ʊ���',
  					width : 100,
  					process : function(v){
  						return moneyFormat2(v);
  					}
              },{
    				name : 'incomeMoney',
  					display : '���ս��',
  					width : 100,
  					process : function(v){
  						return moneyFormat2(v);
  					}
              },{
					name : 'customerName',
					display : '�ͻ�����',
					sortable : true,
					width : 100
			  },{
    				name : 'prinvipalName',
  					display : '��ͬ������',
  					sortable : true
              },{
    				name : 'areaName',
  					display : '��������',
  					sortable : true
              },{
    				name : 'areaPrincipal',
  					display : '��������',
  					sortable : true
              }, {
					name : 'objCode',
					display : 'ҵ����',
					width : 150
			  }, {
					name : 'remark',
					display : '��ע',
					width : 150
				}
          ],
          buttonsEx : [{
			name : 'export',
			text : "�б���",
			icon : 'excel',
			action : function(row) {
				var type = $("#tablename").val();
				var state = $("#state").val();
				var ExaStatus = $("#ExaStatus").val();
				var beginDate = $("#signinGrid").data('yxgrid').options.extParam.beginDate;//��ʼʱ��
				var endDate = $("#signinGrid").data('yxgrid').options.extParam.endDate;//��ֹʱ��
				var ExaDT = $("#signinGrid").data('yxgrid').options.extParam.ExaDT;//����ʱ��
				var areaNameArr = $("#signinGrid").data('yxgrid').options.extParam.areaNameArr;//��������
				var orderCodeOrTempSearch = $("#signinGrid").data('yxgrid').options.extParam.orderCodeOrTempSearch;//��ͬ���
				var prinvipalName = $("#signinGrid").data('yxgrid').options.extParam.prinvipalName;//��ͬ������
				var customerName = $("#signinGrid").data('yxgrid').options.extParam.customerName;//�ͻ�����
				var orderProvince = $("#signinGrid").data('yxgrid').options.extParam.orderProvince;//����ʡ��
				var customerType = $("#signinGrid").data('yxgrid').options.extParam.customerType;//�ͻ�����
				var orderNatureArr = $("#signinGrid").data('yxgrid').options.extParam.orderNatureArr;//��ͬ����
				var i = 1;
				var colId = "";
				var colName = "";
				$("#signinGrid_hTable").children("thead").children("tr")
						.children("th").each(function() {
							if ($(this).css("display") != "none"
									&& $(this).attr("colId") != undefined) {
								colName += $(this).children("div").html() + ",";
								colId += $(this).attr("colId") + ",";
								i++;
							}
						})
			window.open("?model=projectmanagent_order_order&action=singInExportExcel&colId="
								+ colId + "&colName=" + colName + "&type=" + type + "&state=" + state + "&ExaStatus=" + ExaStatus
								+ "&beginDate=" + beginDate + "&endDate=" + endDate + "&ExaDT=" + ExaDT
								+ "&areaNameArr=" + areaNameArr + "&orderCodeOrTempSearch=" + orderCodeOrTempSearch
								+ "&prinvipalName=" + prinvipalName + "&customerName=" + customerName
								+ "&orderProvince=" + orderProvince + "&customerType=" + customerType
								+ "&orderNatureArr=" + orderNatureArr
								+ "&signIn=0"
								+ "&ExaStatus=���,���������"
								+ "&states=2,3,4"
								+ "&1width=200,height=200,top=200,left=200,resizable=yes")
			}
		},{
			name : 'advancedsearch',
			text : "�߼�����",
			icon : 'search',
			action : function(row) {
				showThickboxWin("?model=projectmanagent_order_order&action=search&gridName=signinGrid"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700")
			}
		}],
          comboEx : [ {
			text : '��ͬ����',
			key : 'tablename',
			data : [ {
				text : '���ۺ�ͬ',
				value : 'oa_sale_order'
			}, {
				text : '���޺�ͬ',
				value : 'oa_sale_lease'
			},{
				text : '�����ͬ',
				value : 'oa_sale_service'
			},{
				text : '�з���ͬ',
				value : 'oa_sale_rdproject'
			}  ]
		}],
           /**
			 * ��������
			 */
		searchitems : [{
			display : '��ͬ���',
			name : 'orderCodeOrTempSearch'
		},{
			display : '��ͬ����',
			name : 'orderName'
		},{
			display : '��ͬ������',
			name : 'prinvipalName'
		},{
			display : '��������',
			name : 'areaName'
		},{
			display : '��������',
			name : 'areaPrincipal'
		},{
			display : 'ҵ����',
			name : 'objCode'
		}]


	});
});
