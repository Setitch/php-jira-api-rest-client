<?php
namespace Jira;

/**
* Kontenerek dla refleksji
*/
class ReflectionContainer
{
    protected $ref = null;
    protected $field = null;
    public function __construct($reflection, $field)
    {
        $this->ref = $reflection;
        $this->field = $field;
    }

    public function getName() {
        return $this->ref->getName();
    }

    public function getField() {
        return $this->field;
    }
}

/**
* Mapper dla json'a
*/
class Mapper extends \JsonMapper
{
    protected function inspectProperty(\ReflectionClass $rc, $name)
    {
        $ret = parent::inspectProperty($rc, $name);
        if ($ret[0] === false) {
            if ($rc->hasMethod('__set'))
            {
                $a = $rc->getMethod('__set');
                return [true, new ReflectionContainer($a, $name), 'mixed'];
            }
        }
        return $ret;
    }

    /**
     * Set a property on a given object to a given value.
     *
     * Checks if the setter or the property are public are made before
     * calling this method.
     *
     * @param object $object   Object to set property on
     * @param object $accessor ReflectionMethod or ReflectionProperty
     * @param mixed  $value    Value of property
     *
     * @return void
     */
    protected function setProperty(
        $object, $accessor, $value
    ) {
        if ($accessor instanceof \ReflectionProperty) {
            $object->{$accessor->getName()} = $value;
        } else {
            if ($accessor instanceof ReflectionContainer)
            {
                $object->{$accessor->getName()}($accessor->getField(), $value);
            }
            else
                $object->{$accessor->getName()}($value);
        }
    }

    /**
    * Mapowanie jsona do obiektu
    *
    * @param json $json
    * @param object $object
    * @return $object
    */
    public function map($json, $object)
    {
        if ($this->bEnforceMapType && !is_object($json)) {
            throw new \InvalidArgumentException(
                'JsonMapper::map() requires first argument to be an object'
                . ', ' . gettype($json) . ' given.'
            );
        }
        if (!is_object($object)) {
            throw new \InvalidArgumentException(
                'JsonMapper::map() requires second argument to be an object'
                . ', ' . gettype($object) . ' given.'
            );
        }

        $strClassName = get_class($object);
        $rc = new \ReflectionClass($object);
        $strNs = $rc->getNamespaceName();
        $providedProperties = array();
        foreach ($json as $key => $jvalue) {
            $providedProperties[$key] = true;

            // Store the property inspection results so we don't have to do it
            // again for subsequent objects of the same type
            if (!isset($this->arInspectedClasses[$strClassName][$key])) {
                $this->arInspectedClasses[$strClassName][$key]
                    = $this->inspectProperty($rc, $key);
            }

            list($hasProperty, $accessor, $type)
                = $this->arInspectedClasses[$strClassName][$key];

            if (!$hasProperty) {
                if ($this->bExceptionOnUndefinedProperty) {
                    throw new JsonMapper_Exception(
                        'JSON property "' . $key . '" does not exist'
                        . ' in object of type ' . $strClassName
                    );
                }
                $this->log(
                    'info',
                    'Property {property} does not exist in {class}',
                    array('property' => $key, 'class' => $strClassName)
                );
                continue;
            }

            if ($accessor === null) {
                if ($this->bExceptionOnUndefinedProperty) {
                    throw new \JsonMapper_Exception(
                        'JSON property "' . $key . '" has no public setter method'
                        . ' in object of type ' . $strClassName
                    );
                }
                $this->log(
                    'info',
                    'Property {property} has no public setter method in {class}',
                    array('property' => $key, 'class' => $strClassName)
                );
                continue;
            }

            if ($this->isNullable($type)) {
                if ($jvalue === null) {
                    $this->setProperty($object, $accessor, null);
                    continue;
                }
                $type = $this->removeNullable($type);
            }

            if ($type === null || $type === 'mixed') {
                //no given type - simply set the json data
                $this->setProperty($object, $accessor, $jvalue);
                continue;
            } else if ($this->isObjectOfSameType($type, $jvalue)) {
                $this->setProperty($object, $accessor, $jvalue);
                continue;
            } else if ($this->isSimpleType($type)) {
                settype($jvalue, $type);
                $this->setProperty($object, $accessor, $jvalue);
                continue;
            }

            //FIXME: check if type exists, give detailled error message if not
            if ($type === '') {
                throw new \JsonMapper_Exception(
                    'Empty type at property "'
                    . $strClassName . '::$' . $key . '"'
                );
            }

            $array = null;
            $subtype = null;
            if (substr($type, -2) == '[]') {
                //array
                $array = array();
                $subtype = substr($type, 0, -2);
            } else if (substr($type, -1) == ']') {
                list($proptype, $subtype) = explode('[', substr($type, 0, -1));
                if (!$this->isSimpleType($proptype)) {
                    $proptype = $this->getFullNamespace($proptype, $strNs);
                }
                $array = $this->createInstance($proptype);
            } else if ($type == 'ArrayObject'
                || is_subclass_of($type, 'ArrayObject')
            ) {
                $array = $this->createInstance($type);
            }

            if ($array !== null) {
                if (!$this->isSimpleType($subtype)) {
                    $subtype = $this->getFullNamespace($subtype, $strNs);
                }
                if ($jvalue === null) {
                    $child = null;
                } else {
                    $child = $this->mapArray($jvalue, $array, $subtype);
                }
            } else if ($this->isFlatType(gettype($jvalue))) {
                //use constructor parameter if we have a class
                // but only a flat type (i.e. string, int)
                if ($jvalue === null) {
                    $child = null;
                } else {
                    $type = $this->getFullNamespace($type, $strNs);
                    $child = $this->createInstance($type, true, $jvalue);
                }
            } else {
                $type = $this->getFullNamespace($type, $strNs);
                $child = $this->createInstance($type);
                $this->map($jvalue, $child);
            }
            $this->setProperty($object, $accessor, $child);
        }

        if ($this->bExceptionOnMissingData) {
            $this->checkMissingData($providedProperties, $rc);
        }

        return $object;
    }
}