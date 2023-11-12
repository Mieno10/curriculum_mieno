<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CompanyBillInfo;
use Illuminate\Http\Request;
use App\Http\Requests\CompanyBillInfoCreateRequest;
use App\Http\Requests\CompanyBillInfoUpdateRequest;


class CompanyBillInfoController extends Controller
{
    public function __construct(
        private CompanyBillInfo $companyBillInfo
    )
    {}
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
    public function store(CompanyBillInfoCreateRequest $request)
    {
        $this->companyBillInfo->fill($request->all())->save();
        return ['massage' => 'OK'];
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $companyBillInfo = $this->companyBillInfo->FindOrFail($id);
        return $companyBillInfo;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompanyBillInfo $companyBillInfo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyBillInfoUpdateRequest $request, int $id)
    {
        if($request->has('company_id')){//$requestの中にcompany_idが含まれていた場合、422を返す。
            abort(422,"company_id cannnot be updated");
        }
        $this->companyBillInfo->FindOrFail($id)->update($request->all());
        return ['massage' => 'OK'];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->companyBillInfo->FindOrFail($id)->delete();
        return ['massage' => 'OK'];
    }
}
