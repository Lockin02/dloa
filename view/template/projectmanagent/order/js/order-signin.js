var show_page = function(page) {
	$("#signinGrid").yxgrid("reload");
};
$(function() {
	$("#signinGrid").yxgrid({
		model: 'projectmanagent_order_order',
		action : 'SignInJson',
		param : {'signIn': '0' , 'ExaStatusArr' : '完成,变更审批中' ,'states' :'2,3,4'},
//		'orderstate' : '已提交' ,
		title: '未签收列表',
			isViewAction : false,
			isEditAction : false,
			isDelAction : false,
			showcheckbox : false,
			isAddAction : false,
			customCode : 'signin',
		// 扩展右键菜单 duplicate

		menusEx : [{
			text : '查看',
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
			text : '签收合同',
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
			text : '导出',
			icon : 'add',
			action: function(row){
				     window.open ('?model=contract_common_allcontract&action=importCont&id='
				                      + row.orgid
				                      +'&type='
				                      +row.tablename
				                      + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		   },{
			text : '附件上传',
			icon : 'add',
			action: function(row){
				     showThickboxWin ('?model=projectmanagent_order_order&action=toUploadFile&id='
				                      + row.orgid
				                      +'&type='
				                      +row.tablename
				                      + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
			}
		   }],

		//列信息
		colModel :
			[
			  {
 					display : 'id',
 					name : 'id',
 					sortable : true,
 					hide : true
			  },{
			        name : 'tablename',
			        display : '合同类型',
			        sortable : true,
			        process : function(v){
  						if( v == 'oa_sale_order'){
  							return "销售合同";
  						}else if(v == 'oa_sale_service'){
  							return "服务合同";
  						}else if(v == 'oa_sale_lease'){
  							return "租赁合同";
  						}else if(v == 'oa_sale_rdproject'){
  							return "研发合同";
  						}else if (v == ''){
						    return "金额合计";
						}
  					},
  					width : 80
			  },{
			        name : 'signinType',
			        display : '签收类型',
			        sortable : true,
			        process : function(v){
  						if( v == 'order'){
  							return "销售类";
  						}else if(v == 'service'){
  							return "服务类";
  						}else if(v == 'lease'){
  							return "租赁类";
  						}else if(v == 'rdproject'){
  							return "研发类";
  						}else if (v == ''){
						    return "金额合计";
						}
  					},
  					width : 80
			  },{
					name : 'createTime',
  					display : '建立时间',
  					sortable : true,
  					hide : true,
  					width : 200
              },{
					name : 'orderCode',
  					display : '鼎利合同号',
  					sortable : true,
  					width : 200
              },{
					name : 'orderTempCode',
  					display : '临时合同号',
  					sortable : true,
  					width : 200
              },{
					name : 'state',
  					display : '合同状态',
  					sortable : true,
  					process : function(v){
  						if( v == '0'){
  							return "未提交";
  						}else if(v == '1'){
  							return "审批中";
  						}else if(v == '2'){
  							return "执行中";
  						}else if(v == '3'){
  							return "已关闭";
  						}else if(v == '4'){
  						    return "已完成";
  						}
  					},
  					width : 80
              },{
					name : 'ExaStatus',
  					display : '审批状态',
  					sortable : true,
  					width : 80
              },{
                    name : 'signIn',
                    display : '签收状态',
                    sortalbe : true,
                    process : function(v){
  						if( v == '0'){
  							return "未签收";
  						}else if(v == '1'){
  							return "已签收";
  						}
  					},
  					width : 80
              },{
    				name : 'sign',
  					display : '是否签约',
  					sortable : true,
  					width : 70
              },{
    				name : 'orderstate',
  					display : '纸质合同状态',
  					sortable : true,
  					width : 80
              },{
					name : 'orderTempMoney',
					display : '预计合同金额',
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
					display : '签约合同金额',
					sortable : true,
					width : 80,
					process : function(v) {
						return moneyFormat2(v);
					}
				},{
    				name : 'invoiceMoney',
  					display : '开票金额',
  					width : 100,
  					process : function(v){
  						return moneyFormat2(v);
  					}
              },{
    				name : 'incomeMoney',
  					display : '已收金额',
  					width : 100,
  					process : function(v){
  						return moneyFormat2(v);
  					}
              },{
					name : 'customerName',
					display : '客户名称',
					sortable : true,
					width : 100
			  },{
    				name : 'prinvipalName',
  					display : '合同负责人',
  					sortable : true
              },{
    				name : 'areaName',
  					display : '所属区域',
  					sortable : true
              },{
    				name : 'areaPrincipal',
  					display : '区域负责人',
  					sortable : true
              }, {
					name : 'objCode',
					display : '业务编号',
					width : 150
			  }, {
					name : 'remark',
					display : '备注',
					width : 150
				}
          ],
          buttonsEx : [{
			name : 'export',
			text : "列表导出",
			icon : 'excel',
			action : function(row) {
				var type = $("#tablename").val();
				var state = $("#state").val();
				var ExaStatus = $("#ExaStatus").val();
				var beginDate = $("#signinGrid").data('yxgrid').options.extParam.beginDate;//开始时间
				var endDate = $("#signinGrid").data('yxgrid').options.extParam.endDate;//截止时间
				var ExaDT = $("#signinGrid").data('yxgrid').options.extParam.ExaDT;//建立时间
				var areaNameArr = $("#signinGrid").data('yxgrid').options.extParam.areaNameArr;//归属区域
				var orderCodeOrTempSearch = $("#signinGrid").data('yxgrid').options.extParam.orderCodeOrTempSearch;//合同编号
				var prinvipalName = $("#signinGrid").data('yxgrid').options.extParam.prinvipalName;//合同负责人
				var customerName = $("#signinGrid").data('yxgrid').options.extParam.customerName;//客户名称
				var orderProvince = $("#signinGrid").data('yxgrid').options.extParam.orderProvince;//所属省份
				var customerType = $("#signinGrid").data('yxgrid').options.extParam.customerType;//客户类型
				var orderNatureArr = $("#signinGrid").data('yxgrid').options.extParam.orderNatureArr;//合同属性
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
								+ "&ExaStatus=完成,变更审批中"
								+ "&states=2,3,4"
								+ "&1width=200,height=200,top=200,left=200,resizable=yes")
			}
		},{
			name : 'advancedsearch',
			text : "高级搜索",
			icon : 'search',
			action : function(row) {
				showThickboxWin("?model=projectmanagent_order_order&action=search&gridName=signinGrid"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700")
			}
		}],
          comboEx : [ {
			text : '合同类型',
			key : 'tablename',
			data : [ {
				text : '销售合同',
				value : 'oa_sale_order'
			}, {
				text : '租赁合同',
				value : 'oa_sale_lease'
			},{
				text : '服务合同',
				value : 'oa_sale_service'
			},{
				text : '研发合同',
				value : 'oa_sale_rdproject'
			}  ]
		}],
           /**
			 * 快速搜索
			 */
		searchitems : [{
			display : '合同编号',
			name : 'orderCodeOrTempSearch'
		},{
			display : '合同名称',
			name : 'orderName'
		},{
			display : '合同负责人',
			name : 'prinvipalName'
		},{
			display : '所属区域',
			name : 'areaName'
		},{
			display : '区域负责人',
			name : 'areaPrincipal'
		},{
			display : '业务编号',
			name : 'objCode'
		}]


	});
});
