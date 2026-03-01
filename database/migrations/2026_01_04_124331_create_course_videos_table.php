<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CourseVideos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->string('video_url');
            $table->string('thumnail_url');
            $table->string('status', 10);
            $table->boolean('is_delete')->default(false);
            $table->timestamps();
            $table->foreign('course_id', 'fk_cm')
                ->references('id')
                ->on('CourseMaster')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('CourseVideos');
    }
};
