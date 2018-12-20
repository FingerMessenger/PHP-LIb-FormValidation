<?php

namespace FingerMessenger\Lib;

class FormValidation
{

    /**
     * 
     *          array(
     *              'field' => array(
     *                  'filter' => array('trim', 'filterTrim'), 
     *                  'rules' => array(
     *                      array('type' => 'isNull', 'msg' => ''),
     *                      array('type' => 'isLength', 'param' => array(2, 3), 'msg' => ''),
     *                      array('type' => 'ruleIdCard', 'msg' => '')
     *                  )
     *              )
     *          )
     */

    private $formValidationError = "";

    public final function getValidationError()
    {
        return $this->formValidationError;
    }

    public final function validationRun(&$data = array(), $rules = array())
    {
        foreach ($data as $field => &$value) {
            if (isset($rules[$field])) {
                    if (isset($rules[$field]['filter']) && $rules[$field]['filter'] && is_array($rules[$field]['filter'])) {
                        foreach ($rules[$field]['filter'] as $filter) {
                            $value = $this->$filter($data[$field]);
                        }
                    }
        
                    if (isset($rules[$field]['rules']) && $rules[$field]['rules']) {
                        foreach ($rules[$field]['rules'] as $rule) {
                            $param = array($value);
                            if (!empty($rule['param'])) {
                                $rule['param'] = (array) $rule['param'];
                                $param = array_merger($param, $rule['param']);
                            }
                            
                            if (!call_user_func_array(array($this, $rule['type']), $param)) {
                                $this->formValidationError = $rule['msg'];
                                return false;
                            }
                        }
                    }
            }
        }
        return true;
    }

    public final static function filterTrim($data)
    {
        return trim($data);
    }
	
	public final static function isNotNull($var)
	{
		if (is_null($var) || $var == ''){
			return false;
		}
		
		return true;
	}
	
    public final static function isEmail($string) {

        if (!$string) {
            return false;
        }

        return preg_match('#[a-z0-9&\-_.]+@[\w\-_]+([\w\-.]+)?\.[\w\-]+#is', $string) ? true : false;
    }

    public final static function isUrl($string) {

        if (!$string) {
            return false;
        }

        return preg_match('#(http|https|ftp|ftps)://([\w-]+\.)+[\w-]+(/[\w-./?%&=]*)?#i', $string) ? true : false;
    }

    public final static function isChineseCharacter($string) {

        if (!$string) {
            return false;
        }

        return preg_match('~[\x{4e00}-\x{9fa5}]+~u', $string) ? true : false;
    }

    public final static function isInvalidStr($string) {

        if (!$string) {
            return false;
        }

        return preg_match('#[!\#$%^&*(){}~`"\';:?+=<>/\[\]]+#', $string) ? true : false;
    }

    public final static function isPostNum($num) {

        if (!$num) {
            return false;
        }

        return preg_match('#[0-9]{6}#', $num) ? true : false;
    }

    public final static function isPersonalCard($num) {

        if (!$num) {
            return false;
        }

        return preg_match('#^[\d]{15}$|^[\d]{18}$#', $num) ? true : false;
    }

    public final static function isIPv4($string) {

        if (!$string) {
            return false;
        }

        if (!preg_match('#^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$#', $string)) {
            return false;
        }

        $ipArray = explode('.', $string);

        return ($ipArray[0]<=255 && $ipArray[1]<=255 && $ipArray[2]<=255 && $ipArray[3]<=255) ? true : false;
    }

    public final static function isMobile($num) {

        if (!$num) {
            return false;
        }

        return preg_match('#^13[\d]{9}$|14^[0-9]\d{8}|^15[0-9]\d{8}$|^17[0-9]\d{8}$|^18[0-9]\d{8}$#', $num) ? true : false;
    }

    public final static function isLength($string = null, $min = 0, $max = 255)
    {
        if (is_null($string)) {
            return false;
        }
        $length = (strlen($string) + mb_strlen($string, 'UTF8')) / 2;

        return (($length >= (int)$min) && ($length <= (int)$max)) ? true : false;
    }
}