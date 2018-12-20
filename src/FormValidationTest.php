<?php

require "./FormValidation.php";
require "../vendor/autoload.php";
require "../vendor/nette/tester/src/bootstrap.php";

use FingerMessenger\FormValidation;
use Tester\Assert;

Tester\Environment::setup();

class FormController extends FormValidation
{
    public function myFilter($data)
    {
        return substr($data, 0, 20);
    }

    public function checkData($data)
    {
        if (is_string($data)) {
            return true;
        }
        return false;
    }

    public function run()
    {
        $data = array(
            "title" => " jfilsk93808ii4okemg9fk "
        );

        $rules = array(
            "title" => array(
                "filter" => array("filterTrim", "myFilter"),
                "rules"  => array(
                    array("type" => "isNotNull", "msg" => "title is null"),
                    array("type" => "isEmail", "msg" => "title is not email"),
                    array("type" => "isUrl", "msg" => "title is not url"),
                    array("type" => "isChineseCharacter", "msg" => "title is not chinese"),
                    array("type" => "isInvalidStr", "msg" => "title is not invalid"),
                    array("type" => "isPostNum", "msg" => "title is not post num"),
                    array("type" => "isPersonalCard", "msg" => "title is not personal card"),
                    array("type" => "isIPv4", "msg" => "title is not ipv4"),
                    array("type" => "isMobile", "msg" => "title is not mobile num"),
                    array("type" => "isLength", "param" => array(6, 30), "msg" => "title length must betwen 6 and 30"),
                    array("type" => "checkData", "msg" => "title is not string"),
                )
            )
        );

        if ($this->validationRun()) {
            return true;
        }

        echo $this->getValidationError();
    }
}

Assert::same("abc", FormValidation::filterTrim(" abc    "));
Assert::false(FormValidation::isNotNull(null));
Assert::false(FormValidation::isNotNull(""));
Assert::true(FormValidation::isEmail("me@fingermessenger.com"));
Assert::true(FormValidation::isUrl("http://c.fingermessenger.com"));
Assert::true(FormValidation::isChineseCharacter("好好学习"));
Assert::true(FormValidation::isInvalidStr("[]@#$%^"));
Assert::true(FormValidation::isPostNum("812102"));
Assert::true(FormValidation::isIPv4("192.169.1.1"));
Assert::true(FormValidation::isMobile("18453789402"));
Assert::false(FormValidation::isLength("abcde", 10, 20));
Assert::false(FormValidation::isLength("abcde", 1, 3));

$form = new FormController();
Assert::noError(function() use ($form) {
    $form->run();
});