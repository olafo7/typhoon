package org.chencc.service;

import org.chencc.model.AssetsBaseDropdown;

import java.util.List;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/23
 * @Time:下午6:26
 */
public interface AssetsBaseSetService {
    List<AssetsBaseDropdown> getDropdownByType(Integer type);

    void saveEditRecord(Integer type,String value,Integer id);

    void saveNewRecord(Integer type,String value);

    void deleteRecord(Integer id);
}
