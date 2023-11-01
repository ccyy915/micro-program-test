<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MessageController extends Controller
{
    // 建立留言
    public function create(Request $request)
    {
        // 驗證資料正確性
        $validatedData = $request->validate([
            "message" => "required|string|max:255",
        ]);

        $message = $validatedData["message"];

        // 將留言存在暫存
        $messages = Cache::get("messages", []);
        $messages[] = $message;

        Cache::put("messages", $messages, now()->addHour(1));

        return response()->json(["message" => $message], 201);
    }

    // 取得留言紀錄
    public function getMessages()
    {
        // 從暫存中取得留言列表
        $messages = Cache::get("messages", []);

        return response()->json(["messages" => $messages], 200);
    }
}
