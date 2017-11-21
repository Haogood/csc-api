<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\MainPage;

class MainPagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(MainPage::class, 50)->create();

        //產生排序數字，faker要麼亂數要麼數字repeat
        for ($i = 1; $i <= MainPage::count(); $i++) {
            MainPage::where('id', $i)
                    ->update(['order' => $i]);
        }
        
    }
}
