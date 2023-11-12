<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CompanyBillInfo;
use Illuminate\Support\Facades\DB;

class CompanyInfo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'company_name',
        'company_kana_name',
        'company_address',
        'company_call_num',
        'represent_name',
        'represent_kana_name'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    //companyInfoテーブルの特定のレコードに対して、紐付けたCompanyBillInfoテーブルのレコードを呼び出すメソッド
    public function billInfo()
    {
        return $this->hasOne(CompanyBillInfo::class, 'company_id');
    }

    //Eloquentの呼び出し時、尚且つ、CompanyInfo内のレコード削除時に実行される
    protected static function boot()
    {
        parent::boot();
        static::deleting(function($companyInfo){//Modelクラスの持つデータ削除時のイベントリスナー,引数は自動でオブジェクトが渡される？
            $companyInfo->billInfo()->delete();//紐づけたデータの論理削除
        });
    }

    //普通の削除では、データに生合成の取れない場合があるので、新たにdeleteDataメソッドを追加し、
    //トランザクションを利用することで、まとめてSQLを投げる。
    public static function deleteData($company_id){
        $result = DB::transaction(function () use ($company_id){
            $companyInfo = CompanyInfo::where('id', $company_id)->first();
            $companyInfo->delete();
            return true;
        });
        return $result;
    }

    public function getBillByCompanyId($id)//joinしたテーブルをコントローラーに渡すメソッド
    {
        return DB::table('company_infos')->join(
            'company_bill_infos', 'company_bill_infos.company_id', '=', 'company_infos.id'
            )->where('company_infos.id', $id)->get()->first();
    }
}
