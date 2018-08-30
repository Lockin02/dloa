/**
 * 下拉客户表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_stampconfig', {
		options : {
			hiddenId : 'stampType',
			nameCol : 'stampType',
            valueCol : 'stampType',
			searchName : 'stampNameSearch',
			width : 650,
			gridOptions : {
				showcheckbox : false,
				model : 'system_stamp_stampconfig',
				action : 'jsonSelect',
				param : { 'status' : 1 },
				// 列信息
				colModel : [{
						display : 'id',
						name : 'id',
						sortable : true,
						hide : true
					},{
                        name : 'businessBelongId',
                        display : '公司ID',
                        sortable : true,
                        hide : true
                    },{
                        name : 'businessBelongName',
                        display : '公司',
						width : 150,
                        sortable : true
                    },{
                        name : 'typeId',
                        display : '印章类别ID',
                        sortable : true,
                        hide : true
                    },{
                        name : 'typeName',
                        display : '印章类别',
                        sortable : true,
						hide : true
                    },{
						name : 'stampType',
						display : '印章名称',
						sortable : true,
						width : 150
					},{
						name : 'legalPersonUsername',
						display : '公司法人用户名',
						sortable : true,
						hide : true
					},{
						name : 'legalPersonName',
						display : '公司法人姓名',
						sortable : true,
						width : 220,
						hide : true
					},
					{
						name : 'principalName',
						display : '印章管理员',
						sortable : true,
						width : 200
					}, {
						name : 'principalId',
						display : '印章管理员id',
						sortable : true,
						hide : true
					},{
						name : 'remark',
						display : '备注',
						sortable : true,
						hide : true,
						width : 250
					}
				],
				// 快速搜索
				searchitems : [{
						display : '公司',
						name : 'businessBelongNameSer'
					},{
						display : '章名称',
						name : 'stampNameSearch'
					}],
				//// 默认搜索字段名
				//sortname : "id",
				//// 默认搜索顺序
				//sortorder : "DESC"
					}
				}
			});
})(jQuery);