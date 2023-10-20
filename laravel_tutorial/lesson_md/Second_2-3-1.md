# View の分割を行う

前回までは、一つの file にごそっと書いたままでしたがこの章では、分割を行います。
この章での目的は、機能毎に View の file を用意し、共通部分は、テンプレートを使用し画面の大枠の完成を目指します。

## ページごとに file を作成します。

今回必要となるページは、新規作成・更新/編集・一覧画面の以上 3 ページになると思います。
前回の章で作成したのは、一覧画面に使える html を作成しました。なので前回作成したものを流用し作成していきたいと思います。

Mac の方はコマンドで file を作成しましょう。

```shell
touch resources/views/todo/index.blade.php
```

作成された file をエディタで開き記述を行います。この際、前回作成した file から流用しますがそれに伴い前回の file の変更も行います。

編集 file `resources/views/todo/index.blade.php`

```html
<div class="mt-20 mb-10 flex justify-between">
  <h1 class="text-base">TODO一覧</h1>
  <button
    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
  >
    <a href="{{ route('todo.create') }}">新規追加</a>
  </button>
</div>
<div>
  <table class="table-auto">
    <thead>
      <tr>
        <th class="px-4 py-2">タイトル</th>
        <th class="px-4 py-2">やること</th>
        <th class="px-4 py-2">作成日時</th>
        <th class="px-4 py-2">更新日時</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="border px-4 py-2">Todoのタイトルです</td>
        <td class="border px-4 py-2">Todoの内容です</td>
        <td class="border px-4 py-2">2022-02-01 00:00:00</td>
        <td class="border px-4 py-2">2022-02-10 00:00:00</td>
      </tr>
    </tbody>
  </table>
</div>
```

ただしこのままでは、使用ができません。
なので使用できるように記述を加えていきます。初めてみる単語などが出てきますが file の編集が終わり次第説明をします。

その前にテンプレートの方にも手を加えます。

対象 file `resources/views/layouts/app.blade.php` です。

```html
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Laravel</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>

  <body>
    <div class="container w-auto inline-block px-8">
      @yield('content')
      <!-- 追記 -->
    </div>
  </body>
</html>
```

対象 file `resources/views/todo/index.blade.php` です。

```html
@extends('layouts.app')
<!-- 追記 -->
@section('content')
<!-- 追記 -->

<div class="mt-20 mb-10 flex justify-between">
  <h1 class="text-base">TODO一覧</h1>
  <button
    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
  >
    <a href="{{ route('todo.create') }}">新規追加</a>
  </button>
</div>
<div>
  <table class="table-auto">
    <thead>
      <tr>
        <th class="px-4 py-2">タイトル</th>
        <th class="px-4 py-2">やること</th>
        <th class="px-4 py-2">作成日時</th>
        <th class="px-4 py-2">更新日時</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="border px-4 py-2">Todoのタイトルです</td>
        <td class="border px-4 py-2">Todoの内容です</td>
        <td class="border px-4 py-2">2022-02-01 00:00:00</td>
        <td class="border px-4 py-2">2022-02-10 00:00:00</td>
      </tr>
    </tbody>
  </table>
</div>

@endsection
<!-- 追記 -->
```

追記箇所は、3 箇所となります。

- 以下に出てくる用語の共通点
  - 外部読み込みが可能です。要するに別の file を読み込み使用することが可能ということです。
- @yield
  - 継承は、できずデフォルト値の設定が可能です。なので今回は、テンプレートになる file のみに使用し引数として中には、`'content'`と記入することによって次に説明する section で宣言された名称の file を読み込み表示することが可能です。
- @section
  - 継承ができ、デフォルト値の設定が可能です。おおよその概念としては、yield に近いですが異なる点として親 section というものが定義可能です。この場合親として扱われる section は、通常の section と異なり閉じタグが `@show` となります。※今回は使用しません。
  - 基本的な使い方としては、ページごとに表示分ける際などに各ページの始まりと終わりに`@section('キーワード')` という書き方ではじめ、終わりに `@endsection` または、 `@stop` と書く必要があります。
  - `@yield('キーワード')` で該当するキーワードの `@section('キーワード')` のキーワードが一致する file が読み込まれ表示されます。
