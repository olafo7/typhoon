package org.chencc.service;

import org.apache.commons.lang.StringUtils;
import org.chencc.common.AssetsSortWrapper;
import org.chencc.model.AssetsSort;

import java.util.List;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/25
 * @Time:下午1:12
 */
public interface AssetsSortSetService {
    List<AssetsSortWrapper> getAssetsSortList(String type);

    AssetsSort getAssetsSortById(Integer id);

    void deleteRecord(Integer id);

    void saveEditRecord(String name,Integer fid,Integer order,String about,Integer id);

    void saveNewRecord(String name,Integer fid,Integer order,String about);

    String getNameById(Integer id);
}
