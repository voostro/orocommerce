<?php

namespace Oro\Bundle\WebCatalogBundle\ContentVariantType;

use Oro\Bundle\WebCatalogBundle\Exception\InvalidArgumentException;
use Oro\Component\WebCatalog\ContentVariantTypeInterface;

class ContentVariantTypeRegistry
{
    /**
     * @var ContentVariantTypeInterface[]
     */
    private $contentVariantTypes = [];

    /**
     * @param ContentVariantTypeInterface $contentVariantType
     */
    public function addContentVariantType(ContentVariantTypeInterface $contentVariantType)
    {
        $this->contentVariantTypes[$contentVariantType->getName()] = $contentVariantType;
    }

    /**
     * @param string $contentVariantTypeName
     * @return ContentVariantTypeInterface
     */
    public function getContentVariantType($contentVariantTypeName)
    {
        if (!array_key_exists($contentVariantTypeName, $this->contentVariantTypes)) {
            throw new InvalidArgumentException(
                sprintf('Content variant type "%s" is not known.', $contentVariantTypeName)
            );
        }

        return $this->contentVariantTypes[$contentVariantTypeName];
    }
    
    /**
     * @return ContentVariantTypeInterface[]
     */
    public function getContentVariantTypes()
    {
        return $this->contentVariantTypes;
    }

    /**
     * @return ContentVariantTypeInterface[]
     */
    public function getAllowedContentVariantTypes()
    {
        $types = [];
        foreach ($this->contentVariantTypes as $name => $type) {
            if ($type->isAllowed()) {
                $types[$name] = $type;
            }
        }

        return $types;
    }
}