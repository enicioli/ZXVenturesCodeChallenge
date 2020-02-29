<?php

namespace ZXVentures\CodeChallenge\ODM\Documents;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\PersistentCollection;

/**
 * Class AbstractDocument
 * @package ZXVentures\CodeChallenge\ODM\Documents
 */
abstract class AbstractDocument
{
    /**
     * @return array
     */
    public function toArray()
    {
        $reflect = new \ReflectionClass(static::class);
        $props = $reflect->getProperties(\ReflectionProperty::IS_PRIVATE);

        $output = [];
        foreach ($props as $prop) {
            $propName = $prop->getName();
            $getter = 'get' . ucfirst($propName);

            if (!method_exists($this, $getter)) {
                continue;
            }

            $value = $this->$getter();

            if ($value instanceof AbstractDocument) {
                $value = $value->toArray();
            } elseif ($value instanceof PersistentCollection) {
                $newValue = [];
                /** @var AbstractDocument $v */
                foreach ($value as $v) {
                    $newValue[] = $v->toArray();
                }
                $value = $newValue;
            }

            $output[$propName] = $value;
        }

        return $output;
    }

    /**
     * @param DocumentManager $dm
     * @param array $data
     * @return $this
     */
    public function fromArray(DocumentManager $dm, array $data = [])
    {
        if ($dm->getHydratorFactory()) {
            $dm->getHydratorFactory()->hydrate($this, $data);
        }

        return $this;
    }
}
