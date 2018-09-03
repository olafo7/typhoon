package org.chencc.service.impl;

import org.chencc.mapper.MainStructMapper;
import org.chencc.model.MainStruct;
import org.chencc.service.MainStructService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/21
 * @Time:下午3:52
 */

@Service
@Transactional
public class MainStructServiceImpl implements MainStructService {

    @Autowired
    private MainStructMapper mainStructMapper;

    @Override
    public MainStruct loadStructById(Integer id) {
        return mainStructMapper.loadStructById(id);
    }

    @Override
    public void deleteRecord(Integer id) {
        mainStructMapper.deleteRecord(id);
    }

    @Override
    public void saveNewRecord(String name, Integer order, Integer fid, String path, String about) {
        mainStructMapper.saveNewRecord(name, order, fid, path, about);
    }

    @Override
    public void saveEditRecord(Integer id, String name, Integer order, Integer fid, String path, String about) {
        mainStructMapper.saveEditRecord(id, name, order, fid, path, about);
    }

    @Override
    public MainStruct getTopStruct() {
        return mainStructMapper.getTopStruct();
    }

    @Override
    public List<MainStruct> getChildrenStructList(Integer deptId){
        return mainStructMapper.getChildrenStructList(deptId);
    }
}
