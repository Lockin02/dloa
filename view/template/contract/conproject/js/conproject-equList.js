$(function() {
        //产品清单
   $("#equList").yxeditgrid({
        objName: 'contract[equ]',
        url: '?model=contract_conproject_conproject&action=equListJson',
        param: {
                'projectId': $("#projectId").val()
            },
        type: 'view',
        tableClass: 'form_in_table',
        colModel: [
            {
                display: '物料编号',
                name: 'productCode',
                tclass: 'txt'
            },
            {
                display: '物料名称',
                name: 'productName',
                tclass: 'txt'
            },
            {
                display: '物料Id',
                name: 'productId',
                type: 'hidden'
            },
            {
                display: '需求数量',
                name: 'number',
                tclass: 'txtshort'
            },
            {
                display: '已执行数量',
                name: 'executedNum',
                tclass: 'txtshort'
            },
            {
                display: '已退库数量',
                name: 'backNum',
                tclass: 'txtshort'
            },
            {
                display: '实际执行数量',
                name: 'actNum',
                process: function (v, row) {
                	return parseInt(row.number)-parseInt(row.executedNum)+parseInt(row.backNum);
                }
            }
        ]
    });
    });