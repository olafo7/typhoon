package org.chencc.service;

import org.chencc.common.MenuWrapperForSetting;
import org.chencc.model.SystemConfig;
import org.chencc.model.SystemOutlink;

import java.util.List;
import java.util.Map;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/22
 * @Time:下午3:30
 */
public interface SystemSettingService {
    SystemConfig getSystemConfig();

    List<MenuWrapperForSetting> getSystemMenuList();

    List<SystemOutlink> getOutlinkList();

    List<Map> getUserListByType(String type);
}
