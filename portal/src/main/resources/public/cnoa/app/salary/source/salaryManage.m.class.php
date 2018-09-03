<?php
//decode by qq2859470

class salaryManageM
{

    protected $table_basic_insure = "salary_basic_insure";
    protected $table_basic_set = "salary_basic_set";
    protected $table_basic_set_ls = "salary_basic_set_ls";
    protected $table_basic_cardinal = "salary_basic_cardinal";
    protected $table_basic_cardinal_ls = "salary_basic_cardinal_ls";
    protected $table_basic_weal = "salary_basic_weal";
    protected $table_basic_reserve = "salary_basic_reserve";
    protected $table_basic_key = "salary_basic_key";
    protected $table_basic_basicSalary = "salary_basic_basicsalary";
    protected $table_salary_calculate = "salary_calculate_gs";
    protected $table_salary_calculate_ls = "salary_calculate_gs_ls";
    protected $table_manage_entering = "salary_manage_entering";
    protected $table_manage_person_record = "salary_manage_person_record";
    protected $table_manage_approve = "salary_manage_approve";
    protected $table_main_user = "main_user";
    protected $table_main_struct = "main_struct";

    const SALARY_BASIC_DEPT = 1;
    const SALARY_BASIC_USER = 2;

    public function actionMysalary( )
    {
        app::loadappform( "salary", "manageMysalaryM" )->run( );
    }

}

?>
