<?php

namespace App\Repositories;

use App\Models\RolesMap;
use App\Repositories\BaseRepository;

/**
 * Class RoleMapRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method RoleMapRepository findWithoutFail($id, $columns = ['*'])
 * @method RoleMapRepository find($id, $columns = ['*'])
 * @method RoleMapRepository first($columns = ['*'])
 */
class RoleMapRepository extends BaseRepository
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
        return RolesMap::class;
    }

    /**
     * Create a  RolesMap
     *
     * @param Request $request
     *
     * @return RolesMap
     */
    public function createRolesMap($request)
    {
        $input = collect($request->all());
        $rolesMap = RolesMap::create($input->only($request->fillable('rolesMap'))->all());
        return $rolesMap;
    }

    /**
     * Update the RolesMap
     *
     * @param Request $request
     *
     * @return RolesMap
     */

    public function updateRolesMap($id, $request)
    {

        $input = collect($request->all());
        $rolesMap = RolesMap::findOrFail($id);
        $rolesMap->update($input->only($request->fillable('rolesMap'))->all());
        return $rolesMap;
    }
}
