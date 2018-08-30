/**
 * �����ͻ�������
 */
(function ($) {
    $.woo.yxcombogrid.subclass('woo.yxcombogrid_office', {
        options: {
            hiddenId: 'officeId',
            nameCol: 'officeName',
            gridOptions: {
                showcheckbox: false,
                model: 'engineering_officeinfo_officeinfo',
                action: 'pageJsonUsing',
                // ����Ϣ
                colModel: [{
                    display: '��Ʒ�߱���',
                    name: 'productLine',
                    hide: true
                }, {
                    display: 'ִ�в���',
                    name: 'productLineName',
                    width: '80'
                }, {
                    display: '����',
                    name: 'officeName',
                    width: '80'
                }, {
                    display: '�����ܼ�',
                    name: 'mainManager'
                }, {
                    display: '������',
                    name: 'managerName',
                    width: '120'
                }, {
                    display: '���η�Χ',
                    name: 'rangeName',
                    width: '180'
                }, {
                    display: '��ע',
                    name: 'TypeOne',
                    hide: true
                }],
                // ��������
                searchitems: [{
                    display: '��������',
                    name: 'officeName'
                }],
                // Ĭ�������ֶ���
                sortname: "id",
                // Ĭ������˳��
                sortorder: "ASC"
            }
        }
    });
})(jQuery);