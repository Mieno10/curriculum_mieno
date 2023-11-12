
<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Todo;

class TodoControllerErrorTest extends TestCase
{
    use DatabaseTransactions;

    const STATUS_CODE = 422;

    public function setUp():void
    {
        parent::setUp();
    }

    //新規作成失敗パターン
    /**
     * @test
     */
    public function Todoの新規作成タイトル無しエラー()
    {
        $params = [
            'title' => '',
            'content' => 'たまご'
        ];

        $res = $this->postJson(route('api.todo.create'),$params);
        $res->assertInvalid('title');
        $res->assertStatus(self::STATUS_CODE);

        $todos = Todo::all();

        $this->assertCount(0,$todos);
    }

    /**
     * @test
     */

    public function Todoタイトル文字数制限エラー()
    {
        $params = [
            'title' => 'f97SbCarGJ1bjyGWudjxkAuoeOakIB3FfOsvAeSXtZGqy34kwLxNlbrAJDZbtR9oSDTOEkmg6Kjl6BLzGSw5dZh8zsVt863LZkarEsoSH6EGgFDlrqrRBhO9MESChpNkdZVpiIC3iHxSU0WLbYnrqJPj4YveJ7PZJeFlo4PtBaKVm2jCjDRXz7hNssn1kNlFPsWsaT1vdm0WxQUwVmbLdUemn1uow2bVp9738RAiOpZjCenKPZI9RwR8kRScBH8B',
            'content' => 'たまご'
        ];

        $res = $this->postJson(route('api.todo.create'),$params);
        $res->assertInvalid('title');
        $res->assertStatus(self::STATUS_CODE);

        $todos = Todo::all();

        $this->assertCount(0,$todos);
    }
    
    /**
     * @test
     */
    public function Todoの新規作成コンテンツ無しエラー()
    {
        $params = [
            'title' => 'テスト:タイトル',
            'content' => ''
        ];

        $res = $this->postJson(route('api.todo.create'),$params);
        $res->assertInvalid('content');
        $res->assertStatus(self::STATUS_CODE);

        $todos = Todo::all();

        $this->assertCount(0,$todos);
    }

    /**
     * @test
     */
    public function Todoの新規作成コンテンツ文字数制限エラー()
    {
        $params = [
            'title' => 'たまご',
            'content' => 'f97SbCarGJ1bjyGWudjxkAuoeOakIB3FfOsvAeSXtZGqy34kwLxNlbrAJDZbtR9oSDTOEkmg6Kjl6BLzGSw5dZh8zsVt863LZkarEsoSH6EGgFDlrqrRBhO9MESChpNkdZVpiIC3iHxSU0WLbYnrqJPj4YveJ7PZJeFlo4PtBaKVm2jCjDRXz7hNssn1kNlFPsWsaT1vdm0WxQUwVmbLdUemn1uow2bVp9738RAiOpZjCenKPZI9RwR8kRScBH8B'
        ];

        $res = $this->postJson(route('api.todo.create'),$params);
        $res->assertInvalid('content');
        $res->assertStatus(self::STATUS_CODE);

        $todos = Todo::all();

        $this->assertCount(0,$todos);
    }



    //更新処理失敗パターン
    /**
     * @test
     */
    public function Todoの更新処理タイトル無しエラー()
    {
        Todo::factory()->create();//既存レコードの作成

        $params = [
            'title' => '',
            'content' => 'ごりら'
        ];
        $todoOld = Todo::all()->first();

        $res = $this->putJson(route('api.todo.update', $todoOld->id),$params);
        $res->assertInvalid('title');
        $res->assertStatus(self::STATUS_CODE);

        $todoNew = Todo::all()->first();

        $this->assertSame($todoOld->title,$todoNew->title);
        $this->assertSame($todoOld->content,$todoNew->content);
    }

    /**
     * @test
     */
    public function Todoの更新処理コンテンツ文字数制限エラー()
    {
        Todo::factory()->create();//既存レコードの作成

        $params = [
            'title' => 'ごりら',
            'content' => 'f97SbCarGJ1bjyGWudjxkAuoeOakIB3FfOsvAeSXtZGqy34kwLxNlbrAJDZbtR9oSDTOEkmg6Kjl6BLzGSw5dZh8zsVt863LZkarEsoSH6EGgFDlrqrRBhO9MESChpNkdZVpiIC3iHxSU0WLbYnrqJPj4YveJ7PZJeFlo4PtBaKVm2jCjDRXz7hNssn1kNlFPsWsaT1vdm0WxQUwVmbLdUemn1uow2bVp9738RAiOpZjCenKPZI9RwR8kRScBH8B'
        ];
        $todoOld = Todo::all()->first();

        $res = $this->putJson(route('api.todo.update', $todoOld->id),$params);
        $res->assertInvalid('content');
        $res->assertStatus(self::STATUS_CODE);

        $todoNew = Todo::all()->first();

        $this->assertSame($todoOld->title,$todoNew->title);
        $this->assertSame($todoOld->content,$todoNew->content);
    }

