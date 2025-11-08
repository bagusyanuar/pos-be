<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            $jsonPath = database_path('seeders/data/users.json');
            $json = File::get($jsonPath);
            $data = json_decode($json, true);
            foreach ($data as $datum) {
                $roleId = $datum['role_id'];
                $role = Role::where('id', $roleId)->first();
                if (!$role) {
                    Log::error("role not found...");
                    DB::rollBack();
                    break;
                }

                $dataUser = [
                    'id' => $datum['id'],
                    'email' => $datum['email'],
                    'username' => $datum['username'],
                    'password' => $datum['password'],
                ];

                $user = User::updateOrCreate(
                    ['id' => $dataUser['id']],
                    $dataUser
                );

                if (!$user->hasRole($role)) {
                    $user->assignRole($role);
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("error seed users" . $th->getMessage());
        }
    }
}
