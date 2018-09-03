package org.chencc.service;

import com.github.pagehelper.PageInfo;
import org.chencc.model.AssetsHistorical;

import java.util.List;
import java.util.Map;


/**
 * @Description
 * @author:chencc
 * @Date:2018/7/25
 * @Time:下午10:03
 */
public interface AssetsHistoricalService {
    PageInfo<AssetsHistorical> getHistoricalList(Integer start, Integer limit, String searchOperator, String searchOperation, String searchAssetsName,
                                                 String searchAssetsNum, String searchReturnStatus, String searchNumber,
                                                 String searchsturnover, String searcheturnover);

    List<Map> getCnumList();
}
