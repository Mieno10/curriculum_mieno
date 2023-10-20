<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    public function __construct(
        private Todo $todos
    )
    {}

    /**
         * Store a newly created resource in storage.
         * 
         * @param \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255']
        ]);
        $this->todos->fill($validated)->save();

        return ['massage' => 'OK'];
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,int $id)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255']
        ]);
        $this->todos->findOrFail($id)->update($validated);
        return ['massage' => 'OK'];
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $todo = $this->todos->FindOrFail($id);
        return $todo;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $this->todos->FindOrFail($id)->delete();
        return ['massage' => 'OK'];
    }
}
