<?php


use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\TestCase;

class AdminSeederTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_if_admin_seeder_inserted_db_row(): void
    {
        $adminSeeder = new AdminSeeder();
        $adminSeeder->run();

        dd(User::where("name", "ADMIN"));
        dd(DB::table("users")->get());
        $this->assertTrue(true);
    }
}
