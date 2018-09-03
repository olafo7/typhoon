package org.chencc.service;

import org.chencc.model.MainStation;

import javax.servlet.http.HttpServletRequest;
import java.util.List;
import java.util.Map;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/17
 * @Time:下午11:39
 */
public interface MainStationService {
    List<MainStation> getStationList();

    void saveNewRecord(String name,String about);

    void saveEditRecord(int sid,String name,String about);

    void deleteRecord(int sid);

    void updateSort(int sid,int value);

    MainStation loadStationById(int sid);
}
