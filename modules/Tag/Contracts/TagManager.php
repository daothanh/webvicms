<?php

namespace Modules\Tag\Contracts;

use Modules\Tag\Repositories\TaggableInterface;

interface TagManager
{
    /**
     * Returns all the registered namespaces.
     * @return array
     */
    public function getNamespaces();

    /**
     * Registers an entity namespace.
     * @param TaggableInterface $entity
     * @return void
     */
    public function registerNamespace(TaggableInterface $entity);
}