    /**
     * @test
     */
    public function Todoの更新処理タイトル文字列エラー()
    {
        Todo::factory()->create();

        $params = [
            'title' => 1,
            'content' => 'ごりら'
        ];
        $todoOld = Todo::all()->first();

        $res = $this->putJson(route('api.todo.update', $todoOld->id), $params);
        $res->assertInvalid('title');
        $res->assertStatus(self::STATUS_CODE);

        $todoNew = Todo::all()->first();

        $this->assertSame($todoOld->title,$todoNew->title);
        $this->assertSame($todoOld->content,$todoNew->content);
    }

    /**
     * @test
     */
    public function Todoの更新処理内容無しエラー()
    {
        Todo::factory()->create();//既存レコードの作成

        $params = [
            'title' => 'ごりら',
            'content' => ''
        ];
        $todoOld = Todo::all()->first();

        $res = $this->putJson(route('api.todo.update', $todoOld->id),$params);
        $res->assertInvalid('content');
        $res->assertStatus(self::STATUS_CODE);

        $todoNew = Todo::all()->first();

        $this->assertSame($todoOld->title,$todoNew->title);
        $this->assertSame($todoOld->content,$todoNew->content);
    }

    /**
     * @test
     */
    public function Todoの更新処理タイトル文字数制限エラー()
    {
        Todo::factory()->create();//既存レコードの作成

        $params = [
            'title' => 'f97SbCarGJ1bjyGWudjxkAuoeOakIB3FfOsvAeSXtZGqy34kwLxNlbrAJDZbtR9oSDTOEkmg6Kjl6BLzGSw5dZh8zsVt863LZkarEsoSH6EGgFDlrqrRBhO9MESChpNkdZVpiIC3iHxSU0WLbYnrqJPj4YveJ7PZJeFlo4PtBaKVm2jCjDRXz7hNssn1kNlFPsWsaT1vdm0WxQUwVmbLdUemn1uow2bVp9738RAiOpZjCenKPZI9RwR8kRScBH8B',
            'content' => 'ごりら'
        ];
        $todoOld = Todo::all()->first();

        $res = $this->putJson(route('api.todo.update', $todoOld->id),$params);
        $res->assertInvalid('title');
        $res->assertStatus(self::STATUS_CODE);

        $todoNew = Todo::all()->first();

        $this->assertSame($todoOld->title,$todoNew->title);
        $this->assertSame($todoOld->content,$todoNew->content);
    }

    /**
     * @test
     */
    public function Todoの更新処理コンテンツ文字列エラー()
    {
        Todo::factory()->create();

        $params = [
            'title' => 'ごりら',
            'content' => 1
        ];
        $todoOld = Todo::all()->first();

        $res = $this->putJson(route('api.todo.update', $todoOld->id), $params);
        $res->assertInvalid('content');
        $res->assertStatus(self::STATUS_CODE);

        $todoNew = Todo::all()->first();

        $this->assertSame($todoOld->title,$todoNew->title);
        $this->assertSame($todoOld->content,$todoNew->content);
    }

    //詳細取得
    /**
     * @test
     */
    //受け取ったidのデータが存在しない場合
    public function Todoの詳細取得エラー()
    {
        Todo::factory()->create();//既存レコードの作成
        
        $todo = Todo::all()->first();//todosテーブルから一件取得
        $params = [
            'title' => $todo->title,
            'content' => $todo->content
        ];

        $res = $this->getJson(route('api.todo.show', $todo->id + 1 ));

        $res->assertStatus(404);

        $res->assertJsonMissing($params);
    }

    //削除処理
    /**
     * @test
     */
    //受け取ったidデータが存在しない場合
    public function Todoの削除処理エラー()
    {
        Todo::factory()->create();//todosテーブルに既存データ作成

        $todosOld = Todo::all();//todosテーブルから全件取得
        $todoOld = $todosOld->first();//todosテーブルから一件取得
        $this->assertCount(1, $todosOld);//全体数が1であることを確認

        $res = $this->deleteJson(route('api.todo.destroy', $todoOld->id + 1));//存在しないidを入力

        $res->assertStatus(404);

        $todosNew = Todo::all();//改めてtodosテーブルから全取得
        $this->assertCount(1, $todosNew);//全体数がリクエスト前と同じ1であること。(削除されていないこと)
    }
}
