var show_page = function(page) {
	$("#recontractGrid").yxgrid("reload");
};
//��ͷ��ť����
buttonsArr = [];

// ��ͷ��ť����
excelOutArr1 = {
	name : 'exportIn',
	text : "��������",
	icon : 'edit',
	items:[{
            name: "2",
            text: "ͬ����ǩ",
            icon: 'add',
            action : function(rowData, rows, rowIds, g) 
            {
				var ids='';
				if(rowIds)
				{
				 for (var i = 0; i < rows.length; i++) 
                {
					if (rows[i].statusId == "3") 
                    {
                        ids+='{"pid":'+rows[i].pid+',"id":'+rows[i].id+'},';
                    }
                }
                if(ids)
				{
					options(1,ids);
				}	
				}else
				{
					alert('��ѡ��һ�����ݣ�');
				}
				
				
            }
        }, 
        {
            name: "3",
            text: "��ͬ����ǩ",
            icon: 'add',
            action: function(rowData, rows, rowIds, g)
            {
                var ids='';
				if(rowIds)
				{
				 for (var i = 0; i < rows.length; i++) 
                {
					if (rows[i].statusId == "3") 
                    {
                        ids+='{"pid":'+rows[i].pid+',"id":'+rows[i].id+'},';
                    }
                }
                if(ids)
				{
					options(2,ids);
				}	
				}else
				{
					alert('��ѡ��һ�����ݣ�');
				}
				
            }
        },{
            name: "4",
            text: "ͬ����һ���������",
            icon: 'add',
            action : function(rowData, rows, rowIds, g) 
            {
				var ids='';
				if(rowIds)
				{
				 for (var i = 0; i < rows.length; i++) 
                {
					if (rows[i].statusId == "3") 
                    {
                        ids+='{"pid":'+rows[i].pid+',"id":'+rows[i].id+'},';
                    }
                }
                if(ids)
				{
					options(3,ids);
				}	
				}else
				{
					alert('��ѡ��һ�����ݣ�');
				}
				
				
            }
        }]
}
;
buttonsArr.push(excelOutArr1);

$(function() {

	$("#recontractGrid")
			.yxgrid(
					{
						model : 'hr_recontract_recontractapproval',
						action:'pageJsonAppList',
						title : '��ǩ����',
						isAddAction : false,
						isEditAction : false,
						isViewAction : false,
						isDelAction : false,
						// ����Ϣ
						colModel : [
								{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								},
								{
									name : 'userNo',
									display : 'Ա�����',
									width:'50',
									sortable : true,
									hide : true
								},
								{
									name : 'userName',
									display : '����',
									width:'60',
									sortable : true
								},
								{
									name : 'companyName',
									display : '��˾',
									width:'80',
									sortable : true
								},
								{
									name : 'deptName',
									display : '����',
									width:'100',
									sortable : true
								},
								{
									name : 'jobName',
									display : 'ְλ',
									width:'80',
									sortable : true
								},{
									name : 'Flag',
									display : '����״̬',
									width:'80',
									sortable : true
								},
								{
									name : 'comeinDate',
									display : '��ְ����',
									width:'70',
									sortable : true
								},{
									name : 'obeginDate',
									display : '�ϴκ�ͬ��ʼʱ��',
									width:'85',
									sortable : true
								}, {
									name : 'ocloseDate',
									display : '�ϴκ�ͬ����ʱ��',
									width:'85',
									sortable : true
								}, {
									name : 'oconNumName',
									display : '�ϴκ�ͬ�ù�����',
									width:'85',
									sortable : true
								}, {
									name : 'oconStateName',
									display : '�ϴκ�ͬ�ù���ʽ',
									width:'85',
									sortable : true
								},{
									name : 'beginDate',
									display : '���κ�ͬ��ʼʱ��',
									width:'85',
									sortable : true
								}, {
									name : 'closeDate',
									display : '���κ�ͬ����ʱ��',
									width:'85',
									sortable : true
								}, {
									name : 'conNumName',
									display : '���κ�ͬ�ù�����',
									width:'85',
									sortable : true
								}, {
									name : 'conStateName',
									display : '���κ�ͬ�ù���ʽ',
									width:'85',
									sortable : true
								}, {
									name : 'conContent',
									display : '��ǩ����',
									sortable : true
								}],
						buttonsEx : buttonsArr,
						
						// ��չ�Ҽ��˵�
						menusEx : [{
							text : '����',
							icon : 'add',
							showMenuFn : function(row) {
								if (row.Flag =='������'&&row.Result =='1') {
									return false;
								}
								return true;
							},
							action : function(row, rows, grid) {
								if (row) {
									showThickboxWin("?model=hr_recontract_recontract&action=toApproval&id=" + row.pid + '&fspid=' + row.id + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=700&width=800');
								} else {
									alert("��ѡ��һ������");
								}
							}
						},{
							text : '�鿴��ϸ',
							icon : 'view',
							showMenuFn : function(row) {
								if (row.id == "noId") {
									return false;
								}
								return true;
							},
							action : function(row, rows, grid) {
								if (row) 
								{
									showThickboxWin("?model=hr_recontract_recontract&action=viewApproval&id=" + row.pid + '&fspid=' + row.id + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=700&width=800');
								} else {
									alert("��ѡ��һ������");
								}
							}
						}],
						/**
						 * ��������
						 */
						  searchitems: [
						{
							display: 'Ա������',
							name: 'userName'
						}, 
						{
							display: 'Ա�����',
							name: 'userNo'
						}, 
						{
							display: '��˾',
							name: 'companyName'
						}, 
						{
							display: '����',
							name: 'deptName'
						}, 
						{
							display: 'ְλ',
							name: 'jobName'
						}],
						// ����״̬���ݹ���
						comboEx : [  {
							text : '����״̬',
							key : 'ExaStatus',
							value : '2',
							data : [ {
								text : 'δ����',
								value : '2'
							}, {
								text : '������',
								value : '1'
							} ]
						} ]

					});
});


function  options(status,ids)
{
	$.ajax(
    {
        type: 'POST',
        url: '?model=hr_recontract_recontractapproval&action=batchApproval',
        data: 
        {
            'status': status,
			'ids': ids
        },
        async: false,
        success: function(data)
        {
           if(data==1)
		   {
		   	 alert('���ݴ���');
		   }else
		   {

		   	var jsonobj=eval('('+data+')');
		    alert('��'+jsonobj.sc+'�������ɹ���,��'+jsonobj.fc+'����ʧ�ܣ�');
		   }
           show_page();
        }
		
    });
	
}