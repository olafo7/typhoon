package org.chencc.utils;

/**
 * @Description 验证密码、修改密码工具类
 * @author:chencc
 * @Date:2018/7/27
 * @Time:下午10:10
 */
public class PasswordUtil {
    /**
     * 验证密码的正确性
     * @param salt 数据库中保存的用户salt值
     * @param encPassword 数据库中保存的加密的密码
     * @param password 用户输入的密码
     * @return
     */
    public static boolean checkPassword(String salt,String encPassword,String password){
        PasswordEncoder encoder = new PasswordEncoder(salt);

        boolean passwordValid = encoder.isPasswordValid(encPassword, password);

        return passwordValid;
    }

    /**
     * 根据密码值返回加密后的密码及盐值,修改密码、重置密码、创建用户等业务时使用,
     * 之后可将返回的加密密码及salt存入数据库中
     * @param password 用户输入的密码（明码）
     * @return 字符数组第一个是加密后password，第二个是salt值
     */
    public static String[] getEncPasswordAndSalt(String password){
        String salt = PasswordEncoder.createSalt(12);//获取12位盐值
        PasswordEncoder encoder = new PasswordEncoder(salt);
        String encPassword = encoder.encode(password);//加密密码

        String [] args = new String[2];
        args[0] = encPassword;
        args[1] = salt;

        return args;
    }


    public static void main(String[] args){
        String[] arg = PasswordUtil.getEncPasswordAndSalt("123456");
        System.out.println("加密后：：：密码："+arg[0]+"：：：：盐值："+arg[1]);

    }
}
