var show_page = function () {
    $("#goodsbaseinfoGrid").yxgrid("reload");
};
$(function () {
    $("#goodsbaseinfoGrid").yxgrid({
        title: '��Ʒ�汾��ʷ',
        data : {
            'collection' : [
                { 'id' : 1 ,'version' : 'v0.0.1', 'createName' : 'admin', 'createTime' : '2014-09-12 08:12:10', 'remark' : '���԰汾����'}
            ],
            'page' : 1,
            'totalSize' : 1
        },
        showcheckbox: false,
        isAddAction: false,
        isEditAction: false,
        isViewAction: false,
        isDelAction: false,
        // ����Ϣ
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'version',
                display: '�汾��',
                width: 80,
                sortable: true
            },
            {
                name: 'createName',
                display: '������',
                sortable: true,
                width: 80
            },
            {
                name: 'createTime',
                display: '��������',
                sortable: true,
                width: 140
            },
            {
                name: 'remark',
                display: '��ע',
                sortable: true,
                width: 300
            }
        ],
        menusEx: [
            {
                name: 'view',
                text: "�鿴�������",
                icon: 'view',
                action: function (row, rows, grid) {
                    alert('�鿴�������');
                }
            }
        ],
        searchitems: [
            {
                display: "�汾��",
                name: 'version'
            }
        ]
    });
});