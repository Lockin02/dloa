var show_page = function(page)
{
    $("#recontractGrid").yxsubgrid("reload");
};
var yearI;
$.ajax(
    {
        type: 'POST',
        url: '?model=hr_recontract_recontract&action=getYears',
        async: false,
        success: function(data)
        {
			yearI=eval(data);
        }
    });
$(function()
{
    // ��ͷ��ť����
    /*buttonsArr = [{
		text : "����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_recontract_recontract&action=toExcel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
		}
	}],*/
	
	buttonsArr =[],
	HTDC = {
		name : 'export',
		text : "����",
		icon : 'excel',
		action : function(row) {
			var searchConditionKey = "";
			var searchConditionVal = "";
			for (var t in $("#recontractGrid").data('yxsubgrid').options.searchParam) {
				if (t != "") {
					searchConditionKey += t;
					searchConditionVal += $("#recontractGrid").data('yxsubgrid').options.searchParam[t];
				}
			}
			var isPaperContract=$('#isPaperContract').val();
			var states=$('#statusId').val();
			var month=$('#month').val();
			var year=$('#year').val();
			var urlExpoler="?model=hr_recontract_recontract&action=exportExcel&statusId="+states
			            +"&isPaperContract="+isPaperContract
						 +"&month="+month
						  +"&year="+year
						+"&"+searchConditionKey+'='+searchConditionVal;
			//alert(urlExpoler);
			//return false;			
			window.open(urlExpoler);
		}
	},$.ajax({
		type : 'POST',
		url : '?model=contract_contract_contract&action=getLimits',
		data : {
			'limitName' : '��ͬ����Ȩ��'
		},
		async : false,
		success : function(data) {
			
				buttonsArr.push(HTDC);
			
		}
	});
    // ��ͷ��ť����
    excelOutArr1 = 
    {
        name: 'exportIn',
        text: "��������",
        icon: 'add',
        items: [
        {
            name: "2",
            text: "�ύ����",
            icon: 'add',
            action : function(rowData, rows, rowIds, g) 
            {
        		var ids='';
				for (var i = 0; i < rows.length; i++) 
                {
					if (rows[i].statusId == "2") 
                    {
                        ids=rows[i].id+','+ids;
                    }
                }
                if(ids)
				{
					options(2,ids);
				}
            }
        }, 
        {
            name: "3",
            text: "֪ͨԱ��ȷ��",
            icon: 'add',
            action: function(rowData, rows, rowIds, g)
            {
                var ids='';
				for (var i = 0; i < rows.length; i++) 
                {
                    if (rows[i].statusId == "4"||rows[i].statusId == "5") 
                    {
                        ids=rows[i].id+','+ids;
                    }
                }
                if(ids)
				{
					options(5,ids);
				}
            }
        }, 
        {
            name: "4",
            text: "ǩ��ֽ�ʺ�ͬ",
            icon: 'add',
            action: function(rowData, rows, rowIds, g)
            {
                var ids='';
				for (var i = 0; i < rows.length; i++) 
                {
                    if (rows[i].statusId == "7") 
                    {
                        ids=rows[i].id+','+ids;
                    }
                }
                if(ids)
				{
					options(7,ids);
				}
            }
        }, 
        {
            name: "5",
            text: "���",
            icon: 'add',
            action: function(rowData, rows, rowIds, g)
            {
                var ids='';
				for (var i = 0; i < rows.length; i++) 
                {
                    if (rows[i].statusId == "6") 
                    {
                        ids=rows[i].id+','+ids;
                    }
                }
                if(ids)
				{
					options(8,ids);
				}
            }
        }, 
        {
            name: "6",
            text: "�ر�",
            icon: 'add',
            action: function(rowData, rows, rowIds, g)
            {
               
                if(rowIds)
				{
					options(9,rowIds);
				}
            }
        }]
    };
	
   buttonsArr.push(excelOutArr1);
   $("#recontractGrid").yxsubgrid(
    {
        model: 'hr_recontract_recontract',
        action: 'pageJsons',
        title: '��ͬ��ǩ��Ϣ',
        isAddAction : true,
        isEditAction: false,
        isViewAction: false,
        isDelAction: false,
		event : {
			afterloaddata : function(e, data) {
				if (data) {
					for (var i = 0; i < data.collection.length; i++) {
						if(data.collection[i].different==2){
							$('#row' + data.collection[i].id).css('color', 'red');
						}
					}
				}
			}
		},
        lockCol:['remark'],//����������
        complexColModel: [[
        {
            name: 'id',
            display: '���',
            width: '70',
			hide : true,
			rowspan : 2
        },{
			name : 'remark',
			display : '��ע',
			sortable : true,
			width : 20,
			rowspan: 2,
			process : function(v, row) {
			  if(v == ''){
			     return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=hr_recontract_recontract&action=listremark&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900\')">'
						+ "<img src='images/icon/icon139.gif' />" + '</a>';
			  }else{
				  return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=hr_recontract_recontract&action=listremark&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900\')">'
						+ "<img src='images/icon/icon095.gif' />" + '</a>';
			  }

			}
		},{
            name: 'userInfo',
            display: 'Ա��������Ϣ',
            width: '70',
            sortable: true,
			colspan: 5
        }, 
        {
            name: 'statusId',
            display: '״̬',
            width: '70',
            sortable: true,
            rowspan: 2,
			process : function(v, row) 
			{
				if(v==1)
				{
				  return 'δ����'
				}else if(v==2)
				{
					return '���ύ'
				}else if(v==3)
				{
					return '������'
				}else if(v==4)
				{
					return '��֪ͨԱ��'
				}else if(v==5)
				{
					return '��Ա��ȷ��'
				}else if(v==6)
				{
					return '��HRȷ��'
				}else if(v==7)
				{
					return '��ǩ��ֽ�ʺ�ͬ'
				}else if(v==8)
				{
					return '��ͬ���'
				}else if(v==9)
				{
					return '��ͬ�ر�'
				}
				  
			}
        },{
            name: 'isPaperContract',
            display: 'ֽ�ʺ�ͬ',
            width: '50',
            sortable: true,
            rowspan: 2,
			process : function(v, row) 
			{
				if(v==2)
				{
				  return '��ǩ'
				}else
				{
					return 'δǩ'
				}
				  
			}
        }, 
        {
            name: 'beginDate',
            display: '�ϴκ�ͬǩ���ſ�',
            width: '70',
            sortable: true,
            colspan: 5
        }, 
        {
            name: 'aa',
            display: '���κ�ͬǩ����˾ȷ�Ͻ��',
            colspan: 3
        
        }, 
        {
            name: 'aaaaaa',
            display: '���κ�ͬǩ��Ա��ȷ�Ͻ��',
            colspan: 3
        }, 
        {
            name: 'aaaaaa',
            display: '���κ�ͬǩ��������ǩ���',
            colspan: 7
        }], [
        {
            name: 'userNo',
            display: 'Ա�����',
            width: '50',
            sortable: true,
            hide: true
        }, 
        {
            name: 'userName',
            display: '����',
            width: '50',
            sortable: true
        }, 
        {
            name: 'companyName',
            display: '��˾',
            width: '60',
            sortable: true
        }, 
        {
            name: 'deptName',
            display: '����',
            width: '80',
            sortable: true
        }, 
        {
            name: 'jobName',
            display: 'ְλ',
            width: '70',
            sortable: true
        }, 
        {
            name: 'comeinDate',
            display: '��ְ����',
            width: '70',
            sortable: true
        }, 
        {
            name: 'obeginDate',
            display: '��ʼʱ��',
            width: '70',
            sortable: true
        }, 
        {
            name: 'ocloseDate',
            display: '����ʱ��',
            width: '70',
            sortable: true
        }, 
        {
            name: 'oconNumName',
            display: '�ù�����',
            width: '60',
            sortable: true
        }, 
        {
            name: 'oconStateName',
            display: '�ù���ʽ',
            width: '60',
            sortable: true
        },{
            name: 'oconNumsName',
            display: 'ǩ������',
            width: '60',
            sortable: true
        }, 
        {
            name: 'aisFlagName',
            display: '�Ƿ�ͬ����ǩ',
            width: '60',
            sortable: true,
			process : function(v, row) 
			{
				if(row.statusId>3)
				{
				  return v
				}else
				{
					return ''
				}
				  
			}
        }, 
        {
            name: 'aconStateName',
            display: '�ù���ʽ',
            width: '70',
            sortable: true,
			process : function(v, row) 
			{
				if(row.statusId>3)
				{
				  return v
				}else
				{
					return ''
				}
				  
			}
        }, 
        {
            name: 'aconNumName',
            display: '�ù�����',
            width: '70',
            sortable: true,
			process : function(v, row) 
			{
				if(row.statusId>3)
				{
				  return v
				}else
				{
					return ''
				}
				  
			}
        }, 
        {
            name: 'pisFlagName',
            display: '�Ƿ�ͬ����ǩ',
            width: '70',
            sortable: true
        }, 
        {
            name: 'pconNumName',
            display: '�ù�����',
            width: '70',
            sortable: true
        }, 
        {
            name: 'pconStateName',
            display: '�ù���ʽ',
            width: '70',
            sortable: true
        }, 
        {
            name: 'isFlagName',
            display: '�Ƿ�ͬ����ǩ',
            width: '70',
            sortable: true
        }, 
        {
            name: 'beginDate',
            display: '��ʼʱ��',
            width: '70',
            sortable: true
        }, 
        {
            name: 'closeDate',
            display: '����ʱ��',
            width: '70',
            sortable: true
        },  
        {
            name: 'conNumName',
            display: '�ù�����',
            width: '70',
            sortable: true
        }, 
        {
            name: 'conStateName',
            display: '�ù���ʽ',
            width: '70',
            sortable: true
        }, 
        {
            name: 'signCompanyName',
            display: 'ǩ����˾',
            width: '200',
            sortable: true
        }, 
        {
            name: 'repaAddress',
            display: '���յ�ַ',
            width: '150',
            sortable: true
        }]],
		//lockCol:['userName','companyName'],//����������
		buttonsEx:buttonsArr,
		
        // ���ӱ������
        subGridOptions: 
        {
            url: '?model=hr_recontract_recontractApproval&action=pageJson',// ��ȡ�ӱ�����url
            // ���ݵ���̨�Ĳ�����������
            param: [
            {
                paramId: 'recontractId',// ���ݸ���̨�Ĳ�������
                colId: 'id'// ��ȡ���������ݵ�������
            }],
            
            // ��ʾ����
            colModel: [
            {
                name: 'stepName',
                display: '��������'
            }, 
            {
                name: 'createName',
                display: '������'
            }, 
            {
                name: 'isFlagName',
                display: '�Ƿ���ǩ'
            
            }, 
            {
                name: 'conNumName',
                display: '�ù�����'
            }, 
            {
                name: 'conStateName',
                display: '�ù���ʽ'
            }, 
            {
                name: 'beginDate',
                display: '��ͬ��ʼ����'
            
            }, 
            {
                name: 'closeDate',
                display: '��ͬ��������'
            
            },{
                name: 'createTime',
                display: '��������'
            
            }, 
            {
                name: 'conContent',
                display: '���'
            }]
        },
        // ��չ�Ҽ��˵�
        menusEx: [
        {
            text: '�����ǩ���',
            icon: 'add',
            showMenuFn: function(row)
            {
                if (row.statusId > 1) 
                {
                    return false;
                }
                return true;
            },
            action: function(row, rows, grid)
            {
                if (row) 
                {
                    showThickboxWin("?model=hr_recontract_recontract&action=viewArbitra&id=" + row.id + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=800');
                    
                }
                else 
                {
                    alert("��ѡ��һ������");
                }
            }
        }, 
        {
            text: '�޸���ǩ���',
            icon: 'add',
            showMenuFn: function(row)
            {
                if (row.statusId != 2) 
                {
                    return false;
                }
                return true;
            },
            action: function(row, rows, grid)
            {
                if (row) 
                {
                    showThickboxWin("?model=hr_recontract_recontract&action=editArbitra&id=" +
                    row.id +
                    '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=800');
                }
                else 
                {
                    alert("��ѡ��һ������");
                }
            }
        }, 
        {
            text: '�������',
            icon: 'add',
            showMenuFn: function(row)
            {
                if (row.statusId < 3) 
                {
                    return false;
                }
                return true;
            },
            action: function(row, rows, grid)
            {
                if (row) 
                {
                    showThickboxWin("?model=hr_recontract_recontractapproval&action=approvalInfoApp&id=" +
                    row.id +
                    '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
                }
                else 
                {
                    alert("��ѡ��һ������");
                }
            }
        }, 
        {
            text: '�༭',
            icon: 'add',
            showMenuFn: function(row)
            {
                if (row.statusId < 4 ||row.statusId ==5||row.statusId >6) 
                {
                    return false;
                }
                return true;
            },
            action: function(row, rows, grid)
            {
                if (row) 
                {
                    showThickboxWin("?model=hr_recontract_recontract&action=viewArbitra&id=" + row.id + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
                    
                }
                else 
                {
                    alert("��ѡ��һ������");
                }
            }
        }, 
        {
            text: '�ύ����',
            icon: 'view',
            showMenuFn: function(row)
            {
                if (row.statusId != "2") 
                {
                    return false;
                }
                return true;
            },
            action: function(row, rows, grid)
            {
                if (row) 
                {
                    showThickboxWin("?model=hr_recontract_recontractapproval&action=toApproval&id=" +
                    row.id +
                    '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=800');
                }
                else 
                {
                    alert("��ѡ��һ������");
                }
            }
        }, 
        {
            text: '֪ͨԱ��',
            icon: 'view',
            showMenuFn: function(row)
            {
                if (row.statusId !=4) 
                {
                    return false;
                }
                return true;
            },
            action: function(row, rows, grid)
            {
                if (row) 
                {
                    $.ajax(
                    {
                        type: 'POST',
                        url: '?model=hr_recontract_recontractapproval&action=toInformStaff',
                        data: 
                        {
                            'id': row.id
                        },
                        async: false,
                        success: function(data)
                        {
                            if (data == 1) 
                            {
                                alert('֪ͨԱ����ǩ�ɹ�');
                            }
                            else 
                            {
                                alert('֪ͨԱ����ǩʧ��');
                                
                            }
							show_page();
                        }
                    });
                }
                else 
                {
                    alert("��ѡ��һ������");
                }
            }
        }, 
        {
            text: '���',
            icon: 'view',
            showMenuFn: function(row)
            {
                if (row.statusId != "6"||row.staffFlag!=2) 
                {
                    return false;
                }
                return true;
            },
            action: function(row, rows, grid)
            {
                if (row) 
                {
                    $.ajax(
                    {
                        type: 'POST',
                        url: '?model=hr_recontract_recontractapproval&action=InEnd',
                        data: 
                        {
                            'id': row.id
                        },
                        async: false,
                        success: function(data)
                        {
                            if (data == 1) 
                            {
                                alert('�ύ�ɹ�');
                            }
                            else 
                            {
                                alert('�ύʧ��');
                                
                            }
							show_page();
                        }
                    });
                }
                else 
                {
                    alert("��ѡ��һ������");
                }
            }
        }, 
        {
            text: 'ǩ��ֽ�ʺ�ͬ',
            icon: 'view',
            showMenuFn: function(row)
            {
	            if (row.statusId != "7") 
	                {
                    return false;
                }
                return true;
            },
            action: function(row, rows, grid)
            {
                if (row) 
                {
                    $.ajax(
                    {
                        type: 'POST',
                        url: '?model=hr_recontract_recontractapproval&action=InPaperContract',
                        data: 
                        {
                            'id': row.id
                        },
                        async: false,
                        success: function(data)
                        {
                            if (data == 1) 
                            {
                                alert('�ύ�ɹ�');
                            }
                            else 
                            {
                                alert('�ύʧ��');
                                
                            }
							show_page();
                        }
                    });
                }
                else 
                {
                    alert("��ѡ��һ������");
                }
            }
        }, 
        {
            text: '�ر�',
            icon: 'view',
            showMenuFn: function(row)
            {
	            if (row.statusId == "9") 
	                {
                    return false;
                }
                return true;
            },
            action: function(row, rows, grid)
            {
                if (row) 
                {
                    if (confirm("ȷ��Ҫ�رմ˺�ͬ��ǩ��")) 
				   {
						$.ajax(
	                    {
	                        type: 'POST',
	                        url: '?model=hr_recontract_recontractapproval&action=InClose',
	                        data: 
	                        {
	                            'id': row.id
	                        },
	                        async: false,
	                        success: function(data)
	                        {
	                            if (data == 1) 
	                            {
	                                alert('�رճɹ�');
	                            }
	                            else 
	                            {
	                                alert('�ر�ʧ��');
	                                
	                            }
								show_page();
	                        }
	                    });
						 }
	                else 
	                {
	                    return false;
	                }
                }
                else 
                {
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
        comboEx: [
		{
            text: '���',
            key: 'year',
            data: yearI
		},
		{
            text: '�·�',
            key: 'month',
            data: [
			{
                text: '1�·�',
                value: '01'
            },{
                text: '2�·�',
                value: '02'
            },{
                text: '3�·�',
                value: '03'
            },{
                text: '4�·�',
                value: '04'
            },{
                text: '5�·�',
                value: '05'
            },{
                text: '6�·�',
                value: '06'
            },{
                text: '7�·�',
                value: '07'
            },{
                text: '8�·�',
                value: '08'
            },{
                text: '9�·�',
                value: '09'
            },{
                text: '10�·�',
                value: '10'
            },{
                text: '11�·�',
                value: '11'
            },{
                text: '12�·�',
                value: '12'
            }
			]
        }, 
        {
            text: 'ֽ�ʺ�ͬ״̬',
            key: 'isPaperContract',
            data: [
            {
                text: '��ǩֽ�ʺ�ͬ',
                value: '2'
            }, 
            {
                text: 'δǩֽ�ʺ�ͬ',
                value: '1'
			}]
        },  {
            text: 'ִ��״̬',
            key: 'statusId',
            data: [
            {
                text: 'δ����',
                value: '1'
            }, 
            {
                text: '���ύ',
                value: '2'
            }, 
            {
                text: '������',
                value: '3'
            }
			/*, 
            {
                text: '��֪ͨԱ��',
                value: '4'
            }, 
            {
                text: '��Ա��ȷ��',
                value: '5'
            }*/, 
            {
                text: '��HRȷ��',
                value: '6'
            }, 
            {
                text: '����ǩֽ�ʺ�ͬ',
                value: '7'
            }, 
            {
                text: '���',
                value: '8'
            }, 
            {
                text: '�ر�',
                value: '9'
            }]
        }]
    
    });
	//$('.sDiv2').append("<input id='recordDate' type='text' class='txt' onfocus='WdatePicker()' name='recontract[recordDate]'  />");
	
});


function  options(status,ids)
{
	$.ajax(
    {
        type: 'POST',
        url: '?model=hr_recontract_recontractapproval&action=options',
        data: 
        {
            'status': status,
			'ids': ids
        },
        async: false,
        success: function(data)
        {
             alert('�����ɹ���');
			 show_page();
        }
    });
	
}





