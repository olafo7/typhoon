package org.chencc.service;

import org.chencc.model.MainStruct;

import java.util.List;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/21
 * @Time:下午3:51
 */
public interface MainStructService {
    MainStruct loadStructById(Integer id);

    void deleteRecord(Integer id);

    void saveNewRecord(String name,Integer order,Integer fid,String path,String about);

    void saveEditRecord(Integer id,String name,Integer order,Integer fid,String path,String about);

    MainStruct getTopStruct();

    List<MainStruct> getChildrenStructList(Integer deptId);
}
