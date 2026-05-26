<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        // Simple mock login for admin for now
        $credentials = $request->only('email', 'password');
        
        // For demonstration, let's just accept admin@rupiachat.com / admin123
        if ($request->email === 'admin@rupiachat.com' && $request->password === 'admin123') {
            $request->session()->put('admin_logged_in', true);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin_logged_in');
        $request->session()->forget('crm_users');
        $request->session()->forget('crm_transactions');
        return redirect()->route('admin.login');
    }

    public function dashboard(Request $request)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $users = [];
        $purchases = [];

        // 1. Fetch real-time exchange rate with 1-hour caching in DB
        $usdRate = 16000.00;
        $rateUpdatedAt = '-';
        try {
            $rateSetting = \DB::table('settings')->where('key', 'exchange_rate_usd_idr')->first();
            $shouldUpdate = true;
            
            if ($rateSetting) {
                $cached = json_decode($rateSetting->value, true);
                $usdRate = $cached['rate'] ?? 16000.00;
                $rateUpdatedAt = $rateSetting->updated_at ? date('d M Y, H:i', strtotime($rateSetting->updated_at)) : '-';
                
                // If updated within the last 10 minutes (600 seconds), don't fetch again
                if ($rateSetting->updated_at && (time() - strtotime($rateSetting->updated_at)) < 600) {
                    $shouldUpdate = false;
                }
            }
            
            if ($shouldUpdate) {
                $response = \Illuminate\Support\Facades\Http::timeout(5)->get('https://api.exchangerate-api.com/v4/latest/USD');
                if ($response->successful() && isset($response->json()['rates']['IDR'])) {
                    $usdRate = $response->json()['rates']['IDR'];
                    $now = date('Y-m-d H:i:s');
                    $value = json_encode(['rate' => $usdRate]);
                    
                    if ($rateSetting) {
                        \DB::table('settings')->where('key', 'exchange_rate_usd_idr')->update(['value' => $value, 'updated_at' => $now]);
                    } else {
                        \DB::table('settings')->insert(['key' => 'exchange_rate_usd_idr', 'value' => $value, 'created_at' => $now, 'updated_at' => $now]);
                    }
                    $rateUpdatedAt = date('d M Y, H:i', strtotime($now));
                }
            }
        } catch (\Exception $e) {
            \Log::error('Admin exchange rate load error: ' . $e->getMessage());
        }

        $sort = $request->query('sort', 'newest');
        $orderBy = $sort === 'oldest' ? 'asc' : 'desc';

        try {
            // 2. Fetch real users and their wallets from backend mysql database
            $dbUsers = \DB::table('users')
                ->leftJoin('wallets', 'users.id', '=', 'wallets.user_id')
                ->select('users.*', 'wallets.balance as wallet_balance')
                ->orderBy('users.created_at', $orderBy)
                ->get();
            
            foreach ($dbUsers as $u) {
                $balance = $u->wallet_balance ?? 0;
                
                // Track user status from DB or default to Active
                // Check if user table has a status column or use active default
                $status = 'Active';
                if (isset($u->status)) {
                    $status = $u->status;
                } else {
                    // fallback to mock toggled status if any
                    $mockUsers = $request->session()->get('crm_users', []);
                    $mockMatch = collect($mockUsers)->where('email', $u->email)->first();
                    $status = $mockMatch ? $mockMatch['status'] : 'Active';
                }
                
                $users[] = [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'phone' => $u->phone ?? '-',
                    'balance' => $balance,
                    'joined' => $u->created_at ? date('Y-m-d', strtotime($u->created_at)) : date('Y-m-d'),
                    'status' => $status
                ];
            }
            $request->session()->put('crm_users', $users);
            
            // 3. Fetch real feature purchases
            $dbPurchases = \DB::table('purchases')
                ->join('users', 'purchases.user_id', '=', 'users.id')
                ->select('purchases.*', 'users.name as user_name')
                ->orderBy('purchases.created_at', 'desc')
                ->get();
                
            foreach ($dbPurchases as $p) {
                $purchases[] = [
                    'id' => $p->id,
                    'user' => $p->user_name,
                    'feature' => $p->feature_name ?? $p->feature_slug,
                    'amount' => 'Rp ' . number_format($p->price, 0, ',', '.'),
                    'price' => $p->price,
                    'date' => $p->created_at ? date('Y-m-d H:i:s', strtotime($p->created_at)) : date('Y-m-d H:i:s'),
                    'status' => ucfirst($p->status)
                ];
            }
            
        } catch (\Exception $e) {
            \Log::error('Admin dashboard query error: ' . $e->getMessage());
            // Safe fallback to session mocks if database connection is offline
            $users = $request->session()->get('crm_users', []);
        }

        // 4. Compute dynamic statistics based on Completed purchases
        $totalRevenue = 0;
        $voiceCallsSold = 0;
        $videoCallsSold = 0;
        $groupAccessSold = 0;
        $voiceNotesSold = 0;
        $vipMembershipsSold = 0;

        foreach ($purchases as $p) {
            if (strtolower($p['status']) === 'completed') {
                $totalRevenue += (int) $p['price'];

                if (strtolower($p['feature']) === 'voice call' || strtolower($p['feature']) === 'voice_call' || strtolower($p['feature']) === 'paket nelpon') {
                    $voiceCallsSold++;
                } elseif (strtolower($p['feature']) === 'video call' || strtolower($p['feature']) === 'video_call') {
                    $videoCallsSold++;
                } elseif (strtolower($p['feature']) === 'buat grup' || strtolower($p['feature']) === 'group_create') {
                    $groupAccessSold++;
                } elseif (strtolower($p['feature']) === 'voice note' || strtolower($p['feature']) === 'voice_note' || strtolower($p['feature']) === 'sticker pack' || strtolower($p['feature']) === 'sticker_pack') {
                    $voiceNotesSold++;
                } elseif (strtolower($p['feature']) === 'vip member' || strtolower($p['feature']) === 'vip_member') {
                    $vipMembershipsSold++;
                }
            }
        }

        // Format to Rupiah presentation
        $formattedRevenue = 'Rp ' . number_format($totalRevenue, 0, ',', '.');

        $stats = [
            'revenue' => $formattedRevenue,
            'voice_calls' => number_format($voiceCallsSold, 0, ',', '.'),
            'video_calls' => number_format($videoCallsSold, 0, ',', '.'),
            'group_access' => number_format($groupAccessSold, 0, ',', '.'),
            'voice_notes' => number_format($voiceNotesSold, 0, ',', '.'),
            'vip_memberships' => number_format($vipMembershipsSold, 0, ',', '.'),
            'usd_rate' => 'Rp ' . number_format($usdRate, 2, ',', '.'),
            'rate_updated_at' => $rateUpdatedAt
        ];

        return view('admin.dashboard', compact('purchases', 'users', 'stats'));
    }

    public function toggleUserStatus(Request $request, $id)
    {
        // Try updating in DB first
        try {
            $user = \DB::table('users')->where('id', $id)->first();
            if ($user && isset($user->status)) {
                $newStatus = $user->status === 'Active' ? 'Suspended' : 'Active';
                \DB::table('users')->where('id', $id)->update(['status' => $newStatus]);
                return back()->with('success_message', "Status pengguna {$user->name} berhasil diubah menjadi {$newStatus}!");
            }
        } catch (\Exception $e) {
            \Log::warning('Could not toggle user status in DB: ' . $e->getMessage());
        }

        // Fallback to session
        $users = $request->session()->get('crm_users', []);
        foreach ($users as &$user) {
            if ($user['id'] == $id) {
                $user['status'] = $user['status'] === 'Active' ? 'Suspended' : 'Active';
                $request->session()->put('crm_users', $users);
                return back()->with('success_message', "Status pengguna {$user['name']} berhasil diubah menjadi {$user['status']}!");
            }
        }
        return back()->withErrors(['error' => 'Pengguna tidak ditemukan']);
    }
}
