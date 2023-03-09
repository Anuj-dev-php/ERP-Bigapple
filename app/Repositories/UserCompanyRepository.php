<?php

namespace App\Repositories;

use App\Models\UserCompany;
use App\Repositories\BaseRepository;
use App\Models\Media;
/**
 * Class UserCompanyRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method UserCompanyRepository findWithoutFail($id, $columns = ['*'])
 * @method UserCompanyRepository find($id, $columns = ['*'])
 * @method UserCompanyRepository first($columns = ['*'])
 */
class UserCompanyRepository extends BaseRepository
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
        return UserCompany::class;
    }

    /**
     * Create a  UserCompany
     *
     * @param Request $request
     *
     * @return UserCompany
     */
    public function createUserCompany($request)
    {
        $input = collect($request->all());
        $userCompany = UserCompany::create($input->only($request->fillable('userCompanies'))->all());
        // $image = '';
        // if (!empty($input['userCompany_banner'])) {
        //     $image = $userCompany->id . '.' . $request->userCompany_banner->getClientOriginalExtension();
        //     request()->userCompany_banner->move('web_assets/images/userCompanies', $image);
        // }
        // $userCompany->update(['userCompany_banner' => $image]);
        $this->uploadFile($request,$userCompany);

        return $userCompany;
    }

    /**
     * Update the UserCompany
     *
     * @param Request $request
     *
     * @return UserCompany
     */

    public function updateUserCompany($id, $request)
    {

        $input = collect($request->all());
        $userCompany = UserCompany::findOrFail($id);
        $userCompany->update($input->only($request->fillable('userCompanies'))->all());

        if (isset($userCompany->media)) {
            $storageName  = $userCompany->media->file_name;
            $this->deleteFile('userCompany/' . $storageName);
            // remove from the database
            $userCompany->media->delete();
        }
        $this->uploadFile($request,$userCompany);
        return $userCompany;
    }

    public function uploadFile($request, $item)
    {
        $allowedfileExtension = ['pdf', 'jpg', 'png', 'jpeg'];
        if ($request->has('file')) {
            if (!empty($request->file)) {
                    $extension = strtolower($request->file->getClientOriginalExtension());
                    $check = in_array($extension, $allowedfileExtension);
                    if ($check) {
                        $photo = $this->storeFileMultipart($request->file, 'userCompany');
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
