<?php

namespace App\Repositories;

use App\Models\TableMaster;
use App\Repositories\BaseRepository;

/**
 * Class TableMasterRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method TableMasterRepository findWithoutFail($id, $columns = ['*'])
 * @method TableMasterRepository find($id, $columns = ['*'])
 * @method TableMasterRepository first($columns = ['*'])
 */
class TableMasterRepository extends BaseRepository
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
        return TableMaster::class;
    }

    /**
     * Create a  TableMaster
     *
     * @param Request $request
     *
     * @return TableMaster
     */
    public function createTableMaster($request)
    {
        $input = collect($request->all());
        $tableMaster = TableMaster::create($input->only($request->fillable('tableMaster'))->all());
        return $tableMaster;
    }

    /**
     * Update the TableMaster
     *
     * @param Request $request
     *
     * @return TableMaster
     */

    public function updateTableMaster($id, $request)
    {

        $input = collect($request->all());
        $tableMaster = TableMaster::findOrFail($id);
        $tableMaster->update($input->only($request->fillable('tableMaster'))->all());
        return $tableMaster;
    }
}
