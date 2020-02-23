<?php
namespace Modules\Core\Contracts;

interface StoringSeo
{
    /**
     * Return the entity
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getEntity();

    /**
     * Return the ALL data sent
     * @return array
     */
    public function getSubmissionData();

    /**
     * Return the name of class
     * @return string
     */
    public function getEntityClass();
}
