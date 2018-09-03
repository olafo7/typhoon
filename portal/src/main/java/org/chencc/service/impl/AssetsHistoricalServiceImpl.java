package org.chencc.service.impl;

import com.github.pagehelper.PageHelper;
import com.github.pagehelper.PageInfo;
import org.apache.commons.lang.StringUtils;
import org.chencc.mapper.AssetsHistoricalMapper;
import org.chencc.model.AssetsHistorical;
import org.chencc.service.AssetsHistoricalService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/25
 * @Time:下午10:03
 */
@Service
@Transactional
public class AssetsHistoricalServiceImpl implements AssetsHistoricalService {
    @Autowired
    private AssetsHistoricalMapper assetsHistoricalMapper;

    @Override
    public PageInfo<AssetsHistorical> getHistoricalList(Integer start,Integer limit,String searchOperator,String searchOperation,String searchAssetsName,
                                                    String searchAssetsNum,String searchReturnStatus,String searchNumber,
                                                    String searchsturnover,String searcheturnover) {

        StringBuffer sqlBuffer = new StringBuffer("select ah.hid,case ah.operation when 1 then '添加' when 2 then '变更' when 3 then '删除'" +
                " when 4 then '归还' else '' end as operation,FROM_UNIXTIME(`turnover`,'%Y-%m-%d %h:%i:%s') as turnover," +
                " (select true_name from db_main_user a where ah.operator = a.uid) as operator,ah.cnum,ah.assets_name," +
                " concat_ws('-',ah.cnum,ah.borrow_number) as borrow_number,FROM_UNIXTIME(`borrow_date`,'%Y-%m-%d %h:%i:%s') as borrow_date," +
                " (select name from db_main_struct b where ah.accept_dpt = b.id) as accept_dpt," +
                " (select true_name from db_main_user c where ah.receiver = c.uid) as receiver," +
                " FROM_UNIXTIME(`return_date`,'%Y-%m-%d %h:%i:%s') as return_date,ah.status,ah.remark,ah.shid" +
                " from db_assets_historical as ah where 1=1");

        if(StringUtils.isNotBlank(searchOperator)){
            sqlBuffer.append(" and ah.operator = "+searchOperator+"");
        }

        if(StringUtils.isNotBlank(searchOperation)){
            sqlBuffer.append(" and ah.operation = "+searchOperation+"");
        }

        if(StringUtils.isNotBlank(searchAssetsName)){
            sqlBuffer.append(" and ah.assets_name like '%"+searchAssetsName+"%'");
        }

        if(StringUtils.isNotBlank(searchAssetsNum)){
            sqlBuffer.append(" and ah.cnum = '"+searchAssetsNum+"'");
        }

        if(StringUtils.isNotBlank(searchReturnStatus)){
            sqlBuffer.append(" and ah.status = "+searchReturnStatus+"");
        }

        if(StringUtils.isNotBlank(searchNumber)){
            sqlBuffer.append(" and ah.borrow_number like '%"+searchNumber+"%'");
        }

        if(StringUtils.isNotBlank(searchsturnover)){
            sqlBuffer.append(" and ah.turnover >= '"+searchsturnover+" 00:00:00'");
        }

        if(StringUtils.isNotBlank(searcheturnover)){
            sqlBuffer.append(" and ah.turnover <= '"+searcheturnover+" 23:59:59'");
        }

        sqlBuffer.append(" order by ah.turnover desc");

        PageHelper.startPage(start,limit);
        List<AssetsHistorical> historicalList = assetsHistoricalMapper.getHistoricalList(sqlBuffer.toString());
        PageInfo<AssetsHistorical> pageInfo = new PageInfo<AssetsHistorical>(historicalList);

        return pageInfo;
    }

    @Override
    public List<Map> getCnumList(){
        return assetsHistoricalMapper.getCnumList();
    }
}
