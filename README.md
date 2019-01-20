## Install
	
	composer require fingermessenger/formvalidation

## Usage

```php
use FingerMessenger\FormValidation;
```

## Example

```php
class FormController
{
    use FormValidation;

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

        if (!$this->validationRun()) {
            echo $this->getValidationError();
        }
    }
}
```