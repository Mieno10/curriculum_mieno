<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Todo;
use Tests\TestCase;

class TodoControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp():void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function Todoの新規作成()
    {
        $params = [
            'title' => 'テスト:タイトル',
            'content' => 'テスト:内容'
        ];

        $res = $this->postJson(route('api.todo.create'),$params);
        $res->assertOk();//ステータスコードが200かどうか
        $todos = Todo::all();//todosテーブルのレコード全件取得

        $this->assertCount(1, $todos);//取得したレコードが1つであることを確認

        $todo = $todos->first();//複数のレコードの一番初めを取得

        $this->assertEquals($params['title'], $todo->title);//postJsonを使用して送った内容が元の内容と同じか確認
        $this->assertEquals($params['content'], $todo->content);
    }

    /**
     * @test
     */
    public function Todoの更新処理()
    {
        Todo::factory()->create();//既存レコードの作成

        $todos = Todo::all();
        $todo = $todos->first();//todosテーブルからはじめの一件を取得

        $this->assertCount(1,$todos);

        $params = [
            'title' => 'テスト：たまご',//更新したいデータの配列
            'content' => 'テスト：おでん'
        ];

        $res = $this->putJson(route('api.todo.update', $todo->id), $params);//取得した最初の一件のidを持たせてputメソッドでリクエスト送信
        $res->assertOk();//レスポンスのステータスコードが200であることを確認。

        $todos = Todo::all();//更新後のデータ全件取得
        $todo = $todos->first();//最初の一件に絞る

        $this->assertCount(1, $todos);

        $this->assertSame($params['title'], $todo->title);
        $this->assertSame($params['content'], $todo->content);
    }

    /**
     * @test
     */
    public function Todoの詳細取得()
    {
        Todo::factory()->create();//todosテーブルにレコード追加

        $todo = Todo::all()->first();//todosテーブルの1件目のレコードを取得

        $res = $this->getJson(route('api.todo.show', $todo->id));//getJsonでリクエスト送信

        $res->assertOk();//ステータスコードが200かどうか

        $res->assertJsonPath('title',$todo->title);//返ってきたものがデータベースにあるものと同じかどうか
        $res->assertJsonPath('content',$todo->content);
    }

    /**
     * @test
     */
    public function Todoの削除処理()
    {
        Todo::factory()->create();//既存レコード作成

        $todo = Todo::all()->first();//todosテーブルの1件目のレコードを取得

        $res = $this->deleteJson(route('api.todo.destroy', $todo->id));//deleteJsonでリクエスト送信

        $res->assertOk();//ステータスコードが200であることを確認

        $todos = Todo::all();//todosテーブルのレコード全件取得
        $this->assertCount(0, $todos);//0であることを確認
    }
}
