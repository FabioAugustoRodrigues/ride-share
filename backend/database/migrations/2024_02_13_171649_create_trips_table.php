<?php

use App\Models\Driver;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Driver::class);
            $table->boolean('is_started')->default(false);
            $table->boolean('is_complete')->default(false);
            $table->json('origin')->nullable();
            $table->json('destination')->nullable();
            $table->string('destination_name')->nullable();
            $table->string('driver_location');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trips');
    }
}
