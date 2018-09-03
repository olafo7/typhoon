package org.chencc.service.impl;

import org.chencc.mapper.MainStationMapper;
import org.chencc.model.MainStation;
import org.chencc.service.MainStationService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import javax.servlet.http.HttpServletRequest;
import java.util.List;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/17
 * @Time:下午11:40
 */

@Service
@Transactional
public class MainStationServiceImpl implements MainStationService {

    @Autowired
    private MainStationMapper mainStationMapper;

    @Override
    public List<MainStation> getStationList() {
        return mainStationMapper.getStationList();
    }

    @Override
    public void saveNewRecord(String name, String about) {
        mainStationMapper.saveNewRecord(name,about);
    }

    @Override
    public void saveEditRecord(int sid,String name,String about){
        mainStationMapper.saveEditRecord(sid,name,about);
    }


    @Override
    public void deleteRecord(int sid){
        mainStationMapper.deleteRecord(sid);
    }

    @Override
    public void updateSort(int sid,int value){
        mainStationMapper.updateSort(sid,value);
    }

    @Override
    public MainStation loadStationById(int sid){
        return mainStationMapper.loadStationById(sid);
    }
}
