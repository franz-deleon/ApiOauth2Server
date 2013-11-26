<?php
namespace ApiOauth2Server\Model\Type;

class EnumYesNoType extends EnumType
{
    protected $name = 'enumyesno';
    protected $values = array('yes', 'no');
}
