<?php

namespace App\Repositories;

use App\Models\Company;
use App\Repositories\BaseRepository;
use App\Models\Media;

/**
 * Class CompanyRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method CompanyRepository findWithoutFail($id, $columns = ['*'])
 * @method CompanyRepository find($id, $columns = ['*'])
 * @method CompanyRepository first($columns = ['*'])
 */
class CompanyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
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
        return Company::class;
    }

    /**
     * Create a  Company
     *
     * @param Request $request
     *
     * @return Company
     */
    public function createCompany($request)
    {
        $input = collect($request->all());
        $company = Company::create($input->only($request->fillable('companies'))->all());
        // $image = '';
        // if (!empty($input['company_banner'])) {
        //     $image = $company->id . '.' . $request->company_banner->getClientOriginalExtension();
        //     request()->company_banner->move('web_assets/images/companies', $image);
        // }
        // $company->update(['company_banner' => $image]);
        $this->uploadFile($request, $company);

        return $company;
    }

    /**
     * Update the Company
     *
     * @param Request $request
     *
     * @return Company
     */

    public function updateCompany($id, $request)
    {

        $input = collect($request->all());
        $company = Company::findOrFail($id);
        $company->update($input->only($request->fillable('companies'))->all());

        if (isset($company->media)) {
            $storageName  = $company->media->file_name;
            $this->deleteFile('company/' . $storageName);
            // remove from the database
            $company->media->delete();
        }
        $this->uploadFile($request, $company);
        return $company;
    }

    public function uploadFile($request, $item)
    {
        $allowedfileExtension = ['pdf', 'jpg', 'png', 'jpeg'];
        if ($request->has('file')) {
            if (!empty($request->file)) {
                $extension = strtolower($request->file->getClientOriginalExtension());
                $check = in_array($extension, $allowedfileExtension);
                if ($check) {
                    $photo = $this->storeFileMultipart($request->file, 'company');
                    $input['file_name'] = $photo['name'];
                    $input['status'] = 1;
                    $input['file_type'] = \File::extension($this->getFile($photo['path']));
                    $media = Media::create([
                        'table_type' => get_class($item),
                        'table_id' => $item->id,
                        'file_name' => $input['file_name'],
                        'status' => $input['status'],
                        'default' => null,
                        'file_type' => $input['file_type']
                    ]);
                }
            }
        }
    }
}
