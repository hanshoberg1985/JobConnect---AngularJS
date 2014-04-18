<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Niall
 * Date: 26/08/13
 * Time: 16:25
 * To change this template use File | Settings | File Templates.
 */


class db_object extends DB_Sql
{
    function db_object()
    {
        global $arithonUser;

        if ($arithonUser["migration"] == "IMPORTCOMPLETE")
            $host = $GLOBALS["NEW_ORACLE_SERVER"];
        else
            $host = $GLOBALS["ORACLE_DB_HOST"];

        $this->classname = "db_object";
        $this->Database = $arithonUser["connect_string"];
        $this->User     = $arithonUser["db_username"];
        $this->Password = $arithonUser["db_password"];
    }

    function halt($msg)
    {
        print('Invalid Link - '.$msg);
        exit();
    }

}