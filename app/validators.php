<?php
/*
* app/validators.php
*/

/*
Validator::extend('alpha_spaces', function($attribute, $value)
{
    return preg_match("/^([-a-z0-9_-\sñÑ])+$/i", $value);
});
*/
Validator::extend('positive_num', function($attribute, $value)
{
    return $value>=0;
});

Validator::extend('price', function($attribute, $value)
{
    return preg_match("/^[0-9,.]+$/", $value);
});

