<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop triggers if they already exist
        DB::unprepared('DROP TRIGGER IF EXISTS check_salary_before_insert;');
        DB::unprepared('DROP TRIGGER IF EXISTS check_salary_before_update;');

        // Create trigger for INSERT
        DB::unprepared('
            CREATE TRIGGER check_salary_before_insert
            BEFORE INSERT ON employees
            FOR EACH ROW
            BEGIN
                DECLARE min_salary DECIMAL(10,2);
                DECLARE max_salary DECIMAL(10,2);

                SELECT min_salary, max_salary 
                INTO min_salary, max_salary
                FROM job
                WHERE id = NEW.job_id;

                IF NEW.salary < min_salary OR NEW.salary > max_salary THEN
                    SIGNAL SQLSTATE "45000" 
                    SET MESSAGE_TEXT = "Salary must be between job min_salary and max_salary";
                END IF;
            END
        ');

        // Create trigger for UPDATE
        DB::unprepared('
            CREATE TRIGGER check_salary_before_update
            BEFORE UPDATE ON employees
            FOR EACH ROW
            BEGIN
                DECLARE min_salary DECIMAL(10,2);
                DECLARE max_salary DECIMAL(10,2);

                SELECT min_salary, max_salary 
                INTO min_salary, max_salary
                FROM job
                WHERE id = NEW.job_id;

                IF NEW.salary < min_salary OR NEW.salary > max_salary THEN
                    SIGNAL SQLSTATE "45000" 
                    SET MESSAGE_TEXT = "Salary must be between job min_salary and max_salary";
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS check_salary_before_insert;');
        DB::unprepared('DROP TRIGGER IF EXISTS check_salary_before_update;');
    }
};