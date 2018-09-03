package org.chencc.config;

import java.lang.annotation.*;

/**
 * @Description 在Controller的方法参数中使用此注解，该方法在映射时会注入当前登录的User对象
 * @author:chencc
 * @Date:2018/7/29
 * @Time:上午10:37
 */
@Target({ ElementType.PARAMETER })
@Retention(RetentionPolicy.RUNTIME)
public @interface CurrentUser {
}
