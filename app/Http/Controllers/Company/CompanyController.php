<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Repositories\CompanyRepository;
use App\Repositories\UserCompanyRepository;
use App\Http\Resources\Company\CompanyCollection;
use Illuminate\Support\Facades\Auth;

class CompanyController extends AppBaseController
{
    protected $companyRepository;
    protected $userCompanyRepository;

    public function __construct(CompanyRepository $companies, UserCompanyRepository $userCompanies)
    {
        $this->companyRepository = $companies;
        $this->userCompanyRepository = $userCompanies;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $userCompany = $this->userCompanyRepository->where('uid', \Auth::user()->id)->pluck('compid')->toArray();
        // $userCompany = $this->userCompanyRepository->where('uid', \Auth::user()->id)->where('roleid', \Auth::user()->role_id)->pluck('compid')->toArray();
        $company = $this->companyRepository->whereIn('id', $userCompany)->get();
        $datas = CompanyCollection::collection($company);
        return view('company.company', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
