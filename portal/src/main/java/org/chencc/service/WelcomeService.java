package org.chencc.service;

import org.chencc.common.MenuWrapper;
import org.chencc.model.SystemMenu;
import org.chencc.model.SystemPagesize;

import java.util.List;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/24
 * @Time:下午10:14
 */
public interface WelcomeService {
    List<MenuWrapper> getSystemMenuList();

    List<SystemPagesize> getPageSize();
}
