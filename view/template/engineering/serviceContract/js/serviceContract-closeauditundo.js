/**收票管理列表**/

var show_page=function(page){
   $("#serviceContractGrid").yxgrid("reload");
};

$(function(){
        $("#serviceContractGrid").yxgrid({

        	model:'engineering_serviceContract_serviceContract',
        	action:'jsonCloseAuditNo',
        	title:'未审批销售异常关闭申请',
        	isToolBar:false,
        	showcheckbox : false,
        	isViewAction:false,
        	isAddAction:false,
        	isEditAction:false,
        	isDelAction:false,

			// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'sign',
			display : '是否签约',
			sortable : true
		}, {
			name : 'orderstate',
			display : '纸质合同状态',
			sortable : true
		}, {
			name : 'parentOrder',
			display : '父合同名称',
			sortable : true,
			hide : true
		}, {
			name : 'orderCode',
			display : '鼎利合同号',
			sortable : true
		}, {
			name : 'orderTempCode',
			display : '临时合同号',
			sortable : true
		}, {
			name : 'orderName',
			display : '合同名称',
			sortable : true
		}, {
			name : 'state',
			display : '合同状态',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "未提交";
				} else if (v == '1') {
					return "审批中";
				} else if (v == '2') {
					return "执行中";
				} else if (v == '3') {
					return "已关闭";
				} else if (v == '4') {
					return "已完成";
				}
			},
			width : 90
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 90
		},{
		    name : 'transmit',
		    display : '任务书状态',
		    sortable : true,
		    process : function(v) {
				if (v == '0') {
					return "未下达";
				} else if (v == '1') {
					return "已下达";
				}
			}
		}],



					//扩展右键菜单
					menusEx : [
					{
						text : '查看',
						icon : 'view',
						action : function(row,rows,grid){
							showThickboxWin("?model=engineering_serviceContract_serviceContract&action=init&perm=view&id="+ row.contractId + "&skey="+row['skey_']
							+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800"
							);
						}
					},
						{
							text : '审批',
							icon : 'edit',
							action : function(row,rows,grid){
								if(row){
									location = "controller/engineering/serviceContract/ewf_close.php?actTo=ewfExam&orderId="+row.contractId+"&spid="+row.id+"&billId="+row.contractId+"&examCode=oa_sale_service" + "&skey="+row['skey_'];
								}
							}
						}
					],
			searchitems:[
			        {
			            display:'合同名称',
			            name:'orderName'
			        }
			        ],
			sortname:'id',
			sortorder:'DESC'
        });
});