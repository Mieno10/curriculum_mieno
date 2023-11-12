<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CompanyInfo;
use Illuminate\Http\Request;
use App\Http\Requests\CompanyInfoCreateRequest;
use App\Http\Requests\CompanyInfoUpdateRequest;

class CompanyInfoController extends Controller
{
    public function __construct(
        private CompanyInfo $companyInfo
    ){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyInfoCreateRequest $request)
    {
        $this->companyInfo->fill($request->all())->save();

        return ['massage' => 'OK'];
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $companyInfo = $this->companyInfo->FindOrFail($id);
        return $companyInfo;
    }

    public function showRelated(int $id)
    {
        $results = $this->companyInfo->getBillByCompanyId($id);
        if(!$results){
            abort(404);
        }else{
            return $results;
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompanyInfo $companyInfo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyInfoUpdateRequest $request, int $id)
    {
        $this->companyInfo->FindOrFail($id)->update($request->all());
        return ['massage' => 'OK'];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->companyInfo->deleteData($id);
        return ['massage' => 'OK'];
    }
}