- @include

  - 今回は、使用しませんがこちらは、継承はできずまたデフォルト値の設定ができません。ただし変数の受け渡しが可能です。
  - 用途としては、エラー文言の出力など形式、見た目などが同じで値によって表示非表示を行いたい場合に変数を渡しその変数の値を元に表示を行う場合などが想定されます。
  - もちろん他にも用途は、あげられると思います。

- 外部 file の読み込み以外にも多くのメソッドが存在します。

  - `foreach` と `if文` の性質をもつ `@forelse` など便利なメソッドも多くあるので一度公式サイトなどを見てみることをオススメします。
  - [Loops のドキュメント](https://laravel.com/docs/9.x/blade#loops)

view の実装が一旦終わりました。このままブラウザで確認したいのですが現状だとエラーが出ると思います。
その理由は、実装した view を表示するための記述を Controller に行なっていないためです。なので表示させるために Controller の編集を行いましょう。

編集 file `app/Http/Controllers/TodoController.php` です

```php
// 省略
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('todo.index');  // 追記
    }
// 以下省略
```

これで問題なく表示されます。では早速ブラウザで確認しましょう。

## 同じ方法で他のページの作成も行いましょう。

作成するページが残り 2 ページです。まずは、記述するための file を用意しましょう。

```shell
touch resources/views/todo/create.blade.php resources/views/todo/edit.blade.php
```

編集 file `resources/views/todo/create.blade.php` です。

```html
@extends('layouts.app') @section('content')

<div class="border-solid border-b-2 border-gry-500 p-2 mb-2">
  <div class="flex justify-between">
    <h2 class="text-base mb-4">新規追加</h2>
    <button
      class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow"
    >
      <a href="{{ route('todo.index') }}">戻る</a>
    </button>
  </div>
  <form method="POST">
    <div class="mb-4">
      <label
        class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
      >
        Title
      </label>
      <input
        class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
        type="text"
        name="title"
        placeholder="新規のTodo"
      />
    </div>
    <div class="mb-4">
      <label
        class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
      >
        内容
      </label>
      <input
        class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
        type="text"
        name="content"
        placeholder="新規のTodo"
      />
    </div>
    <button
      class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
      type="submit"
    >
      登録
    </button>
  </form>
</div>

@endsection
```

編集 file `resources/views/todo/edit.blade.php` です。

```html
@extends('layouts.app') @section('content')

<div class="border-solid border-b-2 border-gry-500 p-2 mb-2">
  <div class="flex justify-between">
    <h2 class="text-base mb-4">更新</h2>
    <button
      class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow"
    >
      <a href="{{ route('todo.index') }}">戻る</a>
    </button>
  </div>
  <form method="POST">
    <div class="mb-4">
      <label
        class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
      >
        Title
      </label>
      <input
        class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
        type="text"
        name="title"
      />
    </div>
    <div class="mb-4">
      <label
        class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
      >
        内容
      </label>
      <input
        class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
        type="text"
        name="content"
      />
    </div>
    <button
      class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
      type="submit"
    >
      登録
    </button>
  </form>
</div>

@endsection
```

これで view の file のベースが出来上がりました。

## View で使用する Form タグ変更する

Form タグは、Form タグのままでもいいのですがせっかくなのでより便利に扱えるようにしたいと思います。そのためのライブラリが存在してますので導入を行います。

`laravelcollective/html` というものを使用します。
これを `composer` 経由で install します。

```shell
composer require laravelcollective/html
```

実行した後しばらくしたら install が始まります。

終わったら各 view file に記載ある Form タグを変えていきます。この際、Form タグ内で使用されている Input タグも書き換えていきます。
[リファレンス](https://laravelcollective.com/docs/6.x/html)

使い方は、リンクに記載ある方法を用いて行います。
view の file を編集する前に導入したものを使用可能にするために設定を行います。

編集 file `config/app.php` です。

```php
  'providers' => [
    // ...
    Collective\Html\HtmlServiceProvider::class, // 追記
    // ...
  ],
  'aliases' => [
    // ...
      'Form' => Collective\Html\FormFacade::class,  // 追記
      'Html' => Collective\Html\HtmlFacade::class,  // 追記
    // ...
  ],
```

上記のように変更しましょう。
これで使えるようになります。ただし `config` 以下の file を変更した場合は、`サーバの再起動` を行いましょう。
理由：サーバが立ち上がった時に一度だけ読み込まれる設定 file となります。

では、実際に書いていきましょう。その後に解説を入れたいと思います。

編集 file `resources/views/todo/create.blade.php`

```html
<!-- 省略 -->
{!! Form::open(['route' => 'todo.store']) !!}
<div class="mb-4">
  <label
    class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
  >
    Title
  </label>
  {!! Form::textarea('title', null, ['required', 'class' => 'appearance-none
  block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4
  leading-tight focus:outline-none focus:bg-white focus:border-gray-500',
  'placeholder' => '新規Title', 'rows' => '3']) !!}
</div>
<div class="mb-4">
  <label
    class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
  >
    内容
  </label>
  {!! Form::textarea('content', null, ['required', 'class' => 'appearance-none
  block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4
  leading-tight focus:outline-none focus:bg-white focus:border-gray-500',
  'placeholder' => '新規Todo']) !!}
</div>
{!! Form::submit('登録', ['class' => 'bg-blue-500 hover:bg-blue-700 text-white
font-bold py-2 px-4 rounded']) !!}
<!-- 閉じる -->
{!! Form::close() !!}
<!-- 省略 -->
```

編集 file `resources/views/todo/edit.blade.php`

```html
<!-- 省略 -->
{!! Form::open(['route' => ['todo.update', $todo->id], 'method' => 'PUT']) !!}
<div class="mb-4">
  <label
    class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
  >
    Title
  </label>
  {!! Form::textarea('title', $todo->title, ['required', 'class' =>
  'appearance-none block w-full bg-white text-gray-700 border border-gray-200
  rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white
  focus:border-gray-500', 'placeholder' => '新規Title', 'rows' => '3']) !!}
</div>
<div class="mb-4">
  <label
    class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
  >
    内容
  </label>
  {!! Form::textarea( 'content', $todo->content, ['required', 'class' =>
  'appearance-none block w-full bg-white text-gray-700 border border-gray-200
  rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white
  focus:border-gray-500', 'placeholder' => '新規Todo']) !!}
</div>
{!! Form::submit('登録', ['class' => 'bg-blue-500 hover:bg-blue-700 text-white
font-bold py-2 px-4 rounded']) !!}
<!-- 閉じる -->
{!! Form::close() !!}
<!-- 省略 -->
```

上記のような file になったら問題ありません。

では、記述した内容に関して解説を加えていきます。

まず、最初に基本となる `Form` タグになるものから

```html
{!! Form::open() !!} {!! Form::close() !!}
```

これを書くことによって Form タグを開始し終了を意味してます。
注目は、 `open` 以下です。

- `route` ：これは、次の章で細かく追っておきたい思います。ただしとても重要な箇所となり、Laravel の処理フローに大きく関わってきます。

- `method` ：書く場合と書かない場合があります。書かれている場合に関しては、 `http method` で検索して頂けたらと思います。書いてない場合に関してのみ説明します。端的に `http method の POST` です。基本的に System というものは、数種類の method を使用しますがその多くが `GET` or `POST` です。そして `Form` タグを使用する場合の多くが `POST` です。使用頻度が高い場合は、わざわざ明記するのは手間です。なので明記しなくても使えるようにしてあるのです。
