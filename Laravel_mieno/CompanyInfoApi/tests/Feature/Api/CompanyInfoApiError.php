<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\CompanyInfo;
use App\Models\CompanyBillInfo;

class CompanyInfoApiError extends TestCase
{
    use DatabaseTransactions;

    const VALID_STATUS_CODE = 422;
    const NOT_FOUND_STATUS_CODE = 404;
    const SERVER_ERROR_STATUS_CODE = 500;

    public function setUp():void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function CompanyInfoの新規作成エラー()//電話番号が数字じゃない場合
    {
        $params = [
            'company_name' => 'おにぎり株式会社',
            'company_kana_name' => 'おにぎりかぶしきがいしゃ',
            'company_address' => '東京都新宿区3丁目1-1-1',
            'company_call_num' => 'ぜろぜろぜろぜろ',
            'represent_name' => 'おにぎり太郎',
            'represent_kana_name' => 'おにぎりたろう'
        ];

        $res = $this->postJson(route('api.companyInfo.store'), $params);
        $res->assertStatus(self::VALID_STATUS_CODE);
        $res->assertInvalid('company_call_num');
        $companyInfos = CompanyInfo::all();

        $this->assertCount(0, $companyInfos);
    }

    /**
     * @test
     */
    public function CompanyInfoの詳細取得エラー()//存在しないidの場合
    {
        CompanyInfo::factory()->create();//既存レコードの作成

        $companyInfo = CompanyInfo::all()->first();//レコードの取得と格納

        $res = $this->getJson(route('api.companyInfo.show',$companyInfo->id + 1));//取得したレコードidをもとにリクエスト送信

        $res->assertStatus(self::NOT_FOUND_STATUS_CODE);//ステータスコードが404であることを確認
    }

    /**
     * @test
     */
    public function CompanyInfoの更新処理のエラー()//company_kana_nameが漢字の場合
    {
        CompanyInfo::factory()->create();//既存レコードの作成

        $companyInfoOld = CompanyInfo::all()->first();//レコードの取得と格納

        $params = [
            'company_name' => '味噌汁株式会社',
            'company_kana_name' => '味噌汁',
            'company_address' => '東京都杉並区和田1-1-1',
            'company_call_num' => "08008080",
            'represent_name' => 'おにぎり太郎',
            'represent_kana_name' => 'おにぎりたろう'
        ];

        $res = $this->putJson(route('api.companyInfo.update', $companyInfoOld->id), $params);

        $res->assertStatus(self::VALID_STATUS_CODE);

        $res->assertInvalid('company_kana_name');

        $companyInfoOld = $companyInfoOld->toArray();
        $companyInfoNew = CompanyInfo::all()->first()->toArray();//toArrayを使用し、Collectionインスタンスからレコード部分を配列で取り出す。

        $this->assertSame($companyInfoOld,$companyInfoNew);//リクエスト送信前と相違がないかチェック
    }
    /**
     * @test
     */
    public function CompanyInfoの削除エラー()//存在しないid
    {
        CompanyInfo::factory()->create();//既存レコードの作成
        CompanyBillInfo::factory()->create();//既存レコードの作成

        $companyInfoOld = CompanyInfo::all()->first();//レコードの取得と格納

        $res = $this->deleteJson(route('api.companyInfo.destroy', $companyInfoOld->id + 1));

        $res->assertStatus(self::SERVER_ERROR_STATUS_CODE);

        $companyInfoNew = CompanyInfo::all();
        $companyBillInfo = CompanyBillInfo::all();

        $this->assertCount(1, $companyInfoNew);
        $this->assertCount(1, $companyBillInfo);
    }
    /**
     * @test
     */
    public function CompanyInfoの請求書同時取得エラー()//存在しないidの場合
    {
        CompanyInfo::factory()->create();//既存レコードの作成
        CompanyBillInfo::factory()->create();//既存レコードの作成

        $companyInfo = CompanyInfo::all()->first();

        $res = $this->getJson(route('api.companyInfo.showRelated', $companyInfo->id + 1));

        $res->assertStatus(self::NOT_FOUND_STATUS_CODE);
    }


    //ここから請求先情報テスト
    /**
     * @test
     */
    public function CompanyBillInfoの新規作成エラー()//親レコードが存在しない場合
    {
        $params = [
            'company_id' => 1,
            'billing_place_name' => 'たまご城',
            'billing_place_kana_name' => 'たまごじょう',
            'billing_address' => '愛媛県今治市タオル町1-1-1',
            'billing_call_num' => 111111111,
            'billing_depart' => 'ゆで卵部',
            'billing_to_name' => '茹卵実',
            'billing_to_kana_name' => 'ゆでたまみ'
        ];

        $res = $this->postJson(route('api.companyBillInfo.store'), $params);
        $res->assertStatus(self::VALID_STATUS_CODE);
        $companyBillInfos = CompanyBillInfo::all();

        $this->assertCount(0, $companyBillInfos);
    }
    /**
     * @test
     */
    public function CompanyBillInfoの詳細取得エラー()//親は存在しているが、子が存在していない場合
    {
        CompanyInfo::factory()->create();//親レコード作成

        $companyInfo = CompanyInfo::all()->first();//取得

        $res = $this->getJson(route('api.companyBillInfo.show', $companyInfo->id ));//親のidでget

        $res->assertStatus(self::NOT_FOUND_STATUS_CODE);
    }
    /**
     * @test
     */
    public function CompnayBillInfoの更新処理エラー()//company_idは変更できない
    {
        CompanyInfo::factory()->create();
        CompanyBillInfo::factory()->create();

        $companyBillInfoOld = CompanyBillInfo::all()->first();

        $params = [
            'company_id' => 5,
            'billing_place_name' => 'うなぎ城',
            'billing_place_kana_name' => 'うなぎじょう',
            'billing_address' => '岡山県中山市千鳥町1-1-1',
            'billing_call_num' => 222222222,
            'billing_depart' => '焼き鳥部',
            'billing_to_name' => '焼鳥太郎',
            'billing_to_kana_name' => 'やきとりたろう'
        ];

        $res = $this->putJson(route('api.companyBillInfo.update', $companyBillInfoOld->id), $params);

        $res->assertStatus(self::VALID_STATUS_CODE);
    }
    /**
     * @test
     */
    public function CompanyBillInfoの削除処理エラー()//存在しないidの場合
    {
        CompanyInfo::factory()->create();//既存レコードの作成
        CompanyBillInfo::factory()->create();//既存レコードの作成

        $companyBillInfoOld = CompanyInfo::all()->first();//レコードの取得と格納

        $res = $this->deleteJson(route('api.companyInfo.destroy', $companyBillInfoOld->id + 1));

        $res->assertStatus(self::SERVER_ERROR_STATUS_CODE);

        $companyBillInfoNew = CompanyBillInfo::all();

        $this->assertCount(1, $companyBillInfoNew);
    }
}
