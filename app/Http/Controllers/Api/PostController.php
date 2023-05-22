<?php

//app\Http\Controllers\Api\PostController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePostRequest;
use App\Models\AnonymousPost;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Throwable;

class PostController extends Controller
{
    //用於生成 JSON 字串
    private function makeJson($status, $data, $msg)
    {
        //轉 JSON 時確保中文不會變成 Unicode
        return response()->json(['status' => $status, 'data' => $data, 'message' => $msg])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    public function index()
    {
        $posts = Post::with('user')->get();

        if (isset($posts) && count($posts) > 0) {
            $data = ['posts' => $posts];
            return $this->makeJson(1, $data, null);
        } else {
            return $this->makeJson(0, null, '找不到任何文章');
        }
    }

    public function show(Request $request, $id)
    {
        $post = Post::find($id);

        if (isset($post)) {
            $data = ['post' => $post];
            return $this->makeJson(1, $data, null);
        } else {
            return $this->makeJson(0, null, '找不到該文章');
        }
    }

    public function store(Request $request)
    {
        $input = ['user_id' => $request->user_id, 'content' => $request->content];

        // Check if the user_id is provided
        if ($request->user_id) {
            $post = Post::create($input);
        } else {
            $post = AnonymousPost::create($input);
        }

        if (isset($post)) {
            $data = ['post' => $post];
            return $this->makeJson(1, $data, '新增文章成功');
        } else {
            $data = ['post' => $post];
            return $this->makeJson(0, null, '新增文章失敗');
        }
    }

    public function anonymousStore(Request $request)
    {
        $input = ['user_id' => 99999, 'content' => $request->content];

        $post = Post::create($input);

        if (isset($post)) {
            $data = ['post' => $post];
            return $this->makeJson(1, $data, '新增文章成功');
        } else {
            $data = ['post' => $post];
            return $this->makeJson(0, null, '新增文章失敗');
        }
    }

    public function update(UpdatePostRequest $request, $id)
    {
        $data = $request->validated();

        $post = Post::findOrFail($id);


        if (!$post) {
            return response()->json(['message' => '文章不存在'], 404);
        }

        $post->user_id = $data['user_id'];
        $post->content = $data['content'];
        $post->save();

        return response()->json(['message' => '文章更新成功', 'data' => $post], 200);
    }


    public function destroy($id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->delete();
        } catch (Throwable $e) {
            //刪除失敗
            return $this->makeJson(0, null, '刪除文章失敗');
        }
        return $this->makeJson(1, null, '刪除文章成功');
    }
}
