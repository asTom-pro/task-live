<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LogController extends Controller
{
    public function setLogs($uri, $ipaddress)
    {
        // データベースに同じipアドレスがあるなら更新。なかったら挿入
        $exists = DB::table('logs')->where('uri', $uri)->where('ipaddress', $ipaddress)->exists();

        if ($exists) {
            Log::debug('Updating existing log entry');
            DB::table('logs')->where('uri', $uri)->where('ipaddress', $ipaddress)->update([
                'updated_at' => now()
            ]);
        } else {
            Log::debug('Inserting new log entry');
            DB::table('logs')->insert([
                'uri' => $uri,
                'ipaddress' => $ipaddress,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    public function getLogs($uri)
    {
        Log::debug('Fetching logs for URI: ' . $uri);
        $count = DB::table('logs')
            ->where('uri', $uri)
            ->where('updated_at', '>', now()->subMinute())
            ->distinct('ipaddress')
            ->count('ipaddress');

        Log::debug('Number of unique IP addresses in the last minute: ' . $count);
        return $count;
    }
}
