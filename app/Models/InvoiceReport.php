<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceReport extends Model
{
    protected $table = 'vw_invoice_reports';

    protected $primaryKey = 'id';

    public $timestamps = false;
}
