<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trek_id')->constrained()->onUpdate('restrict')->onDelete('restrict');
            $table->foreignId('user_id')->constrained()->onUpdate('restrict')->onDelete('restrict');
            $table->dateTime('appDateIni');
            $table->dateTime('appDateEnd')->nullable();
            $table->date('day');
            $table->time('hour');
            $table->integer('totalScore')->default(0);
            $table->integer('countScore')->default(0);
            $table->timestamps();
        });

        // INSERT: cuando se crea una meeting
        DB::statement('
            CREATE TRIGGER update_treks_after_insert_meetings
            AFTER INSERT ON meetings
            FOR EACH ROW
            BEGIN
                UPDATE treks
                SET 
                    totalScore = totalScore + IFNULL(NEW.totalScore, 0),
                    countScore = countScore + IFNULL(NEW.countScore, 0)
                WHERE id = NEW.trek_id;
            END;
        ');

        // UPDATE: cuando se modifica una meeting
        DB::statement('
            CREATE TRIGGER update_treks_after_update_meetings
            AFTER UPDATE ON meetings
            FOR EACH ROW
            BEGIN
                IF (NEW.trek_id != OLD.trek_id) THEN

                    -- Restar valores del trek antiguo
                    UPDATE treks
                    SET 
                        totalScore = totalScore - IFNULL(OLD.totalScore, 0),
                        countScore = countScore - IFNULL(OLD.countScore, 0)
                    WHERE id = OLD.trek_id;

                    -- Sumar valores al trek nuevo
                    UPDATE treks
                    SET 
                        totalScore = totalScore + IFNULL(NEW.totalScore, 0),
                        countScore = countScore + IFNULL(NEW.countScore, 0)
                    WHERE id = NEW.trek_id;

                ELSEIF (NEW.totalScore != OLD.totalScore 
                    OR NEW.countScore != OLD.countScore) THEN

                    UPDATE treks
                    SET 
                        totalScore = totalScore 
                            + IFNULL(NEW.totalScore, 0)
                            - IFNULL(OLD.totalScore, 0),
                        countScore = countScore
                            + IFNULL(NEW.countScore, 0)
                            - IFNULL(OLD.countScore, 0)
                    WHERE id = NEW.trek_id;

                END IF;
            END;
        ');

        // DELETE: cuando se elimina una meeting
        DB::statement('
            CREATE TRIGGER update_treks_after_delete_meetings
            AFTER DELETE ON meetings
            FOR EACH ROW
            BEGIN
                UPDATE treks
                SET 
                    totalScore = totalScore - IFNULL(OLD.totalScore, 0),
                    countScore = countScore - IFNULL(OLD.countScore, 0)
                WHERE id = OLD.trek_id;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
        DB::statement('DROP TRIGGER IF EXISTS update_treks_after_insert_meetings;');
        DB::statement('DROP TRIGGER IF EXISTS update_treks_after_update_meetings;');
        DB::statement('DROP TRIGGER IF EXISTS update_treks_after_delete_meetings;');
    }
};
