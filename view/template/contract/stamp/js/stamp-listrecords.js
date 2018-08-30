var show_page = function(page) {
	$("#stampGrid").yxgrid("reload");
};
$(function() {
	$("#stampGrid").yxgrid({
		model : 'contract_stamp_stamp',
		title : '盖章记录',
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		isViewAction :false,
		customCode : 'stampRecordsGrid',
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'contractId',
				display : '合同id',
				sortable : true,
				hide : true
			}, {
				name : 'contractCode',
				display : '合同编号',
            	width : 130,
				sortable : true
			}, {
				name : 'contractName',
				display : '合同名称',
				sortable : true,
            	width : 130,
				hide : true
			}, {
				name : 'contractType',
				display : '合同类型',
				sortable : true,
            	datacode : 'HTGZYD'
			}, {
				name : 'signCompanyName',
				display : '签约单位',
				sortable : true,
            	width : 130,
				hide : true
			}, {
				name : 'contractMoney',
				display : '合同金额',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'applyUserId',
				display : '申请人id',
				sortable : true,
				hide : true
			}, {
				name : 'applyUserName',
				display : '申请人',
				width : 80,
				sortable : true
			}, {
				name : 'applyDate',
				display : '申请日期',
				width : 80,
				sortable : true
			}, {
				name : 'stampType',
				display : '盖章类型',
				sortable : true
			}, {
				name : 'stampUserId',
				display : '盖章人id',
				sortable : true,
				hide : true
			}, {
				name : 'stampUserName',
				display : '盖章人',
				sortable : true
			}, {
				name : 'stampDate',
				display : '盖章日期',
				width : 80,
				sortable : true
			}, {
				name : 'stampCompany',
				display : '公司',
				sortable : true,
				width : 80,
				process : function(v){
					if(v == '' || v == 'NULL'){
						return '';
					}else{
						return v;
					}
				}
			},  {
				name : 'stampCompanyId',
				display : '公司ID',
				sortable : true,
				hide : true,
				process : function(v){
					if(v == '' || v == 'NULL'){
						return '';
					}else{
						return v;
					}
				}
			},{
				name : 'status',
				display : '状态',
				sortable : true,
				width : 80,
				process : function(v,row){
					if(v=="1"){
						return "已盖章";
					}else if(v=='2'){
						return "已关闭";
					}else{
						return "未盖章";
					}
				}
			}, {
				name : 'objCode',
				display : '业务编号',
				width : 120,
				sortable : true
			}, {
				name : 'batchNo',
				display : '盖章批号',
				sortable : true,
				hide : true
			}, {
				name : 'remark',
				display : '备注',
				width : 200,
				sortable : true
			}
		],
        // 扩展按钮
        buttonsEx : [{
            text: "导出",
            icon: 'excel',
            action: function() {
                var i = 1;
                var colId = "";
                var colName = "";
                $("#stampGrid_hTable").children("thead").children("tr")
                    .children("th").each(function() {
                        if ($(this).css("display") != "none"
                            && $(this).attr("colId") != undefined) {
                            colName += $(this).children("div").html() + ",";
                            colId += $(this).attr("colId") + ",";
                            i++;
                        }
                    });
                var url = "?model=contract_stamp_stamp&action=toExportExcel"
                    + '&exportType=getAll'
                    + "&colName="
                    + colName
                    + "&colId="
                    + colId;
                window.open(url);
            }
        }],
		// 扩展右键菜单
		menusEx : [{
				name : 'view',
				text : '查看',
				icon : 'view',
				action : function(row, rows, grid) {
					showModalWin('?model=contract_stamp_stamp&action=toView&id=' + row.id);
				}
			}
		],
		searchitems : [{
			display : "合同编号",
			name : 'contractCodeSer'
		},{
			display : "申请人",
			name : 'applyUserNameSer'
		},{
			display : "公司",
			name : "stampCompanySearcha"
		}],
		// 盖章状态数据过滤
		comboEx : [{
			text: "盖章状态",
			key: 'status',
			value : 1,
			data : [{
				text : '未盖章',
				value : '0'
			}, {
				text : '已盖章',
				value : '1'
			}, {
				text : '已关闭',
				value : '2'
			}]
		}]
	});
});