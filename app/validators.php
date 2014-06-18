<?php
/*
* app/validators.php
*/

Validator::extend('alpha_spaces', function($attribute, $value)
{
    return preg_match('/^([-a-z0-9_-\s])+$/i', $value);
});

Validator::extend('positive_num', function($attribute, $value)
{
    return $value>=0;
});