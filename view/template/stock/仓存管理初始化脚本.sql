--物料类型树形结构初始化
delete from oa_stock_product_type;
insert into oa_stock_product_type  (id,proType,lft,rgt) values(-1,'物料分类','1','2');

--仓存管理基本信息系统设置
insert into oa_stock_syteminfo  (id) values('1')

/*--初始化借出仓--*/
delete from oa_stock_baseinfo where id='-1';
insert into oa_stock_baseinfo (id,stockCode,stockName,stockUseCode,stockType,remark) values(
-1,'OUTSTOCK','借出仓','CKYTJSY','CKLX-XC','此仓库物料表示已经借出去的,但还是属于公司资产!'
)

/*系统设置增加 旧设备仓库设置*/
alter table oa_stock_syteminfo  add ( outStockId   bigint, 
outStockName   varchar(100),
outStockCode         varchar(50))  


--清空数据脚本--
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

--新建编码--
INSERT INTO `oa_billcode` VALUES ('38', '补库计划', 'FILL', 'oa_stock_fillup', '000011');
INSERT INTO `oa_billcode` VALUES ('39', ' 盘盈入库单', 'YADJ', 'oa_stock_check_info', '000090');
INSERT INTO `oa_billcode` VALUES ('40', '盘亏毁损单', 'KADJ', 'oa_stock_check_info', '000036');
--新建菜单--
仓存管理->盘点作业->盘盈入库    index1.php?model=stock_check_checkinfo&action=toList&checkType=OVERAGE
仓存管理->盘点作业->盘亏毁损	index1.php?model=stock_check_checkinfo&action=toList&checkType=SHORTAGE

