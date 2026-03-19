<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            CREATE TRIGGER trg_invoice_details_insert
            BEFORE INSERT ON invoice_details
            FOR EACH ROW
            BEGIN
                DECLARE v_price DECIMAL(10,2);
                DECLARE v_stock INT;

                SELECT quantity, sell_price
                INTO v_stock, v_price
                FROM products
                WHERE id = NEW.product_id;

                IF v_stock IS NULL THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Product not found';
                END IF;

                IF v_stock < NEW.quantity THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Not enough stock';
                END IF;

                UPDATE products
                SET quantity = quantity - NEW.quantity
                WHERE id = NEW.product_id;

                SET NEW.price = v_price;
                SET NEW.total = v_price * NEW.quantity;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER trg_invoice_details_update
            BEFORE UPDATE ON invoice_details
            FOR EACH ROW
            BEGIN
                DECLARE v_stock INT;
                DECLARE v_diff INT;

                SET v_diff = NEW.quantity - OLD.quantity;

                SELECT quantity INTO v_stock
                FROM products
                WHERE id = NEW.product_id;

                IF v_diff > 0 THEN
                    IF v_stock < v_diff THEN
                        SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'Not enough stock for update';
                    END IF;

                    UPDATE products
                    SET quantity = quantity - v_diff
                    WHERE id = NEW.product_id;

                ELSEIF v_diff < 0 THEN
                    UPDATE products
                    SET quantity = quantity + ABS(v_diff)
                    WHERE id = NEW.product_id;
                END IF;

                SET NEW.total = NEW.price * NEW.quantity;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER trg_invoice_details_delete
            AFTER DELETE ON invoice_details
            FOR EACH ROW
            BEGIN
                UPDATE products
                SET quantity = quantity + OLD.quantity
                WHERE id = OLD.product_id;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_invoice_details_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_invoice_details_update");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_invoice_details_delete");
    }
};
