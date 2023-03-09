<?php

namespace App\Repositories;

use App\Models\Role;
use App\Repositories\BaseRepository;

/**
 * Class RoleRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method RoleRepository findWithoutFail($id, $columns = ['*'])
 * @method RoleRepository find($id, $columns = ['*'])
 * @method RoleRepository first($columns = ['*'])
 */
class RoleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Role::class;
    }

    /**
     * Create a  Role
     *
     * @param Request $request
     *
     * @return Role
     */
    public function createRole($request)
    {
        $input = collect($request->all());
        $role = Role::create($input->only($request->fillable('role'))->all());
        return $role;
    }

    /**
     * Update the Role
     *
     * @param Request $request
     *
     * @return Role
     */

    public function updateRole($id, $request)
    {

        $input = collect($request->all());
        $role = Role::findOrFail($id);
        $role->update($input->only($request->fillable('role'))->all());
        return $role;
    }
}
