--�����������νṹ��ʼ��
delete from oa_stock_product_type;
insert into oa_stock_product_type  (id,proType,lft,rgt) values(-1,'���Ϸ���','1','2');

--�ִ���������Ϣϵͳ����
insert into oa_stock_syteminfo  (id) values('1')

/*--��ʼ�������--*/
delete from oa_stock_baseinfo where id='-1';
insert into oa_stock_baseinfo (id,stockCode,stockName,stockUseCode,stockType,remark) values(
-1,'OUTSTOCK','�����','CKYTJSY','CKLX-XC','�˲ֿ����ϱ�ʾ�Ѿ����ȥ��,���������ڹ�˾�ʲ�!'
)

/*ϵͳ�������� ���豸�ֿ�����*/
alter table oa_stock_syteminfo  add ( outStockId   bigint, 
outStockName   varchar(100),
outStockCode         varchar(50))  


--������ݽű�--
delete from oa_stock_product_batchno;
delete from oa_stock_product_serialno;
delete from oa_stock_instock_item;
delete from oa_stock_instock;
delete from oa_stock_outstock_item;
delete from oa_stock_stockout_extraitem;
delete from oa_stock_outstock;

delete from oa_stock_allocation_item;
delete from oa_stock_allocation;
delete from oa_stock_lock;

delete from oa_stock_inventory_info;
delete from oa_stock_baseinfo;

--�½�����--
INSERT INTO `oa_billcode` VALUES ('38', '����ƻ�', 'FILL', 'oa_stock_fillup', '000011');
INSERT INTO `oa_billcode` VALUES ('39', ' ��ӯ��ⵥ', 'YADJ', 'oa_stock_check_info', '000090');
INSERT INTO `oa_billcode` VALUES ('40', '�̿�����', 'KADJ', 'oa_stock_check_info', '000036');
--�½��˵�--
�ִ����->�̵���ҵ->��ӯ���    index1.php?model=stock_check_checkinfo&action=toList&checkType=OVERAGE
�ִ����->�̵���ҵ->�̿�����	index1.php?model=stock_check_checkinfo&action=toList&checkType=SHORTAGE

