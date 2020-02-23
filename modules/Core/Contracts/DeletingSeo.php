<?php
namespace Modules\Core\Contracts;

interface DeletingSeo {
    /**
     * Return the entity
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getEntityId();

    public function getClassName();
}
