<?php
class SqlHelper
{
    public static function arrToCase($caseParam, $values, $alias)
    {
        $case = "case $alias.$caseParam ";
        foreach ($values as $key => $val)
        {
            $case .= "when $key then ".$val.' ';
        }
        $case .= 'end';

        return $case;
    }
}