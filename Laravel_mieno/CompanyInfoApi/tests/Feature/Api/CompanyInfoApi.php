<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\CompanyInfo;
use App\Models\CompanyBillInfo;
use Tests\TestCase;

class CompanyInfoApi extends TestCase
{
    /**
     * A basic feature test example.
     */
    use DatabaseTransactions;

    public function setUp():void
    {
        parent::setUp();
    }
    /**
     * @test
     */
    public function CompanyInfoの新規作成()
    {
        $params = [
            'company_name' => 'おにぎり株式会社',
            'company_kana_name' => 'おにぎりかぶしきがいしゃ',
            'company_address' => '東京都新宿区3丁目1-1-1',
            'company_call_num' => 999999999,
            'represent_name' => 'おにぎり太郎',
            'represent_kana_name' => 'おにぎりたろう'
        ];

        $res = $this->postJson(route('api.companyInfo.store'), $params);
        $res->assertOk();
        $companyInfos = CompanyInfo::all();

        $this->assertCount(1, $companyInfos);

        $companyInfo = $companyInfos->first();

        $this->assertSame($params['company_name'], $companyInfo->company_name);
        $this->assertSame($params['company_kana_name'], $companyInfo->company_kana_name);
        $this->assertSame($params['company_address'], $companyInfo->company_address);
        $this->assertSame($params['company_call_num'], $companyInfo->company_call_num);
        $this->assertSame($params['represent_name'], $companyInfo->represent_name);
        $this->assertSame($params['represent_kana_name'], $companyInfo->represent_kana_name);
    }

    /**
     * @test
     */
    public function CompanyInfoの詳細取得()
    {
        CompanyInfo::factory()->create();//既存レコードの作成

        $companyInfo = CompanyInfo::all()->first();//レコードの取得と格納

        $res = $this->getJson(route('api.companyInfo.show',$companyInfo->id));//取得したレコードidをもとにリクエスト送信

        $res->assertOk();//ステータスコードの確認

        $res->assertJsonPath('company_name',$companyInfo->company_name);
        $res->assertJsonPath('company_kana_name',$companyInfo->company_kana_name);
        $res->assertJsonPath('company_address',$companyInfo->company_address);
        $res->assertJsonPath('company_call_num',$companyInfo->company_call_num);
        $res->assertJsonPath('represent_name',$companyInfo->represent_name);
        $res->assertJsonPath('represent_kana_name',$companyInfo->represent_kana_name);
    }

    /**
     * @test
     */
    public function CompanyInfoの更新処理()
    {
        CompanyInfo::factory()->create();

        $params = [
            'company_name' => '味噌汁株式会社',
            'company_kana_name' => 'みそしるかぶしきがいしゃ',
            'company_address' => '宮崎県延岡市桜丘町1-1-1',
            'company_call_num' => 888888888,
            'represent_name' => '味噌汁太郎',
            'represent_kana_name' => 'みそしるたろう'
        ];

        $companyInfoOld = CompanyInfo::all()->first();//レコードの取得と格納

        $res = $this->putJson(route('api.companyInfo.update', $companyInfoOld->id), $params);

        $res->assertOk();

        $companyInfoNew = CompanyInfo::all()->first();

        $this->assertSame($params['company_name'], $companyInfoNew->company_name);
        $this->assertSame($params['company_kana_name'], $companyInfoNew->company_kana_name);
        $this->assertSame($params['company_address'], $companyInfoNew->company_address);
        $this->assertSame($params['company_call_num'], $companyInfoNew->company_call_num);
        $this->assertSame($params['represent_name'], $companyInfoNew->represent_name);
        $this->assertSame($params['represent_kana_name'], $companyInfoNew->represent_kana_name);
    }

    /**
     * @test
     */
    //CompanyInfoの削除に伴い、CompanyBillInfoも削除
    public function CompanyInfoの削除処理()
    {
        CompanyInfo::factory()->create();
        CompanyBillInfo::factory()->create();

        $companyBillInfosOld = CompanyBillInfo::all();
        $companyInfosOld = CompanyInfo::all();
        $companyInfoOld = $companyInfosOld->first();

        $this->assertCount(1, $companyInfosOld);
        $this->assertCount(1, $companyBillInfosOld);

        $res = $this->deleteJson(route('api.companyInfo.destroy', $companyInfoOld->id));

        $res->assertOk();

        $companyInfosNew = CompanyInfo::all();
        $companyBillInfosNew = CompanyBillInfo::all();

        $this->assertCount(0, $companyInfosNew);
        $this->assertCount(0, $companyBillInfosNew);
    }

    /**
     * @test
     */
    public function CompanyInfoの請求書同時取得()
    {
        CompanyInfo::factory()->create();
        CompanyBillInfo::factory()->create();

        $companyInfo = CompanyInfo::all()->first();
        $companyBillInfo = CompanyBillInfo::all()->first();

        $res = $this->getJson(route('api.companyInfo.showRelated', $companyInfo->id));

        $res->assertOk();

        $res->assertJsonPath('company_name',$companyInfo->company_name);
        $res->assertJsonPath('company_kana_name',$companyInfo->company_kana_name);
        $res->assertJsonPath('company_address',$companyInfo->company_address);
        $res->assertJsonPath('company_call_num',$companyInfo->company_call_num);
        $res->assertJsonPath('represent_name',$companyInfo->represent_name);
        $res->assertJsonPath('represent_kana_name',$companyInfo->represent_kana_name);

        $res->assertJsonPath('company_id',$companyBillInfo->company_id);
        $res->assertJsonPath('billing_place_name',$companyBillInfo->billing_place_name);
        $res->assertJsonPath('billing_place_kana_name',$companyBillInfo->billing_place_kana_name);
        $res->assertJsonPath('billing_address',$companyBillInfo->billing_address);
        $res->assertJsonPath('billing_call_num',$companyBillInfo->billing_call_num);
        $res->assertJsonPath('billing_depart',$companyBillInfo->billing_depart);
        $res->assertJsonPath('billing_to_name',$companyBillInfo->billing_to_name);
        $res->assertJsonPath('billing_to_kana_name',$companyBillInfo->billing_to_kana_name);
    }



