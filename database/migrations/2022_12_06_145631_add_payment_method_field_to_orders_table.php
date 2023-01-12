
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        #DB::statement('SET SESSION sort_buffer_size = 4*1024*1024');

        // $data = DB::select('SHOW VARIABLES LIKE "%max_allowed_packet%";');
        // Log::info('Packet: ', compact('data'));

        // $data = DB::select('SHOW VARIABLES LIKE "%wait%";');
        // Log::info('Timeout: ', compact('data'));

        // $orders = DB::table('orders')->selectRaw('json_extract(order_data, "$.payment.method")')->get();
        // Log::info('orders: ', compact('orders'));

        Schema::table('orders', function(Blueprint $table) {
            $table->string('payment_method')
                ->after('order_status')
                ->storedAs('ifnull(order_data->>"$.payment.method", "")')
                ->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        try {
            Schema::table('orders', function(Blueprint $table) {
                $table->dropColumn('payment_method');
            });
        } catch (Throwable) {
        }
    }
};
