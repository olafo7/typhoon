package org.chencc.service.impl;

import org.chencc.mapper.AssetsBaseSetMapper;
import org.chencc.model.AssetsBaseDropdown;
import org.chencc.service.AssetsBaseSetService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/23
 * @Time:下午6:27
 */
@Service
@Transactional
public class AssetsBaseSetServiceImpl implements AssetsBaseSetService {

    @Autowired
    private AssetsBaseSetMapper assetsBaseSetMapper;

    @Override
    public List<AssetsBaseDropdown> getDropdownByType(Integer type) {
        return assetsBaseSetMapper.getDropdownByType(type);
    }

    @Override
    public void saveEditRecord(Integer type, String value, Integer id) {
        assetsBaseSetMapper.saveEditRecord(type,value,id);
    }

    @Override
    public void saveNewRecord(Integer type, String value) {
        assetsBaseSetMapper.saveNewRecord(type,value);
    }

    @Override
    public void deleteRecord(Integer id) {
        assetsBaseSetMapper.deleteRecord(id);
    }
}