    //ここから請求先のテスト
    /**
     * @test
     */
    public function CompanyBillInfoの新規作成()
    {
        CompanyInfo::factory()->create();

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
        $res->assertOk();
        $companyBillInfos = CompanyBillInfo::all();

        $this->assertCount(1, $companyBillInfos);

        $companyBillInfo = $companyBillInfos->first();

        $this->assertSame($params['company_id'], $companyBillInfo->company_id);
        $this->assertSame($params['billing_place_name'], $companyBillInfo->billing_place_name);
        $this->assertSame($params['billing_place_kana_name'], $companyBillInfo->billing_place_kana_name);
        $this->assertSame($params['billing_address'], $companyBillInfo->billing_address);
        $this->assertSame($params['billing_call_num'], $companyBillInfo->billing_call_num);
        $this->assertSame($params['billing_depart'], $companyBillInfo->billing_depart);
        $this->assertSame($params['billing_to_name'], $companyBillInfo->billing_to_name);
        $this->assertSame($params['billing_to_kana_name'], $companyBillInfo->billing_to_kana_name);
    }

    /**
     * @test
     */
    public function CompanyBillInfoの詳細取得()
    {
        CompanyInfo::factory()->create();//外部キー制約のため親レコード追加
        CompanyBillInfo::factory()->create();//既存レコードの作成

        $companyBillInfo = CompanyBillInfo::all()->first();//レコードの取得と格納

        $res = $this->getJson(route('api.companyBillInfo.show',$companyBillInfo->id));//取得したレコードidをもとにリクエスト送信

        $res->assertOk();//ステータスコードの確認

        $res->assertJsonPath('company_id',$companyBillInfo->company_id);
        $res->assertJsonPath('billing_place_name',$companyBillInfo->billing_place_name);
        $res->assertJsonPath('billing_place_kana_name',$companyBillInfo->billing_place_kana_name);
        $res->assertJsonPath('billing_address',$companyBillInfo->billing_address);
        $res->assertJsonPath('billing_call_num',$companyBillInfo->billing_call_num);
        $res->assertJsonPath('billing_depart',$companyBillInfo->billing_depart);
        $res->assertJsonPath('billing_to_name',$companyBillInfo->billing_to_name);
        $res->assertJsonPath('billing_to_kana_name',$companyBillInfo->billing_to_kana_name);
    }

    /**
     * @test
     */
    public function CompanyBillInfoの更新処理()
    {
        CompanyInfo::factory()->create();
        CompanyBillInfo::factory()->create();

        $params = [
            'billing_place_name' => 'うなぎ城',
            'billing_place_kana_name' => 'うなぎじょう',
            'billing_address' => '岡山県中山市千鳥町1-1-1',
            'billing_call_num' => 222222222,
            'billing_depart' => '焼き鳥部',
            'billing_to_name' => '焼鳥太郎',
            'billing_to_kana_name' => 'やきとりたろう'
        ];

        $companyBillInfoOld = companyBillInfo::all()->first();//レコードの取得と格納

        $res = $this->putJson(route('api.companyBillInfo.update', $companyBillInfoOld->id), $params);

        $res->assertOk();

        $companyBillInfoNew = companyBillInfo::all()->first();

        $this->assertSame($params['billing_place_name'], $companyBillInfoNew->billing_place_name);
        $this->assertSame($params['billing_place_kana_name'], $companyBillInfoNew->billing_place_kana_name);
        $this->assertSame($params['billing_address'], $companyBillInfoNew->billing_address);
        $this->assertSame($params['billing_call_num'], $companyBillInfoNew->billing_call_num);
        $this->assertSame($params['billing_depart'], $companyBillInfoNew->billing_depart);
        $this->assertSame($params['billing_to_name'], $companyBillInfoNew->billing_to_name);
        $this->assertSame($params['billing_to_kana_name'], $companyBillInfoNew->billing_to_kana_name);
    }

    /**
     * @test
     */
    public function CompanyBillInfoの削除処理()
    {
        CompanyInfo::factory()->create();
        CompanyBillInfo::factory()->create();

        $companyInfosOld = CompanyInfo::all();
        $companyBillInfosOld = CompanyBillInfo::all();
        $companyBillInfoOld = $companyBillInfosOld->first();

        $this->assertCount(1, $companyInfosOld);
        $this->assertCount(1, $companyBillInfosOld);

        $res = $this->deleteJson(route('api.companyBillInfo.destroy', $companyBillInfoOld->id));

        $res->assertOk();

        $companyInfosNew = CompanyInfo::all();
        $companyBillInfosNew = CompanyBillInfo::all();

        $this->assertCount(1, $companyInfosNew);
        $this->assertCount(0, $companyBillInfosNew);
    }
}
